document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalMensaje');
    const cerrar = modal.querySelector('.cerrar');
    const botones = document.querySelectorAll('.mensaje'); 
    const inputEmpresaId = document.getElementById('empresaIdInput');
    const nombreSpan = document.getElementById('empresaNombre');
    const formMensaje = document.getElementById('formMensaje');
    const mensajeExito = document.getElementById('mensajeExito');

    // Abrir modal y pasar datos de la empresa
    botones.forEach(boton => {
        boton.addEventListener('click', function(e) {
            e.preventDefault();
            const empresaId = this.getAttribute('data-empresaid'); 
            const empresaNombre = this.getAttribute('data-nombre');

            if (!empresaId) {
                alert("Error: no se pudo capturar el ID de la empresa.");
                return;
            }

            inputEmpresaId.value = empresaId;
            nombreSpan.innerText = empresaNombre || "Empresa";

            console.log("empresaId capturado:", empresaId); // debug

            modal.style.display = 'block';
        });
    });

    // Cerrar modal
    cerrar.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Enviar mensaje por AJAX sin recargar
    formMensaje.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(formMensaje);

        fetch('/Proyecto/apps/controlador/ComunicaControlador.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data); // debug
            if (data.trim() === 'ok') {
                mensajeExito.style.display = 'block'; // mostrar cartel de éxito
                formMensaje.reset(); // limpiar inputs
            } else {
                alert("Error al enviar mensaje: " + data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al enviar el mensaje.');
        });
    });
});
