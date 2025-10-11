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
                <button class='btn-editar' onclick=\"window.location.href='editar_empresa_admin.php?id={$e['id']}'\">Editar</button>
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
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/administrador/admin_clientes.css">
</head>
<body>
<h1>Administrar Empresas</h1>
<?= $htmlEmpresas ?>
<!-- Podés reutilizar el modal de eliminar igual que con clientes -->
</body>
</html>
