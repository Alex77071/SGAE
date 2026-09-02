import os
import re
import shutil
import zipfile
import logging
import tempfile
from pathlib import Path
from typing import Optional

import mysql.connector
from mysql.connector import Error




class HTTPException(Exception):
    """Excepción interna para conservar validaciones sin depender de FastAPI."""
    def __init__(self, status_code=None, detail="Error", headers=None):
        super().__init__(detail)
        self.status_code = status_code
        self.detail = detail
        self.headers = headers or {}


class _Status:
    HTTP_400_BAD_REQUEST = 400
    HTTP_401_UNAUTHORIZED = 401
    HTTP_403_FORBIDDEN = 403
    HTTP_404_NOT_FOUND = 404
    HTTP_413_REQUEST_ENTITY_TOO_LARGE = 413
    HTTP_429_TOO_MANY_REQUESTS = 429
    HTTP_500_INTERNAL_SERVER_ERROR = 500
    HTTP_503_SERVICE_UNAVAILABLE = 503


status = _Status()

# ============================================================
# CONFIGURACIÓN SEGURA
# ============================================================


def env_obligatoria(nombre: str) -> str:
    valor = os.getenv(nombre)
    if not valor:
        raise RuntimeError(
            f"Falta la variable de entorno obligatoria: {nombre}"
        )
    return valor


def env_entero(nombre: str, valor_por_defecto: int, minimo: int = 1) -> int:
    valor = os.getenv(nombre)
    if valor is None:
        return valor_por_defecto

    try:
        convertido = int(valor)
    except ValueError as exc:
        raise RuntimeError(
            f"La variable {nombre} debe ser un número entero."
        ) from exc

    if convertido < minimo:
        raise RuntimeError(
            f"La variable {nombre} debe ser >= {minimo}."
        )

    return convertido


DB_CONFIG = {
    "host": os.getenv("MOODLE_DB_HOST", "localhost"),
    "port": env_entero("MOODLE_DB_PORT", 3306),
    "user": os.getenv("MOODLE_DB_USER", "moodleuser"),
    "password": env_obligatoria("MOODLE_DB_PASSWORD"),
    "database": os.getenv("MOODLE_DB_NAME", "moodle"),
    "charset": "utf8mb4",
    "connection_timeout": env_entero("MOODLE_DB_TIMEOUT", 10),
    "autocommit": True,
}

MOODLEDATA_FILEDIR = Path(
    os.getenv("MOODLEDATA_FILEDIR", "/var/moodledata/filedir")
).resolve()

MAX_ARCHIVOS = env_entero("SGAE_MAX_FILES", 3000)
MAX_BYTES = env_entero("SGAE_MAX_TOTAL_BYTES", 2 * 1024 * 1024 * 1024)

# ============================================================
# ROLES AUTORIZADOS
# ============================================================

ROLES_PROFESOR = {
    "teacher",
    "editingteacher",
}

# ============================================================
# LOGGING
# ============================================================

logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s %(levelname)s %(name)s: %(message)s",
)
logger = logging.getLogger("sgae.descargas")


# ============================================================
# FUNCIONES AUXILIARES DE RUTAS
# ============================================================


def limpiar_nombre(nombre: object) -> str:
    """Devuelve un único componente seguro para archivo/carpeta."""

    if nombre is None:
        return "Sin_nombre"

    nombre = str(nombre).strip()

    if not nombre:
        return "Sin_nombre"

    # Elimina controles y sustituye caracteres no seguros.
    nombre = re.sub(r"[\x00-\x1f\x7f]", "_", nombre)
    nombre = re.sub(r"[\\/:*?\"<>|]", "_", nombre)
    nombre = re.sub(r"\s+", " ", nombre).strip()

    # Evita componentes especiales y nombres que sólo sean puntos.
    nombre = nombre.strip(" .")
    if not nombre or nombre in {".", ".."} or set(nombre) == {"."}:
        nombre = "Sin_nombre"

    # Evita nombres reservados frecuentes de Windows.
    reservados = {
        "CON", "PRN", "AUX", "NUL",
        *(f"COM{i}" for i in range(1, 10)),
        *(f"LPT{i}" for i in range(1, 10)),
    }
    base = nombre.split(".", 1)[0].upper()
    if base in reservados:
        nombre = f"_{nombre}"

    return nombre[:180]


