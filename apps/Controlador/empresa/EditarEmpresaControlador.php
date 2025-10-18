<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Usuario.php');

header('Content-Type: application/json');

if (!isset($_SESSION['idUsuario'])) {
    echo json_encode(['ok' => false, 'error' => 'No logueado']);
    exit();
}

$idUsuario = $_SESSION['idUsuario'];

$nombreEmpresa = $_POST['nombreEmpresa'] ?? null;
$calle = $_POST['calle'] ?? null;
$numero = $_POST['numero'] ?? null;
$email = $_POST['email'] ?? null;
$contrasena = $_POST['contrasena'] ?? null;

// --- VALIDACIÓN BÁSICA ---
if (!$email) {
    echo json_encode(['ok' => false, 'error' => 'El email es obligatorio']);
    exit();
}

if (!preg_match("/^[a-zA-Z0-9._%+-]+@(gmail\.com|hotmail\.com)$/i", $email)) {
    echo json_encode(['ok' => false, 'error' => 'El email debe terminar en @gmail.com o @hotmail.com']);
    exit();
}

try {
    // --- Obtener empresa usando método de la clase ---
    $empresa = Empresa::obtenerPorId($conn, $idUsuario);
    if (!$empresa) throw new Exception("Empresa no encontrada");

    // --- Actualizar valores en la instancia ---
    $empresa->setEmail($email);
    $empresa->setNombreEmpresa($nombreEmpresa ?: $empresa->getNombreEmpresa());
    $empresa->setCalle($calle ?: $empresa->getCalle());
    $empresa->setNumero($numero ?: $empresa->getNumero());

    // --- Crear hash de contraseña si se envía ---
    $hash = !empty($contrasena) ? password_hash($contrasena, PASSWORD_DEFAULT) : null;

    // --- Usar método de la clase para actualizar email, contraseña y datos de empresa ---
    if ($empresa->actualizarEmpresaCompleta($conn, $hash)) {
        echo json_encode(['ok' => true]);
    } else {
        throw new Exception("No se pudo actualizar la empresa");
    }

} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
