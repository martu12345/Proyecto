<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Contrata.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');

$idEmpresa = $_SESSION['idUsuario'] ?? null;

if(!$idEmpresa){
    echo json_encode(['error' => 'No se recibió el ID de la empresa']);
    exit;
}

// POST: aceptar/rechazar cita
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $idCita = $_POST['idContrata'] ?? null;
    $accion = $_POST['accion'] ?? null;

    if(!$idCita || !$accion){
        echo json_encode(['error' => 'Datos incompletos']);
        exit;
    }

    $cita = Contrata::obtenerPorId($conn, $idCita);
    if(!$cita){
        echo json_encode(['error' => 'Cita no encontrada']);
        exit;
    }

    $nuevoEstado = ($accion === 'aceptar') ? 'En proceso' : 'Rechazado';

    // Intentar actualizar y capturar errores
    try {
        $exito = $cita->actualizarEstado($conn, $nuevoEstado);
    } catch(Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }

    if($exito){
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'No se pudo actualizar']);
    }
    exit;
}

// GET: devolver agendados
$agendados = Contrata::obtenerAgendadosPorEmpresa($conn, $idEmpresa);

// Asegurarse de siempre devolver un array
if(!$agendados || !is_array($agendados)){
    $agendados = [];
}

echo json_encode($agendados);
exit;