def ruta_segura_dentro(base: Path, *partes: str) -> Path:
    """Construye una ruta y garantiza que permanezca dentro de base."""

    base_resuelta = base.resolve()
    candidata = base_resuelta.joinpath(*partes).resolve()

    try:
        candidata.relative_to(base_resuelta)
    except ValueError:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Se detectó una ruta no válida.",
        )

    return candidata


def validar_contenthash(contenthash: object) -> str:
    valor = str(contenthash or "").strip().lower()

    if not re.fullmatch(r"[0-9a-f]{40}", valor):
        raise ValueError("contenthash inválido")

    return valor


def obtener_ruta_origen(contenthash: object) -> Path:
    hash_valido = validar_contenthash(contenthash)

    return ruta_segura_dentro(
        MOODLEDATA_FILEDIR,
        hash_valido[:2],
        hash_valido[2:4],
        hash_valido,
    )


def obtener_ruta_destino_unica(
    carpeta: Path,
    filename: object,
    fileid: int,
) -> Path:
    filename_seguro = limpiar_nombre(filename)

    destino = ruta_segura_dentro(carpeta, filename_seguro)

    if not destino.exists():
        return destino

    nombre = destino.stem
    extension = destino.suffix

    return ruta_segura_dentro(
        carpeta,
        f"{nombre}_file_{int(fileid)}{extension}",
    )


def eliminar_temporal(ruta: object) -> None:
    if not ruta:
        return

    try:
        ruta_path = Path(ruta)
        if ruta_path.is_dir():
            shutil.rmtree(ruta_path, ignore_errors=False)
    except Exception:
        logger.exception("No se pudo eliminar una carpeta temporal.")


# ============================================================
# BASE DE DATOS
# ============================================================


def obtener_conexion():
    try:
        return mysql.connector.connect(**DB_CONFIG)
    except Error:
        logger.exception("No fue posible conectar con Moodle/MySQL.")
        raise HTTPException(
            status_code=status.HTTP_503_SERVICE_UNAVAILABLE,
            detail="Servicio temporalmente no disponible.",
        )


def usuario_es_admin_moodle(cursor, userid: int) -> bool:
    cursor.execute(
        "SELECT value FROM mdl_config WHERE name = 'siteadmins' LIMIT 1"
    )
    fila = cursor.fetchone()

    if not fila or not fila.get("value"):
        return False

    try:
        admins = {
            int(valor.strip())
            for valor in fila["value"].split(",")
            if valor.strip()
        }
    except ValueError:
        logger.warning("El valor siteadmins de Moodle no pudo interpretarse.")
        return False

    return userid in admins


def usuario_puede_descargar_curso(cursor, userid: int, courseid: int) -> bool:
    """
    Autoriza administradores Moodle o usuarios con un rol docente
    configurado en el contexto del curso (o contexto padre).
    """

    if usuario_es_admin_moodle(cursor, userid):
        return True

    consulta = """
        SELECT 1
        FROM mdl_user requester
        JOIN mdl_context coursectx
          ON coursectx.contextlevel = 50
         AND coursectx.instanceid = %s
        JOIN mdl_context assignedctx
          ON coursectx.path LIKE CONCAT(assignedctx.path, '/%')
          OR coursectx.id = assignedctx.id
        JOIN mdl_role_assignments ra
          ON ra.contextid = assignedctx.id
         AND ra.userid = %s
        JOIN mdl_role r
          ON r.id = ra.roleid
        WHERE requester.id = %s
          AND requester.deleted = 0
          AND requester.suspended = 0
          AND LOWER(r.shortname) IN ({})
        LIMIT 1
    """.format(
        ",".join(["%s"] * len(ROLES_PROFESOR))
    )

    parametros = [courseid, userid, userid, *sorted(ROLES_PROFESOR)]
    cursor.execute(consulta, tuple(parametros))
    return cursor.fetchone() is not None


