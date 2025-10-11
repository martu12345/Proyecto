document.addEventListener('DOMContentLoaded', () => {
    const modalEliminar = document.getElementById('modalEliminarEmpresa');
    const mensajeModal = document.getElementById('mensajeModalEmpresa');
    const btnConfirm = document.getElementById('confirmDeleteEmpresa');
    const btnCancel = document.getElementById('cancelDeleteEmpresa');

    if (!modalEliminar || !btnConfirm || !btnCancel) return; // evita errores

    let servicioAEliminar = null;

    document.querySelectorAll('.btn-eliminar-empresa').forEach(btn => {
        btn.addEventListener('click', () => {
            servicioAEliminar = btn.dataset.id;
            mensajeModal.style.display = 'none';
            modalEliminar.classList.add('active');
        });
    });

    btnCancel.addEventListener('click', () => modalEliminar.classList.remove('active'));
    modalEliminar.addEventListener('click', () => modalEliminar.classList.remove('active'));
    modalEliminar.querySelector('.modal-content').addEventListener('click', e => e.stopPropagation());

    btnConfirm.addEventListener('click', () => {
        if (!servicioAEliminar) return;

        fetch('/Proyecto/apps/Controlador/servicio/EliminarServiciosControlador.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `idServicio=${encodeURIComponent(servicioAEliminar)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const item = document.querySelector(`.btn-eliminar-empresa[data-id="${servicioAEliminar}"]`)?.closest('.servicio');
                if (item) item.remove();
                mensajeModal.style.display = 'block';
                mensajeModal.textContent = 'Servicio eliminado correctamente';
                setTimeout(() => modalEliminar.classList.remove('active'), 1500);
            } else {
                mensajeModal.style.display = 'block';
                mensajeModal.textContent = 'Error: ' + data.error;
            }
        })
        .catch(() => {
            mensajeModal.style.display = 'block';
            mensajeModal.textContent = 'Error en la solicitud';
        });
    });

    document.addEventListener('keydown', e => {
        if (modalEliminar.classList.contains('active') && e.key === 'Escape') {
            modalEliminar.classList.remove('active');
        }
    });
});
