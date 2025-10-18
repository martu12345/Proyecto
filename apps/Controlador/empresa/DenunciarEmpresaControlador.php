<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Denuncia.php');

header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            'success' => false,
            'message' => 'Método no permitido'
        ]);
        exit;
    }

    $idEmpresa = $_POST['idEmpresa'] ?? null;   
    $idCliente = $_POST['idCliente'] ?? null;   
    $detalle   = $_POST['detalle'] ?? null;
    $asunto    = $_POST['asunto'] ?? 'DenunciarEmpresa'; 

    // Validar 
    if (empty($idEmpresa) || empty($idCliente)) {
        echo json_encode([
            'success' => false,
            'message' => 'Faltan datos obligatorios'
        ]);
        exit;
    }

    $denuncia = new Denuncia($idCliente, $idEmpresa, $asunto, $detalle);
    $resultado = $denuncia->guardar();

    echo json_encode([
        'success' => $resultado,
        'message' => $resultado ? 'Denuncia enviada correctamente' : 'Error al guardar la denuncia'
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error interno: ' . $e->getMessage()
    ]);
}