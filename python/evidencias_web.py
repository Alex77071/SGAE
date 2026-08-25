import sys
import json
import os
import shutil

import mysql.connector

from descargar_evidencias import (
    DB_CONFIG,
    OUTPUT_DIR,
    obtener_cursos,
    obtener_grupos,
    obtener_examenes,
    obtener_imagenes,
    limpiar_nombre,
    obtener_ruta_origen,
    obtener_ruta_destino_unica,
    crear_zip,
)


def conectar():
    conexion = mysql.connector.connect(**DB_CONFIG)
    cursor = conexion.cursor(dictionary=True)

    return conexion, cursor


def responder(data):
    print(
        json.dumps(
            data,
            ensure_ascii=False
        )
    )


def listar_cursos():
    conexion, cursor = conectar()

    try:
        cursos = obtener_cursos(cursor)

        responder({
            "ok": True,
            "data": cursos
        })

    finally:
        cursor.close()
        conexion.close()


def listar_grupos(courseid):
    conexion, cursor = conectar()

    try:
        grupos = obtener_grupos(
            cursor,
            courseid
        )

        responder({
            "ok": True,
            "data": grupos
        })

    finally:
        cursor.close()
        conexion.close()


def listar_examenes(courseid, groupid=None):
    conexion, cursor = conectar()

    try:
        examenes = obtener_examenes(
            cursor,
            courseid,
            groupid
        )

        responder({
            "ok": True,
            "data": examenes
        })

    finally:
        cursor.close()
        conexion.close()


def descargar(courseid, quizid, groupid=None):
    conexion, cursor = conectar()

    try:
        imagenes = obtener_imagenes(
            cursor,
            courseid,
            quizid,
            groupid
        )

        if not imagenes:
            responder({
                "ok": False,
                "message": "No se encontraron evidencias."
            })
            return


        primera = imagenes[0]

        nombre_curso = primera["curso"]
        nombre_examen = primera["examen"]

        nombre_grupo = (
            primera["grupo"]
            if groupid
            else None
        )


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

            carpeta_examen = os.path.join(
                carpeta_curso,
                limpiar_nombre(nombre_examen)
            )

            carpeta_zip = carpeta_curso


        if os.path.isdir(carpeta_examen):
            shutil.rmtree(carpeta_examen)


        os.makedirs(
            carpeta_examen,
            exist_ok=True
        )


        copiadas = 0


        for imagen in imagenes:

            usuario = imagen["username"]

            carpeta_usuario = os.path.join(
                carpeta_examen,
                limpiar_nombre(usuario)
            )

            os.makedirs(
                carpeta_usuario,
                exist_ok=True
            )


            origen = obtener_ruta_origen(
                imagen["contenthash"]
            )


            if not os.path.isfile(origen):
                continue


            destino = obtener_ruta_destino_unica(
                carpeta_usuario,
                imagen["filename"],
                imagen["fileid"]
            )


            shutil.copy2(
                origen,
                destino
            )

            copiadas += 1


        if copiadas == 0:

            responder({
                "ok": False,
                "message": "Las evidencias existen en Moodle pero los archivos físicos no fueron encontrados."
            })

            return


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


        crear_zip(
            carpeta_examen,
            zip_path
        )


        responder({
            "ok": True,
            "message": "Evidencias descargadas correctamente.",
            "zip": zip_path,
            "imagenes": copiadas,
        })


    finally:

        cursor.close()
        conexion.close()


def main():

    if len(sys.argv) < 2:

        responder({
            "ok": False,
            "message": "No se especificó una acción."
        })

        return


    accion = sys.argv[1]


    try:

        if accion == "cursos":

            listar_cursos()


        elif accion == "grupos":

            courseid = int(sys.argv[2])

            listar_grupos(
                courseid
            )


        elif accion == "examenes":

            courseid = int(sys.argv[2])

            groupid = None

            if len(sys.argv) >= 4:
                if sys.argv[3] != "null":
                    groupid = int(
                        sys.argv[3]
                    )


            listar_examenes(
                courseid,
                groupid
            )


        elif accion == "descargar":

            courseid = int(sys.argv[2])

            quizid = int(sys.argv[3])

            groupid = None


            if len(sys.argv) >= 5:
                if sys.argv[4] != "null":
                    groupid = int(
                        sys.argv[4]
                    )


            descargar(
                courseid,
                quizid,
                groupid
            )


        else:

            responder({
                "ok": False,
                "message": "Acción desconocida."
            })


    except Exception as error:

        responder({
            "ok": False,
            "message": str(error)
        })


if __name__ == "__main__":
    main()