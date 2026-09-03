document.addEventListener('DOMContentLoaded', function () {

    const menuButton = document.getElementById('profileMenuButton');
    const dropdown = document.getElementById('profileDropdown');

    if (!menuButton || !dropdown) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | ABRIR / CERRAR MENÚ
    |--------------------------------------------------------------------------
    */

    function toggleProfileMenu() {

        const isOpen = dropdown.classList.contains('profile-dropdown--open');

        if (isOpen) {
            closeProfileMenu();
        } else {
            openProfileMenu();
        }

    }


    /*
    |--------------------------------------------------------------------------
    | ABRIR MENÚ
    |--------------------------------------------------------------------------
    */

    function openProfileMenu() {

        dropdown.classList.add('profile-dropdown--open');

        menuButton.classList.add('profile-arrow--open');

        menuButton.setAttribute('aria-expanded', 'true');

        dropdown.setAttribute('aria-hidden', 'false');

    }


    /*
    |--------------------------------------------------------------------------
    | CERRAR MENÚ
    |--------------------------------------------------------------------------
    */

    function closeProfileMenu() {

        dropdown.classList.remove('profile-dropdown--open');

        menuButton.classList.remove('profile-arrow--open');

        menuButton.setAttribute('aria-expanded', 'false');

        dropdown.setAttribute('aria-hidden', 'true');

    }


    /*
    |--------------------------------------------------------------------------
    | CLICK EN LA FLECHA
    |--------------------------------------------------------------------------
    */

    menuButton.addEventListener('click', function (event) {

        event.stopPropagation();

        toggleProfileMenu();

    });


    /*
    |--------------------------------------------------------------------------
    | EVITA CERRAR AL HACER CLICK DENTRO DEL MENÚ
    |--------------------------------------------------------------------------
    */

    dropdown.addEventListener('click', function (event) {

        event.stopPropagation();

    });


    /*
    |--------------------------------------------------------------------------
    | CERRAR AL HACER CLICK FUERA
    |--------------------------------------------------------------------------
    */

    document.addEventListener('click', function () {

        closeProfileMenu();

    });

/*
|--------------------------------------------------------------------------
| CERRAR CON ESC
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'keydown',
    function (event) {

        if (event.key === 'Escape') {

            closeProfileMenu();

        }

    }
);

});

/*
|--------------------------------------------------------------------------
| DIAGRAMA DEL PROCESO
| Pantalla completa
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', function () {

    const diagramViewer =
        document.getElementById('diagramViewer');

    const fullscreenButton =
        document.getElementById('diagramFullscreenButton');


    /*
     * Este código solo se ejecuta
     * si estamos en la pantalla del diagrama.
     */
    if (!diagramViewer || !fullscreenButton) {
        return;
    }


    fullscreenButton.addEventListener('click', function () {

        if (!document.fullscreenElement) {

            if (diagramViewer.requestFullscreen) {

                diagramViewer.requestFullscreen();

            }

        } else {

            if (document.exitFullscreen) {

                document.exitFullscreen();

            }

        }

    });

});

/*
|--------------------------------------------------------------------------
| PROGRESO DE DESCARGA DE EVIDENCIAS
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', function () {

    const progressState =
        document.getElementById('downloadProgressState');

    const completeState =
        document.getElementById('downloadCompleteState');

    const progressBar =
        document.getElementById('downloadProgressBar');

    const progressPercentage =
        document.getElementById('downloadProgressPercentage');


    /*
     * Este código solo se ejecuta
     * en la pantalla de descarga.
     */
    if (
        !progressState ||
        !completeState ||
        !progressBar ||
        !progressPercentage
    ) {
        return;
    }


    let progress = 0;


    const downloadInterval = setInterval(function () {

        /*
         * Incremento temporal para simular
         * la descarga.
         */
        const increment =
            Math.floor(Math.random() * 4) + 1;


        progress += increment;


        if (progress >= 100) {
            progress = 100;
        }


        /*
         * Actualizar barra.
         */
        progressBar.style.width =
            progress + '%';


        /*
         * Actualizar porcentaje.
         */
        progressPercentage.textContent =
            progress + '%';


        /*
         * Cuando termina.
         */
        if (progress >= 100) {

            clearInterval(downloadInterval);


            /*
             * Esperar un momento para que
             * se alcance a ver el 100 %.
             */
            setTimeout(function () {

                progressState.hidden = true;

                completeState.hidden = false;

            }, 450);

        }

    }, 180);

});

/*
|--------------------------------------------------------------------------
| PROGRESO REAL DEL ANÁLISIS DE EVIDENCIAS
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', function () {

    const page =
        document.getElementById(
            'analysisProcessPage'
        );

    if (!page) {
        return;
    }


    const progressUrl =
        page.dataset.progressUrl;

    if (!progressUrl) {
        return;
    }


    const runningState =
        document.getElementById(
            'analysisRunning'
        );

    const completeState =
        document.getElementById(
            'analysisComplete'
        );

    const percentageElement =
        document.getElementById(
            'analysisPercentage'
        );

    const circleElement =
        document.getElementById(
            'analysisCircleProgress'
        );

    const imageCounter =
        document.getElementById(
            'analysisImageCounter'
        );

    const progressBar =
        document.getElementById(
            'analysisProgressBar'
        );

    const currentFile =
        document.getElementById(
            'analysisCurrentFile'
        );

    const finishedImages =
        document.getElementById(
            'analysisFinishedImages'
        );


    /*
     * =========================================================
     * CÍRCULO DE PROGRESO
     * =========================================================
     */

    let circumference = 0;


    if (circleElement) {

        const radius =
            circleElement.r.baseVal.value;

        circumference =
            2 * Math.PI * radius;


        circleElement.style.strokeDasharray =
            `${circumference} ${circumference}`;


        /*
         * El círculo inicia en 0 %.
         */
        circleElement.style.strokeDashoffset =
            circumference;
    }


    function actualizarCirculo(
        porcentaje
    ) {

        if (
            !circleElement
            ||
            !circumference
        ) {
            return;
        }


        const offset =
            circumference
            -
            (
                porcentaje / 100
            )
            *
            circumference;


        circleElement.style.strokeDashoffset =
            offset;
    }


    /*
     * =========================================================
     * MOSTRAR ESTADO COMPLETADO
     * =========================================================
     */

    function mostrarCompletado()
    {

        if (runningState) {

            runningState.hidden =
                true;

            runningState.style.display =
                'none';
        }


        if (completeState) {

            completeState.hidden =
                false;

            completeState.removeAttribute(
                'hidden'
            );


            completeState.classList.add(
                'analysis-finished-card--visible'
            );


            completeState.style.display =
                'flex';

            completeState.style.visibility =
                'visible';

            completeState.style.opacity =
                '1';
        }
    }


    /*
     * =========================================================
     * ACTUALIZAR LA PANTALLA
     * =========================================================
     */

    function actualizarInterfaz(
        datos
    ) {

        const porcentaje =
            Math.max(
                0,
                Math.min(
                    100,
                    Number(
                        datos.porcentaje
                        ?? 0
                    )
                )
            );


        /*
         * PORCENTAJE
         */
        if (percentageElement) {

            percentageElement.textContent =
                `${porcentaje}%`;
        }


        /*
         * CÍRCULO
         */
        actualizarCirculo(
            porcentaje
        );


        /*
         * BARRA HORIZONTAL
         */
        if (progressBar) {

            progressBar.style.width =
                `${porcentaje}%`;
        }


        /*
         * IMAGEN X DE Y
         */
        if (imageCounter) {

            const actual =
                Number(
                    datos.indice_actual
                    ?? 0
                );

            const total =
                Number(
                    datos.total_imagenes
                    ?? 0
                );


            imageCounter.textContent =
                `Imagen ${actual.toLocaleString('es-MX')} de ${total.toLocaleString('es-MX')}`;
        }


        /*
         * ARCHIVO ACTUAL
         */
        if (currentFile) {

            if (datos.imagen_actual) {

                currentFile.textContent =
                    datos.imagen_actual;

            } else if (datos.fase) {

                currentFile.textContent =
                    datos.fase;
            }
        }


        /*
         * =====================================================
         * ANÁLISIS COMPLETADO
         * =====================================================
         */

        if (
            datos.estado ===
            'completado'
        ) {

            if (percentageElement) {

                percentageElement.textContent =
                    '100%';
            }


            if (progressBar) {

                progressBar.style.width =
                    '100%';
            }


            actualizarCirculo(
                100
            );


            if (finishedImages) {

                const total =
                    Number(
                        datos.total_imagenes
                        ?? 0
                    );

                finishedImages.textContent =
                    total.toLocaleString(
                        'es-MX'
                    );
            }


            mostrarCompletado();


            return true;
        }


        /*
         * =====================================================
         * ERROR
         * =====================================================
         */

        if (
            datos.estado === 'error'
        ) {

            if (currentFile) {

                currentFile.textContent =
                    datos.error
                    ||
                    datos.mensaje
                    ||
                    'Ocurrió un error durante el análisis.';
            }


            return true;
        }


        return false;
    }


    /*
     * =========================================================
     * CONSULTAR PROGRESO EN LARAVEL
     * =========================================================
     */

    async function consultarProgreso()
    {

        try {

            const response =
                await fetch(
                    progressUrl,
                    {
                        method:
                            'GET',

                        headers: {
                            'Accept':
                                'application/json'
                        },

                        credentials:
                            'same-origin',

                        cache:
                            'no-store'
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'No fue posible consultar el progreso.'
                );
            }


            const datos =
                await response.json();


            const terminado =
                actualizarInterfaz(
                    datos
                );


            if (!terminado) {

                setTimeout(
                    consultarProgreso,
                    750
                );
            }


        } catch (error) {

            console.error(
                'Error consultando progreso:',
                error
            );


            setTimeout(
                consultarProgreso,
                1500
            );
        }
    }


    /*
     * =========================================================
     * INICIAR PROGRESO REAL
     * =========================================================
     */

    consultarProgreso();

});

