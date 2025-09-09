<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Usuario.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Empresa.php');

if (!isset($_SESSION['idUsuario'])) {
    header("Location: /Proyecto/apps/vistas/autenticacion/login.php");
    exit();
}

$idUsuario = $_SESSION['idUsuario'];

$stmt = $conn->prepare("
    SELECT u.Email, e.NombreEmpresa, e.Calle, e.Numero
    FROM usuario u
    JOIN empresa e ON u.IdUsuario = e.IdUsuario
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
