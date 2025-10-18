
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("modalMensaje");
    const btnResponder = document.getElementById("abrirModalResponder");
    const cerrar = document.querySelector("#modalMensaje .cerrar");
    const empresaIdInput = document.getElementById("empresaIdInput");
    const empresaNombre = document.getElementById("empresaNombre");
    const asuntoInput = document.getElementById("asunto");
    const contenidoTextarea = document.getElementById("contenido");
    const contador = document.getElementById("contadorMensaje");
    const idMensajeInput = document.getElementById("idMensajeRespondido");

    btnResponder.addEventListener("click", () => {
        empresaNombre.textContent = btnResponder.dataset.emisor;
        empresaIdInput.value = btnResponder.dataset.usuario; // empresa original
        idMensajeInput.value = btnResponder.dataset.mensaje;  // ID del mensaje
        asuntoInput.value = "Re: " + btnResponder.dataset.asunto;
        contenidoTextarea.value = "";
        contador.textContent = "0 / 1000 caracteres";
        modal.style.display = "block";
    });

    cerrar.addEventListener("click", () => { modal.style.display = "none"; });
    window.addEventListener("click", (event) => { if (event.target === modal) modal.style.display = "none"; });

    contenidoTextarea.addEventListener("input", () => {
        contador.textContent = `${contenidoTextarea.value.length} / 1000 caracteres`;
    });

    // Enviar mensaje vía fetch
    const form = document.getElementById("formMensaje");
    const mensajeExito = document.getElementById("mensajeExito");

    form.addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch(form.action, {
            method: form.method,
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if(data === "ok"){
                mensajeExito.style.display = "block";
                form.asunto.value = "";
                form.contenido.value = "";
                contador.textContent = "0 / 1000 caracteres";

                setTimeout(() => {
                    mensajeExito.style.display = "none";
                    modal.style.display = "none";
                }, 2000);
            } else {
                alert("Error al enviar el mensaje");
            }
        })
        .catch(error => console.error("Error:", error));
    });
});
