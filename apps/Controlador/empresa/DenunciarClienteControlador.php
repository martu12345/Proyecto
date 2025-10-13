<?php
// DenunciarClienteControlador.php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Denuncia.php');

header('Content-Type: application/json; charset=utf-8');

try {
    // Solo permitir POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            'success' => false,
            'message' => 'Método no permitido'
        ]);
        exit;
    }

    // Obtener datos del formulario
    $idCliente = $_POST['idCliente'] ?? null;
    $idEmpresa = $_POST['idEmpresa'] ?? null;
    $detalle   = $_POST['detalle'] ?? null;
    $asunto    = $_POST['asunto'] ?? 'DenunciarCliente'; // asunto por defecto

    // Validar datos obligatorios
    if (empty($idCliente) || empty($idEmpresa)) {
        echo json_encode([
            'success' => false,
            'message' => 'Faltan datos obligatorios'
        ]);
        exit;
    }

    // Crear y guardar denuncia
    $denuncia = new Denuncia($idCliente, $idEmpresa, $asunto, $detalle);
    $resultado = $denuncia->guardar();

    // Responder JSON
    echo json_encode([
        'success' => $resultado,
        'message' => $resultado ? 'Denuncia enviada correctamente' : 'Error al guardar la denuncia'
    ]);

} catch (Throwable $e) {
    // Manejo de errores internos
    echo json_encode([
        'success' => false,
        'message' => 'Error interno en el servidor'
    ]);
}
