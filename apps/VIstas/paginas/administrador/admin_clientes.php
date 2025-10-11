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
                    <span class="nombre"><?= htmlspecialchars($c->getNombre()) ?></span>
                    <span class="email"><?= htmlspecialchars($c->getEmail()) ?></span>
                    <span class="telefono"><?= htmlspecialchars(implode(", ", $telefonos)) ?></span>
                </div>
                <div class="acciones">
                    <button class="btn-editar" onclick="window.location.href='editar_cliente_admin.php?id=<?= $c->getIdUsuario() ?>'">Editar</button>
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

        fetch('/Proyecto/apps/Controlador/administrador/eliminarClienteAdmin.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `idCliente=${clienteAEliminar}`
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
});
</script>

</body>
</html>
