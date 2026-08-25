import os
import re
import shutil
import zipfile
from datetime import datetime

import mysql.connector
from mysql.connector import Error


# ============================================================
# CONFIGURACIÓN
# ============================================================

DB_CONFIG = {
    "host": "localhost",
    "user": "moodleuser",
    "password": os.getenv("MOODLE_DB_PASSWORD", "moodle3xamen"),
    "database": "moodle",
    "charset": "utf8mb4",
}

# Carpeta filedir de moodledata.
MOODLEDATA_FILEDIR = os.getenv(
    "MOODLEDATA_FILEDIR",
    "/var/moodledata/filedir"
)

# Carpeta donde se guardarán las imágenes descargadas.
OUTPUT_DIR = os.getenv(
    "MOODLE_OUTPUT_DIR",
    "/home/egresados/moodle_imgs"
)

# Áreas del plugin que contienen imágenes.
FILE_AREAS = ("picture", "face_image")

# Elimina la carpeta del examen antes de volver a descargar.
# Evita conservar archivos de ejecuciones anteriores.
LIMPIAR_CARPETA_SELECCIONADA = True

# Crear archivo ZIP al finalizar.
CREAR_ZIP = True


# ============================================================
# FUNCIONES AUXILIARES
# ============================================================

def limpiar_nombre(nombre):
    """
    Convierte un texto en un nombre válido para carpetas
    y archivos en Linux y Windows.
    """
    if nombre is None:
        return "Sin_nombre"

    nombre = str(nombre).strip()

    if not nombre:
        return "Sin_nombre"

    nombre = re.sub(r'[\\/:*?"<>|]', "_", nombre)
    nombre = re.sub(r"[\r\n\t]+", " ", nombre)
    nombre = re.sub(r"\s+", " ", nombre).strip()

    return nombre[:180]


def seleccionar_elemento(elementos, titulo, campo_nombre):
    """
    Muestra un menú numerado y devuelve el elemento seleccionado.
    """
    if not elementos:
        print(f"\nNo se encontraron registros para: {titulo}")
        return None

    print("\n" + "=" * 75)
    print(titulo)
    print("=" * 75)

    for indice, elemento in enumerate(elementos, start=1):
        nombre = elemento.get(campo_nombre, "Sin nombre")

        identificador = (
            elemento.get("id")
            or elemento.get("quizid")
            or elemento.get("groupid")
            or elemento.get("courseid")
        )

        print(f"{indice:3}. {nombre} [ID: {identificador}]")

    while True:
        respuesta = input(
            "\nEscribe el número correspondiente o 'q' para salir: "
        ).strip()

        if respuesta.lower() in ("q", "salir", "exit"):
            return None

        try:
            numero = int(respuesta)

            if 1 <= numero <= len(elementos):
                return elementos[numero - 1]

            print("El número seleccionado está fuera del rango.")

        except ValueError:
            print("Debes escribir un número válido.")


def obtener_ruta_origen(contenthash):
    """
    Obtiene la ruta física del archivo dentro de moodledata/filedir.
    """
    subcarpeta_1 = contenthash[:2]
    subcarpeta_2 = contenthash[2:4]

    return os.path.join(
        MOODLEDATA_FILEDIR,
        subcarpeta_1,
        subcarpeta_2,
        contenthash
    )


def obtener_ruta_destino_unica(carpeta, filename, fileid):
    """
    Evita sobrescribir imágenes cuando existen archivos
    con el mismo nombre.
    """
    filename = limpiar_nombre(filename)
    destino = os.path.join(carpeta, filename)

    if not os.path.exists(destino):
        return destino

    nombre, extension = os.path.splitext(filename)

    return os.path.join(
        carpeta,
        f"{nombre}_file_{fileid}{extension}"
    )


def escribir_registro(registro_path, texto):
    """
    Agrega una línea al registro de descargas.
    """
    fecha_hora = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

    with open(registro_path, "a", encoding="utf-8") as archivo:
        archivo.write(f"[{fecha_hora}] {texto}\n")


