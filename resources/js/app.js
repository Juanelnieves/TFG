import * as bootstrap from 'bootstrap'

document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar'); // Selecciona el elemento con la clase sidebar
    const sidebar2 = document.querySelector('.sidebar2'); // Selecciona el elemento con la clase sidebar

    const mediaQuery = window.matchMedia('(max-width: 576px)'); // Tamaño extra pequeño (xs)

    function handleScreenSizeChange(e) {
        if (e.matches) {
            sidebar.classList.add('d-none'); // Agrega la clase d-none si el tamaño de la pantalla es xs
            sidebar2.classList.remove('d-none'); // Agrega la clase d-none si el tamaño de la pantalla es xs
        } else {
            sidebar.classList.remove('d-none'); // Remueve la clase d-none si el tamaño de la pantalla no es xs
            sidebar2.classList.add('d-none'); // Remueve la clase d-none si el tamaño de la pantalla no es xs
        }
    }

    handleScreenSizeChange(mediaQuery);
    mediaQuery.addEventListener('change', handleScreenSizeChange);
});
