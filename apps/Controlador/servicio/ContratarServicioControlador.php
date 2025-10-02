<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Contrata.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_SESSION['idUsuario'] ?? null;
    $idServicio = $_POST['idServicio'] ?? null;
    $dia = $_POST['dia'] ?? null;
    $hora = $_POST['hora'] ?? null;

    if (!$idUsuario || !$idServicio || !$dia || !$hora) {
        die("Faltan datos para agendar el servicio.");
    }

    // ✅ Traer duración del servicio desde el modelo
    $servicio = Servicio::obtenerPorId($conn, $idServicio);
    $duracion = $servicio->getDuracion(); // en horas

    [$h, $m] = explode(':', $hora);
$inicio = intval($h) * 60 + intval($m);
$fin = $inicio + $duracion * 60;

if ($fin > 24 * 60) {
    die("⚠️ No se puede agendar este servicio a esa hora porque se pasaría del final del día.");
}


    // ✅ Verificar disponibilidad usando la función del modelo
    if (!Contrata::estaDisponible($conn, $idServicio, $dia, $hora, $duracion)) {
        die("Ya existe una cita para este servicio en la fecha y hora seleccionada.");
    }

    
if ($fin > 24 * 60) {
    echo "<script>alert('El servicio no puede agendarse porque excede el fin del día.'); window.history.back();</script>";
    exit;
}


    // Crear la cita y guardarla
    $cita = new Contrata($idUsuario, $idServicio, null, $dia, $hora, null, null);

    if ($cita->guardar($conn)) {
        header("Location: /Proyecto/apps/vistas/paginas/servicio/ConfirmacionServicio.php");
        exit;
    } else {
        die("Ocurrió un error al agendar el servicio.");
    }
}


// --- Si se carga la página normalmente ---
$idServicio = $_GET['idServicio'] ?? null;
if (!$idServicio) die("No se indicó el servicio.");

$servicio = Servicio::obtenerPorId($conn, $idServicio);
if (!$servicio) die("Servicio no encontrado.");

// Obtener ID de la empresa
$idEmpresa = Brinda::obtenerIdEmpresaPorServicio($conn, $idServicio);
$empresa = null;
if ($idEmpresa) {
    $stmt = $conn->prepare("SELECT * FROM Empresa WHERE IdUsuario = ?");
    $stmt->bind_param("i", $idEmpresa);
    $stmt->execute();
    $empresa = $stmt->get_result()->fetch_assoc() ?? null;
    $stmt->close();
}

// Datos del calendario
$mesActual = date('m');
$anioActual = date('Y');
$primerDia = date('N', strtotime("$anioActual-$mesActual-01"));
$diasMes = date('t');
$hoy = date('Y-m-d');

$meses = [
    1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',
    7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'
];
$mesNombre = $meses[(int)$mesActual];

// Obtener citas ocupadas
$citasOcupadas = Contrata::obtenerCitasPorServicio($conn, $idServicio);

// Traer duración del servicio
$duracion = $servicio->getDuracion();

// Cargar vista
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/vistas/paginas/servicio/ContratarServicio.php');
