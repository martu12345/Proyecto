<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');

header('Content-Type: application/json');

if (!isset($_SESSION['idUsuario'])) {
    echo json_encode(['ok' => false, 'error' => 'No logueado']);
    exit();
}

$idUsuario = (int)$_SESSION['idUsuario'];

if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] != 0) {
    echo json_encode(['ok'=>false, 'error'=>'No se subió la imagen']);
    exit();
}

$archivo = $_FILES['imagen'];
$ext = pathinfo($archivo['name'], PATHINFO_EXTENSION);
$nombreNuevo = 'empresa_' . $idUsuario . '.' . $ext;
$rutaDestino = $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/public/imagen/empresas/' . $nombreNuevo;

// Mover archivo a la carpeta de destino
if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
    // Actualizar en la base de datos
    $stmt = $conn->prepare("UPDATE empresa SET Imagen = ? WHERE IdUsuario = ?");
    $stmt->bind_param("si", $nombreNuevo, $idUsuario);
    if($stmt->execute()){
        echo json_encode(['ok'=>true,'imagen'=>$nombreNuevo]);
    } else {
        echo json_encode(['ok'=>false,'error'=>'No se pudo actualizar la base de datos']);
    }
    $stmt->close();
} else {
    echo json_encode(['ok'=>false,'error'=>'Error al mover el archivo']);
}
