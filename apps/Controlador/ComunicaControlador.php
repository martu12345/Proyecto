<?php
session_start();
require_once '../Modelos/Comunica.php';
require_once '../Modelos/conexion.php';

if (!isset($_SESSION['idUsuario'])) {
    header("Location: /Proyecto/apps/vistas/login.php?error=debes_iniciar_sesion");
    exit();
}

$asunto = $_POST['asunto'] ?? '';
$contenido = $_POST['contenido'] ?? '';
$idMensajeRespondido = $_POST['idMensajeRespondido'] ?? null;
$mensajePadre = $idMensajeRespondido ? Comunica::obtenerMensajePorId($conn, $idMensajeRespondido) : null;

$idUsuarioEmisor = $_SESSION['idUsuario'];
$idUsuarioCliente = null;
$idUsuarioEmpresa = null;

// Lógica según rol
if ($_SESSION['rol'] === 'empresa') {
    $idUsuarioEmpresa = $_SESSION['idUsuario']; // la empresa que responde
    $idUsuarioCliente = $mensajePadre ? $mensajePadre['IdUsuarioCliente'] : $_POST['cliente_id'] ?? null;
} else {
    $idUsuarioCliente = $_SESSION['idUsuario']; // el cliente que responde
    $idUsuarioEmpresa = $mensajePadre ? $mensajePadre['IdUsuarioEmpresa'] : $_POST['empresa_id'] ?? null;
}


if ($idUsuarioCliente && $idUsuarioEmpresa && $asunto && $contenido) {
    $fecha = date("Y-m-d H:i:s");

    $mensaje = new Comunica(
        $idUsuarioCliente,
        $idUsuarioEmpresa,
        0,
        $asunto,
        $contenido,
        $fecha,
        $idUsuarioEmisor,
        "no leido",
        $idMensajeRespondido
    );

    echo $mensaje->enviar($conn) ? "ok" : "error";
} else {
    echo "incompleto";
}
