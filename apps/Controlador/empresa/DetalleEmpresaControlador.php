<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Empresa.php');

$idEmpresa = $_GET['idEmpresa'] ?? null;
$idServicio = $_GET['idServicio'] ?? null;
$empresa = null;
$mensajeError = null;

if ($idEmpresa === null) {
    $mensajeError = "No se recibió el Id de la empresa.";
} else {
    $id = intval($idEmpresa);
    if ($id <= 0) {
        $mensajeError = "Id de empresa inválido.";
    } else {
        $empresa = Empresa::obtenerPorId($conn, $id);
        if (!$empresa) {
            $mensajeError = "No se encontró la empresa con Id {$id}.";
        }
    }
}

// Cargar vista
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/empresa/Detalle_Empresa.php');
exit;
