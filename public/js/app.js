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

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            closeProfileMenu();

        }

    });

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
| SIMULACIÓN DEL ANÁLISIS DE EVIDENCIAS
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', function () {

    const analysisPage =
        document.getElementById('analysisProcessPage');

    const analysisRunning =
        document.getElementById('analysisRunning');

    const analysisComplete =
        document.getElementById('analysisComplete');

    const progressBar =
        document.getElementById('analysisProgressBar');

    const percentage =
        document.getElementById('analysisPercentage');

    const circle =
        document.getElementById('analysisCircleProgress');

    const imageCounter =
        document.getElementById('analysisImageCounter');

    const currentFile =
        document.getElementById('analysisCurrentFile');


    /*
     * Si no estamos en la pantalla de análisis,
     * no ejecutamos este código.
     */
    if (
        !analysisPage ||
        !analysisRunning ||
        !analysisComplete ||
        !progressBar ||
        !percentage ||
        !circle ||
        !imageCounter ||
        !currentFile
    ) {
        return;
    }


    const reportUrl =
        analysisPage.dataset.reportUrl;

    const totalImages = 1248;

    const circumference =
        2 * Math.PI * 50;

    let progress = 0;


    circle.style.strokeDasharray =
        circumference;

    circle.style.strokeDashoffset =
        circumference;


    const analysisInterval = setInterval(function () {

        /*
         * Simulación temporal.
         */
        const increment =
            Math.floor(Math.random() * 3) + 1;

        progress += increment;


        if (progress >= 100) {
            progress = 100;
        }


        /*
         * Porcentaje.
         */
        percentage.textContent =
            progress + ' %';


        /*
         * Barra horizontal.
         */
        progressBar.style.width =
            progress + '%';


        /*
         * Círculo de progreso.
         */
        const offset =
            circumference -
            (progress / 100) * circumference;

        circle.style.strokeDashoffset =
            offset;


        /*
         * Número de imagen procesada.
         */
        const currentImage =
            Math.min(
                totalImages,
                Math.round(
                    totalImages *
                    (progress / 100)
                )
            );


        imageCounter.textContent =
            'Imagen ' +
            currentImage.toLocaleString('es-MX') +
            ' de ' +
            totalImages.toLocaleString('es-MX');


        /*
         * Nombre temporal de la imagen.
         */
        currentFile.textContent =
            'ciwa_' +
            String(currentImage).padStart(4, '0') +
            '.jpg';


        /*
         * Final del análisis.
         */
        if (progress >= 100) {

    clearInterval(analysisInterval);


    /*
     * OCULTAR PANTALLA DE ANÁLISIS
     */
    analysisRunning.hidden = true;

    analysisRunning.style.display = 'none';


    /*
     * MOSTRAR PANTALLA DE ANÁLISIS COMPLETADO
     */
    analysisComplete.hidden = false;

    analysisComplete.removeAttribute('hidden');

    analysisComplete.classList.add(
        'analysis-finished-card--visible'
    );


    /*
     * Forzar visibilidad por si algún estilo
     * quedó duplicado después del merge.
     */
    analysisComplete.style.display = 'flex';

    analysisComplete.style.visibility = 'visible';

    analysisComplete.style.opacity = '1';

}

    }, 220);

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


            imagenes =
                Number(
                    data.imagenes || 0
                ).toLocaleString(
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

});