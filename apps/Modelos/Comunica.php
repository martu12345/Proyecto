<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');

class Comunica
{
    private $idUsuarioCliente;
    private $idUsuarioEmpresa;
    private $idMensaje;
    private $asunto;
    private $contenido;
    private $fecha;

    public function __construct($idUsuarioCliente, $idUsuarioEmpresa, $idMensaje, $asunto, $contenido, $fecha)
    {
        $this->idUsuarioCliente = $idUsuarioCliente; // pk
        $this->idUsuarioEmpresa = $idUsuarioEmpresa; // pk
        $this->idMensaje = $idMensaje; // pk
        $this->asunto = $asunto;
        $this->contenido = $contenido;
        $this->fecha = $fecha;
    }

    // Getters
    public function getIdUsuarioCliente() { return $this->idUsuarioCliente; }
    public function getIdUsuarioEmpresa() { return $this->idUsuarioEmpresa; }
    public function getIdMensaje() { return $this->idMensaje; }
    public function getAsunto() { return $this->asunto; }
    public function getContenido() { return $this->contenido; }
    public function getFecha() { return $this->fecha; }

    // Setters
    public function setIdUsuarioCliente($idUsuarioCliente) { $this->idUsuarioCliente = $idUsuarioCliente; }
    public function setIdUsuarioEmpresa($idUsuarioEmpresa) { $this->idUsuarioEmpresa = $idUsuarioEmpresa; }
    public function setIdMensaje($idMensaje) { $this->idMensaje = $idMensaje; }
    public function setAsunto($asunto) { $this->asunto = $asunto; }
    public function setContenido($contenido) { $this->contenido = $contenido; }
    public function setFecha($fecha) { $this->fecha = $fecha; }

    // Función para enviar mensaje
    public function enviar($conn) {
        $sql = "INSERT INTO comunica (idUsuarioCliente, idUsuarioEmpresa, asunto, contenido, fecha) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iisss",
            $this->idUsuarioCliente,
            $this->idUsuarioEmpresa,
            $this->asunto,
            $this->contenido,
            $this->fecha
        );
        return $stmt->execute();
    }
}
