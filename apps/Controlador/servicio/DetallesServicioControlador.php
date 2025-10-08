<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Contrata.php');

// Obtener idServicio desde GET
$idRaw = $_GET['idServicio'] ?? null;

$servicio = null;
$empresa = null;
$resenas = [];
$mensajeError = null;

if (!$idRaw) {
    $mensajeError = "No se recibió el Id del servicio.";
} else {
    $idServicio = intval($idRaw);
    if ($idServicio <= 0) {
        $mensajeError = "Id de servicio inválido.";
    } else {
        $servicio = Servicio::obtenerPorId($conn, $idServicio);
        if (!$servicio) {
            $mensajeError = "No se encontró el servicio con Id {$idServicio}.";
        } else {
            $idEmpresa = Brinda::obtenerIdEmpresaPorServicio($conn, $idServicio);
            $empresa = $idEmpresa ? Empresa::obtenerPorId($conn, $idEmpresa) : null;
        }
    }
}

// Cargar la vista
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/servicio/DetallesServicio.php');
exit;
