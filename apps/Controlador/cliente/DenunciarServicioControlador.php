<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/Conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/Denuncia.php');

header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }

    if (!isset($_SESSION['idUsuario'])) {
        echo json_encode(['success' => false, 'message' => 'No logueado']);
        exit;
    }
    $idCliente = (int)$_SESSION['idUsuario'];

    // Datos del formulario
    $idEmpresa = $_POST['idEmpresa'] ?? null;
    $detalle   = $_POST['detalle'] ?? null;
    $motivo    = $_POST['motivo'] ?? 'DenunciarServicio';

    // Verificar los datos obligatorios
    if (empty($idEmpresa)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
        exit;
    }

    // Crear y guardar denuncia
    $denuncia = new Denuncia($idCliente, $idEmpresa, $motivo, $detalle);
    $resultado = $denuncia->guardar($conn);

    echo json_encode([
        'success' => $resultado,
        'message' => $resultado ? 'Denuncia enviada correctamente' : 'Error al guardar la denuncia'
    ]);
} catch (Throwable $e) {
    error_log("Error en DenunciarServicioControlador: " . $e->getMessage());

    echo json_encode([
        'success' => false,
        'message' => 'Error interno en el servidor'
    ]);
}

exit;
