<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Usuario.php');

header('Content-Type: application/json');

if (!isset($_SESSION['idUsuario'])) {
    echo json_encode(['ok' => false, 'error' => 'No logueado']);
    exit();
}

$idUsuario = $_SESSION['idUsuario'];

$nombreEmpresa = $_POST['nombreEmpresa'] ?? '';
$calle = $_POST['calle'] ?? '';
$numero = $_POST['numero'] ?? '';
$email = $_POST['email'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

// Actualizar contraseña y email si se proporcionan
if (!empty($contrasena)) {
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt2 = $conn->prepare("UPDATE usuario SET Email = ?, Contraseña = ? WHERE IdUsuario = ?");
    if(!$stmt2) die("Error prepare update usuario: ".$conn->error);
    $stmt2->bind_param("ssi", $email, $hash, $idUsuario);
    $stmt2->execute();
    $stmt2->close();
} else {
    // Solo actualizar email si no hay contraseña
    $stmt2 = $conn->prepare("UPDATE usuario SET Email = ? WHERE IdUsuario = ?");
    if(!$stmt2) die("Error prepare update email: ".$conn->error);
    $stmt2->bind_param("si", $email, $idUsuario);
    $stmt2->execute();
    $stmt2->close();
}

// Traer datos actuales de la empresa
$stmt = $conn->prepare("SELECT NombreEmpresa, Calle, Numero, Imagen FROM empresa WHERE IdUsuario = ?");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

$empresa = new Empresa($idUsuario, $email, '', null, $nombreEmpresa, $calle, $numero, $data['Imagen']);

// Actualizar datos de empresa
if ($empresa->actualizarEmpresa($conn)) {
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'error' => 'No se pudo actualizar empresa']);
}
