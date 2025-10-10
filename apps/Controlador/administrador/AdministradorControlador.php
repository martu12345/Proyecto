<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/administrador.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');

session_start(); // asegurarse de que la sesión está iniciada
$idPropietario = $_SESSION['idUsuario'] ?? null; // el propietario que crea el administrador

$email    = $_POST['email'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

// Validaciones
if (empty($email)) {
    die("El email no puede estar vacío");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email inválido");
}
if (empty($contrasena)) {
    die("La contraseña no puede estar vacía");
}
if (strlen($contrasena) < 8) {
    die("La contraseña debe tener al menos 8 caracteres");
}
if (!$idPropietario) {
    die("No se pudo identificar al propietario.");
}

// Crear administrador usando el propietario de la sesión
$unAdministrador = new Administrador($idPropietario, $email, $contrasena);

if ($unAdministrador->guardarAdministrador($conn, $telefono)) {
    echo "Administrador creado correctamente!";
} else {
    echo "Error al guardar el administrador.";
}
?>
