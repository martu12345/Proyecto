<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Contrata.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Comunica.php');

if (!isset($_SESSION['idUsuario'])) {
    header("Location: /Proyecto/apps/vistas/autenticacion/login.php");
    exit();
}

$idUsuario = $_SESSION['idUsuario'];
$seccion = $_GET['seccion'] ?? 'perfil';
if ($seccion === 'null') $seccion = 'perfil';

// Datos de la empresa
$empresa = Empresa::obtenerPorId($conn, $idUsuario);
if (!$empresa) die("No se encontraron datos de la empresa.");

$datos = [
    "Email" => $empresa->getEmail(),
    "NombreEmpresa" => $empresa->getNombreEmpresa(),
    "Calle" => $empresa->getCalle(),
    "Numero" => $empresa->getNumero(),
    "Imagen" => $empresa->getImagen()
];

// MARCAR COMO LEÍDOS según sección
if ($seccion === 'mensajes') {
    Comunica::marcarComoLeidoPorEmpresa($conn, $idUsuario);
}
if ($seccion === 'agendados') {
    Contrata::marcarComoLeido($conn, $idUsuario);
}
?>
