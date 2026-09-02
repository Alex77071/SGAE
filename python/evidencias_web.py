import os
import sys
import json
import shutil
from pathlib import Path

from descargar_evidencias import (
    obtener_conexion,
    obtener_cursos_docente,
    obtener_quizzes_curso,
    obtener_grupos_curso,
    usuario_puede_descargar_curso,
    generar_zip,
    eliminar_temporal,
    HTTPException,
)


# ============================================================
# RESPUESTA JSON
# ============================================================

def responder(datos):
    print(
        json.dumps(
            datos,
            ensure_ascii=False
        )
    )


# ============================================================
# CONVERTIR ID
# ============================================================

def convertir_id(valor):
    try:
        return int(valor)
    except (TypeError, ValueError):
        return None


# ============================================================
# OBTENER CURSOS DEL PROFESOR
# ============================================================

def listar_cursos(userid):
    conexion = None
    cursor = None

    try:
        conexion = obtener_conexion()

        cursor = conexion.cursor(
            dictionary=True
        )

        cursos = obtener_cursos_docente(
            cursor,
            userid
        )

        resultado = []

        for curso in cursos:

            resultado.append({
                "id": int(curso["id"]),
                "nombre": curso["fullname"],
            })

        responder({
            "ok": True,
            "cursos": resultado,
        })

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
# OBTENER GRUPOS DEL CURSO
# ============================================================

def listar_grupos(userid, courseid):
    conexion = None
    cursor = None

    try:
        conexion = obtener_conexion()

        cursor = conexion.cursor(
            dictionary=True
        )

        # Verificar que el profesor
        # tenga acceso al curso.
        if not usuario_puede_descargar_curso(
            cursor,
            userid,
            courseid
        ):

            responder({
                "ok": False,
                "message":
                    "No tienes permiso para acceder a este curso.",
            })

            return

        grupos = obtener_grupos_curso(
            cursor,
            courseid
        )

        resultado = []

        for grupo in grupos:

            resultado.append({
                "id": int(grupo["id"]),
                "nombre": grupo["name"],
            })

        responder({
            "ok": True,
            "grupos": resultado,
        })

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
# OBTENER EXÁMENES DEL CURSO
# ============================================================

def listar_examenes(userid, courseid):
    conexion = None
    cursor = None

    try:
        conexion = obtener_conexion()

        cursor = conexion.cursor(
            dictionary=True
        )

        # Verificar permisos.
        if not usuario_puede_descargar_curso(
            cursor,
            userid,
            courseid
        ):

            responder({
                "ok": False,
                "message":
                    "No tienes permiso para acceder a este curso.",
            })

            return

        examenes = obtener_quizzes_curso(
            cursor,
            courseid
        )

        resultado = []

        for examen in examenes:

            resultado.append({
                "id": int(examen["id"]),
                "nombre": examen["name"],
            })

        responder({
            "ok": True,
            "examenes": resultado,
        })

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
# DESCARGAR EVIDENCIAS
# ============================================================

def descargar_evidencias(
    userid,
    courseid,
    quizid,
    groupid=None
):
    resultado = None

    try:

        resultado = generar_zip(
            courseid=courseid,
            quizid=quizid,
            userid_solicitante=userid,
            groupid=groupid,
        )

        zip_origen = Path(
            resultado["zip_path"]
        ).resolve()


        # Carpeta donde Laravel podrá
        # encontrar temporalmente el ZIP.
        carpeta_salida = Path(
            os.getenv(
                "MOODLE_OUTPUT_DIR",
                os.getcwd()
            )
        ).resolve()


        carpeta_salida.mkdir(
            parents=True,
            exist_ok=True
        )


        zip_destino = (
            carpeta_salida /
            resultado["zip_name"]
        )


        shutil.copy2(
            zip_origen,
            zip_destino
        )


        responder({
            "ok": True,
            "zip": str(zip_destino),
            "nombre": resultado["zip_name"],
            "imagenes": resultado["imagenes"],
        })


    finally:

        # El ZIP original se genera
        # dentro de una carpeta temporal.
        if (
            resultado
            and resultado.get("temp_dir")
        ):

            eliminar_temporal(
                resultado["temp_dir"]
            )


# ============================================================
# PROGRAMA PRINCIPAL
# ============================================================

def main():

    if len(sys.argv) < 2:

        responder({
            "ok": False,
            "message":
                "No se indicó ninguna acción.",
        })

        return


    accion = sys.argv[1]


    try:

        # ====================================================
        # CURSOS
        #
        # evidencias_web.py cursos USERID
        # ====================================================

        if accion == "cursos":

            if len(sys.argv) < 3:
                raise ValueError(
                    "Falta el ID del profesor."
                )

            userid = convertir_id(
                sys.argv[2]
            )


            if not userid:
                raise ValueError(
                    "El ID del profesor no es válido."
                )


            listar_cursos(
                userid
            )

            return


        # ====================================================
        # GRUPOS
        #
        # evidencias_web.py grupos USERID COURSEID
        # ====================================================

        if accion == "grupos":

            if len(sys.argv) < 4:
                raise ValueError(
                    "Faltan parámetros para consultar grupos."
                )


            userid = convertir_id(
                sys.argv[2]
            )

            courseid = convertir_id(
                sys.argv[3]
            )


            if not userid or not courseid:

                raise ValueError(
                    "Los parámetros de grupos no son válidos."
                )


            listar_grupos(
                userid,
                courseid
            )

            return


        # ====================================================
        # EXÁMENES
        #
        # evidencias_web.py examenes USERID COURSEID
        # ====================================================

        if accion == "examenes":

            if len(sys.argv) < 4:

                raise ValueError(
                    "Faltan parámetros para consultar exámenes."
                )


            userid = convertir_id(
                sys.argv[2]
            )

            courseid = convertir_id(
                sys.argv[3]
            )


            if not userid or not courseid:

                raise ValueError(
                    "Los parámetros de exámenes no son válidos."
                )


            listar_examenes(
                userid,
                courseid
            )

            return


        # ====================================================
        # DESCARGAR
        #
        # evidencias_web.py
        # descargar USERID COURSEID QUIZID GROUPID
        # ====================================================

        if accion == "descargar":

            if len(sys.argv) < 5:

                raise ValueError(
                    "Faltan parámetros para realizar la descarga."
                )


            userid = convertir_id(
                sys.argv[2]
            )

            courseid = convertir_id(
                sys.argv[3]
            )

            quizid = convertir_id(
                sys.argv[4]
            )


            groupid = None


            if (
                len(sys.argv) >= 6
                and sys.argv[5] != "null"
            ):

                groupid = convertir_id(
                    sys.argv[5]
                )


            if (
                not userid
                or not courseid
                or not quizid
            ):

                raise ValueError(
                    "Los parámetros de descarga no son válidos."
                )


            descargar_evidencias(
                userid=userid,
                courseid=courseid,
                quizid=quizid,
                groupid=groupid
            )

            return


        # ====================================================
        # ACCIÓN DESCONOCIDA
        # ====================================================

        responder({
            "ok": False,
            "message":
                "La acción indicada no es válida.",
        })


    except HTTPException as exc:

        responder({
            "ok": False,
            "message": exc.detail,
        })


    except Exception as exc:

        responder({
            "ok": False,
            "message": str(exc),
        })


# ============================================================
# INICIO
# ============================================================

if __name__ == "__main__":
    main()