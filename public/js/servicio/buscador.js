// guarda lo que la perosna bsuqeu en la barra de bsuqueda y tambien hace que el buscado no titile como loco 

document.addEventListener('DOMContentLoaded', () => {
    const buscador = document.querySelector('.busqueda input[name="q"]');
    const form = buscador.closest('form');

    // Recupera lo que la persina guardo en el busca
    const valorGuardado = sessionStorage.getItem('ultimaBusqueda');

    if (valorGuardado && buscador && !form.dataset.submitAutomático) {
        buscador.value = valorGuardado;
        form.dataset.submitAutomático = "true";

        if (!document.querySelector('#resultados').innerHTML.trim()) {
            form.submit();
        }
    }

    form.addEventListener('submit', () => {
        sessionStorage.setItem('ultimaBusqueda', buscador.value);
    });
});