def crear_zip(carpeta_examen, zip_path):
    """
    Comprime la carpeta del examen.
    """
    if os.path.exists(zip_path):
        os.remove(zip_path)

    carpeta_padre = os.path.dirname(carpeta_examen)

    with zipfile.ZipFile(
        zip_path,
        mode="w",
        compression=zipfile.ZIP_DEFLATED,
        compresslevel=6
    ) as archivo_zip:

        for raiz, _, archivos in os.walk(carpeta_examen):
            for archivo in archivos:
                ruta_completa = os.path.join(raiz, archivo)

                ruta_dentro_zip = os.path.relpath(
                    ruta_completa,
                    carpeta_padre
                )

                archivo_zip.write(
                    ruta_completa,
                    arcname=ruta_dentro_zip
                )


# ============================================================
# CONSULTAS A MOODLE
# ============================================================

def obtener_cursos(cursor):
    """
    Obtiene los cursos que tienen imágenes del plugin
    quizaccess_proctoring.
    """
    consulta = """
        SELECT DISTINCT
            c.id,
            c.fullname,
            c.shortname
        FROM mdl_course c

        JOIN mdl_quiz q
            ON q.course = c.id

        JOIN mdl_course_modules cm
            ON cm.instance = q.id

        JOIN mdl_modules m
            ON m.id = cm.module
           AND m.name = 'quiz'

        JOIN mdl_context ctx
            ON ctx.instanceid = cm.id
           AND ctx.contextlevel = 70

        JOIN mdl_files f
            ON f.contextid = ctx.id

        WHERE f.component = 'quizaccess_proctoring'
          AND f.filearea IN ('picture', 'face_image')
          AND f.filename <> '.'
          AND f.mimetype LIKE 'image/%%'

        ORDER BY c.fullname, c.id
    """

    cursor.execute(consulta)
    return cursor.fetchall()


def obtener_grupos(cursor, courseid):
    """
    Obtiene todos los grupos existentes dentro del curso.

    Si devuelve una lista vacía, significa que el curso
    no tiene grupos y se debe pasar directamente al menú
    de exámenes.
    """
    consulta = """
        SELECT DISTINCT
            g.id,
            g.name,
            g.courseid
        FROM mdl_groups g
        WHERE g.courseid = %s
        ORDER BY g.name, g.id
    """

    cursor.execute(consulta, (courseid,))
    return cursor.fetchall()


def obtener_examenes(cursor, courseid, groupid=None):
    """
    Obtiene los exámenes del curso.

    Si groupid contiene un ID:
        obtiene exámenes con imágenes de usuarios del grupo.

    Si groupid es None:
        obtiene exámenes con imágenes sin aplicar filtro de grupo.
    """

    if groupid is not None:
        consulta = """
            SELECT DISTINCT
                q.id AS quizid,
                q.name,
                q.course AS courseid,
                cm.id AS coursemodule,
                ctx.id AS contextid

            FROM mdl_quiz q

            JOIN mdl_course_modules cm
                ON cm.instance = q.id

            JOIN mdl_modules m
                ON m.id = cm.module
               AND m.name = 'quiz'

            JOIN mdl_context ctx
                ON ctx.instanceid = cm.id
               AND ctx.contextlevel = 70

            JOIN mdl_files f
                ON f.contextid = ctx.id

            JOIN mdl_groups_members gm
                ON gm.userid = f.userid
               AND gm.groupid = %s

            WHERE q.course = %s
              AND f.component = 'quizaccess_proctoring'
              AND f.filearea IN ('picture', 'face_image')
              AND f.filename <> '.'
              AND f.mimetype LIKE 'image/%%'
              AND EXISTS (
                  SELECT 1
                  FROM mdl_quiz_attempts qa
                  WHERE qa.quiz = q.id
                    AND qa.userid = f.userid
                    AND qa.preview = 0
              )

            ORDER BY q.name, q.id
        """

        cursor.execute(
            consulta,
            (groupid, courseid)
        )

    else:
        consulta = """
            SELECT DISTINCT
                q.id AS quizid,
                q.name,
                q.course AS courseid,
                cm.id AS coursemodule,
                ctx.id AS contextid

            FROM mdl_quiz q

            JOIN mdl_course_modules cm
                ON cm.instance = q.id

            JOIN mdl_modules m
                ON m.id = cm.module
               AND m.name = 'quiz'

            JOIN mdl_context ctx
                ON ctx.instanceid = cm.id
               AND ctx.contextlevel = 70

            JOIN mdl_files f
                ON f.contextid = ctx.id

            WHERE q.course = %s
              AND f.component = 'quizaccess_proctoring'
              AND f.filearea IN ('picture', 'face_image')
              AND f.filename <> '.'
              AND f.mimetype LIKE 'image/%%'
              AND EXISTS (
                  SELECT 1
                  FROM mdl_quiz_attempts qa
                  WHERE qa.quiz = q.id
                    AND qa.userid = f.userid
                    AND qa.preview = 0
              )

            ORDER BY q.name, q.id
        """

        cursor.execute(
            consulta,
            (courseid,)
        )

    return cursor.fetchall()


