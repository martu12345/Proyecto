<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$paginaActual = basename($_SERVER['PHP_SELF']);

$restricciones = [
    // Administrador
    'home_admin.php' => ['admin'],
    'admin.clientes' => ['admin'],
    'admin_denuncias.php' => ['admin'],
    'admin_empresas.php' => ['admin'],
    'admin_servicios.php' => ['admin'],

    // Cliente
    'home_cliente.php' => ['cliente'],
    'perfil_cliente.php' => ['cliente'],

    // Empresa
    'home_empresa.php' => ['empresa'],
    'perfil_empresa.php' => ['empresa'],
    'detalle_empresa.php' => ['empresa'],
    'detalleMensaje.php' => ['empresa'],

    // Propietario 
    'home_propietario.php' => ['propietario'],
    'crear_admin.php' => ['propietario'],
    'eliminar_admin.php' => ['propietario'],
    'historial_cambios.php' => ['propietario'],

];

if (isset($restricciones[$paginaActual])) {
    $rolesPermitidos = $restricciones[$paginaActual];
    $rolUsuario = $_SESSION['rol'] ?? null;

    if (!$rolUsuario || !in_array($rolUsuario, $rolesPermitidos)) {
        header('Location: /Proyecto/apps/seguridad/no_autorizado.php');
        exit();
    }
}
