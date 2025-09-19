// guarda lo que la perosna bsuqeu en la barra de bsuqueda y tambien hace que el buscado no titile como loco 

document.addEventListener('DOMContentLoaded', () => {
    const buscador = document.querySelector('.busqueda input[name="q"]');
    if (!buscador) return; // si no hay buscador, salimos

    const form = buscador.closest('form');
    if (!form) return; // si no hay form, salimos

    // Recupera lo que la persona guardó en el buscador
    const valorGuardado = sessionStorage.getItem('ultimaBusqueda');

    if (valorGuardado && !form.dataset.submitAutomatico) {
        buscador.value = valorGuardado;
        form.dataset.submitAutomatico = "true";

        // Solo hacemos submit si existe el contenedor de resultados
        const resultados = document.querySelector('#resultados');
        if (resultados && !resultados.innerHTML.trim()) {
            form.submit();
        }
    }

    form.addEventListener('submit', () => {
        sessionStorage.setItem('ultimaBusqueda', buscador.value);
    });
});
