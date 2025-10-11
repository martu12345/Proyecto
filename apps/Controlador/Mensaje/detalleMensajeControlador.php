<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Comunica.php');
session_start();
$idMensaje = $_GET['id'] ?? null;

if (!$idMensaje) {
    echo "Mensaje no encontrado.";
    exit;
}

$mensaje = Comunica::obtenerMensajePorId($conn, $idMensaje);

if (!$mensaje) {
    echo "No se encontró el mensaje.";
    exit;
}
$puedeResponder = ($mensaje['IdUsuarioEmisor'] != $_SESSION['idUsuario']);

include($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Vistas/paginas/empresa/detalleMensaje.php');
