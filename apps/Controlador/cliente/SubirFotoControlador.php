<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Cliente.php');

if (!isset($_SESSION['idUsuario'])) {
    header("Location: /Proyecto/apps/vistas/autenticacion/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $idUsuario = $_SESSION['idUsuario'];
    $nombreArchivo = $_FILES['imagen']['name'];
    $tmpName = $_FILES['imagen']['tmp_name'];

    $directorio = $_SERVER['DOCUMENT_ROOT'] . "/Proyecto/public/imagen/clientes/";
    if (!file_exists($directorio)) {
        mkdir($directorio, 0777, true);
    }

    $rutaFinal = $directorio . uniqid() . "_" . basename($nombreArchivo);
    if (move_uploaded_file($tmpName, $rutaFinal)) {
        $rutaRelativa = "/Proyecto/public/imagen/clientes/" . basename($rutaFinal);

        $cliente = new Cliente($idUsuario, null, null, null, null, $rutaRelativa);
        $cliente->setImagen($rutaRelativa);
        $cliente->actualizarImagen($conn);
    }
}

header("Location: /Proyecto/apps/vistas/paginas/cliente/perfil_cliente.php");
exit();