def obtener_imagenes(cursor, courseid, quizid, groupid=None):
    """
    Obtiene las imágenes del examen.

    Con grupo:
        solo imágenes de usuarios pertenecientes al grupo.

    Sin grupo:
        imágenes de todos los usuarios que presentaron el examen.
    """

    if groupid is not None:
        consulta = """
            SELECT DISTINCT
                f.id AS fileid,
                f.filename,
                f.contenthash,
                f.filearea,
                f.mimetype,
                f.filesize,
                f.timecreated,
                f.timemodified,

                u.id AS userid,
                u.username,
                u.firstname,
                u.lastname,

                q.id AS quizid,
                q.name AS examen,

                c.id AS courseid,
                c.fullname AS curso,

                g.id AS groupid,
                g.name AS grupo,

                cm.id AS coursemodule,
                ctx.id AS contextid

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

            JOIN mdl_groups_members gm
                ON gm.userid = u.id
               AND gm.groupid = %s

            JOIN mdl_groups g
                ON g.id = gm.groupid
               AND g.courseid = c.id

            WHERE f.component = 'quizaccess_proctoring'
              AND f.filearea IN ('picture', 'face_image')
              AND f.filename <> '.'
              AND f.mimetype LIKE 'image/%%'
              AND u.deleted = 0
              AND EXISTS (
                  SELECT 1
                  FROM mdl_quiz_attempts qa
                  WHERE qa.quiz = q.id
                    AND qa.userid = u.id
                    AND qa.preview = 0
              )

            ORDER BY
                u.username,
                f.timecreated,
                f.id
        """

        cursor.execute(
            consulta,
            (quizid, courseid, groupid)
        )

    else:
        consulta = """
            SELECT DISTINCT
                f.id AS fileid,
                f.filename,
                f.contenthash,
                f.filearea,
                f.mimetype,
                f.filesize,
                f.timecreated,
                f.timemodified,

                u.id AS userid,
                u.username,
                u.firstname,
                u.lastname,

                q.id AS quizid,
                q.name AS examen,

                c.id AS courseid,
                c.fullname AS curso,

                NULL AS groupid,
                NULL AS grupo,

                cm.id AS coursemodule,
                ctx.id AS contextid

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

            WHERE f.component = 'quizaccess_proctoring'
              AND f.filearea IN ('picture', 'face_image')
              AND f.filename <> '.'
              AND f.mimetype LIKE 'image/%%'
              AND u.deleted = 0
              AND EXISTS (
                  SELECT 1
                  FROM mdl_quiz_attempts qa
                  WHERE qa.quiz = q.id
                    AND qa.userid = u.id
                    AND qa.preview = 0
              )

            ORDER BY
                u.username,
                f.timecreated,
                f.id
        """

        cursor.execute(
            consulta,
            (quizid, courseid)
        )

    return cursor.fetchall()


# ============================================================
# PROCESO PRINCIPAL
# ============================================================

