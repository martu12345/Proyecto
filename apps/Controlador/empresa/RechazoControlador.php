<?php
session_start();
header('Content-Type: application/json');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Comunica.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Contrata.php');

$idEmpresa = $_SESSION['idUsuario'] ?? null;

if (!$idEmpresa) {
    echo json_encode(['error' => 'No se encontró la sesión de empresa']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idContrata = $_POST['idContrata'] ?? null;
    $contenido = trim($_POST['contenido'] ?? '');

    if (!$idContrata || !$contenido) {
        echo json_encode(['error' => 'Debe completar el motivo del rechazo']);
        exit;
    }

    // Obtener la cita
    $cita = Contrata::obtenerPorId($conn, $idContrata);
    if (!$cita) {
        echo json_encode(['error' => 'Cita no encontrada']);
        exit;
    }

    // Guardar mensaje en comunica 
    $fecha = date('Y-m-d H:i:s');
    $mensaje = new Comunica(
        $cita->getIdUsuario(), 
        $idEmpresa,
        null,
        'Rechazo de reserva',  
        $contenido,
        $fecha,
        $idEmpresa              
    );

    $exitoMensaje = $mensaje->enviar($conn);

    if (!$exitoMensaje) {
        echo json_encode(['error' => 'No se pudo enviar el mensaje']);
        exit;
    }

    // Actualizar estado de la cita a Cancelado
    $exitoEstado = $cita->actualizarEstado($conn, 'Cancelado'); 

    if ($exitoEstado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'No se pudo actualizar el estado de la cita']);
    }
    exit;
}

echo json_encode(['error' => 'Método no permitido']);
exit;
