document.addEventListener("DOMContentLoaded", function() {
    // =========================
    // TABLAS FINALIZADOS / CANCELADOS
    // =========================
    const tablaFinalizado = document.getElementById("tablaFinalizado");
    const tablaCancelado = document.getElementById("tablaCancelado");
    const btnFinalizado = document.querySelector('.filtro-btn[data-estado="Finalizado"]');
    const btnCancelado = document.querySelector('.filtro-btn[data-estado="Cancelado"]');
    const botonesFiltro = document.querySelectorAll(".filtro-btn");

    // Mostrar Finalizados automáticamente
    if (tablaFinalizado && tablaCancelado && btnFinalizado && btnCancelado) {
        tablaFinalizado.style.display = "table";
        tablaCancelado.style.display = "none";
        btnFinalizado.classList.add("activa");
        btnCancelado.classList.remove("activa");
    }

    // Función para cambiar tablas al click
    botonesFiltro.forEach(btn => {
        btn.addEventListener("click", () => {
            if (btn.dataset.estado === "Finalizado") {
                tablaFinalizado.style.display = "table";
                tablaCancelado.style.display = "none";
            } else {
                tablaFinalizado.style.display = "none";
                tablaCancelado.style.display = "table";
            }

            botonesFiltro.forEach(b => b.classList.remove("activa"));
            btn.classList.add("activa");
        });
    });

    // =========================
    // MODAL DE DENUNCIA
    // =========================
    const modal = document.getElementById("denunciaModal");
    const closeBtn = document.getElementById("closeModalBtn");
    const form = document.getElementById("denunciaForm");
    const mensajeDiv = document.getElementById("mensajeDenuncia");

    // Abrir modal al clickear "Denunciar"
    document.querySelectorAll(".btn-denunciar").forEach(btn => {
        btn.addEventListener("click", () => {
            form.idCliente.value = btn.dataset.idcliente;
            mensajeDiv.innerHTML = "";
            modal.style.display = "block";
        });
    });

    // Cerrar modal con la X
    closeBtn.addEventListener("click", () => modal.style.display = "none");

    // Cerrar modal al clickear fuera
    window.addEventListener("click", (e) => {
        if (e.target === modal) modal.style.display = "none";
    });

    // Enviar denuncia vía fetch
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
            mensajeDiv.textContent = "Error de conexión o respuesta inválida: " + err.message;
            mensajeDiv.className = "mensaje-denuncia error";
            console.error(err);
        }
    });
});

