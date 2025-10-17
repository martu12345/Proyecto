<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Contrata.php');

$idUsuario = $_SESSION['idUsuario'] ?? null;

//  Agendar un servicio 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idServicio = $_POST['idServicio'] ?? null;
    $dia = $_POST['dia'] ?? null;
    $hora = $_POST['hora'] ?? null;

    if ($idUsuario && $idServicio && $dia && $hora) {
        $servicio = Servicio::obtenerPorId($conn, $idServicio);

        if ($servicio) {
            $duracion = $servicio->getDuracion();

            if (Contrata::estaDisponible($conn, $idServicio, $dia, $hora, $duracion)) {
                $cita = new Contrata(
                    $idUsuario,
                    $idServicio,
                    null,         // idCita - se incrementa solo 
                    $dia,
                    $hora,
                    null,         // calificacion
                    null,         // resena
                    null,         // estado - Pendiente por defecto
                    'no_leido'
                );

                if ($cita->guardar($conn)) {
                    $_SESSION['confirmacionServicio'] = [
                        'titulo' => $servicio->getTitulo(),
                        'dia' => $dia,
                        'hora' => $hora
                    ];
                    header("Location: /Proyecto/apps/controlador/servicio/ConfirmarServicioControlador.php");
                    exit();
                }
            } else {
                $_SESSION['errorHora'] = " No se puede agendar: el horario choca con otra cita.";
            }
        }
    }
}

//  Datos del servicio
$servicio = Servicio::obtenerPorId($conn, $_GET['idServicio'] ?? null);
if (!$servicio) die("Servicio no encontrado.");

// Empresa que brinda el servicio
$idEmpresa = Brinda::obtenerIdEmpresaPorServicio($conn, $servicio->getIdServicio());
$empresa = null;

if ($idEmpresa) {
    $stmt = $conn->prepare("SELECT * FROM Empresa WHERE IdUsuario = ?");
    $stmt->bind_param("i", $idEmpresa);
    $stmt->execute();
    $empresa = $stmt->get_result()->fetch_assoc() ?? null;
    $stmt->close();
}

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

// Citas ocupadas
$citasOcupadas = Contrata::obtenerCitasPorServicio($conn, $servicio->getIdServicio());
$duracion = $servicio->getDuracion(); // en horas

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/servicio/ContratarServicio.php');
