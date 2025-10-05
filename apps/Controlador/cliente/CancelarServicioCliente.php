<?php // Si el cliente cancela su serivico en la tabla en proceos y pendiente
session_start();
header('Content-Type: application/json');

require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Controlador/cliente/ServiciosClienteControlador.php');

$idUsuario = $_SESSION['idUsuario'] ?? null;
if(!$idUsuario){
    echo json_encode(['error' => 'Usuario no logueado']);
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $idCita = $_POST['idCita'] ?? null;
    if(!$idCita){
        echo json_encode(['error'=>'ID de cita faltante']);
        exit;
    }

    $controlador = new ServiciosClienteControlador($conn, $idUsuario);
    $exito = $controlador->cancelarServicio($idCita);

    if($exito){
        echo json_encode(['success'=>true]);
    } else {
        echo json_encode(['error'=>'No se pudo cancelar']);
    }
}
