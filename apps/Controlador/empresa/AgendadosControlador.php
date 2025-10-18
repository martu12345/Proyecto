<?php 
session_start();

// Configuración de errores
ini_set('display_errors', 0);


header('Content-Type: application/json');

require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Contrata.php');

if (!$conn) {
    echo json_encode(['error' => 'Error en la conexión a la base de datos']);
    exit;
}

$idEmpresa = $_SESSION['idUsuario'] ?? null;
if (!$idEmpresa) {
    echo json_encode(['error' => 'No se recibió el ID de la empresa']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCita = $_POST['idContrata'] ?? null;
    $accion = $_POST['accion'] ?? null;

    if (!$idCita || !$accion) {
        echo json_encode(['error' => 'Datos incompletos']);
        exit;
    }

    $cita = Contrata::obtenerPorId($conn, $idCita);
    if (!$cita) {
        echo json_encode(['error' => 'Cita no encontrada']);
        exit;
    }

    $nuevoEstado = ($accion === 'aceptar') ? 'En proceso' : 'Cancelado';

    try {
        $exito = $cita->actualizarEstado($conn, $nuevoEstado);
        if ($exito) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'No se pudo actualizar']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

    exit;
}

try {
    $agendados = Contrata::obtenerAgendadosPorEmpresa($conn, $idEmpresa);
    if (!$agendados || !is_array($agendados)) $agendados = [];

    $agendados = array_map(function($item){
        $item['Calificacion'] = $item['Calificacion'] !== null ? (int)$item['Calificacion'] : 'No hay';
        $item['Resena'] = $item['Resena'] ?? 'No hay';
        return $item;
    }, $agendados);

    echo json_encode($agendados);

} catch(Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
exit;
