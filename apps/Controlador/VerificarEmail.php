<?php
header('Content-Type: application/json');
require_once('../Modelos/Usuario.php'); // ajustar según tu ruta
require_once('../Modelos/conexion.php'); // ajustar según tu ruta

$email = $_GET['email'] ?? '';
$existe = false;

if($email != ''){
    // $conn = tu conexión
    $existe = Usuario::existeEmail($conn, $email);
}

echo json_encode(['existe' => $existe]);

?>