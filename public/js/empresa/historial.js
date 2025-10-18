
document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("denunciaModal");
    const closeBtn = document.getElementById("closeModalBtn");
    const form = document.getElementById("denunciaForm");
    const mensajeDiv = document.getElementById("mensajeDenuncia");

    // Abrir modal al hacer click en "Denunciar"
    document.querySelectorAll(".btn-denunciar").forEach(btn => {
        btn.addEventListener("click", () => {
            form.idCliente.value = btn.dataset.idcliente;
            mensajeDiv.innerHTML = "";
            modal.style.display = "block";
        });
    });

    // Cerrar modal
    closeBtn.addEventListener("click", () => modal.style.display = "none");
    window.addEventListener("click", (e) => {
        if (e.target === modal) modal.style.display = "none";
    });

    // Enviar denuncia
    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        mensajeDiv.innerHTML = "";

        const formData = new FormData(form);

        try {
            const response = await fetch("/Proyecto/apps/controlador/empresa/DenunciarEmpresaControlador.php", {
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
        // Mostrar error real del fetch o JSON
        mensajeDiv.textContent = "Error de conexión o respuesta inválida: " + err.message;
        mensajeDiv.className = "mensaje-denuncia error";
        console.error(err); // esto lo ves en consola
    }
    });
});
