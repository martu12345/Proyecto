<?php
// Iniciamos sesión solo si no hay activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php'); 

$q = isset($_POST['q']) ? trim($_POST['q']) : '';
$departamento = isset($_POST['departamento']) ? trim($_POST['departamento']) : '';

$stopWords = ['a','de','y','el','la','en','con','por','para','que','los','las','un','una','del'];

// Arrays para WHERE
$wherePalabra = [];
$whereDep = [];

// Filtro por palabra
if (!empty($q)) {
    $q_escapado = $conn->real_escape_string($q);

    if (strlen($q) >= 4) {
        $wherePalabra[] = "Titulo LIKE '%$q_escapado%'";
    }

    $q_lower = strtolower($q);
    if (!in_array($q_lower, $stopWords) && strlen($q) >= 4) {
        $wherePalabra[] = "Descripcion REGEXP '\\\\b$q_escapado\\\\b'";
    }
}

// Filtro por departamento
if (!empty($departamento)) {
    $departamento_escapado = $conn->real_escape_string($departamento);
    $whereDep[] = "LOWER(departamento) = '$departamento_escapado'";
}

// Construcción de la consulta
$sql = "SELECT * FROM Servicio";
$finalWhere = [];

if (!empty($wherePalabra)) {
    $finalWhere[] = "(" . implode(" OR ", $wherePalabra) . ")";
}

if (!empty($whereDep)) {
    $finalWhere[] = implode(" AND ", $whereDep);
}

if (!empty($finalWhere)) {
    // Aquí está la clave: usamos AND entre palabra y departamento
    $sql .= " WHERE " . implode(" AND ", $finalWhere);
} else {
    $sql .= " WHERE 1"; // Si no hay filtros, muestra todos
}

// Ejecutamos la consulta
$result = $conn->query($sql);
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

// Guardamos resultados y filtros en sesión
$servicios = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
$_SESSION['servicios'] = $servicios;
$_SESSION['departamento_seleccionado'] = $departamento;
$_SESSION['ultima_busqueda'] = $q;

// Redirigimos
header("Location: /Proyecto/apps/vistas/paginas/busqueda.php");
exit();
?>
