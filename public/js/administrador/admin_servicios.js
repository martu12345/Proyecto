
    document.addEventListener('DOMContentLoaded', () => {
        let servicioAEliminar = null;
        const modalEliminar = document.getElementById('modalEliminar');
        const mensajeModal = document.getElementById('mensajeModal');

        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', () => {
                servicioAEliminar = btn.dataset.id;
                mensajeModal.style.display = 'none';
                modalEliminar.style.display = 'flex';
            });
        });

        document.getElementById('cancelDelete').onclick = () => modalEliminar.style.display = 'none';
        modalEliminar.querySelector('.close').onclick = () => modalEliminar.style.display = 'none';

        document.getElementById('confirmDelete').onclick = () => {
            if (!servicioAEliminar) return;

            fetch('/Proyecto/apps/Controlador/servicio/EliminarServiciosControlador.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `idServicio=${servicioAEliminar}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const item = document.querySelector(`.btn-eliminar[data-id="${servicioAEliminar}"]`)?.closest('.servicio-item');
                    if (item) item.remove();
                    mensajeModal.style.display = 'block';
                    mensajeModal.textContent = 'Servicio eliminado correctamente';
                    setTimeout(() => modalEliminar.style.display = 'none', 1500);
                } else {
                    mensajeModal.style.display = 'block';
                    mensajeModal.textContent = 'Error: ' + data.error;
                }
            })
            .catch(() => {
                mensajeModal.style.display = 'block';
                mensajeModal.textContent = 'Error en la solicitud';
            });
        };

        window.addEventListener('click', e => {
            if (e.target === modalEliminar) modalEliminar.style.display = 'none';
        });

        document.addEventListener('keydown', e => {
            if (modalEliminar.style.display === 'flex' && e.key === 'Escape') modalEliminar.style.display = 'none';
        });
    });
        // ✅ Cambiamos el destino del form para que use el controlador del admin
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById('formServicio');
            form.action = "/Proyecto/apps/Controlador/administrador/AdministrarServiciosControlador.php";
        });