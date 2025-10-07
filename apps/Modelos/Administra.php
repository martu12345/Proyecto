<?php
class Administra
{
    private $idServicio;
    private $idUsuario;
    private $fecha; // nuevo atributo

    public function __construct($idServicio, $idUsuario, $fecha = null)
    {
        $this->idServicio = $idServicio;
        $this->idUsuario = $idUsuario;
        $this->fecha = $fecha; // asignación opcional
    }

    // getters
    public function getIdServicio()
    {
        return $this->idServicio;
    }

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    // setters
    public function setIdServicio($idServicio)
    {
        $this->idServicio = $idServicio;
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
}
?>
