<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/Cliente.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/Comunica.php');

if (!isset($_SESSION['idUsuario'])) {
    header("Location: /Proyecto/apps/vistas/autenticacion/login.php");
    exit();
}

$idUsuario = (int)$_SESSION['idUsuario'];

$cliente = Cliente::obtenerPorId($conn, $idUsuario);
if (!$cliente) {
    die("No se encontraron datos del usuario");
}

// Subida de imagen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $nombreArchivo = $_FILES['imagen']['name'];
    $tmpName = $_FILES['imagen']['tmp_name'];

    $directorio = $_SERVER['DOCUMENT_ROOT'] . "/Proyecto/public/imagen/clientes/";
    if (!file_exists($directorio)) mkdir($directorio, 0777, true);

    $rutaFinal = $directorio . uniqid() . "_" . basename($nombreArchivo);

    if (move_uploaded_file($tmpName, $rutaFinal)) {
        $rutaRelativa = "/Proyecto/public/imagen/clientes/" . basename($rutaFinal);
        $cliente->setImagen($rutaRelativa);
        $cliente->actualizarImagen($conn);

        header("Location: /Proyecto/apps/controlador/cliente/PerfilControlador.php");
        exit;
    } else {
        echo "Error al subir la imagen.";
    }
}

// Secciones
$seccion = $_GET['seccion'] ?? 'perfil';
if ($seccion === "null") $seccion = 'perfil';
$_SESSION['idCliente'] = $cliente->getIdUsuario();

// Notificaciones
$notificacionesMensajesCliente = Comunica::contarNoLeidosPorCliente($conn, $idUsuario);
if ($seccion === 'mensajes') {
    Comunica::marcarComoLeidoPorCliente($conn, $idUsuario);
}

// Datos para la vista
$datos = [
    "Email" => $cliente->getEmail(),
    "Nombre" => $cliente->getNombre(),
    "Apellido" => $cliente->getApellido(),
    "Imagen" => $cliente->getImagen()
];

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/cliente/perfil_cliente.php');
