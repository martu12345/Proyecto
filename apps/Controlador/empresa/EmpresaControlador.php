<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');


$email    = $_POST['emailEmpresa'] ?? '';
$contrasena = $_POST['contrasenaEmpresa'] ?? '';
$nombreEmpresa = $_POST['nombreEmpresa'] ?? '';
$telefono = $_POST['telefonoEmpresa'] ?? '';
$calle = $_POST['calle'] ?? '';
$numero = $_POST['numero'] ?? '';


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

// crear empresa
$unaEmpresa = new Empresa(null, $email, $contrasena, $telefono, $nombreEmpresa, $calle, $numero);

if ($unaEmpresa->guardarEmpresa($conn, $telefono)) {
    header("Location: /Proyecto/apps/vistas/autenticacion/login.php");
} else {
    echo "Error al guardar el Empresa.";
}
