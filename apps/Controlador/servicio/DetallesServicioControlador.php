<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Contrata.php');

// Iniciar variables
$idServicio = null;
$servicio = null;
$empresa = null;
$resenas = [];
$mensajeError = null;

// Obtener idServicio desde GET
$idRaw = $_GET['idServicio'] ?? null;

if (!$idRaw) {
    $mensajeError = "No se recibió el Id del servicio.";
} else {
    $idServicio = intval($idRaw);

    if ($idServicio <= 0) {
        $mensajeError = "Id de servicio inválido.";
    } else {
        // Obtener servicio
        $servicio = Servicio::obtenerPorId($conn, $idServicio);

        if (!$servicio) {
            $mensajeError = "No se encontró el servicio con Id {$idServicio}.";
        } else {
            // Obtener idEmpresa asociado al servicio
            $idEmpresa = Brinda::obtenerIdEmpresaPorServicio($conn, $idServicio);

            if (!$idEmpresa) {
                // Si Brinda no devuelve nada, intentar obtener directamente del servicio (si existe el método)
                if (method_exists($servicio, 'getIdEmpresa')) {
                    $idEmpresa = $servicio->getIdEmpresa();
                }
            }

            // Cargar empresa si existe idEmpresa
            $empresa = $idEmpresa ? Empresa::obtenerPorId($conn, $idEmpresa) : null;

            // Obtener reseñas del servicio
            $resenas = Contrata::obtenerResenasPorServicio($conn, $idServicio);
        }
    }
}

// Cargar la vista
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/servicio/DetallesServicio.php');
exit;