/*
|--------------------------------------------------------------------------
| DESCARGAR PDF DEL ANÁLISIS
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', function () {

    const saveButton =
        document.getElementById('saveAnalysisPdf');

    if (!saveButton) {
        return;
    }


    saveButton.addEventListener('click', async function () {

        const reportUrl =
            saveButton.dataset.reportUrl;


        if (!reportUrl) {

            alert(
                'No se encontró la ruta del reporte PDF.'
            );

            return;
        }


        try {

            /*
             * =========================================================
             * CHROME / EDGE
             * Permite seleccionar manualmente dónde guardar el PDF.
             * =========================================================
             */

            if ('showSaveFilePicker' in window) {

                const fileHandle =
                    await window.showSaveFilePicker({

                        suggestedName:
                            'Reporte_Analisis.pdf',

                        types: [
                            {
                                description:
                                    'Documento PDF',

                                accept: {
                                    'application/pdf': [
                                        '.pdf'
                                    ]
                                }
                            }
                        ]

                    });


                /*
                 * Obtener el PDF desde Laravel.
                 */
                const response =
                    await fetch(
                        reportUrl,
                        {
                            method: 'GET',

                            credentials:
                                'same-origin'
                        }
                    );


                if (!response.ok) {

                    throw new Error(
                        'No se pudo obtener el reporte PDF.'
                    );
                }


                const pdfBlob =
                    await response.blob();


                /*
                 * Escribir el PDF en el archivo
                 * seleccionado por el usuario.
                 */
                const writable =
                    await fileHandle.createWritable();


                await writable.write(
                    pdfBlob
                );


                await writable.close();


                /*
                 * El PDF ya está guardado.
                 *
                 * NO abrimos otra pestaña porque el PDF
                 * ya está visible en resultados.blade.php.
                 */

                return;
            }


            /*
             * =========================================================
             * OTROS NAVEGADORES
             * Descarga convencional del PDF.
             * =========================================================
             */

            const response =
                await fetch(
                    reportUrl,
                    {
                        method: 'GET',

                        credentials:
                            'same-origin'
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'No se pudo obtener el reporte PDF.'
                );
            }


            const pdfBlob =
                await response.blob();


            const blobUrl =
                URL.createObjectURL(
                    pdfBlob
                );


            const downloadLink =
                document.createElement('a');


            downloadLink.href =
                blobUrl;


            downloadLink.download =
                'Reporte_Analisis.pdf';


            /*
             * El enlace se agrega temporalmente
             * para iniciar la descarga.
             */
            document.body.appendChild(
                downloadLink
            );


            downloadLink.click();


            downloadLink.remove();


            /*
             * Liberar memoria.
             */
            URL.revokeObjectURL(
                blobUrl
            );


        } catch (error) {

            /*
             * El usuario cerró el selector
             * sin seleccionar una ubicación.
             */
            if (error.name === 'AbortError') {
                return;
            }


            console.error(
                'Error al descargar el PDF:',
                error
            );


            alert(
                'No fue posible descargar el reporte PDF.'
            );

        }

    });

});

/* ==========================================================
   MANUALES - CAMBIO DE VISTA PREVIA
========================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const manualItems = document.querySelectorAll('[data-manual]');
    const manualPreview = document.getElementById('manualPreview');
    const manualDownload = document.getElementById('manualDownload');

    if (
        !manualItems.length ||
        !manualPreview ||
        !manualDownload
    ) {
        return;
    }


    manualItems.forEach(function (item) {

        item.addEventListener('click', function () {

            const pdf = item.dataset.pdf;

            if (!pdf) {
                return;
            }


            /*
             * Quitar selección anterior.
             */
            manualItems.forEach(function (manual) {

                manual.classList.remove('manual-item--active');

            });


            /*
             * Seleccionar nuevo manual.
             */
            item.classList.add('manual-item--active');


            /*
             * Actualizar vista previa.
             */
            manualPreview.src =
                pdf +
                '#page=1&zoom=page-width&toolbar=0&navpanes=0';


            /*
             * Actualizar descarga.
             */
            manualDownload.href = pdf;

        });

    });

});

/* ==========================================================
   MODAL - CERRAR SESIÓN
========================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const openLogoutModal =
        document.getElementById('openLogoutModal');

    const logoutModal =
        document.getElementById('logoutModal');

    const cancelLogout =
        document.getElementById('cancelLogout');

    if (
        !openLogoutModal ||
        !logoutModal ||
        !cancelLogout
    ) {
        return;
    }


    function openModal() {

        logoutModal.classList.add('logout-modal--open');

        logoutModal.setAttribute(
            'aria-hidden',
            'false'
        );


        /*
         * Cerramos también el menú del usuario.
         */
        const profileDropdown =
            document.getElementById('profileDropdown');

        const profileButton =
            document.getElementById('profileMenuButton');

        if (profileDropdown) {

            profileDropdown.classList.remove(
                'profile-dropdown--open'
            );

            profileDropdown.setAttribute(
                'aria-hidden',
                'true'
            );

        }

        if (profileButton) {

            profileButton.classList.remove(
                'profile-arrow--open'
            );

            profileButton.setAttribute(
                'aria-expanded',
                'false'
            );

        }

    }


    function closeModal() {

        logoutModal.classList.remove(
            'logout-modal--open'
        );

        logoutModal.setAttribute(
            'aria-hidden',
            'true'
        );

    }


    /*
     * Abrir modal.
     */
    openLogoutModal.addEventListener(
        'click',
        function (event) {

            event.preventDefault();

            openModal();

        }
    );


    /*
     * Cancelar.
     */
    cancelLogout.addEventListener(
        'click',
        closeModal
    );


    /*
     * Cerrar haciendo clic en el fondo oscuro.
     */
    logoutModal.addEventListener(
        'click',
        function (event) {

            if (event.target === logoutModal) {

                closeModal();

            }

        }
    );


    /*
     * Cerrar con ESC.
     */
    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                logoutModal.classList.contains(
                    'logout-modal--open'
                )
            ) {

                closeModal();

            }

        }
    );

});

