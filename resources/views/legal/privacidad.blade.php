@extends('layouts.guest')

@section('content')

<section class="privacy-screen">

    <div class="privacy-card">

        {{-- TÍTULO --}}
        <div class="privacy-card__header">

            <h1 class="privacy-card__title">
                Aviso de Privacidad
            </h1>

            <p class="privacy-card__subtitle">
                Sistema de Gestión y Análisis de Evidencias
            </p>

        </div>


        {{-- CONTENIDO --}}
        <div class="privacy-card__content">

            <p>
                La Universidad Tecnológica de la Mixteca en adelante "la UTM" 
                en cumplimiento a la Ley General de Protección de Datos Personales
                en Posesión de Sujetos Obligados, Ley de Protección de Datos Personales
                en Posesión de Sujetos Obligados del Estado de Oaxaca y sus lineamientos,
                hacen de su conocimiento que es la responsable del tratamiento de los datos
                personales que nos proporcione; Al proporcionar sus datos a la UTM se da
                por entendido que está de acuerdo con los términos de este aviso, las 
                finalidades del tratamiento de los datos así como los medios y procedimiento
                que ponemos a su disposición para ejercer sus derechos de Acceso, Rectificación, 
                cancelación y oposición de este aviso de privacidad. Los datos personales que 
                recabamos de usted (estudiantes, empleados, proveedores, usuarios y grupos de interés),
                los utilizaremos para las siguientes finalidades que son necesarias dentro de las funciones
                sustantivas y adjetivas, y actividades propias de "La Universidad": Actividades, trámites 
                y servicios académicos, de investigación, administrativos y servicios diversos, que de manera
                enunciativa y no limitativa serán los relativos a la Rectoría, Secretario Particular del Rector
                , Abogado General, Departamento de Auditoría Interna, Coordinación de Promoción del Desarrollo,
                Coordinación de Difusión Cultural, Coordinación de Investigación, Dirección de Kadasoftware, 
                Vice-Rectoría Académica, Direcciones de Institutos de Investigación, Jefaturas de Carrera, 
                Jefatura de la División de Estudios de Postgrados, Jefatura del Centro de Idiomas, Departamento
                de Servicios Escolares, Jefatura de Biblioteca, Jefatura de Archivo Histórico de Minería, 
                Coordinación de la Universidad Virtual, Vice-Rectoría de Administración, Departamento de 
                Recursos Financieros, Departamento de Recursos Materiales, Departamento de Recursos Humanos, 
                Departamento de Red de Cómputo, Departamento de Gestión Administrativa, Departamento de Proyectos,
                Construcción y Mantenimiento, Departamento de Mantenimiento Eléctrico, Departamento de Talleres, 
                y Vice-Rectoría de Relaciones y Recursos; así como las demás relativas a la contraloría, marco 
                legal, gestión, planeación, estadística universitaria, actividades como pueden ser sociales, de 
                difusión de la cultura, deportivos, médicos, recreativos, empresariales, de investigación, e
                xtensión, publicación de eventos, sistema de consulta en línea para terceros (padres de familia),
                entre otros y/o cualquier actividad y obligación surgida del quehacer universitario."
            </p>


            <p>
                Para ejercer los derechos de Acceso, Rectificación, Cancelación, Oposición y Portabilidad
                al tratamiento de datos personales (ARCOP), podrá dirigirse a la Unidad de Transparencia 
                de la UTM, ubicada en Avenida Doctor Modesto Seara Vázquez No. 1, Acatlima, Heroica Ciudad
                de Huajuapan de León, Oaxaca, C.P. 69004 Interior del Edificio del Departamento de Recursos 
                Materiales de la U.T.M o enviar su solicitud al correo electrónico 
                transparenciaderechosarco@mixteco.utm.mx, Tel. 953-53-2 456 0 Ext. 165 y 701. 
                Para mayor información sobre el uso de sus datos personales, puede consultar el aviso de 
                privacidad integral en la unidad de Transparencia de la UTM o en el siguiente 
                link: https://www.utm.mx/avisodeprivacidad/Aviso_de_privacidad_Integral.pdf
            </p>


            <h2>
                Uso de cookies
            </h2>

            <p>
                El Sistema de Gestión y Análisis de Evidencias (SGAE)
                utiliza una cookie funcional con la finalidad de recordar
                que el usuario ha leído el presente Aviso de Privacidad.
            </p>

            <p>
                Esta cookie no se utiliza con fines publicitarios,
                comerciales ni para elaborar perfiles del usuario.
                Si las cookies del navegador son eliminadas, el sistema
                podrá solicitar nuevamente la lectura del aviso.
            </p>

        </div>


        {{-- ACEPTACIÓN --}}
        <form
            action="{{ route('legal.privacidad.aceptar') }}"
            method="POST"
            class="privacy-form"
        >

            @csrf


            <label class="privacy-acceptance">

                <input
                    type="checkbox"
                    name="privacy_accepted"
                    id="privacyAccepted"
                    value="1"
                    required
                >

                <span>
                    He leído el Aviso de Privacidad.
                </span>

            </label>


            @error('privacy_accepted')

                <p>
                    Debes indicar que has leído el
                    Aviso de Privacidad para continuar.
                </p>

            @enderror


            <button
                type="submit"
                id="privacyContinueButton"
                disabled
            >
                Aceptar y continuar
            </button>

        </form>

    </div>

</section>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const checkbox =
            document.getElementById(
                'privacyAccepted'
            );

        const button =
            document.getElementById(
                'privacyContinueButton'
            );


        if (!checkbox || !button) {
            return;
        }


        checkbox.addEventListener(
            'change',
            function () {

                button.disabled =
                    !checkbox.checked;

            }
        );

    }
);

</script>

@endsection