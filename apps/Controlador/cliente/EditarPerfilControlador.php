<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Cliente.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Usuario.php');

header('Content-Type: application/json');

if (!isset($_SESSION['idUsuario'])) {
    echo json_encode(['ok' => false, 'error' => 'No logueado']);
    exit();
}

$idUsuario = (int)$_SESSION['idUsuario'];

// Obtener datos actuales
$stmt = $conn->prepare("SELECT u.Email, c.Nombre, c.Apellido, c.Imagen FROM usuario u JOIN cliente c ON u.IdUsuario = c.IdUsuario WHERE u.IdUsuario = ?");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$datos = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$datos) {
    echo json_encode(['ok'=>false,'error'=>'No se encontraron datos del usuario']);
    exit();
}

// Recibir datos del formulario
$nombre = $_POST['nombre'] ?? $datos['Nombre'];
$apellido = $_POST['apellido'] ?? $datos['Apellido'];
$email = $_POST['email'] ?? $datos['Email'];
$contrasena = $_POST['contrasena'] ?? null;

if(empty($email)) {
    echo json_encode(['ok'=>false,'error'=>'Email es obligatorio']);
    exit();
}

// Crear objeto cliente
$cliente = new Cliente($idUsuario, $email, null, $nombre, $apellido, $datos['Imagen']);
$cliente->setEmail($email);

// Actualizar contraseña solo si se pasó nueva
if($contrasena) {
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt2 = $conn->prepare("UPDATE usuario SET Contraseña = ? WHERE IdUsuario = ?");
    $stmt2->bind_param("si", $hash, $idUsuario);
    $stmt2->execute();
    $stmt2->close();
}

// Actualizar cliente en DB
if($cliente->actualizarCliente($conn)) {
    echo json_encode([
        'ok' => true,
        'nombre' => $nombre,
        'apellido' => $apellido,
        'email' => $email
    ]);
} else {
    echo json_encode(['ok' => false,'error'=>'No se pudo actualizar cliente']);
}
