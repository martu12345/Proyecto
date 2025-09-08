<?php
class Brinda
{
    private $idServicio;
    private $idUsuario;

    public function __construct($idServicio, $idUsuario)
    {
        $this->idServicio = $idServicio;
        $this->idUsuario = $idUsuario;
    }

    // Getters
    public function getIdServicio()
    {
        return $this->idServicio;
    }

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    // Setters
    public function setIdServicio($idServicio)
    {
        $this->idServicio = $idServicio;
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }
}
