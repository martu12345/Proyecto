document.addEventListener('DOMContentLoaded', () => {
    const filtro = document.getElementById('filtroEstrellas');
    const resenasList = document.getElementById('resenasList');

    if (!filtro || !resenasList) return;

    let valorSeleccionado = 0;

    filtro.addEventListener('mouseover', (e) => {
        const span = e.target.closest('span');
        if (!span || !span.dataset.valor) return;
        const valor = parseInt(span.dataset.valor);

        filtro.querySelectorAll('span').forEach((s, i) => {
            s.style.color = (i < valor) ? '#ffdd00' : '#ccc';
        });
    });

    filtro.addEventListener('mouseout', () => {
        filtro.querySelectorAll('span').forEach((s, i) => {
            s.style.color = (i < valorSeleccionado) ? '#ffd700' : '#ccc';
        });
    });

    filtro.addEventListener('click', (e) => {
        const span = e.target.closest('span');
        if (!span || !span.dataset.valor) return;

        const valor = parseInt(span.dataset.valor);
        valorSeleccionado = (valorSeleccionado === valor) ? 0 : valor;

        const cards = resenasList.querySelectorAll('.resena-card');
        cards.forEach(card => {
            const calificacion = parseInt(card.getAttribute('data-calificacion'));
            card.style.display = (valorSeleccionado === 0 || calificacion === valorSeleccionado) ? 'block' : 'none';
        });

        filtro.querySelectorAll('span').forEach((s, i) => {
            s.style.color = (i < valorSeleccionado) ? '#ffd700' : '#ccc';
        });
    });
});