# ============================================================
# CONSULTAS MOODLE
# ============================================================


def obtener_examen(cursor, courseid: int, quizid: int):
    consulta = """
        SELECT
            q.id AS quizid,
            q.name AS examen,
            c.id AS courseid,
            c.fullname AS curso
        FROM mdl_quiz q
        JOIN mdl_course c ON c.id = q.course
        WHERE q.id = %s
          AND q.course = %s
        LIMIT 1
    """

    cursor.execute(consulta, (quizid, courseid))
    return cursor.fetchone()


def obtener_grupo(cursor, courseid: int, groupid: Optional[int]):
    if groupid is None:
        return None

    consulta = """
        SELECT id, name, courseid
        FROM mdl_groups
        WHERE id = %s
          AND courseid = %s
        LIMIT 1
    """

    cursor.execute(consulta, (groupid, courseid))
    return cursor.fetchone()


def obtener_imagenes(
    cursor,
    courseid: int,
    quizid: int,
    groupid: Optional[int] = None,
):
    """
    Obtiene únicamente archivos de imagen del componente de proctoring
    ubicados en el contexto del módulo del quiz solicitado.

    Nota: no se fuerza f.itemid = qa.id porque esa relación depende de la
    implementación/versión exacta del plugin. El aislamiento principal se
    realiza por contextid -> course_module -> quiz, userid y existencia de
    intento no-preview.
    """

    join_grupo = """
        JOIN mdl_groups_members gm
          ON gm.userid = u.id
         AND gm.groupid = %s
        JOIN mdl_groups g
          ON g.id = gm.groupid
         AND g.courseid = c.id
    """ if groupid is not None else ""

    columnas_grupo = (
        "g.id AS groupid, g.name AS grupo"
        if groupid is not None
        else "NULL AS groupid, NULL AS grupo"
    )

    consulta = f"""
        SELECT DISTINCT
            f.id AS fileid,
            f.filename,
            f.contenthash,
            f.filearea,
            f.mimetype,
            f.filesize,
            f.timecreated,
            f.itemid,
            u.id AS userid,
            u.username,
            u.firstname,
            u.lastname,
            q.id AS quizid,
            q.name AS examen,
            c.id AS courseid,
            c.fullname AS curso,
            {columnas_grupo}
        FROM mdl_files f
        JOIN mdl_context ctx
          ON ctx.id = f.contextid
         AND ctx.contextlevel = 70
        JOIN mdl_course_modules cm
          ON cm.id = ctx.instanceid
        JOIN mdl_modules m
          ON m.id = cm.module
         AND m.name = 'quiz'
        JOIN mdl_quiz q
          ON q.id = cm.instance
         AND q.id = %s
         AND q.course = %s
        JOIN mdl_course c
          ON c.id = q.course
        JOIN mdl_user u
          ON u.id = f.userid
        {join_grupo}
        WHERE f.component = 'quizaccess_proctoring'
          AND f.filearea IN ('picture', 'face_image')
          AND f.filename <> '.'
          AND f.mimetype LIKE 'image/%%'
          AND f.filesize > 0
          AND u.deleted = 0
          AND u.suspended = 0
          AND EXISTS (
              SELECT 1
              FROM mdl_quiz_attempts qa
              WHERE qa.quiz = q.id
                AND qa.userid = u.id
                AND qa.preview = 0
          )
          AND EXISTS (
              SELECT 1
              FROM mdl_quizaccess_proctoring_logs pl
              WHERE pl.courseid = c.id
                AND pl.quizid = cm.id
                AND pl.userid = u.id
          )
        ORDER BY u.username, f.timecreated, f.id
    """

    parametros = [quizid, courseid]
    if groupid is not None:
        parametros.append(groupid)

    cursor.execute(consulta, tuple(parametros))
    return cursor.fetchall()


