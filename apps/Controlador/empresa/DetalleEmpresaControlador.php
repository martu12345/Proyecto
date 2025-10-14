<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Empresa.php');

// Inicializar variables
$empresa = null;
$mensajeError = null;

// Obtener variables desde GET
$idEmpresa = $_GET['idEmpresa'] ?? null;
$idServicio = $_GET['idServicio'] ?? null; // necesario para el botón volver

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

// Convertir idServicio a entero si existe
$idServicio = $idServicio ? intval($idServicio) : null;

// Cargar la vista
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/empresa/Detalle_Empresa.php');
exit;
