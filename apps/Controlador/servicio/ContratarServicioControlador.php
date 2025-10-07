<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Contrata.php');

$idUsuario = $_SESSION['idUsuario'] ?? null;

// AGENDAR SERVICIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idServicio = $_POST['idServicio'] ?? null;
    $dia = $_POST['dia'] ?? null;
    $hora = $_POST['hora'] ?? null;

    if ($idUsuario && $idServicio && $dia && $hora) {
        $servicio = Servicio::obtenerPorId($conn, $idServicio);
        if ($servicio) {
            
            $duracion = $servicio->getDuracion();
            error_log("DEBUG: intentando agendar $dia $hora, duración $duracion");


            if (Contrata::estaDisponible($conn, $dia, $hora, $duracion)) {
                $cita = new Contrata($idUsuario, $idServicio, null, $dia, $hora, null, null);
                if ($cita->guardar($conn)) {
                    $_SESSION['confirmacionServicio'] = [
                        'titulo' => $servicio->getTitulo(),
                        'dia' => $dia,
                        'hora' => $hora
                    ];
                    header("Location: /Proyecto/apps/vistas/paginas/servicio/ConfirmacionServicio.php");
                    exit();
                }
            } else {
                $_SESSION['errorHora'] = "⚠️ No se puede agendar: el horario choca con otra cita.";
            }
        }
    }
}


// Obtener datos del servicio
$servicio = Servicio::obtenerPorId($conn, $_GET['idServicio'] ?? null);
if (!$servicio) die("Servicio no encontrado.");

// Obtener empresa que brinda el servicio
$idEmpresa = Brinda::obtenerIdEmpresaPorServicio($conn, $servicio->getIdServicio());
$empresa = null;
if ($idEmpresa) {
    $stmt = $conn->prepare("SELECT * FROM Empresa WHERE IdUsuario = ?");
    $stmt->bind_param("i", $idEmpresa);
    $stmt->execute();
    $empresa = $stmt->get_result()->fetch_assoc() ?? null;
    $stmt->close();
}

// Meses para el calendario
$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];

// Obtener todas las citas del servicio
$citasOcupadas = Contrata::obtenerCitasPorServicio($conn, $servicio->getIdServicio());
$duracion = $servicio->getDuracion(); // en horas

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/servicio/ContratarServicio.php');
?>
