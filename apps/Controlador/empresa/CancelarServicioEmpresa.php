<?php
session_start();
header('Content-Type: application/json');

require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Contrata.php');

$idUsuario = $_SESSION['idUsuario'] ?? null;
if (!$idUsuario) {
    echo json_encode(['error' => 'Empresa no logueada']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCita = $_POST['idCita'] ?? null;
    if (!$idCita) {
        echo json_encode(['error' => 'ID de cita faltante']);
        exit;
    }

    $contrata = Contrata::obtenerPorId($conn, $idCita);

    if (!$contrata) {
        echo json_encode(['error' => 'Cita no encontrada']);
        exit;
    }

    // Solo permitir cancelar si está "En proceso"
    if ($contrata->getEstado() !== 'En proceso') {
        echo json_encode(['error' => 'Solo se pueden cancelar servicios en proceso']);
        exit;
    }

    $exito = $contrata->actualizarEstado($conn, 'Cancelado');
    if ($exito) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'No se pudo cancelar']);
    }
}
