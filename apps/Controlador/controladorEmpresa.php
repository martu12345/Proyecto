<?php
require_once('../Modelos/modeloEmpresa.php');
require_once('../Modelos/conexion.php');


$email    = $_POST['emailEmpresa'] ?? '';
$contrasena = $_POST['contrasenaEmpresa'] ?? ''; 
$nombreEmpresa = $_POST['nombreEmpresa'] ?? '';
$telefono = $_POST['telefonoEmpresa'] ?? '';
$calle = $_POST['calle'] ?? '';
$numero = $_POST['numero'] ?? '';

echo "Email: $email <br>";
echo "Contraseña: $contrasena <br>";
echo "Teléfono: $telefono <br>";
echo "NombreEmpresa: $nombreEmpresa <br>";
echo "Calle: $calle <br>";
echo "Numero: $numero <br>";

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

if($unaEmpresa->guardarEmpresa($conn, $telefono)) {
    echo "Empresa y teléfono guardados correctamente!";
} else {
    echo "Error al guardar el Empresa.";
}
?>
