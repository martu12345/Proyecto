<?php
session_start(); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php'); 

$q = isset($_POST['q']) ? trim($_POST['q']) : '';
$where = [];

$stopWords = ['a','de','y','el','la','en','con','por','para','que','los','las','un','una','del'];

if (!empty($q)) {
    $q_lower = strtolower($q); 
    $q_escapado = $conn->real_escape_string($q); 

    if (strlen($q) >= 4) {
        $where[] = "Titulo LIKE '%$q_escapado%'";
    }

    if (!in_array($q_lower, $stopWords) && strlen($q) >= 4) {
        $where[] = "Descripcion REGEXP '\\\\b$q_escapado\\\\b'";
    }
}

$sql = "SELECT * FROM Servicio";

if (!empty($where)) {
    $sql .= " WHERE " . implode(" OR ", $where);
} else {
    $sql .= " WHERE 1=0"; 
}

$result = $conn->query($sql);

$servicios = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

$_SESSION['servicios'] = $servicios;

header("Location: /Proyecto/apps/vistas/paginas/busqueda.php");
exit();
?>
