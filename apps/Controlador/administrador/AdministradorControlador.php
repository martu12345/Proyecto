<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/administrador.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');

header('Content-Type: application/json');

session_start();
$idPropietario = $_SESSION['idUsuario'] ?? null;

$email      = trim($_POST['email'] ?? '');
$telefono   = trim($_POST['telefono'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';

if (empty($email)) {
    echo json_encode(['error' => 'El email no puede estar vacío']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['error' => 'Email inválido']);
    exit;
}
if (empty($contrasena)) {
    echo json_encode(['error' => 'La contraseña no puede estar vacía']);
    exit;
}
if (strlen($contrasena) < 8) {
    echo json_encode(['error' => 'La contraseña debe tener al menos 8 caracteres']);
    exit;
}
if (!$idPropietario) {
    echo json_encode(['error' => 'No se pudo identificar al propietario.']);
    exit;
}

if (Usuario::existeEmail($conn, $email)) {
    echo json_encode(['error' => 'El email ya está registrado']);
    exit;
}

$admin = new Administrador(null, $email, $contrasena);
if ($admin->guardarAdministrador($conn, $idPropietario, $telefono)) {
    echo json_encode(['success' => 'Administrador creado correctamente!']);
} else {
    echo json_encode(['error' => 'Error al guardar el administrador.']);
}

