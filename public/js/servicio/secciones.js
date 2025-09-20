// Código para que al tocar una de las categorías principales del home se busque automáticamente
document.addEventListener('DOMContentLoaded', () => {
    const formularioBusqueda = document.querySelector('.busqueda form'); // tu formulario
    const inputBusqueda = formularioBusqueda.querySelector('input[name="q"]');

    document.querySelectorAll('.cuadro').forEach(cuadro => {
        cuadro.addEventListener('click', () => {
            const categoria = cuadro.dataset.busqueda;

            // Escribimos la categoría en el input
            inputBusqueda.value = categoria;

            sessionStorage.setItem('ultimaBusqueda', categoria);
            sessionStorage.removeItem('ultimosResultados'); // opcional: limpiar resultados previos

            // Hacemos submit del formulario
            formularioBusqueda.submit();
        });
    });
});
