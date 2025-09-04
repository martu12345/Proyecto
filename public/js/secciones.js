document.addEventListener('DOMContentLoaded', () => {
    const formularioBusqueda = document.querySelector('.busqueda form'); // tu formulario
    const inputBusqueda = formularioBusqueda.querySelector('input[name="q"]');

    document.querySelectorAll('.cuadro').forEach(cuadro => {
        cuadro.addEventListener('click', () => {
            // Pone la palabra en el input
            inputBusqueda.value = cuadro.dataset.busqueda;
            // Envía el formulario
            formularioBusqueda.submit();
        });
    });
});
