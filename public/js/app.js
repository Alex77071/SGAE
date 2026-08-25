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