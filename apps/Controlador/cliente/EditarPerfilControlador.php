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

$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellido'] ?? '';
$email = $_POST['email'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

if (empty($email) || empty($contrasena)) {
    echo json_encode(['ok'=>false, 'error'=>'Email y contraseña son obligatorios']);
    exit();
}

$stmt = $conn->prepare("SELECT u.Email, c.Nombre, c.Apellido, c.Imagen 
                        FROM usuario u 
                        JOIN cliente c ON u.IdUsuario = c.IdUsuario 
                        WHERE u.IdUsuario = ?");
if(!$stmt) die("Error prepare select: ".$conn->error);

$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$datos = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$datos) {
    echo json_encode(['ok'=>false,'error'=>'No se encontraron datos del usuario']);
    exit();
}

// crea cliente con datos actuales más los cambios
$cliente = new Cliente($idUsuario, $email, $contrasena, $nombre, $apellido, $datos['Imagen']);

// actualizar email
$cliente->setEmail($email);

// actualizar contraseña
$hash = password_hash($contrasena, PASSWORD_DEFAULT);
$stmt2 = $conn->prepare("UPDATE usuario SET Contraseña = ? WHERE IdUsuario = ?");
if(!$stmt2) die("Error prepare update contraseña: ".$conn->error);

$stmt2->bind_param("si", $hash, $idUsuario);
if(!$stmt2->execute()) die("Error update contraseña: ".$stmt2->error);
$stmt2->close();

// actualizar cliente con sus datos-nombre, apellido, email, imagen 
if ($cliente->actualizarCliente($conn)) {
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'error'=>'No se pudo actualizar cliente']);
}