/* ==========================================================
   MODAL - ACERCA DE
========================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const openAboutModal =
        document.getElementById('openAboutModal');

    const aboutModal =
        document.getElementById('aboutModal');

    if (!openAboutModal || !aboutModal) {
        return;
    }
    const closeAboutModal =
    document.getElementById('closeAboutModal');


    function openAbout() {

        aboutModal.classList.add('about-modal--open');

        aboutModal.setAttribute(
            'aria-hidden',
            'false'
        );


        /* Cerrar menú del perfil */
        const profileDropdown =
            document.getElementById('profileDropdown');

        const profileButton =
            document.getElementById('profileMenuButton');


        if (profileDropdown) {

            profileDropdown.classList.remove(
                'profile-dropdown--open'
            );

            profileDropdown.setAttribute(
                'aria-hidden',
                'true'
            );

        }


        if (profileButton) {

            profileButton.classList.remove(
                'profile-arrow--open'
            );

            profileButton.setAttribute(
                'aria-expanded',
                'false'
            );

        }

    }


    function closeAbout() {

        aboutModal.classList.remove(
            'about-modal--open'
        );

        aboutModal.setAttribute(
            'aria-hidden',
            'true'
        );

        const aboutCards =
            aboutModal.querySelector('.about-modal__cards');

        const developerCard =
            aboutModal.querySelector('#aboutDeveloperCard');

        const developerDetails =
            aboutModal.querySelector(
                '.about-developer__details'
            );


        if (aboutCards) {

            aboutCards.classList.remove(
                'about-modal__cards--developer-open'
            );

        }


        if (developerCard) {

            developerCard.classList.remove(
                'about-info-card--developer-open'
            );

            developerCard.setAttribute(
                'aria-expanded',
                'false'
            );

        }


        if (developerDetails) {

            developerDetails.setAttribute(
                'aria-hidden',
                'true'
            );

        }


        aboutModal.classList.remove(
            'about-modal--developer-open'
        );

    }


    openAboutModal.addEventListener(
        'click',
        function (event) {

            event.preventDefault();

            openAbout();

        }
    );
if (closeAboutModal) {

    closeAboutModal.addEventListener(
        'click',
        function () {

            /*
            |--------------------------------------------------------------------------
            | SI "DESARROLLADO POR" ESTÁ EXPANDIDO
            |--------------------------------------------------------------------------
            |
            | La X NO cierra el modal.
            | Primero regresa a los tres cuadros:
            |
            | Versión | Universidad | Desarrollado por
            |
            */

            const developerCard =
                document.getElementById('aboutDeveloperCard');

            const cardsContainer =
                aboutModal.querySelector('.about-modal__cards');

            const developerDetails =
                developerCard
                    ? developerCard.querySelector(
                        '.about-developer__details'
                    )
                    : null;


            const developerIsOpen =
                developerCard &&
                developerCard.classList.contains(
                    'about-info-card--developer-open'
                );


            if (developerIsOpen) {

                /*
                 * Regresar el contenedor a los tres cuadros.
                 */
                if (cardsContainer) {

                    cardsContainer.classList.remove(
                        'about-modal__cards--developer-open'
                    );

                }


                /*
                 * Regresar "Desarrollado por"
                 * a su tamaño normal.
                 */
                developerCard.classList.remove(
                    'about-info-card--developer-open'
                );


                developerCard.setAttribute(
                    'aria-expanded',
                    'false'
                );


                /*
                 * Ocultar nuevamente el texto del equipo.
                 */
                if (developerDetails) {

                    developerDetails.setAttribute(
                        'aria-hidden',
                        'true'
                    );

                }


                /*
                 * Quitar el estado expandido del modal.
                 */
                aboutModal.classList.remove(
                    'about-modal--developer-open'
                );


                /*
                 * IMPORTANTE:
                 * Detenemos aquí la función.
                 * NO cerramos el modal.
                 */
                return;

            }


            /*
            |--------------------------------------------------------------------------
            | SI YA ESTÁN VISIBLES LOS TRES CUADROS
            |--------------------------------------------------------------------------
            |
            | Ahora sí la X cierra completamente "Acerca de".
            */

            closeAbout();

        }
    );

}


    /* Cerrar al tocar el fondo */
    aboutModal.addEventListener(
        'click',
        function (event) {

            if (event.target === aboutModal) {

                closeAbout();

            }

        }
    );


    /* Cerrar con ESC */
    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                aboutModal.classList.contains(
                    'about-modal--open'
                )
            ) {

                closeAbout();

            }

        }
    );




    

});

/*
|--------------------------------------------------------------------------
| IR A ANALIZAR EVIDENCIAS DESPUÉS DE LA DESCARGA
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', function () {

    const analyzeButton =
        document.getElementById('goToAnalyzeButton');

    if (!analyzeButton) {
        return;
    }

    analyzeButton.addEventListener('click', function () {

        const analyzeUrl =
            analyzeButton.dataset.url;

        if (!analyzeUrl) {
            return;
        }

        window.location.href = analyzeUrl;

    });

});

/*
|--------------------------------------------------------------------------
| IR AL HISTORIAL DE ANÁLISIS
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', function () {

    const historyButton =
        document.getElementById('goToHistoryButton');

    if (!historyButton) {
        return;
    }

    historyButton.addEventListener('click', function () {

        const historyUrl =
            historyButton.dataset.url;

        if (!historyUrl) {
            return;
        }

        window.location.href = historyUrl;

    });

});

/*
|--------------------------------------------------------------------------
| IR AL HISTORIAL DE ANÁLISIS
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', function () {

    const historyButton =
        document.getElementById('goToHistoryButton');

    if (!historyButton) {
        return;
    }

    historyButton.addEventListener('click', function () {

        const historyUrl =
            historyButton.dataset.url;

        if (!historyUrl) {
            return;
        }

        window.location.href = historyUrl;

    });

});

/*
|--------------------------------------------------------------------------
| HISTORIAL DE ANÁLISIS
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', function () {

    const historyRadios =
        document.querySelectorAll(
            '.analysis-history-radio'
        );

    const viewButton =
        document.getElementById(
            'historyViewButton'
        );

    if (!historyRadios.length || !viewButton) {
        return;
    }


    let selectedReportUrl = '';


    historyRadios.forEach(function (radio) {

        radio.addEventListener('change', function () {

            /*
             * Quitar selección visual anterior.
             */
            document
                .querySelectorAll(
                    '[data-history-row]'
                )
                .forEach(function (row) {

                    row.classList.remove(
                        'analysis-history-row--selected'
                    );

                });


            /*
             * Marcar fila seleccionada.
             */
            const selectedRow =
                radio.closest(
                    '[data-history-row]'
                );


            if (selectedRow) {

                selectedRow.classList.add(
                    'analysis-history-row--selected'
                );

            }


            /*
             * Guardar PDF seleccionado.
             */
            selectedReportUrl =
                radio.dataset.reportUrl || '';


            /*
             * Activar botón.
             */
            viewButton.disabled =
                !selectedReportUrl;

        });

    });


    /*
     * Abrir PDF.
     */
    viewButton.addEventListener('click', function () {

        if (!selectedReportUrl) {
            return;
        }

        window.location.href =
            selectedReportUrl;

    });

});

