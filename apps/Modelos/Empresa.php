<?php
require_once('Usuario.php'); 
require_once('Telefono.php'); 

class Empresa extends Usuario
{
    private $nombreEmpresa;
    private $calle;
    private $numero;

    public function __construct($idUsuario, $email, $contrasena, $telefono, $nombreEmpresa, $calle, $numero)
    {
        parent::__construct($idUsuario, $email, $contrasena, $telefono);
        // rne
        $this->nombreEmpresa = $nombreEmpresa;
        $this->calle = $calle;
        $this->numero = $numero;
    }
    // getters

    public function getNombreEmpresa()
    {
        return $this->nombreEmpresa;
    }
    public function getCalle()
    {
        return $this->calle;
    }
    public function getNumero()
    {
        return $this->numero;
    }
    // setters
    public function setNombreEmpresa($nombreEmpresa)
    {
        $this->nombreEmpresa = $nombreEmpresa;
    }
    public function setCalle($calle)
    {
        $this->calle = $calle;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
    }
    //guardar empresa en la base y telefono 
    public function guardarEmpresa($conn, $telefono = null)
    {
        if (parent::guardar($conn)) {
            $this->idUsuario = $conn->insert_id;
            $sql = "INSERT INTO empresa (IdUsuario, NombreEmpresa, Calle, Numero) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $this->idUsuario, $this->nombreEmpresa, $this->calle, $this->numero);
            Telefono::insertarTelefono($conn, $this->idUsuario, $telefono);
            return $stmt->execute();
        } else {
            return false;
        }
    }
}
