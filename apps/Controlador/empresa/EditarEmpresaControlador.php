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

// Actualizar contraseña si se puso
if (!empty($contrasena)) {
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt2 = $conn->prepare("UPDATE usuario SET Email = ?, Contraseña = ? WHERE IdUsuario = ?");
    $stmt2->bind_param("ssi", $email, $hash, $idUsuario);
    $stmt2->execute();
    $stmt2->close();
}

// Traer la empresa
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
