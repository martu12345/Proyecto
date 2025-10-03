<?php
class Contrata
{
    private $idUsuario;
    private $idServicio;
    private $idCita;
    private $fecha;
    private $hora;
    private $calificacion;
    private $resena;

    public function __construct($idUsuario, $idServicio, $idCita, $fecha, $hora, $calificacion, $resena) {
        $this->idUsuario = $idUsuario;
        $this->idServicio = $idServicio;
        $this->idCita = $idCita;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->calificacion = $calificacion;
        $this->resena = $resena;
    }

    public function getIdUsuario() { return $this->idUsuario; }
    public function getIdServicio() { return $this->idServicio; }
    public function getIdCita() { return $this->idCita; }
    public function getFecha() { return $this->fecha; }
    public function getHora() { return $this->hora; }
    public function getCalificacion() { return $this->calificacion; }
    public function getResena() { return $this->resena; }

    public function setIdUsuario($idUsuario) { $this->idUsuario = $idUsuario; }
    public function setIdServicio($idServicio) { $this->idServicio = $idServicio; }
    public function setIdCita($idCita) { $this->idCita = $idCita; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    public function setHora($hora) { $this->hora = $hora; }
    public function setCalificacion($calificacion) { $this->calificacion = $calificacion; }
    public function setResena($resena) { $this->resena = $resena; }

    public function guardar($conn) {
        $stmt = $conn->prepare("INSERT INTO Contrata (IdUsuario, IdServicio, Fecha, Hora) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $this->idUsuario, $this->idServicio, $this->fecha, $this->hora);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public static function estaDisponible($conn, $idServicio, $fecha, $hora, $duracion) {
        $stmt = $conn->prepare("
            SELECT c.Hora, s.Duracion
            FROM Contrata c
            INNER JOIN Servicio s ON c.IdServicio = s.IdServicio
            WHERE c.IdServicio = ? AND c.Fecha = ?
        ");
        $stmt->bind_param("is", $idServicio, $fecha);
        $stmt->execute();
        $resultado = $stmt->get_result();

        [$h, $m] = array_map('intval', explode(':', $hora));
        $inicioNuevo = $h*60 + $m;
        $finNuevo = $inicioNuevo + $duracion*60;

        while ($row = $resultado->fetch_assoc()) {
            [$ch, $cm] = array_map('intval', explode(':', $row['Hora']));
            $inicioExistente = $ch*60 + $cm;
            $finExistente = $inicioExistente + (int)$row['Duracion']*60;

            // Solo bloquear si realmente se solapan (se permite desde fin+1 minuto)
            if (!($finNuevo <= $inicioExistente || $inicioNuevo >= $finExistente)) {
                return false;
            }
        }

        $stmt->close();
        return true;
    }

    public static function obtenerCitasPorServicio($conn, $idServicio) {
        $stmt = $conn->prepare("
            SELECT c.Fecha, c.Hora, s.Duracion
            FROM Contrata c
            INNER JOIN Servicio s ON c.IdServicio = s.IdServicio
            WHERE c.IdServicio = ?
        ");
        $stmt->bind_param("i", $idServicio);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $citas = [];
        while ($row = $resultado->fetch_assoc()) {
            $row['Duracion'] = (int)$row['Duracion'];
            $citas[] = $row;
        }

        $stmt->close();
        return $citas;
    }
}
