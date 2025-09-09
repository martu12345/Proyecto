<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Usuario.php');

header('Content-Type: application/json');

if (!isset($_SESSION['idUsuario'])) {
    echo json_encode(['ok' => false, 'error' => 'No logueado']);
    exit();
}

$idUsuario = (int)$_SESSION['idUsuario'];

$nombreEmpresa = $_POST['nombreEmpresa'] ?? '';
$calle = $_POST['calle'] ?? '';
$numero = $_POST['numero'] ?? '';
$email = $_POST['email'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

if (empty($email) || empty($contrasena)) {
    echo json_encode(['ok'=>false, 'error'=>'Email y contraseña son obligatorios']);
    exit();
}

// actualizar contraseña
$hash = password_hash($contrasena, PASSWORD_DEFAULT);
$stmt2 = $conn->prepare("UPDATE usuario SET Email = ?, Contraseña = ? WHERE IdUsuario = ?");
if(!$stmt2) die("Error prepare update usuario: ".$conn->error);

$stmt2->bind_param("ssi", $email, $hash, $idUsuario);
if(!$stmt2->execute()) die("Error update usuario: ".$stmt2->error);
$stmt2->close();

// actualizar empresa
$stmt3 = $conn->prepare("UPDATE empresa SET NombreEmpresa = ?, Calle = ?, Numero = ? WHERE IdUsuario = ?");
if(!$stmt3) die("Error prepare update empresa: ".$conn->error);

$stmt3->bind_param("ssii", $nombreEmpresa, $calle, $numero, $idUsuario);
if($stmt3->execute()){
    echo json_encode(['ok'=>true]);
}else{
    echo json_encode(['ok'=>false, 'error'=>'No se pudo actualizar empresa']);
}
$stmt3->close();
