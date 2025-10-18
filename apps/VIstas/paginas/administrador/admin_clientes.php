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
        <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">

    <link rel="stylesheet" href="/Proyecto/public/css/paginas/administrador/admin_clientes.css">

</head>

<body>
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

<div class="admin-clientes-page"> 
<h1>Administrar Clientes</h1>

<?php if (count($clientes) === 0): ?>
    <p>No hay clientes registrados.</p>
<?php else: ?>
    <ul class="clientes-list">
        <?php foreach ($clientes as $c): ?>
            <?php $telefonos = $c->obtenerTelefonos($conn);  ?>
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
</div>
<script src="/Proyecto/public/js/administrador/admin_clientes.js"></script>


</body>
</html>
