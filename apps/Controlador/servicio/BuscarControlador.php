
<?php
// filtros para al bsuqueda 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php'); 

// Recibimos datos del formulario
$q = isset($_POST['q']) ? trim($_POST['q']) : '';
$departamento = isset($_POST['departamento']) ? trim($_POST['departamento']) : '';

$stopWords = ['a','de','y','el','la','en','con','por','para','que','los','las','un','una','del'];
$categorias = ['Hogar','Autos','Belleza','Cuidado de niños','Digital','Cocina','Salud','Mascotas','Eventos','Educación','Transporte','Arte y Cultura'];

$wherePalabra = [];
$whereDep = [];
$whereCat = [];

// Filtro por palabra
if (!empty($q)) {
    $q_escapado = $conn->real_escape_string($q);

    // Chequeamos si la palabra coincide con alguna categoría (ignorando mayúsculas/minúsculas)
    foreach ($categorias as $cat) {
        if (strcasecmp($q, $cat) === 0) {
            $cat_escapada = $conn->real_escape_string($cat);
            $whereCat[] = "LOWER(categoria) = LOWER('$cat_escapada')";
            break; 
        }
    }

    // Si no es categoría, buscamos en Titulo y Descripcion
    if (empty($whereCat)) {
        if (strlen($q) >= 4) {
            $wherePalabra[] = "Titulo LIKE '%$q_escapado%'";
        }

        $q_lower = strtolower($q);
        if (!in_array($q_lower, $stopWords) && strlen($q) >= 4) {
            $wherePalabra[] = "Descripcion REGEXP '\\\\b$q_escapado\\\\b'";
        }
    }
}

// Filtro por departamento
if (!empty($departamento)) {
    $departamento_escapado = $conn->real_escape_string($departamento);
    $whereDep[] = "LOWER(departamento) = '$departamento_escapado'";
}

$sql = "SELECT * FROM Servicio";
$finalWhere = [];

// Combinamos filtros
if (!empty($wherePalabra)) {
    $finalWhere[] = "(" . implode(" OR ", $wherePalabra) . ")";
}
if (!empty($whereDep)) {
    $finalWhere[] = implode(" AND ", $whereDep);
}
if (!empty($whereCat)) {
    $finalWhere[] = implode(" AND ", $whereCat);
}

// Unimos todos los filtros 
if (!empty($finalWhere)) {
    $sql .= " WHERE " . implode(" AND ", $finalWhere);
} else {
    $sql .= " WHERE 1"; // si no hay filtros, muestra todos
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

header("Location: /Proyecto/apps/vistas/paginas/busqueda.php");
exit();
?>
