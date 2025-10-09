<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Servicio.php');

$q = $_POST['q'] ?? ($_SESSION['ultima_busqueda'] ?? '');
$departamento = $_POST['departamento'] ?? ($_SESSION['departamento_seleccionado'] ?? '');
$estrellas = isset($_POST['estrellas']) ? intval($_POST['estrellas']) : ($_SESSION['estrellas'] ?? 0);

$servicios = Servicio::buscarServicios($conn, $q, $departamento, $estrellas);

$_SESSION['servicios'] = $servicios;
$_SESSION['ultima_busqueda'] = $q;
$_SESSION['departamento_seleccionado'] = $departamento;
$_SESSION['estrellas'] = $estrellas;

header("Location: /Proyecto/apps/vistas/paginas/busqueda.php");
exit();
