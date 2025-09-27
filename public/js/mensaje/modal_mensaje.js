document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalMensaje');
    const cerrar = modal.querySelector('.cerrar');
    const botones = document.querySelectorAll('.mensaje'); // todos los botones de mensaje

    botones.forEach(boton => {
        boton.addEventListener('click', function(e) {
            e.preventDefault();
            modal.style.display = 'block';
        });
    });

    // Cerrar modal con X
    cerrar.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Cerrar modal si clickeas afuera
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
