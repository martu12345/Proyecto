<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/Cliente.php');

header('Content-Type: application/json');

if (!isset($_SESSION['idUsuario'])) {
    echo json_encode(['ok' => false, 'error' => 'No logueado']);
    exit();
}

$idUsuario = (int)$_SESSION['idUsuario'];

$cliente = Cliente::obtenerPorId($conn, $idUsuario);
if (!$cliente) {
    echo json_encode(['ok' => false, 'error' => 'No se encontraron datos del usuario']);
    exit();
}

// Datos del formulario (si no hay, quedan los que ya estaban)
$nombre = $_POST['nombre'] ?? $cliente->getNombre();
$apellido = $_POST['apellido'] ?? $cliente->getApellido();
$email = $_POST['email'] ?? $cliente->getEmail();
$contrasena = $_POST['contrasena'] ?? null;

if (empty($email)) {
    echo json_encode(['ok' => false, 'error' => 'Email es obligatorio']);
    exit();
}

// Actualizar datos en cliente
$cliente->setNombre($nombre);
$cliente->setApellido($apellido);
$cliente->setEmail($email);

// Actualizar en base
if (!$cliente->actualizarCliente($conn)) {
    echo json_encode(['ok' => false, 'error' => 'No se pudo actualizar cliente']);
    exit();
}

// Actualizar la contraseña solo si se pasó una nueva
if ($contrasena) {
    if (!$cliente->actualizarContrasena($conn, $contrasena)) {
        echo json_encode(['ok' => false, 'error' => 'No se pudo actualizar la contraseña']);
        exit();
    }
}

echo json_encode([
    'ok' => true,
    'nombre' => $cliente->getNombre(),
    'apellido' => $cliente->getApellido(),
    'email' => $cliente->getEmail()
]);
