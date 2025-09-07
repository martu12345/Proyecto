<?php
require_once('../Modelos/modeloCliente.php');

$email    = $_POST['email'] ?? '';
$contrasena = $_POST['contrasena'] ?? ''; // <-- cambio aquí
$telefono = $_POST['telefono'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellido'] ?? '';

// validaciones 
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

// crear cliente
$unCliente = new Cliente(null, $email, $contrasena, $telefono, $nombre, $apellido);

?>
