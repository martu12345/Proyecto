<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/Cliente.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/Conexion.php');

header('Content-Type: application/json; charset=utf-8');

// Datos del formulario 
$email      = $_POST['email'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';
$telefono   = $_POST['telefono'] ?? '';
$nombre     = $_POST['nombre'] ?? '';
$apellido   = $_POST['apellido'] ?? '';

// Validaciones
$errores = [];

if (empty($email)) {
    $errores[] = "El email no puede estar vacío.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "Email inválido.";
} elseif (Cliente::existeEmail($conn, $email)) {
    $errores[] = "El email ya está registrado.";
}

if (empty($contrasena)) {
    $errores[] = "La contraseña no puede estar vacía.";
} elseif (strlen($contrasena) < 8) {
    $errores[] = "La contraseña debe tener al menos 8 caracteres.";
}

if (empty($nombre)) {
    $errores[] = "El nombre no puede estar vacío.";
}

if (empty($apellido)) {
    $errores[] = "El apellido no puede estar vacío.";
}

if (!empty($errores)) {
    echo json_encode(['success' => false, 'errors' => $errores]);
    exit;
}

// Crear cliente
$unCliente = new Cliente(null, $email, $contrasena, $nombre, $apellido);

if ($unCliente->guardarCliente($conn, $telefono)) {
    header("Location: /Proyecto/apps/vistas/autenticacion/login.php");
} else {
    echo "Error al guardar el Cliete.";
}
