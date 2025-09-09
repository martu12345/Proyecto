<?php
// perfilclientecontrolador.php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Usuario.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Cliente.php');

// Verificar que el usuario esté logueado
if (!isset($_SESSION['idUsuario'])) {
    header("Location: /Proyecto/apps/vistas/autenticacion/login.php");
    exit();
}

// Traer datos del usuario
$idUsuario = $_SESSION['idUsuario'];

$stmt = $conn->prepare("SELECT u.Email, c.Nombre, c.Apellido 
                        FROM usuario u 
                        JOIN cliente c ON u.IdUsuario = c.IdUsuario 
                        WHERE u.IdUsuario = ?");
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$datos = $result->fetch_assoc();
