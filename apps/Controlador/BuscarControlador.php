<?php

session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');

$q = isset($_POST['q']) ? trim($_POST['q']) : '';

if (!empty($q)) {
    $q_escapado = $conn->real_escape_string($q);
    $sql = "SELECT * FROM Servicio WHERE Titulo LIKE '%$q_escapado%'";
} else {
    $sql = "SELECT * FROM Servicio WHERE 1=0";
}

$result = $conn->query($sql);
$servicios = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

// gurada los resultados en sesion 
$_SESSION['servicios'] = $servicios;

// te envia al busqueda prinicpal 
header("Location: /Proyecto/apps/vistas/paginas/busqueda.php");
exit();
