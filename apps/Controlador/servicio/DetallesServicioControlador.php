<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Empresa.php');

// Aceptar POST (Ver más) o GET (Volver desde empresa)
$idRaw = $_POST['IdServicio'] ?? $_GET['id'] ?? null;

$servicio = null;
$empresa = null;
$mensajeError = null;

if ($idRaw === null) {
    $mensajeError = "No se recibió el Id del servicio.";
} else {
    $id = intval($idRaw);
    if ($id <= 0) {
        $mensajeError = "Id de servicio inválido.";
    } else {
        $servicio = Servicio::obtenerPorId($conn, $id);
        if (!$servicio) {
            $mensajeError = "No se encontró el servicio con Id {$id}.";
        } else {
            $idEmpresa = Brinda::obtenerIdEmpresaPorServicio($conn, $id);
            if ($idEmpresa) {
                $empresa = Empresa::obtenerPorId($conn, $idEmpresa);
            }
        }
    }
}

// Cargar vista
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/servicio/DetallesServicio.php');
exit;
