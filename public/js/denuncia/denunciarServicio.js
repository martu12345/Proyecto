document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("denunciaModal");
    const openBtn = document.getElementById("openModalBtn");
    const closeBtn = document.getElementById("closeModalBtn");
    const form = document.getElementById("denunciaForm");
    const mensajeDiv = document.getElementById("mensajeDenuncia");

    if (openBtn) openBtn.addEventListener("click", () => {
        mensajeDiv.innerHTML = ""; // limpiar mensajes previos
        modal.style.display = "block";
    });

    if (closeBtn) closeBtn.addEventListener("click", () => modal.style.display = "none");

    window.addEventListener("click", (event) => {
        if (event.target === modal) modal.style.display = "none";
    });

    if (form) {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            mensajeDiv.innerHTML = "";

            const formData = new FormData(form);
            formData.set("motivo", "DenunciarServicio");

            try {
                const response = await fetch("/Proyecto/apps/controlador/cliente/DenunciarServicioControlador.php", {
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
    }
});
