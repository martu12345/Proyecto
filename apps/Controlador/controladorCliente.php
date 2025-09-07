<?php
require_once('../Modelos/modeloCliente.php');
$email    = $_POST['email'] ?? '';
$contraseña = $_POST['contraseña'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellido'] ?? '';

// validaciones 
// email no vacio y que sea valido
if (empty($email)) {
    die("El email no puede estar vacío");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email inválido");
}

// ontaseña no este vacia y que tenga al menos 8 caracteres 
if (empty($contraseña)) {
    die("La contraseña no puede estar vacía");
}
if (strlen($contraseña) < 8) {
    die("La contraseña debe tener al menos 8 caracteres");
}
$unCliente = new Cliente(null, $email, $contraseña, $telefono, $nombre, $apellido);


?>