/* ==========================================================
   ACERCA DE - SUPERVISADO POR
========================================================== */
/* ==========================================================
   ACERCA DE - SUPERVISADO POR
========================================================== */

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const aboutModal =
            document.getElementById(
                'aboutModal'
            );


        const cards =
            document.querySelector(
                '.about-modal__cards'
            );


        const supervisorCard =
            document.getElementById(
                'aboutSupervisorCard'
            );


        const supervisorDetails =
            document.getElementById(
                'aboutSupervisorDetails'
            );


        const supervisorClose =
            document.getElementById(
                'aboutSupervisorClose'
            );


        const defaultDescription =
            document.getElementById(
                'aboutDefaultDescription'
            );


        const supervisorIntro =
            document.getElementById(
                'aboutSupervisorIntro'
            );


        if (
            !aboutModal ||
            !cards ||
            !supervisorCard ||
            !supervisorDetails ||
            !supervisorClose ||
            !defaultDescription ||
            !supervisorIntro
        ) {
            return;
        }


        /* =====================================================
           ABRIR SUPERVISADO POR
        ====================================================== */

        function abrirSupervisores() {

            /*
             * Cerrar Desarrollado por
             * si estuviera abierto.
             */

            cards.classList.remove(
                'about-modal__cards--developer-open'
            );


            aboutModal.classList.remove(
                'about-modal--developer-open'
            );


            const developerCard =
                document.getElementById(
                    'aboutDeveloperCard'
                );


            const developerDetails =
                developerCard
                    ? developerCard.querySelector(
                        '.about-developer__details'
                    )
                    : null;


            if (developerCard) {

                developerCard.classList.remove(
                    'about-info-card--developer-open'
                );


                developerCard.setAttribute(
                    'aria-expanded',
                    'false'
                );

            }


            if (developerDetails) {

                developerDetails.setAttribute(
                    'aria-hidden',
                    'true'
                );

            }


            /*
             * Expandir Supervisado por.
             */

            cards.classList.add(
                'about-modal__cards--supervisor-open'
            );


            supervisorCard.classList.add(
                'about-info-card--supervisor-open'
            );


            supervisorCard.setAttribute(
                'aria-expanded',
                'true'
            );


            supervisorDetails.setAttribute(
                'aria-hidden',
                'false'
            );


            /*
             * Cambiar información superior.
             */

            defaultDescription.hidden =
                true;


            supervisorIntro.hidden =
                false;

        }


        /* =====================================================
           CERRAR SUPERVISADO POR
        ====================================================== */

        function cerrarSupervisores() {

            cards.classList.remove(
                'about-modal__cards--supervisor-open'
            );


            supervisorCard.classList.remove(
                'about-info-card--supervisor-open'
            );


            supervisorCard.setAttribute(
                'aria-expanded',
                'false'
            );


            supervisorDetails.setAttribute(
                'aria-hidden',
                'true'
            );


            /*
             * Restaurar descripción normal.
             */

            defaultDescription.hidden =
                false;


            supervisorIntro.hidden =
                true;

        }


        /* =====================================================
           CLICK EN LA TARJETA
        ====================================================== */

        supervisorCard.addEventListener(
            'click',
            function (event) {

                /*
                 * Si se hizo clic sobre la X,
                 * este evento no debe abrir otra vez.
                 */

                if (
                    event.target.closest(
                        '#aboutSupervisorClose'
                    )
                ) {
                    return;
                }


                const estaAbierto =
                    supervisorCard.classList.contains(
                        'about-info-card--supervisor-open'
                    );


                if (!estaAbierto) {

                    abrirSupervisores();

                }

            }
        );


        /* =====================================================
           X INTERNA
        ====================================================== */

        supervisorClose.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                event.stopPropagation();


                cerrarSupervisores();

            }
        );


        /* =====================================================
           TECLADO
        ====================================================== */

        supervisorCard.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Enter' ||
                    event.key === ' '
                ) {

                    event.preventDefault();


                    const estaAbierto =
                        supervisorCard.classList.contains(
                            'about-info-card--supervisor-open'
                        );


                    if (!estaAbierto) {

                        abrirSupervisores();

                    }

                }

            }
        );

    }
);

/* ==========================================================
   ACERCA DE - EXPANDIR / CONTRAER "DESARROLLADO POR"
========================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const developerCard =
        document.getElementById('aboutDeveloperCard');

    const aboutModal =
        document.getElementById('aboutModal');


    /*
     * Si los elementos no existen en esta pantalla,
     * simplemente no ejecutamos esta funcionalidad.
     */
    if (!developerCard || !aboutModal) {
        return;
    }


    const cardsContainer =
        developerCard.closest('.about-modal__cards');


    const developerDetails =
        developerCard.querySelector(
            '.about-developer__details'
        );


    if (!cardsContainer || !developerDetails) {
        return;
    }


    /* ======================================================
       EXPANDIR
    ====================================================== */

    function expandDeveloperCard() {

        cardsContainer.classList.add(
            'about-modal__cards--developer-open'
        );


        developerCard.classList.add(
            'about-info-card--developer-open'
        );


        aboutModal.classList.add(
            'about-modal--developer-open'
        );


        developerCard.setAttribute(
            'aria-expanded',
            'true'
        );


        developerDetails.setAttribute(
            'aria-hidden',
            'false'
        );

    }


    /* ======================================================
       REGRESAR A LOS TRES CUADROS
    ====================================================== */

    function collapseDeveloperCard() {

        cardsContainer.classList.remove(
            'about-modal__cards--developer-open'
        );


        developerCard.classList.remove(
            'about-info-card--developer-open'
        );


        aboutModal.classList.remove(
            'about-modal--developer-open'
        );


        developerCard.setAttribute(
            'aria-expanded',
            'false'
        );


        developerDetails.setAttribute(
            'aria-hidden',
            'true'
        );

    }
    /* ======================================================
   X INTERNA - CERRAR SOLO EL CUADRO EXPANDIDO
====================================================== */

const closeDeveloperCard =
    document.getElementById('closeDeveloperCard');


if (closeDeveloperCard) {

    closeDeveloperCard.addEventListener(
        'click',
        function (event) {

            /*
             * Evitamos que el clic llegue al <article>.
             *
             * Esto es MUY importante porque todo el
             * cuadro también tiene su propio evento click.
             */
            event.preventDefault();
            event.stopPropagation();


            /*
             * Regresa únicamente a:
             *
             * Versión | Universidad | Desarrollado por
             *
             * NO cierra el modal Acerca de.
             */
            collapseDeveloperCard();

        }
    );

}


    /* ======================================================
       CLIC SOBRE "DESARROLLADO POR"
    ====================================================== */

    developerCard.addEventListener(
        'click',
        function (event) {

            event.stopPropagation();


            const isExpanded =
                developerCard.classList.contains(
                    'about-info-card--developer-open'
                );


            if (isExpanded) {

                collapseDeveloperCard();

            } else {

                expandDeveloperCard();

            }

        }
    );


    /* ======================================================
       ENTER / ESPACIO
    ====================================================== */

    developerCard.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key !== 'Enter' &&
                event.key !== ' '
            ) {
                return;
            }


            event.preventDefault();


            const isExpanded =
                developerCard.classList.contains(
                    'about-info-card--developer-open'
                );


            if (isExpanded) {

                collapseDeveloperCard();

            } else {

                expandDeveloperCard();

            }

        }
    );

});

/* ==========================================================
   ACERCA DE - EXPANDIR / CONTRAER VERSIÓN
========================================================== */

