document.addEventListener('DOMContentLoaded', () => {
    const filtro = document.getElementById('filtroEstrellas');
    const resenasList = document.getElementById('resenasList');
    if (!filtro || !resenasList) return;

    const inputs = filtro.querySelectorAll('input[name="estrellas"]');

    inputs.forEach(input => {
        input.addEventListener('change', () => {
            const valorSeleccionado = parseInt(input.value);
            const cards = resenasList.querySelectorAll('.resena-card');

            cards.forEach(card => {
                const calificacion = parseInt(card.getAttribute('data-calificacion'));
                card.style.display = (calificacion === valorSeleccionado) ? 'flex' : 'none';
            });
        });
    });

    // Permitir deseleccionar todas al hacer click en la estrella ya seleccionada
    filtro.querySelectorAll('label').forEach(label => {
        label.addEventListener('click', () => {
            const input = document.getElementById(label.getAttribute('for'));
            if (input.checked) {
                setTimeout(() => {
                    input.checked = false;
                    const cards = resenasList.querySelectorAll('.resena-card');
                    cards.forEach(card => card.style.display = 'flex');
                }, 10);
            }
        });
    });
});
