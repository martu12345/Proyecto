<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/cliente.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');


$email    = $_POST['email'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellido'] ?? '';

echo "Email: $email <br>";
echo "Contraseña: $contrasena <br>";
echo "Teléfono: $telefono <br>";
echo "Nombre: $nombre <br>";
echo "Apellido: $apellido <br>";

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
$unCliente = new Cliente(null, $email, $contrasena, $nombre, $apellido);

if ($unCliente->guardarCliente($conn, $telefono)) {
    echo "Cliente y teléfono guardados correctamente!";
} else {
    echo "Error al guardar el cliente.";
}
?> donde=?