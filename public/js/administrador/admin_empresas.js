
document.addEventListener('DOMContentLoaded', () => {
    let empresaAEliminar = null;
    const modalEliminar = document.getElementById('modalEliminar');
    const mensajeModal = document.getElementById('mensajeModal');

    // --- ELIMINAR ---
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', () => {
            empresaAEliminar = btn.dataset.id;
            mensajeModal.style.display = 'none';
            modalEliminar.style.display = 'flex';
        });
    });

    document.getElementById('cancelDelete').onclick = () => modalEliminar.style.display = 'none';
    modalEliminar.querySelector('.close').onclick = () => modalEliminar.style.display = 'none';

    document.getElementById('confirmDelete').onclick = () => {
        if (!empresaAEliminar) return;

        fetch('/Proyecto/apps/Controlador/administrador/AdministrarEmpresasControlador.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `accion=eliminar&idEmpresa=${empresaAEliminar}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const item = document.querySelector(`.btn-eliminar[data-id="${empresaAEliminar}"]`)?.closest('.cliente-item');
                if (item) item.remove();
                mensajeModal.style.display = 'block';
                mensajeModal.textContent = 'Empresa eliminada correctamente';
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

    // --- EDITAR ---
    const modalEditar = document.getElementById('modalEditar');

    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', () => {
            const item = btn.closest('.cliente-item');
            const idEmpresa = btn.dataset.id;
            const nombre = item.querySelector('.nombre').textContent;
            const email = item.querySelector('.email').textContent;
            const telefono = item.querySelector('.telefono').textContent;

            document.getElementById('editarIdEmpresa').value = idEmpresa;
            document.getElementById('editarNombre').value = nombre;
            document.getElementById('editarEmail').value = email;
            document.getElementById('editarTelefono').value = telefono;

            document.getElementById('mensajeEditar').style.display = 'none';
            modalEditar.style.display = 'flex';
        });
    });

    document.getElementById('cancelEditar').onclick = () => modalEditar.style.display = 'none';
    modalEditar.querySelector('.closeEditar').onclick = () => modalEditar.style.display = 'none';

    // Enviar formulario editar
    document.getElementById('formEditarEmpresa').addEventListener('submit', e => {
        e.preventDefault();

        const idEmpresa = document.getElementById('editarIdEmpresa').value.trim();
        const nombre = document.getElementById('editarNombre').value.trim();
        const email = document.getElementById('editarEmail').value.trim();
        const telefono = document.getElementById('editarTelefono').value.trim();

        if (!idEmpresa || !nombre || !email) {
            const mensajeEditar = document.getElementById('mensajeEditar');
            mensajeEditar.style.display = 'block';
            mensajeEditar.textContent = 'Faltan datos obligatorios';
            return;
        }

        const formData = new URLSearchParams();
        formData.append('accion', 'editar');
        formData.append('idEmpresa', idEmpresa);
        formData.append('nombreEmpresa', nombre);
        formData.append('email', email);
        formData.append('telefono', telefono);

        fetch('/Proyecto/apps/Controlador/administrador/AdministrarEmpresasControlador.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData.toString()
        })
        .then(res => res.json())
        .then(data => {
            const mensajeEditar = document.getElementById('mensajeEditar');
            mensajeEditar.style.display = 'block';
            if (data.success) {
                mensajeEditar.textContent = 'Empresa actualizada correctamente';
                setTimeout(() => modalEditar.style.display = 'none', 1500);

                // Actualiza visualmente la lista
                const item = document.querySelector(`.btn-editar[data-id="${idEmpresa}"]`).closest('.cliente-item');
                item.querySelector('.nombre').textContent = nombre;
                item.querySelector('.email').textContent = email;
                item.querySelector('.telefono').textContent = telefono;
            } else {
                mensajeEditar.textContent = 'Error: ' + data.error;
            }
        })
        .catch(() => {
            const mensajeEditar = document.getElementById('mensajeEditar');
            mensajeEditar.style.display = 'block';
            mensajeEditar.textContent = 'Error en la solicitud';
        });
    });
});