# ============================================================
# VALIDACIÓN DE LÍMITES
# ============================================================


def validar_limites(imagenes) -> None:
    cantidad = len(imagenes)

    if cantidad > MAX_ARCHIVOS:
        raise HTTPException(
            status_code=status.HTTP_413_REQUEST_ENTITY_TOO_LARGE,
            detail=(
                "La descarga excede el número máximo de archivos permitido."
            ),
        )

    total_bytes = 0

    for imagen in imagenes:
        try:
            filesize = int(imagen.get("filesize") or 0)
        except (TypeError, ValueError):
            filesize = 0

        if filesize < 0:
            filesize = 0

        total_bytes += filesize

        if total_bytes > MAX_BYTES:
            raise HTTPException(
                status_code=status.HTTP_413_REQUEST_ENTITY_TOO_LARGE,
                detail="La descarga excede el tamaño máximo permitido.",
            )


# ============================================================
# CREAR ZIP
# ============================================================


def generar_zip(
    courseid: int,
    quizid: int,
    userid_solicitante: int,
    groupid: Optional[int] = None,
):
    conexion = None
    cursor = None
    carpeta_temporal = None

    try:
        conexion = obtener_conexion()
        cursor = conexion.cursor(dictionary=True)

        # Autorización: primero se verifica que el solicitante pueda ver
        # evidencias del curso indicado.
        if not usuario_puede_descargar_curso(
            cursor,
            userid_solicitante,
            courseid,
        ):
            logger.warning(
                "Descarga rechazada: userid=%s courseid=%s quizid=%s",
                userid_solicitante,
                courseid,
                quizid,
            )
            raise HTTPException(
                status_code=status.HTTP_403_FORBIDDEN,
                detail="No tienes permisos para descargar evidencias de este curso.",
            )

        examen = obtener_examen(cursor, courseid, quizid)
        if not examen:
            raise HTTPException(
                status_code=status.HTTP_404_NOT_FOUND,
                detail="No se encontró el examen indicado.",
            )

        nombre_curso = examen["curso"]
        nombre_examen = examen["examen"]

        nombre_grupo = None
        if groupid is not None:
            grupo = obtener_grupo(cursor, courseid, groupid)
            if not grupo:
                raise HTTPException(
                    status_code=status.HTTP_404_NOT_FOUND,
                    detail="El grupo indicado no existe dentro del curso.",
                )
            nombre_grupo = grupo["name"]

        imagenes = obtener_imagenes(
            cursor,
            courseid,
            quizid,
            groupid,
        )

        if not imagenes:
            raise HTTPException(
                status_code=status.HTTP_404_NOT_FOUND,
                detail="No se encontraron evidencias para este examen.",
            )

        validar_limites(imagenes)

        if not MOODLEDATA_FILEDIR.is_dir():
            logger.error("MOODLEDATA_FILEDIR no existe o no es directorio.")
            raise HTTPException(
                status_code=status.HTTP_503_SERVICE_UNAVAILABLE,
                detail="El almacenamiento de evidencias no está disponible.",
            )

        carpeta_temporal = Path(
            tempfile.mkdtemp(prefix="moodle_evidencias_")
        ).resolve()

        carpeta_evidencias = ruta_segura_dentro(
            carpeta_temporal,
            limpiar_nombre(nombre_examen),
        )
        carpeta_evidencias.mkdir(parents=True, exist_ok=True)

        copiadas = 0
        bytes_reales = 0

        for imagen in imagenes:
            usuario = imagen["username"]
            fileid = int(imagen["fileid"])
            filename = imagen["filename"]

            try:
                archivo_origen = obtener_ruta_origen(imagen["contenthash"])
            except ValueError:
                logger.warning(
                    "Se omitió fileid=%s por contenthash inválido.",
                    fileid,
                )
                continue

            if not archivo_origen.is_file():
                # No se expone la ruta física en la respuesta HTTP.
                logger.warning(
                    "Archivo físico no disponible para fileid=%s.",
                    fileid,
                )
                continue

            # Revalida contra el tamaño físico real para evitar confiar sólo
            # en metadatos de la base de datos.
            try:
                tamano_real = archivo_origen.stat().st_size
            except OSError:
                logger.warning("No se pudo leer fileid=%s.", fileid)
                continue

            if tamano_real <= 0:
                continue

            if bytes_reales + tamano_real > MAX_BYTES:
                raise HTTPException(
                    status_code=status.HTTP_413_REQUEST_ENTITY_TOO_LARGE,
                    detail="La descarga excede el tamaño máximo permitido.",
                )

            carpeta_usuario = ruta_segura_dentro(
                carpeta_evidencias,
                limpiar_nombre(usuario),
            )
            carpeta_usuario.mkdir(parents=True, exist_ok=True)

            archivo_destino = obtener_ruta_destino_unica(
                carpeta_usuario,
                filename,
                fileid,
            )

            shutil.copyfile(archivo_origen, archivo_destino)

            copiadas += 1
            bytes_reales += tamano_real

            if copiadas > MAX_ARCHIVOS:
                raise HTTPException(
                    status_code=status.HTTP_413_REQUEST_ENTITY_TOO_LARGE,
                    detail=(
                        "La descarga excede el número máximo de archivos permitido."
                    ),
                )

        if copiadas == 0:
            raise HTTPException(
                status_code=status.HTTP_404_NOT_FOUND,
                detail=(
                    "Las evidencias están registradas, pero no hay archivos "
                    "físicos disponibles para descargar."
                ),
            )

        partes_nombre = [limpiar_nombre(nombre_curso)]
        if nombre_grupo:
            partes_nombre.append(limpiar_nombre(nombre_grupo))
        partes_nombre.append(limpiar_nombre(nombre_examen))

        nombre_zip = "_".join(partes_nombre) + ".zip"
        zip_path = ruta_segura_dentro(carpeta_temporal, nombre_zip)

        with zipfile.ZipFile(
            zip_path,
            "w",
            compression=zipfile.ZIP_DEFLATED,
            compresslevel=6,
            allowZip64=True,
        ) as archivo_zip:
            for ruta_completa in carpeta_evidencias.rglob("*"):
                if not ruta_completa.is_file():
                    continue

                # Las rutas del ZIP siempre son relativas a la carpeta raíz.
                ruta_zip = ruta_completa.relative_to(carpeta_evidencias)
                archivo_zip.write(
                    ruta_completa,
                    arcname=ruta_zip.as_posix(),
                )

        logger.info(
            "Descarga autorizada: userid=%s courseid=%s quizid=%s "
            "groupid=%s archivos=%s bytes=%s",
            userid_solicitante,
            courseid,
            quizid,
            groupid,
            copiadas,
            bytes_reales,
        )

        return {
            "zip_path": str(zip_path),
            "zip_name": nombre_zip,
            "temp_dir": str(carpeta_temporal),
            "imagenes": copiadas,
        }

    except HTTPException:
        if carpeta_temporal:
            eliminar_temporal(carpeta_temporal)
        raise

    except Error:
        if carpeta_temporal:
            eliminar_temporal(carpeta_temporal)

        logger.exception("Error de Moodle/MySQL al generar el ZIP.")
        raise HTTPException(
            status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
            detail="No fue posible generar la descarga.",
        )

    except Exception:
        if carpeta_temporal:
            eliminar_temporal(carpeta_temporal)

        logger.exception("Error interno al generar el ZIP.")
        raise HTTPException(
            status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
            detail="Error interno al generar la descarga.",
        )

    finally:
        if cursor is not None:
            try:
                cursor.close()
            except Exception:
                logger.exception("No se pudo cerrar el cursor MySQL.")

        if conexion is not None:
            try:
                if conexion.is_connected():
                    conexion.close()
            except Exception:
                logger.exception("No se pudo cerrar la conexión MySQL.")



