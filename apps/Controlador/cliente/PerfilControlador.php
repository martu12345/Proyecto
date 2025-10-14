<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Cliente.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Usuario.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Comunica.php');

if (!isset($_SESSION['idUsuario'])) {
    header("Location: /Proyecto/apps/vistas/autenticacion/login.php");
    exit();
}

$idUsuario = (int)$_SESSION['idUsuario'];
$idCliente = $_SESSION['idUsuario'] ?? 0;

// ------------------
// Obtener datos del cliente
// ------------------
$stmt = $conn->prepare("
    SELECT u.Email, u.Contraseña, c.Nombre, c.Apellido, c.Imagen
    FROM usuario u
    JOIN cliente c ON u.IdUsuario = c.IdUsuario
    WHERE u.IdUsuario = ?
");
if(!$stmt) die("Error prepare select: ".$conn->error);

$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$result = $stmt->get_result();
$datosCliente = $result->fetch_assoc();
$stmt->close();

if(!$datosCliente) die("No se encontraron datos del usuario");

// ------------------
// Crear objeto Cliente
// ------------------
$cliente = new Cliente(
    $idUsuario,
    $datosCliente['Email'],
    $datosCliente['Contraseña'],
    $datosCliente['Nombre'],
    $datosCliente['Apellido'],
    $datosCliente['Imagen']
);

// ------------------
// Array $datos para la vista
// ------------------
$datos = [
    "Email" => $cliente->getEmail(),
    "Nombre" => $cliente->getNombre(),
    "Apellido" => $cliente->getApellido(),
    "Imagen" => $cliente->getImagen()
];

// ------------------
// Subida de imagen
// ------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $nombreArchivo = $_FILES['imagen']['name'];
    $tmpName = $_FILES['imagen']['tmp_name'];

    $directorio = $_SERVER['DOCUMENT_ROOT'] . "/Proyecto/public/imagen/clientes/";
    if (!file_exists($directorio)) mkdir($directorio, 0777, true);

    $rutaFinal = $directorio . uniqid() . "_" . basename($nombreArchivo);

    if (move_uploaded_file($tmpName, $rutaFinal)) {
        $rutaRelativa = "/Proyecto/public/imagen/clientes/" . basename($rutaFinal);

        $cliente->setImagen($rutaRelativa);
        $cliente->actualizarCliente($conn);

        header("Location: /Proyecto/apps/vistas/paginas/cliente/perfil_cliente.php");
        exit;
    } else {
        echo "Error al subir la imagen.";
    }
}

// ------------------
// Manejo de secciones
// ------------------
$seccion = $_GET['seccion'] ?? 'perfil';
if ($seccion === "null") $seccion = 'perfil';
$_SESSION['idCliente'] = $cliente->getIdUsuario();

// ------------------
// Notificaciones de mensajes no leídos
// ------------------
$notificacionesMensajesCliente = Comunica::contarNoLeidosPorCliente($conn, $idCliente);

// Si estamos en la sección de mensajes, marcamos los mensajes como leídos
if($seccion === 'mensajes') {
    Comunica::marcarComoLeidoPorCliente($conn, $idCliente);
}
?>
