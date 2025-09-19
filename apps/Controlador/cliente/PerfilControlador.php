<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Cliente.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Usuario.php');

if (!isset($_SESSION['idUsuario'])) {
    header("Location: /Proyecto/apps/vistas/autenticacion/login.php");
    exit();
}

$idUsuario = (int)$_SESSION['idUsuario'];

// Obtener datos del usuario
$stmt = $conn->prepare("
    SELECT u.Email, u.Contraseña, c.Nombre, c.Apellido, c.Imagen
    FROM usuario u
    JOIN cliente c ON u.IdUsuario = c.IdUsuario
    WHERE u.IdUsuario = ?
");
if(!$stmt) die("Error prepare select: ".$conn->error);

$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$datos = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$datos) die("No se encontraron datos del usuario");

// Subida de imagen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $nombreArchivo = $_FILES['imagen']['name'];
    $tmpName = $_FILES['imagen']['tmp_name'];

    $directorio = $_SERVER['DOCUMENT_ROOT'] . "/Proyecto/public/imagen/clientes/";
    if (!file_exists($directorio)) mkdir($directorio, 0777, true);

    $rutaFinal = $directorio . uniqid() . "_" . basename($nombreArchivo);

    if (move_uploaded_file($tmpName, $rutaFinal)) {
        $rutaRelativa = "/Proyecto/public/imagen/clientes/" . basename($rutaFinal);

        $cliente = new Cliente($idUsuario, $datos['Email'], null, $datos['Nombre'], $datos['Apellido'], $rutaRelativa);
        $cliente->setImagen($rutaRelativa);
        $cliente->actualizarCliente($conn);

        header("Location: /Proyecto/apps/vistas/paginas/cliente/perfil_cliente.php");
        exit;
    } else {
        echo "Error al subir la imagen.";
    }
}
