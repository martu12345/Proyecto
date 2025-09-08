<?php
class Comunica
{
    private $idUsuarioCliente;
    private $idUsuarioEmpresa;
    private $idMensaje;
    private $contenido;
    private $fecha;

    public function __construct($idUsuarioCliente, $idUsuarioEmpresa, $idMensaje, $contenido, $fecha)
    {
        $this->idUsuarioCliente = $idUsuarioCliente; //pk
        $this->idUsuarioEmpresa = $idUsuarioEmpresa; //pk
        $this->idMensaje = $idMensaje; // pk
        $this->contenido = $contenido;
        $this->fecha = $fecha;
    }

    // getters
    public function getIdUsuarioCliente()
    {
        return $this->idUsuarioCliente;
    }

    public function getIdUsuarioEmpresa()
    {
        return $this->idUsuarioEmpresa;
    }

    public function getIdMensaje()
    {
        return $this->idMensaje;
    }

    public function getContenido()
    {
        return $this->contenido;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    // setters
    public function setIdUsuarioCliente($idUsuarioCliente)
    {
        $this->idUsuarioCliente = $idUsuarioCliente;
    }

    public function setIdUsuarioEmpresa($idUsuarioEmpresa)
    {
        $this->idUsuarioEmpresa = $idUsuarioEmpresa;
    }

    public function setIdMensaje($idMensaje)
    {
        $this->idMensaje = $idMensaje;
    }

    public function setContenido($contenido)
    {
        $this->contenido = $contenido;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
}
