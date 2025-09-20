<?php
session_start();
header('Content-Type: application/json'); // siempre JSON

require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Usuario.php');

$email = $_POST['email'] ?? '';
$contrasenaIngresada = $_POST['contrasena'] ?? '';

if (!$conn) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error de conexión']);
    exit();
}

// Busco usuario
$usuario = Usuario::buscarPorEmail($conn, $email);
if (!$usuario) {
    echo json_encode(['exito' => false, 'mensaje' => 'No existe ninguna cuenta con ese email']);
    exit();
}

// Verifico contraseña
if (!$usuario->verificarContrasena($contrasenaIngresada)) {
    echo json_encode(['exito' => false, 'mensaje' => 'Contraseña incorrecta']);
    exit();
}

// Contraseña correcta → inicio sesión
$_SESSION['idUsuario'] = $usuario->getIdUsuario();
$_SESSION['email'] = $usuario->getEmail();
$idUsuario = $usuario->getIdUsuario();

// Determinar rol
$rol = '';
$redirect = '';

// Cliente
$stmtCliente = $conn->prepare("SELECT 1 FROM cliente WHERE idUsuario = ?");
$stmtCliente->bind_param("i", $idUsuario);
$stmtCliente->execute();
$resCliente = $stmtCliente->get_result();
if ($resCliente && $resCliente->num_rows > 0) {
    $rol = 'cliente';
    $redirect = '/Proyecto/apps/vistas/paginas/cliente/home_cliente.php';
}
$stmtCliente->close();

// Empresa
$stmtEmpresa = $conn->prepare("SELECT 1 FROM empresa WHERE idUsuario = ?");
$stmtEmpresa->bind_param("i", $idUsuario);
$stmtEmpresa->execute();
$resEmpresa = $stmtEmpresa->get_result();
if ($resEmpresa && $resEmpresa->num_rows > 0) {
    $rol = 'empresa';
    $redirect = '/Proyecto/apps/vistas/paginas/empresa/home_empresa.php';
}
$stmtEmpresa->close();

// Admin
$stmtAdmin = $conn->prepare("SELECT 1 FROM administrador WHERE idUsuario = ?");
$stmtAdmin->bind_param("i", $idUsuario);
$stmtAdmin->execute();
$resAdmin = $stmtAdmin->get_result();
if ($resAdmin && $resAdmin->num_rows > 0) {
    $rol = 'admin';
    $redirect = '/Proyecto/apps/vistas/paginas/admin/home_admin.php';
}
$stmtAdmin->close();

// Guardamos rol en sesión para usarlo en el navbar
$_SESSION['rol'] = $rol;

// Si no tiene rol
if ($redirect === '') {
    echo json_encode(['exito' => false, 'mensaje' => 'Usuario sin rol asignado']);
    exit();
}

// Todo OK → devuelvo JSON con redirección
echo json_encode(['exito' => true, 'redirect' => $redirect]);
exit();
