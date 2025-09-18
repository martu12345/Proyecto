<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/servicio.php');

$idRaw = null;
if (!empty($_POST['IdServicio'])) {
    $idRaw = $_POST['IdServicio'];
}

$servicio = null;
$errorMessage = null;

if ($idRaw === null) {
    $errorMessage = "No se recibió el Id del servicio. Asegurate de llamar a este controlador desde el formulario.";
} else {
    $id = intval($idRaw);
    if ($id <= 0) {
        $errorMessage = "Id de servicio inválido.";
    } else {
        
        $servicio = Servicio::obtenerPorId($conn, $id);
        if (!$servicio) {
            $errorMessage = "No se encontró el servicio con Id {$id}.";
        }
    }
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/servicio/DetallesServicio.php');
exit;
