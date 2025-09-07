<?php
class Contrata {
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

    // Getters
    public function getIdUsuario() { return $this->idUsuario; }
    public function getIdServicio() { return $this->idServicio; }
    public function getIdCita() { return $this->idCita; }
    public function getFecha() { return $this->fecha; }
    public function getHora() { return $this->hora; }
    public function getCalificacion() { return $this->calificacion; }
    public function getResena() { return $this->resena; }

    // Setters
    public function setIdUsuario($idUsuario) { $this->idUsuario = $idUsuario; }
    public function setIdServicio($idServicio) { $this->idServicio = $idServicio; }
    public function setIdCita($idCita) { $this->idCita = $idCita; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    public function setHora($hora) { $this->hora = $hora; }
    public function setCalificacion($calificacion) { $this->calificacion = $calificacion; }
    public function setResena($resena) { $this->resena = $resena; }
}
?>
