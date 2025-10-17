<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Servicio.php');

// Obtener filtros 
$q = $_POST['q'] ?? ($_SESSION['ultima_busqueda'] ?? '');
$departamento = $_POST['departamento'] ?? ($_SESSION['departamento_seleccionado'] ?? '');
$estrellas = isset($_POST['estrellas']) ? intval($_POST['estrellas']) : ($_SESSION['estrellas'] ?? 0);

// Guardar filtros para mantener búsqueda
$_SESSION['ultima_busqueda'] = $q;
$_SESSION['departamento_seleccionado'] = $departamento;
$_SESSION['estrellas'] = $estrellas;

// Buscar servicios con los filtros 
$servicios = Servicio::buscarServicios($conn, $q, $departamento, $estrellas);

require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/vistas/paginas/busqueda.php');
