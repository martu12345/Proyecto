<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Comunica.php');

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

include($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Vistas/paginas/empresa/detalleMensaje.php');
?>