<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Empresa.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    // eliminar
    if ($accion === 'eliminar') {
        $idEmpresa = $_POST['idEmpresa'] ?? null;

        if (!$idEmpresa) {
            echo json_encode(['success' => false, 'error' => 'No se recibió el ID de la empresa']);
            exit;
        }

        try {
            $resultado = Empresa::eliminarPorId($conn, $idEmpresa);

            if ($resultado) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'No se pudo eliminar la empresa']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    // editar
    if ($accion === 'editar') {
        $idEmpresa = $_POST['idEmpresa'] ?? null;
        $nombre    = $_POST['nombreEmpresa'] ?? '';
        $email     = $_POST['email'] ?? '';
        $telefono  = $_POST['telefono'] ?? '';

        if (!$idEmpresa || !$nombre || !$email) {
            echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios']);
            exit;
        }

        try {
            // Creamos la instancia con los datos mínimos
            $empresa = new Empresa(
                $idEmpresa,
                $email,
                '',       
                null,     
                $nombre,  
                '',       
                0,        
                null      
            );

            $resultado = $empresa->actualizarDatosEmpresa($conn, $telefono);

            if ($resultado) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'No se pudo actualizar la empresa']);
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
