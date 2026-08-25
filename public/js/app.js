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
| SIMULACIÓN DE DESCARGA DE EVIDENCIAS
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
     * Si no estamos en la pantalla de descarga,
     * no hacemos nada.
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


    /*
     * Simulación temporal.
     *
     * Más adelante este valor podrá sustituirse
     * por el progreso real de la descarga.
     */
    const downloadInterval = setInterval(function () {

        /*
         * Incrementos ligeramente variables para
         * dar una sensación más natural.
         */
        const increment =
            Math.floor(Math.random() * 6) + 2;

        progress += increment;


        if (progress >= 100) {
            progress = 100;
        }


        progressBar.style.width =
            progress + '%';

        progressPercentage.textContent =
            progress + '%';


        /*
         * Descarga terminada.
         */
        if (progress === 100) {

            clearInterval(downloadInterval);


            /*
             * Pequeña pausa para que pueda verse el 100%.
             */
            setTimeout(function () {

                progressState.hidden = true;

                completeState.hidden = false;

            }, 500);

        }

    }, 180);

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