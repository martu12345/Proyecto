<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Administra.php');


// Obtener rol e ID de usuario
$rol = $_SESSION['rol'] ?? '';
$idUsuario = $_SESSION['idUsuario'] ?? null;

if (!$rol) {
    echo json_encode(['success' => false, 'error' => 'No logueado']);
    exit;
}

$idServicio = $_POST['idServicio'] ?? null;
if (!$idServicio) {
    echo json_encode(['success' => false, 'error' => 'ID de servicio no recibido']);
    exit;
}

// Obtener servicio
$servicio = Servicio::obtenerPorId($conn, $idServicio);
if (!$servicio) {
    echo json_encode(['success' => false, 'error' => 'Servicio no encontrado']);
    exit;
}

// Validar permisos
if ($rol === 'empresa') {
    // Solo puede eliminar si es de su empresa
    require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Brinda.php');
    $misServicios = Brinda::obtenerServiciosPorEmpresa($conn, $idUsuario);
    $misIds = array_map(fn($s) => $s->getIdServicio(), $misServicios);

    if (!in_array($idServicio, $misIds)) {
        echo json_encode(['success' => false, 'error' => 'No podés eliminar este servicio']);
        exit;
    }
} elseif ($rol !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
    exit;
}

// Registrar en administra si es admin
if ($rol === 'admin') {
    $administra = new Administra($idServicio, $idUsuario, date('Y-m-d H:i:s'));
    if (!$administra->guardar($conn)) {
        echo json_encode(['success' => false, 'error' => 'No se pudo registrar en administra']);
        exit;
    }
}

// Intentar eliminar
if ($servicio->eliminar($conn)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al eliminar el servicio']);
}
