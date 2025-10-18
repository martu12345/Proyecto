<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Controlador/administrador/MostrarEmpresasControlador.php');

$controlador = new EmpresasAdminControlador($conn);
$empresas = $controlador->obtenerTodosConTelefonos();

$htmlEmpresas = '';
if (count($empresas) === 0) {
    $htmlEmpresas = '<p>No hay empresas registradas.</p>';
} else {
    $htmlEmpresas .= '<ul class="clientes-list">';
    foreach ($empresas as $e) {
        $telefonos = implode(", ", $e['telefonos']);
        $htmlEmpresas .= "
        <li class='cliente-item'>
            <div class='info'>
                <span class='nombre'>" . htmlspecialchars($e['nombreEmpresa']) . "</span>
                <span class='email'>" . htmlspecialchars($e['email']) . "</span>
                <span class='telefono'>" . htmlspecialchars($telefonos) . "</span>
            </div>
            <div class='acciones'>
                <button class='btn-editar' data-id='{$e['id']}'>Editar</button>
                <button class='btn-eliminar' data-id='{$e['id']}'>Eliminar</button>
            </div>
        </li>";
    }
    $htmlEmpresas .= '</ul>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Empresas</title>
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/administrador/admin_empresas.css">
</head>
<body class="admin-empresas-page">

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

<h1>Administrar Empresas</h1>
<?= $htmlEmpresas ?>

<div id="modalEliminar" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>¿Seguro querés eliminar esta empresa?</p>
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
        <h3>Editar Empresa</h3>
        <form id="formEditarEmpresa">
            <input type="hidden" id="editarIdEmpresa" name="idEmpresa">

            <label for="editarNombre">Nombre de la empresa:</label>
            <input type="text" id="editarNombre" name="nombreEmpresa" required>

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
<script src="/Proyecto/public/js/administrador/admin_empresas.js"></script>

</body>
</html>
