<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php'); 

// 🔸 Tomamos valores del POST o de la sesión
$q = isset($_POST['q']) ? trim($_POST['q']) : ($_SESSION['ultima_busqueda'] ?? '');
$departamento = isset($_POST['departamento']) ? trim($_POST['departamento']) : ($_SESSION['departamento_seleccionado'] ?? '');
$estrellas = isset($_POST['estrellas']) ? intval($_POST['estrellas']) : ($_SESSION['estrellas'] ?? 0);

$stopWords = ['a','de','y','el','la','en','con','por','para','que','los','las','un','una','del'];
$categorias = ['Hogar','Autos','Belleza','Cuidado de niños','Digital','Cocina','Salud','Mascotas','Eventos','Educación','Transporte','Arte y Cultura'];

$where = [];

// 🔹 Filtro por palabra
if (!empty($q)) {
    $q_escapado = $conn->real_escape_string($q);
    $categoriaCoincide = false;

    foreach ($categorias as $cat) {
        if (strcasecmp($q, $cat) === 0) {
            $categoriaCoincide = true;
            $where[] = "LOWER(s.Categoria) = LOWER('$cat')";
            break;
        }
    }

    if (!$categoriaCoincide && strlen($q) >= 3) {
        $where[] = "(s.Titulo LIKE '%$q_escapado%' OR s.Descripcion LIKE '%$q_escapado%')";
    }
}

// 🔹 Filtro por departamento
if (!empty($departamento)) {
    $departamento_escapado = $conn->real_escape_string($departamento);
    $where[] = "LOWER(s.Departamento) = LOWER('$departamento_escapado')";
}

// 🔹 Construimos la base de la consulta
$sql = "
    SELECT 
        s.*,
        COALESCE(ROUND(AVG(c.Calificacion),1), 0) AS promedio_calificacion
    FROM Servicio s
    LEFT JOIN Contrata c ON s.IdServicio = c.IdServicio
";

// 🔹 Aplicamos WHERE si corresponde
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " GROUP BY s.IdServicio";

if ($estrellas > 0) {
    $sql .= " HAVING ROUND(promedio_calificacion) = $estrellas";
}

$sql .= " ORDER BY promedio_calificacion DESC";

$result = $conn->query($sql);
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

$servicios = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

// 🔹 Guardamos filtros activos
$_SESSION['servicios'] = $servicios;
$_SESSION['ultima_busqueda'] = $q;
$_SESSION['departamento_seleccionado'] = $departamento;
$_SESSION['estrellas'] = $estrellas;

header("Location: /Proyecto/apps/vistas/paginas/busqueda.php");
exit();
?>