# ============================================================
# SELECCIÓN INTERACTIVA
# ============================================================

def obtener_usuario_por_username(cursor, username: str):
    cursor.execute(
        """
        SELECT id, username, firstname, lastname
        FROM mdl_user
        WHERE username = %s
          AND deleted = 0
          AND suspended = 0
        LIMIT 1
        """,
        (username.strip(),),
    )
    return cursor.fetchone()


def obtener_cursos_docente(cursor, userid: int):
    # Administrador: puede ver todos los cursos.
    if usuario_es_admin_moodle(cursor, userid):
        cursor.execute(
            """
            SELECT id, fullname
            FROM mdl_course
            WHERE id > 1
            ORDER BY fullname
            """
        )
        return cursor.fetchall()

    roles = sorted(ROLES_PROFESOR)
    placeholders = ",".join(["%s"] * len(roles))

    consulta = f"""
        SELECT DISTINCT c.id, c.fullname
        FROM mdl_course c
        JOIN mdl_context coursectx
          ON coursectx.contextlevel = 50
         AND coursectx.instanceid = c.id
        JOIN mdl_context assignedctx
          ON coursectx.path LIKE CONCAT(assignedctx.path, '/%%')
          OR coursectx.id = assignedctx.id
        JOIN mdl_role_assignments ra
          ON ra.contextid = assignedctx.id
         AND ra.userid = %s
        JOIN mdl_role r
          ON r.id = ra.roleid
        WHERE c.id > 1
          AND LOWER(r.shortname) IN ({placeholders})
        ORDER BY c.fullname
    """
    cursor.execute(consulta, (userid, *roles))
    return cursor.fetchall()


