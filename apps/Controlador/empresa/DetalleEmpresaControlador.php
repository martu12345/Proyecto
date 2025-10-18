<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Empresa.php');

$empresa = null;
$mensajeError = null;

$idEmpresa = $_GET['idEmpresa'] ?? null;
$idServicio = $_GET['idServicio'] ?? null; 

// Validar idEmpresa
if ($idEmpresa === null) {
    $mensajeError = "No se recibió el Id de la empresa.";
} else {
    $idEmpresaInt = intval($idEmpresa);
    if ($idEmpresaInt <= 0) {
        $mensajeError = "Id de empresa inválido.";
    } else {
        // Obtener empresa desde la base de datos
        $empresa = Empresa::obtenerPorId($conn, $idEmpresaInt);
        if (!$empresa) {
            $mensajeError = "No se encontró la empresa con Id {$idEmpresaInt}.";
        }
    }
}

$idServicio = $idServicio ? intval($idServicio) : null;

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/empresa/Detalle_Empresa.php');
exit;
