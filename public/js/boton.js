document.addEventListener('DOMContentLoaded', () => {
    const btnCrear = document.getElementById('btnCrearServicio');
    const modal = document.getElementById('modalServicio');
    const btnCerrar = document.querySelector('#modalServicio .cerrar');

    if (!btnCrear || !modal || !btnCerrar) {
        console.error('No se encontraron elementos del modal o del botón. Revisa IDs y clases.');
        return;
    }

    btnCrear.addEventListener('click', () => {
        modal.style.display = 'block';
    });

    btnCerrar.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
    });
});