document.addEventListener('DOMContentLoaded', function () {

    const versionCard =
        document.getElementById('aboutVersionCard');

    const aboutModal =
        document.getElementById('aboutModal');


    if (!versionCard || !aboutModal) {
        return;
    }


    const cardsContainer =
        versionCard.closest('.about-modal__cards');


    const versionDetails =
        versionCard.querySelector(
            '.about-version__details'
        );


    const closeVersionCard =
        document.getElementById('closeVersionCard');


    if (!cardsContainer || !versionDetails) {
        return;
    }


    /* ======================================================
       EXPANDIR
    ====================================================== */

    function expandVersionCard() {

        cardsContainer.classList.add(
            'about-modal__cards--version-open'
        );


        versionCard.classList.add(
            'about-info-card--version-open'
        );


        aboutModal.classList.add(
            'about-modal--version-open'
        );


        versionCard.setAttribute(
            'aria-expanded',
            'true'
        );


        versionDetails.setAttribute(
            'aria-hidden',
            'false'
        );

    }


    /* ======================================================
       CONTRAER
    ====================================================== */

    function collapseVersionCard() {

        cardsContainer.classList.remove(
            'about-modal__cards--version-open'
        );


        versionCard.classList.remove(
            'about-info-card--version-open'
        );


        aboutModal.classList.remove(
            'about-modal--version-open'
        );


        versionCard.setAttribute(
            'aria-expanded',
            'false'
        );


        versionDetails.setAttribute(
            'aria-hidden',
            'true'
        );

    }


    /* ======================================================
       CLIC EN VERSIÓN
    ====================================================== */

    versionCard.addEventListener(
        'click',
        function (event) {

            /*
             * Si se presionó la X,
             * ese clic se controla aparte.
             */
            if (
                event.target.closest(
                    '#closeVersionCard'
                )
            ) {
                return;
            }


            const isExpanded =
                versionCard.classList.contains(
                    'about-info-card--version-open'
                );


            if (!isExpanded) {
                expandVersionCard();
            }

        }
    );


    /* ======================================================
       X INTERNA
    ====================================================== */

    if (closeVersionCard) {

        closeVersionCard.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                event.stopPropagation();


                /*
                 * Solo cierra el recuadro rojo.
                 * El modal "Acerca de" permanece abierto.
                 */
                collapseVersionCard();

            }
        );

    }


    /* ======================================================
       ENTER / ESPACIO
    ====================================================== */

    versionCard.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key !== 'Enter' &&
                event.key !== ' '
            ) {
                return;
            }


            event.preventDefault();


            const isExpanded =
                versionCard.classList.contains(
                    'about-info-card--version-open'
                );


            if (!isExpanded) {
                expandVersionCard();
            }

        }
    );

});


/*
|--------------------------------------------------------------------------
| FILTROS DINÁMICOS - DESCARGAR EVIDENCIAS
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', function () {

    const page =
        document.getElementById('downloadEvidencePage');

    const courseSelect =
        document.getElementById('curso');

    const groupSelect =
        document.getElementById('grupo');

    const examSelect =
        document.getElementById('examen');
        const resultsBody =
    document.getElementById(
        'downloadResultsBody'

);
        /*
|--------------------------------------------------------------------------
| NAVEGACIÓN CON TECLADO
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'keydown',
    function (event) {

        /*
         * Solamente funciona si la
         * imagen grande está abierta.
         */
        if (
            !evidenceImageViewer ||
            evidenceImageViewer.hidden
        ) {
            return;
        }


        /*
         * Flecha izquierda.
         */
        if (
            event.key ===
            'ArrowLeft'
        ) {

            mostrarImagenAnterior();

            return;
        }


        /*
         * Flecha derecha.
         */
        if (
            event.key ===
            'ArrowRight'
        ) {

            mostrarImagenSiguiente();

            return;
        }


        /*
         * ESC.
         */
        if (
            event.key ===
            'Escape'
        ) {

            cerrarImagenAmpliada();

        }

    }

    );

    /*
|--------------------------------------------------------------------------
| MODAL DE EVIDENCIAS
|--------------------------------------------------------------------------
*/

const evidenceModal =
    document.getElementById(
        'evidenceGalleryModal'
    );

const evidenceModalTitle =
    document.getElementById(
        'evidenceGalleryTitle'
    );

const evidenceModalCount =
    document.getElementById(
        'evidenceGalleryCount'
    );

const evidenceModalStatus =
    document.getElementById(
        'evidenceGalleryStatus'
    );

const evidenceGallery =
    document.getElementById(
        'evidenceGallery'
    );

const evidenceModalShown =
    document.getElementById(
        'evidenceGalleryShown'
    );

const evidenceModalMore =
    document.getElementById(
        'evidenceGalleryMore'
    );

const evidenceLoadMore =
    document.getElementById(
        'evidenceGalleryLoadMore'
    );

const evidenceClose =
    document.getElementById(
        'evidenceGalleryClose'
    );

const evidenceCloseButton =
    document.getElementById(
        'evidenceGalleryCloseButton'
    );


    /*
|--------------------------------------------------------------------------
| VISOR DE IMAGEN AMPLIADA
|--------------------------------------------------------------------------
*/

const evidenceImageViewer =
    document.getElementById(
        'evidenceImageViewer'
    );


const evidenceImageViewerImage =
    document.getElementById(
        'evidenceImageViewerImage'
    );


const evidenceImageViewerClose =
    document.getElementById(
        'evidenceImageViewerClose'
    );


const evidenceImageViewerInformation =
    document.getElementById(
        'evidenceImageViewerInformation'
    );


    const evidenceImageViewerPrev =
    document.getElementById(
        'evidenceImageViewerPrev'
    );


