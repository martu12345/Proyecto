<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/usuario.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');

$email    = $_POST['email'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';
$telefono = $_POST['telefono'] ?? '';

   
// validaciones 
// email no vacio y que sea valido
if (empty($email)) {
    die("El email no puede estar vacío");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email inválido");
}

// ontaseña no este vacia y que tenga al menos 8 caracteres 
if (empty($contrasena)) {
   die("La contraseña no puede estar vacía");
}
if (strlen($contrasena) < 8) {
    die("La contraseña debe tener al menos 8 caracteres");
}
$unUsuario = new Usuario($idUsuario, $email, $contrasena, $telefono);
