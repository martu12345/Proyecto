<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Contrata.php');

$idServicio = null;
$servicio = null;
$empresa = null;
$resenas = [];
$mensajeError = null;

$logueado = isset($_SESSION['idUsuario']);
$rol = $logueado ? $_SESSION['rol'] : null;
$usuario_id = $_SESSION['idUsuario'] ?? null;

// Obtener idServicio desde GET
$idRaw = $_GET['idServicio'] ?? null;

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
            // Empresa que brinda el servicio
            $idEmpresa = Brinda::obtenerIdEmpresaPorServicio($conn, $idServicio);
            $empresa = $idEmpresa ? Empresa::obtenerPorId($conn, $idEmpresa) : null;

            // Reseñas del servicio
            $resenas = Contrata::obtenerResenasPorServicio($conn, $idServicio);

            $imagen = $servicio->getImagen();
            $rutaImagenFinal = (!empty($imagen) && file_exists($_SERVER['DOCUMENT_ROOT'] . "/Proyecto/public/imagen/servicios/$imagen"))
                                ? "/Proyecto/public/imagen/servicios/$imagen"
                                : "/Proyecto/public/imagen/servicios/default.png"; 

            $precioFormateado = number_format($servicio->getPrecio(), 0, '', '.');
        }
    }
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/servicio/DetallesServicio.php');
exit;