const evidenceImageViewerNext =
    document.getElementById(
        'evidenceImageViewerNext'
    );
    /*
     * Este código solamente se ejecuta
     * en Descargar evidencias.
     */
  if (
    !page ||
    !courseSelect ||
    !groupSelect ||
    !examSelect ||
    !resultsBody
) {
    return;
}

    const coursesUrl =
        page.dataset.coursesUrl;

    const groupsUrl =
        page.dataset.groupsUrl;

    const examsUrl =
        page.dataset.examsUrl;

    const examDataUrl =
        page.dataset.examDataUrl;

    const capturesUrl =
    page.dataset.capturesUrl;

    /*
    |--------------------------------------------------------------------------
    | FUNCIÓN AUXILIAR
    |--------------------------------------------------------------------------
    */

    async function obtenerJson(url) {

        const response = await fetch(
            url,
            {
                method: 'GET',

                headers: {
                    'Accept': 'application/json'
                },

                credentials: 'same-origin'
            }
        );


        if (!response.ok) {

            throw new Error(
                'No fue posible consultar la información.'
            );

        }


        return await response.json();
    }


    /*
    |--------------------------------------------------------------------------
    | CARGAR CURSOS DEL PROFESOR
    |--------------------------------------------------------------------------
    */

    async function cargarCursos() {

        courseSelect.disabled = true;

        courseSelect.innerHTML = `
            <option value="">
                Cargando cursos...
            </option>
        `;


        try {

            const data =
                await obtenerJson(
                    coursesUrl
                );


            if (!data.ok) {

                throw new Error(
                    data.message ||
                    'No fue posible obtener los cursos.'
                );

            }


            courseSelect.innerHTML = `
                <option value="" selected disabled>
                    Selecciona un curso
                </option>
            `;


            data.cursos.forEach(function (curso) {

                const option =
                    document.createElement('option');


                option.value =
                    curso.id;


                option.textContent =
                    curso.nombre;


                courseSelect.appendChild(
                    option
                );

            });


            courseSelect.disabled =
                false;


        } catch (error) {

            console.error(
                'Error cargando cursos:',
                error
            );


            courseSelect.innerHTML = `
                <option value="">
                    No fue posible cargar los cursos
                </option>
            `;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CARGAR GRUPOS
    |--------------------------------------------------------------------------
    */

    async function cargarGrupos(courseId) {

        groupSelect.disabled = true;

        groupSelect.innerHTML = `
            <option value="">
                Cargando grupos...
            </option>
        `;


        try {

            const url =
                new URL(
                    groupsUrl,
                    window.location.origin
                );


            url.searchParams.set(
                'courseid',
                courseId
            );


            const data =
                await obtenerJson(
                    url.toString()
                );


            if (!data.ok) {

                throw new Error(
                    data.message ||
                    'No fue posible obtener los grupos.'
                );

            }


            /*
             * Siempre permitimos descargar
             * sin filtrar por grupo.
             */
            groupSelect.innerHTML = `
                <option value="">
                    Todos los grupos
                </option>
            `;


            data.grupos.forEach(function (grupo) {

                const option =
                    document.createElement('option');


                option.value =
                    grupo.id;


                option.textContent =
                    grupo.nombre;


                groupSelect.appendChild(
                    option
                );

            });


            groupSelect.disabled =
                false;


        } catch (error) {

            console.error(
                'Error cargando grupos:',
                error
            );


            groupSelect.innerHTML = `
                <option value="">
                    No fue posible cargar los grupos
                </option>
            `;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CARGAR EXÁMENES
    |--------------------------------------------------------------------------
    */

    async function cargarExamenes(courseId) {

        examSelect.disabled = true;

        examSelect.innerHTML = `
            <option value="">
                Cargando exámenes...
            </option>
        `;


        try {

            const url =
                new URL(
                    examsUrl,
                    window.location.origin
                );


            url.searchParams.set(
                'courseid',
                courseId
            );


            const data =
                await obtenerJson(
                    url.toString()
                );


            if (!data.ok) {

                throw new Error(
                    data.message ||
                    'No fue posible obtener los exámenes.'
                );

            }


            examSelect.innerHTML = `
                <option value="" selected disabled>
                    Selecciona un examen
                </option>
            `;


          data.examenes.forEach(function (examen) {

    const option =
        document.createElement('option');


    option.value =
        examen.id;


    option.textContent =
        examen.nombre;


    /*
     * Guardamos también la fecha del examen
     * dentro de la opción.
     */
   option.dataset.fechaHora =
    examen.fecha_hora || 0;


/*
 * Guardamos el CMID porque lo necesitaremos
 * para consultar las capturas del examen.
 */
option.dataset.cmid =
    examen.cmid || 0;


examSelect.appendChild(
    option
);

});

            examSelect.disabled =
                false;


        } catch (error) {

            console.error(
                'Error cargando exámenes:',
                error
            );


            examSelect.innerHTML = `
                <option value="">
                    No fue posible cargar los exámenes
                </option>
            `;

        }

    }


    /*
|--------------------------------------------------------------------------
| MOSTRAR RESULTADO SELECCIONADO
|--------------------------------------------------------------------------
*/

let totalImagenesSeleccionadas = 0;

let evidenceOffset = 0;

const evidenceLimit = 24;

let evidenceHasMore = false;

let evidenceLoading = false;

/*
|--------------------------------------------------------------------------
| IMÁGENES CARGADAS EN EL MODAL
|--------------------------------------------------------------------------
*/

let evidenceImages = [];

let evidenceCurrentIndex = -1;

async function actualizarTablaResultados() {

    const courseId =
        courseSelect.value;

    const quizId =
        examSelect.value;


    if (!courseId || !quizId) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | DATOS SELECCIONADOS
    |--------------------------------------------------------------------------
    */

    const courseOption =
        courseSelect.options[
            courseSelect.selectedIndex
        ];


    const groupOption =
        groupSelect.options[
            groupSelect.selectedIndex
        ];


    const examOption =
        examSelect.options[
            examSelect.selectedIndex
        ];


    const courseName =
        courseOption.textContent.trim();


    const examName =
        examOption.textContent.trim();


    /*
    |--------------------------------------------------------------------------
    | FECHA DEL EXAMEN
    |--------------------------------------------------------------------------
    */

    const fechaTimestamp =
        Number(
            examOption.dataset.fechaHora || 0
        );


    let fechaHora =
        'Sin fecha definida';


    if (fechaTimestamp > 0) {

        const fecha =
            new Date(
                fechaTimestamp * 1000
            );


        fechaHora =
            fecha.toLocaleString(
                'es-MX',
                {
                    dateStyle: 'short',
                    timeStyle: 'short'
                }
            );

    }


    /*
    |--------------------------------------------------------------------------
    | GRUPO
    |--------------------------------------------------------------------------
    */

    let groupName =
        'Todos los grupos';


    if (
        groupSelect.value &&
        groupOption
    ) {

        groupName =
            groupOption.textContent.trim();

    }


    /*
    |--------------------------------------------------------------------------
    | ALUMNOS E IMÁGENES
    |--------------------------------------------------------------------------
    */

    let alumnos =
        '—';


    let imagenes =
        '—';


    try {

        const url =
            new URL(
                examDataUrl,
                window.location.origin
            );


        url.searchParams.set(
            'courseid',
            courseId
        );


        url.searchParams.set(
            'quizid',
            quizId
        );


        const data =
            await obtenerJson(
                url.toString()
            );


        if (data.ok) {

            const alumnosConIntento =
                Number(
                    data.alumnos_con_intento || 0
                );


            const alumnosTotal =
                Number(
                    data.alumnos_total || 0
                );


            alumnos =
                alumnosConIntento +
                ' / ' +
                alumnosTotal;


            totalImagenesSeleccionadas =
    Number(
        data.imagenes || 0
    );


imagenes =
    totalImagenesSeleccionadas
        .toLocaleString(
            'es-MX'
        );

        }


    } catch (error) {

        console.error(
            'Error cargando datos del examen:',
            error
        );

    }


    /*
    |--------------------------------------------------------------------------
    | MOSTRAR FILA
    |--------------------------------------------------------------------------
    */

    resultsBody.innerHTML = `
        <tr>

            <td class="download-table__exam">
                ${examName}
            </td>

            <td>
                ${groupName}
            </td>

            <td>
                ${courseName}
            </td>

            <td>
                ${alumnos}
            </td>

            <td>
                ${imagenes}
            </td>

            <td>
                ${fechaHora}
            </td>

            <td>

                <button
                    type="button"
                    class="download-details-button"
                >

                    <span
                        class="download-details-button__icon"
                        aria-hidden="true"
                    >
                        •••
                    </span>

                    <span>
                        Detalles
                    </span>

                </button>

            </td>

        </tr>
    `;
}

/*
|--------------------------------------------------------------------------
| CREAR UNA MINIATURA
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| AJUSTAR TAMAÑO DE MINIATURAS
|--------------------------------------------------------------------------
*/

function actualizarTamanoMiniaturas() {

    if (!evidenceGallery) {
        return;
    }


    const total =
        evidenceImages.length;


    /*
     * Primero quitar estados anteriores.
     */
    evidenceGallery.classList.remove(
        'evidence-gallery--medium',
        'evidence-gallery--compact'
    );


    /*
     * 72 o más:
     * miniaturas pequeñas.
     */
    if (total >= 72) {

        evidenceGallery.classList.add(
            'evidence-gallery--compact'
        );

        return;
    }


    /*
     * 48 a 71:
     * miniaturas medianas.
     */
    if (total >= 48) {

        evidenceGallery.classList.add(
            'evidence-gallery--medium'
        );

    }

}

// abrir imagen ampliada

function abrirImagenAmpliada(indice) {

    if (
        !evidenceImageViewer ||
        !evidenceImageViewerImage
    ) {
        return;
    }


    /*
     * Verificar que el índice exista.
     */
    if (
        indice < 0 ||
        indice >= evidenceImages.length
    ) {
        return;
    }


    evidenceCurrentIndex =
        indice;


    const imagen =
        evidenceImages[
            evidenceCurrentIndex
        ];


    if (!imagen || !imagen.url) {
        return;
    }


    /*
     * Mostrar imagen.
     */
    evidenceImageViewerImage.src =
        imagen.url;


    /*
     * Mostrar fecha.
     */
    if (
        evidenceImageViewerInformation
    ) {

        let texto =
            'Evidencia del examen';


        if (imagen.fecha) {

            const fecha =
                new Date(
                    Number(
                        imagen.fecha
                    ) * 1000
                );


            texto =
                fecha.toLocaleString(
                    'es-MX',
                    {
                        dateStyle:
                            'medium',

                        timeStyle:
                            'medium'
                    }
                );

        }


        /*
         * También indicamos cuál imagen
         * estamos viendo.
         */
        evidenceImageViewerInformation
            .textContent =
                'Imagen ' +
                (
                    evidenceCurrentIndex + 1
                ).toLocaleString('es-MX')
                +
                ' de ' +
                evidenceImages.length
                    .toLocaleString('es-MX')
                +
                ' · ' +
                texto;

    }


    /*
     * Mostrar visor.
     */
    evidenceImageViewer.hidden =
        false;


    evidenceImageViewer.setAttribute(
        'aria-hidden',
        'false'
    );


    /*
     * Actualizar flechas.
     */
    actualizarNavegacionImagen();

}


/*
|--------------------------------------------------------------------------
| ACTUALIZAR FLECHAS DEL VISOR
|--------------------------------------------------------------------------
*/

function actualizarNavegacionImagen() {

    if (evidenceImageViewerPrev) {

        evidenceImageViewerPrev.disabled =
            evidenceCurrentIndex <= 0;

    }


    if (evidenceImageViewerNext) {

        evidenceImageViewerNext.disabled =
            evidenceCurrentIndex >=
            evidenceImages.length - 1;

    }

}


/*
|--------------------------------------------------------------------------
| IMAGEN ANTERIOR
|--------------------------------------------------------------------------
*/

function mostrarImagenAnterior() {

    if (evidenceCurrentIndex <= 0) {
        return;
    }


    abrirImagenAmpliada(
        evidenceCurrentIndex - 1
    );

}


/*
|--------------------------------------------------------------------------
| IMAGEN SIGUIENTE
|--------------------------------------------------------------------------
*/

function mostrarImagenSiguiente() {

    if (
        evidenceCurrentIndex >=
        evidenceImages.length - 1
    ) {
        return;
    }


    abrirImagenAmpliada(
        evidenceCurrentIndex + 1
    );

}


/*
|--------------------------------------------------------------------------
| CERRAR IMAGEN AMPLIADA
|--------------------------------------------------------------------------
*/

function cerrarImagenAmpliada() {

    if (!evidenceImageViewer) {
        return;
    }


    evidenceImageViewer.hidden =
        true;


    evidenceImageViewer.setAttribute(
        'aria-hidden',
        'true'
    );


    if (evidenceImageViewerImage) {

        /*
         * Liberar la imagen cuando se cierre.
         */
        evidenceImageViewerImage.src =
            '';

    }

}

function agregarImagenGaleria(imagen) {

    if (!imagen || !imagen.url) {
        return;
    }


    /*
     * Guardar la posición de esta fotografía.
     *
     * Ejemplo:
     * primera imagen  = 0
     * segunda imagen  = 1
     * tercera imagen  = 2
     */
    const indice =
        evidenceImages.length;


    /*
     * Guardar la fotografía en memoria.
     */
    evidenceImages.push(
        imagen
    );


    /*
     * Crear miniatura.
     */
    const item =
        document.createElement(
            'div'
        );


    item.className =
        'evidence-gallery__item';


    item.dataset.evidenceIndex =
        indice;


    const img =
        document.createElement(
            'img'
        );


    img.className =
        'evidence-gallery__image';


    img.src =
        imagen.url;


    img.alt =
        'Evidencia ' +
        (
            indice + 1
        );


    img.loading =
        'lazy';


    /*
     * Si falla solamente esa imagen,
     * ocultamos su miniatura.
     */
    img.addEventListener(
        'error',
        function () {

            item.style.display =
                'none';

        }
    );


    /*
     * Abrir en grande.
     */
    item.addEventListener(
        'click',
        function () {

            abrirImagenAmpliada(
                indice
            );

        }
    );


    item.appendChild(
        img
    );


    evidenceGallery.appendChild(
        item
    );


    /*
     * Conforme se agregan fotografías,
     * ajustar el tamaño de todas.
     */
    actualizarTamanoMiniaturas();

}


/*
|--------------------------------------------------------------------------
| CARGAR CAPTURAS DESDE LARAVEL
|--------------------------------------------------------------------------
*/

async function cargarCapturas(
    reiniciar = false
) {

    if (
        evidenceLoading ||
        !capturesUrl
    ) {
        return;
    }


    const courseId =
        courseSelect.value;


    const quizId =
        examSelect.value;


    if (!courseId || !quizId) {
        return;
    }


    /*
     * Primera carga.
     */
    if (reiniciar) {

        evidenceOffset = 0;

        evidenceHasMore = false;

        evidenceImages =
    [];


evidenceCurrentIndex =
    -1;


evidenceGallery.classList.remove(
    'evidence-gallery--medium',
    'evidence-gallery--compact'
);

        evidenceGallery.innerHTML =
            '';

        if (evidenceModalShown) {

            evidenceModalShown.textContent =
                '';
        }

    }


    evidenceLoading =
        true;


    /*
     * Mostrar mensaje solo si todavía
     * no hay fotografías.
     */
    if (evidenceOffset === 0) {

        evidenceModalStatus.hidden =
            false;

        evidenceModalStatus.textContent =
            'Cargando evidencias...';

    }


    if (evidenceLoadMore) {

        evidenceLoadMore.disabled =
            true;
    }


    try {

        const url =
            new URL(
                capturesUrl,
                window.location.origin
            );


        url.searchParams.set(
            'courseid',
            courseId
        );


        url.searchParams.set(
            'quizid',
            quizId
        );


        url.searchParams.set(
            'offset',
            evidenceOffset
        );


        url.searchParams.set(
            'limit',
            evidenceLimit
        );


        const data =
            await obtenerJson(
                url.toString()
            );


        if (!data.ok) {

            throw new Error(
                data.message ||
                'No fue posible cargar las imágenes.'
            );

        }


        const imagenes =
            Array.isArray(
                data.imagenes
            )
                ? data.imagenes
                : [];


        /*
         * Ocultamos "Cargando..."
         */
        evidenceModalStatus.hidden =
            true;


        /*
         * Dibujar fotografías.
         */
        imagenes.forEach(
            function (imagen) {

                agregarImagenGaleria(
                    imagen
                );

            }
        );


        /*
         * Actualizar offset.
         */
        evidenceOffset =
            Number(
                data.next_offset
                ?? (
                    evidenceOffset +
                    imagenes.length
                )
            );


        evidenceHasMore =
            Boolean(
                data.has_more
            );


        /*
         * Texto inferior.
         */
        if (evidenceModalShown) {

            evidenceModalShown.textContent =
                'Mostrando ' +
                evidenceOffset.toLocaleString(
                    'es-MX'
                ) +
                ' de ' +
                totalImagenesSeleccionadas
                    .toLocaleString(
                        'es-MX'
                    ) +
                ' imágenes';

        }


        /*
         * Mostrar u ocultar "Cargar más".
         */
        if (evidenceModalMore) {

            evidenceModalMore.hidden =
                !evidenceHasMore;

        }


        /*
         * Si no existe ninguna fotografía.
         */
        if (
            reiniciar &&
            imagenes.length === 0
        ) {

            evidenceModalStatus.hidden =
                false;

            evidenceModalStatus.textContent =
                'No se encontraron evidencias para este examen.';

        }


    } catch (error) {

        console.error(
            'Error cargando evidencias:',
            error
        );


        evidenceModalStatus.hidden =
            false;


        evidenceModalStatus.textContent =
            'No fue posible cargar las evidencias.';


    } finally {

        evidenceLoading =
            false;


        if (evidenceLoadMore) {

            evidenceLoadMore.disabled =
                false;

        }

    }

}



/*
|--------------------------------------------------------------------------
| ABRIR MODAL DE EVIDENCIAS
|--------------------------------------------------------------------------
*/

function abrirModalEvidencias() {

    if (
        !evidenceModal ||
        !evidenceModalTitle ||
        !evidenceModalCount ||
        !evidenceModalStatus ||
        !evidenceGallery
    ) {
        return;
    }


    const examOption =
        examSelect.options[
            examSelect.selectedIndex
        ];


    if (!examOption || !examSelect.value) {
        return;
    }


    const examName =
        examOption.textContent.trim();


    /*
     * Título.
     */
    evidenceModalTitle.textContent =
        'Evidencias - ' + examName;


    /*
     * Total.
     */
    evidenceModalCount.textContent =
        totalImagenesSeleccionadas
            .toLocaleString('es-MX')
        +
        ' imágenes';


    /*
     * Reiniciar galería.
     */
    evidenceGallery.innerHTML = '';


    evidenceModalStatus.hidden =
        false;


    evidenceModalStatus.textContent =
        'Cargando evidencias...';


    if (evidenceModalShown) {

        evidenceModalShown.textContent =
            '';
    }


    if (evidenceModalMore) {

        evidenceModalMore.hidden =
            true;
    }


    /*
     * Mostrar modal.
     */
    evidenceModal.hidden =
        false;

        /*
 * Cargar las primeras 24 imágenes.
 */
cargarCapturas(true);

    evidenceModal.setAttribute(
        'aria-hidden',
        'false'
    );

}


/*
|--------------------------------------------------------------------------
| CERRAR MODAL DE EVIDENCIAS
|--------------------------------------------------------------------------
*/

function cerrarModalEvidencias() {

    if (!evidenceModal) {
        return;
    }


    evidenceModal.hidden =
        true;


    evidenceModal.setAttribute(
        'aria-hidden',
        'true'
    );


    if (evidenceGallery) {

        evidenceGallery.innerHTML =
            '';
    }

}


/*
|--------------------------------------------------------------------------
| CLICK EN DETALLES
|--------------------------------------------------------------------------
*/

resultsBody.addEventListener(
    'click',
    function (event) {

        const detailsButton =
            event.target.closest(
                '.download-details-button'
            );


        if (!detailsButton) {
            return;
        }


        abrirModalEvidencias();

    }
);
/*
|--------------------------------------------------------------------------
| CERRAR CON X
|--------------------------------------------------------------------------
*/

if (evidenceClose) {

    evidenceClose.addEventListener(
        'click',
        cerrarModalEvidencias
    );

}

/*
|--------------------------------------------------------------------------
| CARGAR MÁS EVIDENCIAS
|--------------------------------------------------------------------------
*/

if (evidenceLoadMore) {

    evidenceLoadMore.addEventListener(
        'click',
        function () {

            if (!evidenceHasMore) {
                return;
            }


            cargarCapturas(false);

        }
    );

}

/*
|--------------------------------------------------------------------------
| BOTÓN CERRAR
|--------------------------------------------------------------------------
*/

if (evidenceCloseButton) {

    evidenceCloseButton.addEventListener(
        'click',
        cerrarModalEvidencias
    );

}


/*
|--------------------------------------------------------------------------
| CLIC EN EL FONDO
|--------------------------------------------------------------------------
*/

if (evidenceModal) {

    evidenceModal.addEventListener(
        'click',
        function (event) {

            if (
                event.target.matches(
                    '[data-close-evidence-modal]'
                )
            ) {

                cerrarModalEvidencias();

            }

        }
    );

}


/*
|--------------------------------------------------------------------------
| CUANDO CAMBIA EL EXAMEN
|--------------------------------------------------------------------------
*/

examSelect.addEventListener(
    'change',
    function () {

        actualizarTablaResultados();

    }
);


/*
|--------------------------------------------------------------------------
| CUANDO CAMBIA EL GRUPO
|--------------------------------------------------------------------------
*/

groupSelect.addEventListener(
    'change',
    function () {

        if (examSelect.value) {

            actualizarTablaResultados();

        }

    }
);

    /*
    |--------------------------------------------------------------------------
    | CUANDO CAMBIA EL CURSO
    |--------------------------------------------------------------------------
    */

    courseSelect.addEventListener(
        'change',
        function () {

            const courseId =
                courseSelect.value;


            /*
             * Reiniciar filtros dependientes.
             */
            groupSelect.innerHTML = `
                <option value="">
                    Cargando grupos...
                </option>
            `;


            examSelect.innerHTML = `
                <option value="">
                    Cargando exámenes...
                </option>
            `;


            groupSelect.disabled = true;

            examSelect.disabled = true;


            if (!courseId) {
                return;
            }


            cargarGrupos(
                courseId
            );


            cargarExamenes(
                courseId
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INICIAR
    |--------------------------------------------------------------------------
    */

    cargarCursos();

    /*
|--------------------------------------------------------------------------
| CERRAR IMAGEN AMPLIADA CON X
|--------------------------------------------------------------------------
*/

if (evidenceImageViewerClose) {

    evidenceImageViewerClose.addEventListener(
        'click',
        cerrarImagenAmpliada
    );

}

/*
|--------------------------------------------------------------------------
| FLECHA ANTERIOR
|--------------------------------------------------------------------------
*/

if (evidenceImageViewerPrev) {

    evidenceImageViewerPrev.addEventListener(
        'click',
        function (event) {

            event.stopPropagation();

            mostrarImagenAnterior();

        }
    );

}


/*
|--------------------------------------------------------------------------
| FLECHA SIGUIENTE
|--------------------------------------------------------------------------
*/

if (evidenceImageViewerNext) {

    evidenceImageViewerNext.addEventListener(
        'click',
        function (event) {

            event.stopPropagation();

            mostrarImagenSiguiente();

        }
    );

}

/*
|--------------------------------------------------------------------------
| CERRAR AL TOCAR EL FONDO
|--------------------------------------------------------------------------
*/

if (evidenceImageViewer) {

    evidenceImageViewer.addEventListener(
        'click',
        function (event) {

            if (
                event.target.matches(
                    '[data-close-evidence-viewer]'
                )
            ) {

                cerrarImagenAmpliada();

            }

        }
    );

}
/*
|--------------------------------------------------------------------------
| CERRAR VISOR / MODAL CON ESC
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'keydown',
    function (event) {

        if (event.key !== 'Escape') {
            return;
        }


        /*
         * Si está abierta la fotografía grande,
         * cerrar solamente la fotografía.
         */
        if (
            evidenceImageViewer &&
            !evidenceImageViewer.hidden
        ) {

            cerrarImagenAmpliada();

            return;
        }


        /*
         * Si no hay fotografía ampliada,
         * cerrar el modal de evidencias.
         */
        if (
            evidenceModal &&
            !evidenceModal.hidden
        ) {

            cerrarModalEvidencias();

        }

    }
);

});
