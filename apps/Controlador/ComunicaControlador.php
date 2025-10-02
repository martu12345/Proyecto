<?php
var_dump($_POST);

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

if ($idUsuarioEmpresa && $asunto && $contenido) {
    $fecha = date("Y-m-d H:i:s");

    // Crear objeto Comunica con asunto
    $mensaje = new Comunica($idUsuarioCliente, $idUsuarioEmpresa, 0, $asunto, $contenido, $fecha);

    // Enviar mensaje usando la función enviar de la misma clase
    if ($mensaje->enviar($conn)) {
        header("Location: /Proyecto/apps/vistas/paginas/cliente/bandeja.php?exito=1");
        exit();
    } else {
        echo "Error al enviar mensaje";
    }
} else {
    echo "Datos incompletos";
}
