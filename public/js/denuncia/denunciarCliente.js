document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("denunciaModal");
    const closeBtn = document.getElementById("closeModalBtn");
    const form = document.getElementById("denunciaForm");
    const mensajeDiv = document.getElementById("mensajeDenuncia");

    // Abrir modal al click en un botón "Denunciar"
    document.querySelectorAll(".btn-denunciar").forEach(btn => {
        btn.addEventListener("click", () => {
            mensajeDiv.innerHTML = "";
            form.detalle.value = "";
            form.idCliente.value = btn.dataset.idcliente; // asignar cliente
            modal.style.display = "block";
        });
    });

    // Cerrar modal
    closeBtn.addEventListener("click", () => modal.style.display = "none");
    window.addEventListener("click", e => {
        if (e.target === modal) modal.style.display = "none";
    });

    // Enviar denuncia vía fetch
    form.addEventListener("submit", async e => {
        e.preventDefault();
        mensajeDiv.innerHTML = "";

        const formData = new FormData(form);

        try {
            const response = await fetch("/Proyecto/apps/controlador/empresa/DenunciarClienteControlador.php", {
                method: "POST",
                body: formData
            });

            const data = await response.json();

            mensajeDiv.textContent = data.message;
            mensajeDiv.className = "mensaje-denuncia " + (data.success ? "exito" : "error");

            if (data.success) {
                form.reset();
                setTimeout(() => modal.style.display = "none", 1500);
            }
        } catch (err) {
            mensajeDiv.textContent = "Ocurrió un error al enviar la denuncia.";
            mensajeDiv.className = "mensaje-denuncia error";
        }
    });
});
