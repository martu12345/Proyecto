<?php
header('Content-Type: application/json');
require_once('../Modelos/Usuario.php'); 
require_once('../Modelos/conexion.php'); 

$email = $_GET['email'] ?? '';
$existe = false;

if($email != ''){
    $existe = Usuario::existeEmail($conn, $email);
}

echo json_encode(['existe' => $existe]);

?>
