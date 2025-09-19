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

$nombreEmpresa = $_POST['nombreEmpresa'] ?? null;
$calle = $_POST['calle'] ?? null;
$numero = $_POST['numero'] ?? null;
$email = $_POST['email'] ?? null;
$contrasena = $_POST['contrasena'] ?? null;

// --- VALIDACIÓN BÁSICA ---
if (!$email) {
    echo json_encode(['ok' => false, 'error' => 'El email es obligatorio']);
    exit();
}

// --- Actualizar usuario ---
if (!empty($contrasena)) {
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE usuario SET Email = ?, Contraseña = ? WHERE IdUsuario = ?");
    if (!$stmt) die("Error prepare update usuario: ".$conn->error);
    $stmt->bind_param("ssi", $email, $hash, $idUsuario);
} else {
    $stmt = $conn->prepare("UPDATE usuario SET Email = ? WHERE IdUsuario = ?");
    if (!$stmt) die("Error prepare update email: ".$conn->error);
    $stmt->bind_param("si", $email, $idUsuario);
}
$stmt->execute();
$stmt->close();

// --- Traer datos actuales de la empresa ---
$stmt = $conn->prepare("SELECT NombreEmpresa, Calle, Numero, Imagen FROM empresa WHERE IdUsuario = ?");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

// --- Si no se envía algún dato, usamos los actuales ---
$nombreEmpresa = $nombreEmpresa ?: $data['NombreEmpresa'];
$calle = $calle ?: $data['Calle'];
$numero = $numero ?: $data['Numero'];

// --- Actualizar empresa ---
$empresa = new Empresa($idUsuario, $email, '', null, $nombreEmpresa, $calle, $numero, $data['Imagen']);

if ($empresa->actualizarEmpresa($conn)) {
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'error' => 'No se pudo actualizar empresa']);
}
