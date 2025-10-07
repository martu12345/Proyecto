<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Contrata.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Cliente.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Conexion.php'); // conexión $conn

if (!isset($_GET['idServicio'])) {
    die("No se especificó el servicio"); // ahora esto no va a pasar
}

$idServicio = intval($_GET['idServicio']);
$resenas = Contrata::obtenerResenasPorServicio($conn, $idServicio);



include($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/vistas/paginas/servicio/DetallesServicio.php');
