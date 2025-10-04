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
    private $idUsuarioEmisor;


    public function __construct($idUsuarioCliente, $idUsuarioEmpresa, $idMensaje, $asunto, $contenido, $fecha, $idUsuarioEmisor)
    {
        $this->idUsuarioCliente = $idUsuarioCliente; // pk
        $this->idUsuarioEmpresa = $idUsuarioEmpresa; // pk
        $this->idMensaje = $idMensaje; // pk
        $this->asunto = $asunto;
        $this->contenido = $contenido;
        $this->fecha = $fecha;
        $this->idUsuarioEmisor = $idUsuarioEmisor;

    }

    // Getters
    public function getIdUsuarioCliente() { return $this->idUsuarioCliente; }
    public function getIdUsuarioEmpresa() { return $this->idUsuarioEmpresa; }
    public function getIdMensaje() { return $this->idMensaje; }
    public function getAsunto() { return $this->asunto; }
    public function getContenido() { return $this->contenido; }
    public function getFecha() { return $this->fecha; }
    public function getIdUsuarioEmisor() { return $this->IdUsuarioEmisor; }

    // Setters
    public function setIdUsuarioCliente($idUsuarioCliente) { $this->idUsuarioCliente = $idUsuarioCliente; }
    public function setIdUsuarioEmpresa($idUsuarioEmpresa) { $this->idUsuarioEmpresa = $idUsuarioEmpresa; }
    public function setIdMensaje($idMensaje) { $this->idMensaje = $idMensaje; }
    public function setAsunto($asunto) { $this->asunto = $asunto; }
    public function setContenido($contenido) { $this->contenido = $contenido; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    public function setIdUsuarioEmisor($idUsuarioEmisor) { $this->idUsuarioEmisor = $idUsuarioEmisor; }


    // Función para enviar mensaje
    public function enviar($conn) {
        $sql = "INSERT INTO comunica (idUsuarioCliente, idUsuarioEmpresa, asunto, contenido, FechaHora, idUsuarioEmisor) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iisssi",
            $this->idUsuarioCliente,
            $this->idUsuarioEmpresa,
            $this->asunto,
            $this->contenido,
            $this->fecha,
            $this->idUsuarioEmisor

        );
        return $stmt->execute();
    }

    //funcion para obtener msj por empresa
public static function obtenerMensajesParaEmpresa($conn, $idEmpresa) {
    $sql = "SELECT 
                c.idMensaje,
                c.contenido AS mensaje,
                c.FechaHora AS fecha,
                CASE 
                    WHEN c.idUsuarioEmisor = c.idUsuarioCliente THEN CONCAT(cl.nombre, ' ', cl.apellido)
                    ELSE e.nombreEmpresa
                END AS emisor,
                c.idUsuarioEmisor
            FROM comunica c
            LEFT JOIN cliente cl ON c.idUsuarioEmisor = cl.idUsuario
            LEFT JOIN empresa e ON c.idUsuarioEmisor = e.idUsuario
            WHERE c.idUsuarioEmpresa = ?
            ORDER BY c.FechaHora ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idEmpresa);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $mensajes = [];
    while ($fila = $resultado->fetch_assoc()) {
        $mensajes[] = $fila;
    }
    return $mensajes;
}



}

