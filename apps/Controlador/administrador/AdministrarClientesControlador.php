<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Cliente.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    // --- ELIMINAR CLIENTE ---
    if ($accion === 'eliminar') {
        $idCliente = $_POST['idCliente'] ?? null;

        if (!$idCliente) {
            echo json_encode(['success' => false, 'error' => 'No se recibió el ID del cliente']);
            exit;
        }

        try {
            // Llamamos al método del modelo que elimina el cliente
            $resultado = Cliente::eliminarPorId($conn, $idCliente);

            if ($resultado) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'No se pudo eliminar el cliente']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Acción no válida']);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Método no permitido']);
