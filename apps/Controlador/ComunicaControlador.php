<?php

session_start();
require_once '../Modelos/Comunica.php';
require_once '../Modelos/conexion.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['idUsuario'])) {
    header("Location: /Proyecto/apps/vistas/login.php?error=debes_iniciar_sesion");
    exit();
}

$idUsuarioCliente = $_SESSION['idUsuario'];
$idUsuarioEmpresa = $_POST['empresa_id'] ?? null;
$asunto = $_POST['asunto'] ?? '';
$contenido = $_POST['contenido'] ?? '';
$idUsuarioEmisor = $_SESSION['idUsuario']; 

if ($idUsuarioEmpresa && $asunto && $contenido) {
    $fecha = date("Y-m-d H:i:s");

    // Crear objeto Comunica con asunto
    $mensaje = new Comunica($idUsuarioCliente, $idUsuarioEmpresa, 0, $asunto, $contenido, $fecha, $idUsuarioEmisor);

    // Enviar mensaje usando la función enviar de la misma clase
    if ($mensaje->enviar($conn)) {
        echo "ok";
    } else {
        echo "error";
    }
} else {
    echo "incompleto";
}
