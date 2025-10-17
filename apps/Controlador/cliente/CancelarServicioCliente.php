<?php
// Cancelar servicio de un cliente (pendiente o en proceso)
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/Conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Controlador/Cliente/ServiciosClienteControlador.php');

$idUsuario = $_SESSION['idUsuario'] ?? null;
if (!$idUsuario) {
    echo json_encode(['success' => false, 'error' => 'Usuario no logueado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

// Obtener ID de la cita
$idCita = $_POST['idCita'] ?? null;
if (!$idCita) {
    echo json_encode(['success' => false, 'error' => 'ID de cita faltante']);
    exit;
}

// Cancelar servicio
$controlador = new ServiciosClienteControlador($conn, $idUsuario);
$exito = $controlador->cancelarServicio($idCita);

if ($exito) {
    echo json_encode(['success' => true, 'message' => 'Servicio cancelado correctamente']);
} else {
    echo json_encode(['success' => false, 'error' => 'No se pudo cancelar el servicio']);
}
