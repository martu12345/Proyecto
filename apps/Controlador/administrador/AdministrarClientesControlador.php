<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Cliente.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Telefono.php');

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

    // --- EDITAR CLIENTE ---
    if ($accion === 'editar') {
        $idCliente = $_POST['idCliente'] ?? null;
        $nombre    = $_POST['nombre'] ?? '';
        $apellido  = $_POST['apellido'] ?? '';
        $email     = $_POST['email'] ?? '';
        $telefono  = $_POST['telefono'] ?? '';

        if (!$idCliente || !$nombre || !$apellido || !$email) {
            echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios']);
            exit;
        }

        try {
            // Creamos el objeto Cliente
            $cliente = new Cliente($idCliente, $email, '', $nombre, $apellido);

            // Actualizamos cliente en la base
            $cliente->actualizarCliente($conn);

            // Actualizamos teléfono (elimina el anterior y agrega el nuevo)
            if ($telefono) {
                Telefono::actualizarTelefono($conn, $idCliente, $telefono);
            }

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Acción no válida']);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Método no permitido']);
