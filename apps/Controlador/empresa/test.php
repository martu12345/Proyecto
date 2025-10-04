<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Contrata.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');

$cita = Contrata::obtenerPorId($conn, 5);
var_dump($cita);
$cita->actualizarEstado($conn, 'En proceso');
echo "Funciona";
