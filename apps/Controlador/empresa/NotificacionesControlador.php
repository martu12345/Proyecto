<?php // NO HACE NADA TODAVIA 
session_start();
header('Content-Type: application/json');

require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Contrata.php');

$idEmpresa = $_SESSION['idUsuario'] ?? null;

if(!$idEmpresa){
    echo json_encode(['nuevos' => 0]);
    exit;
}

$nuevos = Contrata::contarPendientesPorEmpresa($conn, $idEmpresa);

echo json_encode(['nuevos' => $nuevos]);
