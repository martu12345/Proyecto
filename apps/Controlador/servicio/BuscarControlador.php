<?php
session_start(); 
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php'); 

$q = isset($_POST['q']) ? trim($_POST['q']) : '';
$departamento = isset($_POST['departamento']) ? trim($_POST['departamento']) : '';

$stopWords = ['a','de','y','el','la','en','con','por','para','que','los','las','un','una','del'];

$wherePalabra = [];
$whereDep = [];

// Filtros por palabra
if (!empty($q)) {
    $q_lower = strtolower($q);
    $q_escapado = $conn->real_escape_string($q);

    if (strlen($q) >= 4) {
        $wherePalabra[] = "Titulo LIKE '%$q_escapado%'";
    }

    if (!in_array($q_lower, $stopWords) && strlen($q) >= 4) {
        $wherePalabra[] = "Descripcion REGEXP '\\\\b$q_escapado\\\\b'";
    }
}

// Filtro por departamento
if (!empty($departamento)) {
    $departamento_escapado = $conn->real_escape_string($departamento);
    $whereDep[] = "Departamento = '$departamento_escapado'";
}

// Armo la consulta final
$sql = "SELECT * FROM Servicio";
$finalWhere = [];

if (!empty($wherePalabra)) {
    $finalWhere[] = "(" . implode(" OR ", $wherePalabra) . ")";
}

if (!empty($whereDep)) {
    $finalWhere[] = implode(" AND ", $whereDep);
}

if (!empty($finalWhere)) {
    $sql .= " WHERE " . implode(" AND ", $finalWhere);
} else {
    $sql .= " WHERE 1=0";
}

$result = $conn->query($sql);
$servicios = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

$_SESSION['servicios'] = $servicios;
header("Location: /Proyecto/apps/vistas/paginas/busqueda.php");
exit();
?>
