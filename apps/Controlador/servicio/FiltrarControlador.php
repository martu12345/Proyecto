<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');

// Capturo el departamento seleccionado
$departamento = isset($_GET['departamento']) ? trim($_GET['departamento']) : '';

// Si no se seleccionó ninguno, mostramos todos los servicios
$sql = "SELECT * FROM Servicio";
if (!empty($departamento)) {
    $departamento_escapado = $conn->real_escape_string($departamento);
    $sql .= " WHERE Departamento = '$departamento_escapado'";
}

$result = $conn->query($sql);

// Guardo resultados en sesión
$servicios = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
$_SESSION['servicios'] = $servicios;

// Redirijo a la misma vista de búsqueda
header("Location: /Proyecto/apps/vistas/paginas/busqueda.php");
exit();
?>