def obtener_quizzes_curso(cursor, courseid: int):
    cursor.execute(
        """
        SELECT q.id, q.name
        FROM mdl_quiz q
        WHERE q.course = %s
        ORDER BY q.name
        """,
        (courseid,),
    )
    return cursor.fetchall()


def obtener_grupos_curso(cursor, courseid: int):
    cursor.execute(
        """
        SELECT id, name
        FROM mdl_groups
        WHERE courseid = %s
        ORDER BY name
        """,
        (courseid,),
    )
    return cursor.fetchall()


def seleccionar_de_lista(titulo: str, elementos, etiqueta):
    if not elementos:
        return None

    print()
    print(titulo)
    print("-" * 60)
    for i, elemento in enumerate(elementos, start=1):
        print(f"{i}. {etiqueta(elemento)}")

    while True:
        valor = input("Selecciona una opción: ").strip()
        try:
            indice = int(valor)
            if 1 <= indice <= len(elementos):
                return elementos[indice - 1]
        except ValueError:
            pass
        print("Opción inválida. Intenta nuevamente.")


def seleccionar_datos_interactivamente():
    conexion = None
    cursor = None
    try:
        conexion = obtener_conexion()
        cursor = conexion.cursor(dictionary=True)

        print("=" * 60)
        print("DESCARGA DE EVIDENCIAS MOODLE")
        print("=" * 60)

        username = input("Usuario de Moodle: ").strip()
        if not username:
            raise SystemExit("ERROR: Debes indicar tu usuario de Moodle.")

        usuario = obtener_usuario_por_username(cursor, username)
        if not usuario:
            raise SystemExit("ERROR: No se encontró un usuario Moodle activo con ese nombre.")

        userid = int(usuario["id"])

        cursos = obtener_cursos_docente(cursor, userid)
        if not cursos:
            raise SystemExit("ERROR: El usuario no tiene cursos disponibles para descargar.")

        curso = seleccionar_de_lista(
            "Cursos disponibles:",
            cursos,
            lambda x: x["fullname"],
        )

        quizzes = obtener_quizzes_curso(cursor, int(curso["id"]))
        if not quizzes:
            raise SystemExit("ERROR: El curso seleccionado no tiene exámenes tipo quiz.")

        quiz = seleccionar_de_lista(
            "Exámenes disponibles:",
            quizzes,
            lambda x: x["name"],
        )

        grupos = obtener_grupos_curso(cursor, int(curso["id"]))
        groupid = None

        if grupos:
            print()
            print("Grupos disponibles")
            print("-" * 60)
            print("0. Todos los grupos / sin filtro")
            for i, grupo in enumerate(grupos, start=1):
                print(f"{i}. {grupo['name']}")

            while True:
                valor = input("Selecciona un grupo: ").strip()
                try:
                    indice = int(valor)
                    if indice == 0:
                        groupid = None
                        break
                    if 1 <= indice <= len(grupos):
                        groupid = int(grupos[indice - 1]["id"])
                        break
                except ValueError:
                    pass
                print("Opción inválida. Intenta nuevamente.")

        return {
            "userid": userid,
            "courseid": int(curso["id"]),
            "quizid": int(quiz["id"]),
            "groupid": groupid,
        }

    finally:
        if cursor is not None:
            try:
                cursor.close()
            except Exception:
                pass
        if conexion is not None:
            try:
                if conexion.is_connected():
                    conexion.close()
            except Exception:
                pass


