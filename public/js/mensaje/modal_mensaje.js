document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalMensaje');
    const cerrar = modal.querySelector('.cerrar');
    const botones = document.querySelectorAll('.mensaje'); 
    const inputEmpresaId = document.getElementById('empresaIdInput');

    botones.forEach(boton => {
        boton.addEventListener('click', function(e) {
            e.preventDefault();
            const empresaId = this.getAttribute('data-empresaid'); 
            inputEmpresaId.value = empresaId; // lo pasa al input hidden
                        console.log("empresaId capturado:", empresaId); // debug

            modal.style.display = 'block';
        });
    });

    cerrar.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
