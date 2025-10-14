<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');

echo "<h3>DEBUG INICIO CONTROLADOR</h3>";

// Verificar si hay sesión iniciada
if (!isset($_SESSION['idUsuario'])) {
    die("<p style='color:red;'>ERROR: No hay sesión iniciada como empresa.</p>");
}

$idEmpresa = $_SESSION['idUsuario'];
echo "<p>ID Empresa logueada: <b>$idEmpresa</b></p>";

// Consulta para obtener los clientes que contrataron servicios de esta empresa
$sql = "
    SELECT 
        c.idUsuario AS idCliente,
        c.nombre,
        c.apellido,
        c.imagen,
        u.email,
        con.resena,
        con.calificacion
    FROM contrata con
    INNER JOIN servicio s ON con.idServicio = s.idServicio
    INNER JOIN cliente c ON con.idUsuario = c.idUsuario
    INNER JOIN usuario u ON c.idUsuario = u.idUsuario
    WHERE s.idUsuarioEmpresa = ?
";

echo "<p>Consulta SQL preparada:</p><pre>$sql</pre>";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("<p style='color:red;'>Error al preparar la consulta: " . $conn->error . "</p>");
}

$stmt->bind_param("i", $idEmpresa);
$ok = $stmt->execute();

if (!$ok) {
    die("<p style='color:red;'>Error al ejecutar la consulta: " . $stmt->error . "</p>");
}

$result = $stmt->get_result();

if ($result === false) {
    die("<p style='color:red;'>Error al obtener resultados: " . $stmt->error . "</p>");
}

$clientes = [];
while ($fila = $result->fetch_assoc()) {
    $clientes[] = $fila;
}

echo "<h4>Resultado del var_dump de clientes:</h4>";
echo "<pre>";
var_dump($clientes);
echo "</pre>";

if (empty($clientes)) {
    echo "<p style='color:orange;'>No se encontraron clientes para esta empresa.</p>";
} else {
    echo "<p style='color:green;'>Clientes encontrados: " . count($clientes) . "</p>";
}

$stmt->close();
$conn->close();

echo "<h3>DEBUG FIN CONTROLADOR</h3>";

// Incluir la vista (solo si querés seguir probando)
include($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/empresa/clientes.php');
