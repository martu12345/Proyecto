<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// veo si hay un usuario logueado y q es 
$logueado = isset($_SESSION['idUsuario']);
$rol = $logueado ? $_SESSION['rol'] : null;

// el logo manitas los lleva a su home correspondiente 
$home_url = "/Proyecto/public/index.php"; //  por defecto
if ($logueado) {
    if ($rol === 'admin') {
        $home_url = "/Proyecto/apps/vistas/paginas/admin/dashboard.php";
    } elseif ($rol === 'empresa') {
        $home_url = "/Proyecto/apps/vistas/paginas/empresa/home_empresa.php";
    } else {
        $home_url = "/Proyecto/apps/vistas/paginas/cliente/home_cliente.php";
    }
}

// mi perfil los lleva al perfil de cad uno 
if ($logueado) {
    if ($rol === 'admin') {
        $perfil_url = "/Proyecto/apps/vistas/paginas/admin/perfil.php";
    } elseif ($rol === 'empresa') {
        $perfil_url = "/Proyecto/apps/vistas/paginas/empresa/perfil.php";
    } elseif ($rol === 'cliente') {
        $perfil_url = "/Proyecto/apps/vistas/paginas/cliente/perfil_cliente.php";
    }
} else {
    $perfil_url = null; // no logueado
}

?>