def main():
    conexion = None
    cursor = None

    os.makedirs(OUTPUT_DIR, exist_ok=True)

    registro_path = os.path.join(
        OUTPUT_DIR,
        "registro_descargas.txt"
    )

    print("=" * 75)
    print("DESCARGA DE EVIDENCIAS DE PROCTORING DE MOODLE")
    print("=" * 75)
    print(f"Moodledata: {MOODLEDATA_FILEDIR}")
    print(f"Salida:     {OUTPUT_DIR}")

    try:
        # ----------------------------------------------------
        # Conexión con la base de datos
        # ----------------------------------------------------
        conexion = mysql.connector.connect(**DB_CONFIG)
        cursor = conexion.cursor(dictionary=True)

        print("\nConexión con la base de datos establecida.")

        # ----------------------------------------------------
        # 1. Seleccionar curso
        # ----------------------------------------------------
        cursos = obtener_cursos(cursor)

        curso = seleccionar_elemento(
            cursos,
            "CURSOS CON EVIDENCIAS DISPONIBLES",
            "fullname"
        )

        if curso is None:
            print("Proceso cancelado.")
            return

        courseid = curso["id"]
        nombre_curso = curso["fullname"]

        # ----------------------------------------------------
        # 2. Detectar grupos
        # ----------------------------------------------------
        grupos = obtener_grupos(cursor, courseid)

        if grupos:
            print(
                f"\nEl curso '{nombre_curso}' tiene "
                f"{len(grupos)} grupo(s)."
            )

            grupo = seleccionar_elemento(
                grupos,
                f"GRUPOS DEL CURSO: {nombre_curso}",
                "name"
            )

            if grupo is None:
                print("Proceso cancelado.")
                return

            groupid = grupo["id"]
            nombre_grupo = grupo["name"]

        else:
            print(
                f"\nEl curso '{nombre_curso}' no tiene grupos."
            )
            print(
                "Se mostrarán directamente los exámenes disponibles."
            )

            groupid = None
            nombre_grupo = None

        grupo_registro = (
            nombre_grupo
            if nombre_grupo
            else "Curso sin grupos"
        )

        # ----------------------------------------------------
        # 3. Seleccionar examen
        # ----------------------------------------------------
        examenes = obtener_examenes(
            cursor,
            courseid,
            groupid
        )

        if nombre_grupo:
            titulo_examenes = (
                f"EXÁMENES DEL CURSO: {nombre_curso}\n"
                f"GRUPO: {nombre_grupo}"
            )
        else:
            titulo_examenes = (
                f"EXÁMENES DEL CURSO: {nombre_curso}"
            )

        examen = seleccionar_elemento(
            examenes,
            titulo_examenes,
            "name"
        )

        if examen is None:
            print("Proceso cancelado.")
            return

        quizid = examen["quizid"]
        nombre_examen = examen["name"]

        # ----------------------------------------------------
        # 4. Crear estructura de carpetas
        # ----------------------------------------------------
        carpeta_curso = os.path.join(
            OUTPUT_DIR,
            limpiar_nombre(nombre_curso)
        )

        if nombre_grupo:
            carpeta_grupo = os.path.join(
                carpeta_curso,
                limpiar_nombre(nombre_grupo)
            )

            carpeta_examen = os.path.join(
                carpeta_grupo,
                limpiar_nombre(nombre_examen)
            )

            carpeta_zip = carpeta_grupo

        else:
            carpeta_grupo = None

            carpeta_examen = os.path.join(
                carpeta_curso,
                limpiar_nombre(nombre_examen)
            )

            carpeta_zip = carpeta_curso

        if (
            LIMPIAR_CARPETA_SELECCIONADA
            and os.path.isdir(carpeta_examen)
        ):
            print(
                "\nEliminando descarga anterior del examen..."
            )
            shutil.rmtree(carpeta_examen)

        os.makedirs(carpeta_examen, exist_ok=True)

        # ----------------------------------------------------
        # 5. Obtener imágenes
        # ----------------------------------------------------
        imagenes = obtener_imagenes(
            cursor,
            courseid,
            quizid,
            groupid
        )

        print("\n" + "-" * 75)
        print(f"Curso:  {nombre_curso}")

        if nombre_grupo:
            print(f"Grupo:  {nombre_grupo}")
        else:
            print("Grupo:  Curso sin grupos")

        print(f"Examen: {nombre_examen}")
        print(f"Imágenes encontradas: {len(imagenes)}")
        print("-" * 75)

        if not imagenes:
            print(
                "\nNo se encontraron imágenes para la selección."
            )

            if os.path.isdir(carpeta_examen):
                try:
                    os.rmdir(carpeta_examen)
                except OSError:
                    pass

            return

        copiadas = 0
        no_encontradas = 0
        errores = 0
        usuarios = set()

        # ----------------------------------------------------
        # 6. Copiar imágenes
        # ----------------------------------------------------
        for imagen in imagenes:
            usuario = imagen["username"]
            fileid = imagen["fileid"]
            filename = imagen["filename"]
            contenthash = imagen["contenthash"]
            filearea = imagen["filearea"]

            usuarios.add(usuario)

            carpeta_usuario = os.path.join(
                carpeta_examen,
                limpiar_nombre(usuario)
            )

            os.makedirs(carpeta_usuario, exist_ok=True)

            archivo_origen = obtener_ruta_origen(contenthash)

            archivo_destino = obtener_ruta_destino_unica(
                carpeta_usuario,
                filename,
                fileid
            )

            try:
                if not os.path.isfile(archivo_origen):
                    no_encontradas += 1

                    mensaje = (
                        f"NO ENCONTRADO | "
                        f"Curso: {nombre_curso} | "
                        f"Grupo: {grupo_registro} | "
                        f"Examen: {nombre_examen} | "
                        f"Usuario: {usuario} | "
                        f"Archivo: {filename} | "
                        f"Contenthash: {contenthash} | "
                        f"Ruta: {archivo_origen}"
                    )

                    print(f"No encontrado: {archivo_origen}")
                    escribir_registro(registro_path, mensaje)
                    continue

                shutil.copy2(
                    archivo_origen,
                    archivo_destino
                )

                copiadas += 1

                print(
                    f"Copiada [{filearea}]: "
                    f"{usuario}/"
                    f"{os.path.basename(archivo_destino)}"
                )

                mensaje = (
                    f"COPIADO | "
                    f"Curso: {nombre_curso} | "
                    f"Grupo: {grupo_registro} | "
                    f"Examen: {nombre_examen} | "
                    f"Usuario: {usuario} | "
                    f"Área: {filearea} | "
                    f"Archivo: {filename} | "
                    f"Destino: {archivo_destino}"
                )

                escribir_registro(registro_path, mensaje)

            except OSError as error:
                errores += 1

                mensaje = (
                    f"ERROR | "
                    f"Curso: {nombre_curso} | "
                    f"Grupo: {grupo_registro} | "
                    f"Examen: {nombre_examen} | "
                    f"Usuario: {usuario} | "
                    f"Archivo: {filename} | "
                    f"Detalle: {error}"
                )

                print(f"Error copiando {filename}: {error}")
                escribir_registro(registro_path, mensaje)

        # ----------------------------------------------------
        # 7. Crear ZIP
        # ----------------------------------------------------
        zip_path = None

        if CREAR_ZIP and copiadas > 0:
            if nombre_grupo:
                nombre_zip = (
                    f"{limpiar_nombre(nombre_curso)}_"
                    f"{limpiar_nombre(nombre_grupo)}_"
                    f"{limpiar_nombre(nombre_examen)}.zip"
                )
            else:
                nombre_zip = (
                    f"{limpiar_nombre(nombre_curso)}_"
                    f"{limpiar_nombre(nombre_examen)}.zip"
                )

            zip_path = os.path.join(
                carpeta_zip,
                nombre_zip
            )

            print("\nCreando archivo ZIP...")

            crear_zip(
                carpeta_examen,
                zip_path
            )

            print(f"ZIP creado: {zip_path}")

            escribir_registro(
                registro_path,
                (
                    f"ZIP CREADO | "
                    f"Curso: {nombre_curso} | "
                    f"Grupo: {grupo_registro} | "
                    f"Examen: {nombre_examen} | "
                    f"Ruta: {zip_path}"
                )
            )

        # ----------------------------------------------------
        # 8. Resumen
        # ----------------------------------------------------
        print("\n" + "=" * 75)
        print("PROCESO FINALIZADO")
        print("=" * 75)
        print(f"Curso:                 {nombre_curso}")
        print(f"Grupo:                 {grupo_registro}")
        print(f"Examen:                {nombre_examen}")
        print(f"Usuarios encontrados: {len(usuarios)}")
        print(f"Imágenes copiadas:     {copiadas}")
        print(f"No encontradas:        {no_encontradas}")
        print(f"Errores:                {errores}")
        print(f"Carpeta:                {carpeta_examen}")

        if zip_path:
            print(f"ZIP:                    {zip_path}")

        print(f"Registro:               {registro_path}")

    except Error as error:
        print("\nError al conectarse o consultar la base de datos:")
        print(error)

    except KeyboardInterrupt:
        print("\n\nProceso cancelado por el usuario.")

    except Exception as error:
        print("\nOcurrió un error inesperado:")
        print(error)

    finally:
        if cursor is not None:
            cursor.close()

        if conexion is not None and conexion.is_connected():
            conexion.close()

        print("\nConexión con la base de datos cerrada.")


if __name__ == "__main__":
    main()