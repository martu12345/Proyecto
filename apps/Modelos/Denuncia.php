<?php
require_once('Usuario.php'); // Si necesitás usar Usuario, Cliente o Empresa

class Denuncia
{
    private $idDenuncia;
    private $idCliente;
    private $idEmpresa;
    private $motivo;
    private $fecha;

    public function __construct($idCliente, $idEmpresa, $motivo, $fecha = null, $idDenuncia = null)
    {
        $this->idDenuncia = $idDenuncia;
        $this->idCliente = $idCliente;
        $this->idEmpresa = $idEmpresa;
        $this->motivo = $motivo;
        $this->fecha = $fecha ?? date('Y-m-d H:i:s'); // si no se pasa fecha, se pone la actual
    }

    // Getters
    public function getIdDenuncia()
    {
        return $this->idDenuncia;
    }

    public function getIdCliente()
    {
        return $this->idCliente;
    }

    public function getIdEmpresa()
    {
        return $this->idEmpresa;
    }

    public function getMotivo()
    {
        return $this->motivo;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    // Setters
    public function setIdCliente($idCliente)
    {
        $this->idCliente = $idCliente;
    }

    public function setIdEmpresa($idEmpresa)
    {
        $this->idEmpresa = $idEmpresa;
    }

    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
}
