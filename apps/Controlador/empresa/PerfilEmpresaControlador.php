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

$empresa = Empresa::obtenerPorId($conn, $idUsuario);

if (!$empresa) {
    // Manejar error si no existe
    die("No se encontraron datos de la empresa para este usuario.");
}


$datos = [
    "Email" => $empresa->getEmail(), // heredado de Usuario
    "NombreEmpresa" => $empresa->getNombreEmpresa(),
    "Calle" => $empresa->getCalle(),
    "Numero" => $empresa->getNumero(),
    "Imagen" => $empresa->getImagen()
];
