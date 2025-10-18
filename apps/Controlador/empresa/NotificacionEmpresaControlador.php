<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Comunica.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$seccion = $_GET['seccion'] ?? 'perfil';
if ($seccion === "null") {
    $seccion = 'perfil';
}

$notificacionesNoLeidas = 0;

if (isset($_SESSION['idUsuario']) && $_SESSION['rol'] === 'empresa') {
    $idEmpresa = $_SESSION['idUsuario'];

    if (in_array($seccion, ['mensajes','agendados'])) {
        Comunica::marcarComoLeidoPorEmpresa($conn, $idEmpresa);
    }

    $notificacionesNoLeidas = Comunica::contarNoLeidosPorEmpresa($conn, $idEmpresa);
}
?>
