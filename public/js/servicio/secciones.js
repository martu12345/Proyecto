
// codigo para que al tocar una de las cateogiras principales del home te las busque en la barra de busqueda
document.addEventListener('DOMContentLoaded', () => {
    const formularioBusqueda = document.querySelector('.busqueda form'); // tu formulario
    const inputBusqueda = formularioBusqueda.querySelector('input[name="q"]');

    document.querySelectorAll('.cuadro').forEach(cuadro => {
        cuadro.addEventListener('click', () => {
           
            inputBusqueda.value = cuadro.dataset.busqueda;
            
            formularioBusqueda.submit();
        });
    });
});