# ============================================================
# EJECUCIÓN POR CONSOLA
# ============================================================

def main():
    import argparse

    parser = argparse.ArgumentParser(
        description="Descarga evidencias de Moodle y genera un archivo ZIP."
    )
    parser.add_argument("--courseid", type=int, help="ID del curso Moodle.")
    parser.add_argument("--quizid", type=int, help="ID del quiz Moodle.")
    parser.add_argument("--groupid", type=int, default=None, help="ID opcional del grupo.")
    parser.add_argument("--userid", type=int, help="ID Moodle del profesor/administrador.")
    parser.add_argument(
        "--salida",
        default=None,
        help="Ruta o nombre del ZIP de salida. Si se omite, usa el nombre generado.",
    )
    args = parser.parse_args()

    # Si no se proporcionan todos los IDs esenciales, se usa el modo interactivo.
    if not (args.courseid and args.quizid and args.userid):
        seleccion = seleccionar_datos_interactivamente()
        courseid = seleccion["courseid"]
        quizid = seleccion["quizid"]
        userid = seleccion["userid"]
        groupid = seleccion["groupid"]
    else:
        courseid = args.courseid
        quizid = args.quizid
        userid = args.userid
        groupid = args.groupid

    resultado = None
    try:
        resultado = generar_zip(
            courseid=courseid,
            quizid=quizid,
            groupid=groupid,
            userid_solicitante=userid,
        )

        origen = Path(resultado["zip_path"]).resolve()
        if args.salida:
            destino = Path(args.salida).expanduser().resolve()
            if destino.is_dir():
                destino = destino / resultado["zip_name"]
        else:
            destino = Path.cwd() / resultado["zip_name"]

        destino.parent.mkdir(parents=True, exist_ok=True)
        shutil.copy2(origen, destino)

        print()
        print("=" * 60)
        print("DESCARGA COMPLETADA")
        print("=" * 60)
        print(f"Archivo ZIP: {destino}")
        print(f"Imágenes incluidas: {resultado['imagenes']}")

    except HTTPException as exc:
        logger.error("No se pudo generar la descarga: %s", exc.detail)
        raise SystemExit(f"ERROR: {exc.detail}")
    except Exception:
        logger.exception("Error inesperado durante la descarga.")
        raise SystemExit("ERROR: No fue posible generar la descarga.")
    finally:
        if resultado and resultado.get("temp_dir"):
            eliminar_temporal(resultado["temp_dir"])


if __name__ == "__main__":
    main()
