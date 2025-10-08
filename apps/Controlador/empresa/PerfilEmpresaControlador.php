<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Usuario.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Contrata.php'); // <- necesitamos Contrata
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Brinda.php');   // <- y Brinda

if (!isset($_SESSION['idUsuario'])) {
    header("Location: /Proyecto/apps/vistas/autenticacion/login.php");
    exit();
}

$idUsuario = $_SESSION['idUsuario'];

$empresa = Empresa::obtenerPorId($conn, $idUsuario);

if (!$empresa) {
    die("No se encontraron datos de la empresa para este usuario.");
}

// ==========================
// MARCAR CONTRATOS COMO LEÍDOS
// ==========================
$seccion = $_GET['seccion'] ?? 'perfil';
if ($seccion === 'agendados') {
    Contrata::marcarComoLeido($conn, $idUsuario); // <- Aquí se actualizan los contratos
}

// ==========================
// PREPARAR DATOS PARA LA VISTA
// ==========================
$datos = [
    "Email" => $empresa->getEmail(),
    "NombreEmpresa" => $empresa->getNombreEmpresa(),
    "Calle" => $empresa->getCalle(),
    "Numero" => $empresa->getNumero(),
    "Imagen" => $empresa->getImagen()
];
