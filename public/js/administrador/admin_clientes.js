document.addEventListener('DOMContentLoaded', () => {
    let clienteAEliminar = null;
    const modalEliminar = document.getElementById('modalEliminar');
    const mensajeModal = document.getElementById('mensajeModal');

    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', () => {
            clienteAEliminar = btn.dataset.id;
            mensajeModal.style.display = 'none';
            modalEliminar.style.display = 'flex';
        });
    });

    document.getElementById('cancelDelete').onclick = () => modalEliminar.style.display = 'none';
    modalEliminar.querySelector('.close').onclick = () => modalEliminar.style.display = 'none';

    document.getElementById('confirmDelete').onclick = () => {
        if (!clienteAEliminar) return;

  fetch('/Proyecto/apps/Controlador/administrador/AdministrarClientesControlador.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `accion=eliminar&idCliente=${clienteAEliminar}`
})

        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const item = document.querySelector(`.btn-eliminar[data-id="${clienteAEliminar}"]`)?.closest('.cliente-item');
                if (item) item.remove();
                mensajeModal.style.display = 'block';
                mensajeModal.textContent = 'Cliente eliminado correctamente';
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

    const modalEditar = document.getElementById('modalEditar');

    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', () => {
            const item = btn.closest('.cliente-item');

            // Obtener datos correctos desde los spans
            const idCliente = btn.dataset.id;
            const nombre = item.querySelector('.nombre').textContent;
            const apellido = item.querySelector('.apellido').textContent;
            const email = item.querySelector('.email').textContent;
            const telefono = item.querySelector('.telefono').textContent;

            // Llenar el modal
            document.getElementById('editarIdCliente').value = idCliente;
            document.getElementById('editarNombre').value = nombre;
            document.getElementById('editarApellido').value = apellido;
            document.getElementById('editarEmail').value = email;
            document.getElementById('editarTelefono').value = telefono;

            document.getElementById('mensajeEditar').style.display = 'none';
            modalEditar.style.display = 'flex';
        });
    });

    // Cerrar modal
    document.getElementById('cancelEditar').onclick = () => modalEditar.style.display = 'none';
    modalEditar.querySelector('.closeEditar').onclick = () => modalEditar.style.display = 'none';
    window.addEventListener('click', e => { if (e.target === modalEditar) modalEditar.style.display = 'none'; });
    document.addEventListener('keydown', e => { if (modalEditar.style.display === 'flex' && e.key === 'Escape') modalEditar.style.display = 'none'; });

    // Enviar formulario de editar
    document.getElementById('formEditarCliente').addEventListener('submit', e => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const params = new URLSearchParams(formData).toString();

        fetch('/Proyecto/apps/Controlador/administrador/AdministrarClientesControlador.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `accion=editar&${params}`
        })
        .then(res => res.json())
        .then(data => {
            const mensajeEditar = document.getElementById('mensajeEditar');
            mensajeEditar.style.display = 'block';
            if (data.success) {
                mensajeEditar.textContent = 'Cliente actualizado correctamente';
                setTimeout(() => modalEditar.style.display = 'none', 1500);

                // Actualizar la lista sin recargar
                const item = document.querySelector(`.btn-editar[data-id="${formData.get('idCliente')}"]`).closest('.cliente-item');
                item.querySelector('.nombre').textContent = formData.get('nombre');
                item.querySelector('.apellido').textContent = formData.get('apellido');
                item.querySelector('.email').textContent = formData.get('email');
                item.querySelector('.telefono').textContent = formData.get('telefono');
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
