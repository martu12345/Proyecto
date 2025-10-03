<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Contrata.php');

$idUsuario = $_SESSION['idUsuario'] ?? null;
$mensajeError = "";

// AGENDAR SERVICIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idServicio = $_POST['idServicio'] ?? null;
    $dia = $_POST['dia'] ?? null;
    $hora = $_POST['hora'] ?? null;

    if (!$idUsuario || !$idServicio || !$dia || !$hora) {
        $mensajeError = "Faltan datos para agendar el servicio.";
    } else {
        $servicio = Servicio::obtenerPorId($conn, $idServicio);
        if (!$servicio) {
            $mensajeError = "Servicio no encontrado.";
        } else {
            $duracion = $servicio->getDuracion();
            [$h, $m] = array_map('intval', explode(':', $hora));
            $inicioNuevo = $h * 60 + $m;
            $finNuevo = $inicioNuevo + $duracion * 60;

            if ($finNuevo > 24 * 60) {
                $mensajeError = "No se puede agendar este horario. Servicio dura $duracion h, se pasaría del día.";
            } else {
                // Obtener todas las citas del mismo día
                $citasDia = Contrata::obtenerCitasPorServicio($conn, $idServicio);
                $citasDia = array_filter($citasDia, fn($c) => $c['Fecha'] === $dia);

                $choque = false;
                $ocupadosDia = [];
                foreach ($citasDia as $c) {
                    [$ch, $cm] = array_map('intval', explode(':', $c['Hora']));
                    $inicioC = $ch * 60 + $cm;
                    $finC = $inicioC + $c['Duracion'] * 60;
                    $ocupadosDia[] = ['inicio' => $inicioC, 'fin' => $finC];
                    if (!($finNuevo <= $inicioC || $inicioNuevo >= $finC)) $choque = true;
                }

                if ($choque) {
                    // Ordenar ocupados
                    usort($ocupadosDia, fn($a, $b) => $a['inicio'] - $b['inicio']);
                    // Calcular horarios libres
                    $libres = [];
                    $start = 0;
                    foreach ($ocupadosDia as $c) {
                        if ($c['inicio'] - $start >= $duracion * 60) $libres[] = ['inicio' => $start, 'fin' => $c['inicio']];
                        $start = $c['fin'];
                    }
                    if (24 * 60 - $start >= $duracion * 60) $libres[] = ['inicio' => $start, 'fin' => 24 * 60];

                    // Convertir a formato hh:mm
                    function minutosAHora($minutos) {
                        $h = floor($minutos / 60);
                        $m = $minutos % 60;
                        return sprintf("%02d:%02d", $h, $m);
                    }

                    $mensajeError = "Este horario no se puede agendar porque choca con servicios existentes:\n";
                    foreach ($ocupadosDia as $c) $mensajeError .= "• " . minutosAHora($c['inicio']) . " - " . minutosAHora($c['fin']) . "\n";
                    $mensajeError .= "Horarios libres para tu servicio:\n";
                    foreach ($libres as $l) $mensajeError .= "• " . minutosAHora($l['inicio']) . " - " . minutosAHora($l['fin']) . "\n";
                } else {
                    // Guardar cita con estado inicial "Pendiente"
                    $cita = new Contrata(
                        $idUsuario,   // IdUsuario
                        $idServicio,  // IdServicio
                        null,         // IdCita
                        $dia,         // Fecha
                        $hora,        // Hora
                        null,         // Calificación
                        null,         // Reseña
                        'Pendiente'   // Estado inicial
                    );

                    if ($cita->guardar($conn)) {
                        // Guardamos datos en sesión para la confirmación
                        $_SESSION['confirmacionServicio'] = [
                            'titulo' => $servicio->getTitulo(),
                            'dia' => $dia,
                            'hora' => $hora
                        ];
                        header("Location: /Proyecto/apps/vistas/paginas/servicio/ConfirmacionServicio.php");
                        exit();
                    } else {
                        $mensajeError = "Ocurrió un error al agendar el servicio.";
                    }
                }
            }
        }
    }
}

// Obtener datos del servicio para mostrar en la página
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

$mesActual = date('m');
$anioActual = date('Y');

$meses = [
    1 => 'Enero',
    2 => 'Febrero',
    3 => 'Marzo',
    4 => 'Abril',
    5 => 'Mayo',
    6 => 'Junio',
    7 => 'Julio',
    8 => 'Agosto',
    9 => 'Septiembre',
    10 => 'Octubre',
    11 => 'Noviembre',
    12 => 'Diciembre'
];

$citasOcupadas = Contrata::obtenerCitasPorServicio($conn, $servicio->getIdServicio());

$duracion = $servicio->getDuracion();

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/servicio/ContratarServicio.php');
?>
