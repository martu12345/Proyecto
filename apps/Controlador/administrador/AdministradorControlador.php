<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/administrador.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');


$email    = $_POST['email'] ?? '';
$telefono = $_POST['telefono'] ?? '';

$contrasena = $_POST['contrasena'] ?? '';


// validaciones  - hay que agregarlas
if (empty($email)) {
    //die("El email no puede estar vacío");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
   // die("Email inválido");
}

if (empty($contrasena)) {
   // die("La contraseña no puede estar vacía");
}
if (strlen($contrasena) < 8) {
   // die("La contraseña debe tener al menos 8 caracteres");
}

// crear cliente
$unAdministrador = new Administrador(null, $email, $contrasena);

if ($unAdministrador->guardarAdministrador($conn, $telefono)) {
    echo "admin creado!";
} 
else {
    echo "Error al guardar el admin.";
}


?> 