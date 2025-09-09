<?php
// hace que en el perfil se puedan ver los datos del cliente en la base 
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Usuario.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Cliente.php');

if (!isset($_SESSION['idUsuario'])) {
    header("Location: /Proyecto/apps/vistas/autenticacion/login.php");
    exit();
}

$idUsuario = $_SESSION['idUsuario'];

$stmt = $conn->prepare("
    SELECT u.Email, c.Nombre, c.Apellido 
    FROM usuario u 
    JOIN cliente c ON u.IdUsuario = c.IdUsuario 
    WHERE u.IdUsuario = ?
");
if(!$stmt) {
    die("Error prepare: " . $conn->error);
}

$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$datos = $result->fetch_assoc();

$stmt->close();
