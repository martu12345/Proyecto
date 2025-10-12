<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Controlador/administrador/MostrarClientesControlador.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Cliente.php');

$controlador = new ClientesAdminControlador($conn);
$clientes = $controlador->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Clientes - Administrador</title>
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/administrador/admin_clientes.css">

</head>
<body>

<h1>Administrar Clientes</h1>

<?php if (count($clientes) === 0): ?>
    <p>No hay clientes registrados.</p>
<?php else: ?>
    <ul class="clientes-list">
        <?php foreach ($clientes as $c): ?>
            <?php $telefonos = $c->obtenerTelefonos($conn); // obtenemos todos los teléfonos ?>
            <li class="cliente-item">
                <div class="info">
    <div class="info">
    <span class="nombre"><?= htmlspecialchars($c->getNombre()) ?></span>
                <span class="apellido"><?= htmlspecialchars($c->getApellido()) ?></span>
    <span class="email"><?= htmlspecialchars($c->getEmail()) ?></span>
    <span class="telefono"><?= htmlspecialchars(implode(", ", $telefonos)) ?></span>
</div>

                </div>
                <div class="acciones">
<button class="btn-editar" data-id="<?= $c->getIdUsuario() ?>">Editar</button>
                    <button class="btn-eliminar" data-id="<?= $c->getIdUsuario() ?>">Eliminar</button>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<!-- MODAL ELIMINAR -->
<div id="modalEliminar" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>¿Seguro querés eliminar este cliente?</p>
        <div class="modal-buttons">
            <button id="confirmDelete" class="btn-confirm">✔</button>
            <button id="cancelDelete" class="btn-cancel">X</button>
        </div>
        <div id="mensajeModal" class="mensaje-modal" style="display:none;"></div>
    </div>
</div>
<!-- MODAL EDITAR -->
<div id="modalEditar" class="modal">
    <div class="modal-content">
        <span class="closeEditar">&times;</span>
        <h3>Editar Cliente</h3>
        <form id="formEditarCliente">
            <input type="hidden" id="editarIdCliente" name="idCliente">

            <label for="editarNombre">Nombre:</label>
            <input type="text" id="editarNombre" name="nombre" required>

            <label for="editarApellido">Apellido:</label>
            <input type="text" id="editarApellido" name="apellido" required>

            <label for="editarEmail">Email:</label>
            <input type="email" id="editarEmail" name="email" required>

            <label for="editarTelefono">Teléfono:</label>
            <input type="text" id="editarTelefono" name="telefono">

            <div class="modal-buttons">
                <button type="submit" class="btn-confirm">Guardar</button>
                <button type="button" id="cancelEditar" class="btn-cancel">Cancelar</button>
            </div>
            <div id="mensajeEditar" class="mensaje-modal" style="display:none;"></div>
        </form>
    </div>
</div>

<script>
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
</script>

</body>
</html>
