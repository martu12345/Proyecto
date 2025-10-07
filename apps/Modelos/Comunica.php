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
    private $notificacion; // Nuevo atributo

    public function __construct($idUsuarioCliente, $idUsuarioEmpresa, $idMensaje, $asunto, $contenido, $fecha, $idUsuarioEmisor, $notificacion = null)
    {
        $this->idUsuarioCliente = $idUsuarioCliente; // pk
        $this->idUsuarioEmpresa = $idUsuarioEmpresa; // pk
        $this->idMensaje = $idMensaje; // pk
        $this->asunto = $asunto;
        $this->contenido = $contenido;
        $this->fecha = $fecha;
        $this->idUsuarioEmisor = $idUsuarioEmisor;
        $this->notificacion = $notificacion; // inicializamos
    }

    // Getters
    public function getIdUsuarioCliente() { return $this->idUsuarioCliente; }
    public function getIdUsuarioEmpresa() { return $this->idUsuarioEmpresa; }
    public function getIdMensaje() { return $this->idMensaje; }
    public function getAsunto() { return $this->asunto; }
    public function getContenido() { return $this->contenido; }
    public function getFecha() { return $this->fecha; }
    public function getIdUsuarioEmisor() { return $this->idUsuarioEmisor; }
    public function getNotificacion() { return $this->notificacion; }

    // Setters
    public function setIdUsuarioCliente($idUsuarioCliente) { $this->idUsuarioCliente = $idUsuarioCliente; }
    public function setIdUsuarioEmpresa($idUsuarioEmpresa) { $this->idUsuarioEmpresa = $idUsuarioEmpresa; }
    public function setIdMensaje($idMensaje) { $this->idMensaje = $idMensaje; }
    public function setAsunto($asunto) { $this->asunto = $asunto; }
    public function setContenido($contenido) { $this->contenido = $contenido; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    public function setIdUsuarioEmisor($idUsuarioEmisor) { $this->idUsuarioEmisor = $idUsuarioEmisor; }
    public function setNotificacion($notificacion) { $this->notificacion = $notificacion; }

    // Función para enviar mensaje
    public function enviar($conn) {
        $sql = "INSERT INTO comunica (idUsuarioCliente, idUsuarioEmpresa, asunto, contenido, FechaHora, idUsuarioEmisor, notificacion) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iisssis",
            $this->idUsuarioCliente,
            $this->idUsuarioEmpresa,
            $this->asunto,
            $this->contenido,
            $this->fecha,
            $this->idUsuarioEmisor,
            $this->notificacion
        );
        return $stmt->execute();
    }

    // Función para obtener mensajes por empresa
    public static function obtenerMensajesRecibidosPorEmpresa($conn, $idEmpresa) {
        $sql = "SELECT 
                    c.idMensaje,
                    c.contenido AS mensaje,
                    c.FechaHora AS fecha,
                    c.asunto,
                    c.notificacion,
                    CASE 
                        WHEN c.idUsuarioEmisor = c.idUsuarioCliente THEN CONCAT(cl.nombre, ' ', cl.apellido)
                        ELSE e.nombreEmpresa
                    END AS emisor,
                    c.idUsuarioEmisor,
                    c.idUsuarioCliente
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

    // Función para obtener mensaje por id
    public static function obtenerMensajePorId($conn, $idMensaje)
    {
        $sql = "
            SELECT 
                IdMensaje,
                IdUsuarioCliente,
                IdUsuarioEmpresa,
                Asunto,
                Contenido,
                FechaHora,
                IdUsuarioEmisor,
                notificacion
            FROM comunica
            WHERE IdMensaje = ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idMensaje);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $mensaje = $resultado->fetch_assoc();

        if (!$mensaje) return null;

        $idEmisor = $mensaje['IdUsuarioEmisor'];

        $sqlCliente = "SELECT CONCAT(Nombre, ' ', Apellido) AS NombreCompleto FROM cliente WHERE IdUsuario = ?";
        $stmtCliente = $conn->prepare($sqlCliente);
        $stmtCliente->bind_param("i", $idEmisor);
        $stmtCliente->execute();
        $resCliente = $stmtCliente->get_result();

        if ($resCliente->num_rows > 0) {
            $mensaje['Emisor'] = $resCliente->fetch_assoc()['NombreCompleto'];
        } else {
            $sqlEmpresa = "SELECT NombreEmpresa FROM empresa WHERE IdUsuario = ?";
            $stmtEmpresa = $conn->prepare($sqlEmpresa);
            $stmtEmpresa->bind_param("i", $idEmisor);
            $stmtEmpresa->execute();
            $resEmpresa = $stmtEmpresa->get_result();

            if ($resEmpresa->num_rows > 0) {
                $mensaje['Emisor'] = $resEmpresa->fetch_assoc()['NombreEmpresa'];
            } else {
                $mensaje['Emisor'] = "Desconocido";
            }
        }

        return $mensaje;
    }



public static function obtenerMensajesRecibidosPorCliente($conn, $idCliente) {
    $sql = "SELECT 
                c.idMensaje,
                c.contenido AS mensaje,
                c.FechaHora AS fecha,
                c.asunto,
                -- Mostramos el nombre del emisor (empresa o cliente que no sos vos)
                CASE 
                    WHEN c.idUsuarioEmisor != c.idUsuarioCliente THEN
                        COALESCE(CONCAT(cl.nombre, ' ', cl.apellido), e.nombreEmpresa)
                END AS emisor
            FROM comunica c
            LEFT JOIN cliente cl ON c.idUsuarioEmisor = cl.idUsuario
            LEFT JOIN empresa e ON c.idUsuarioEmisor = e.idUsuario
            WHERE c.idUsuarioCliente = ? AND c.idUsuarioEmisor != ?
            ORDER BY c.FechaHora ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $idCliente, $idCliente);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $mensajes = [];
    while ($fila = $resultado->fetch_assoc()) {
        $mensajes[] = $fila;
    }
    return $mensajes;
}
public static function obtenerMensajesEnviadosPorCliente($conn, $idCliente) {
    $sql = "SELECT 
                c.idMensaje,
                c.contenido AS mensaje,
                c.FechaHora AS fecha,
                c.asunto,
                CASE 
                    WHEN c.idUsuarioEmisor = c.idUsuarioCliente THEN CONCAT(cl.nombre, ' ', cl.apellido)
                    ELSE e.nombreEmpresa
                END AS destinatario
            FROM comunica c
            LEFT JOIN cliente cl ON c.idUsuarioCliente = cl.idUsuario
            LEFT JOIN empresa e ON c.idUsuarioCliente = e.idUsuario
            WHERE c.idUsuarioEmisor = ?
            ORDER BY c.FechaHora ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCliente);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $mensajes = [];
    while ($fila = $resultado->fetch_assoc()) {
        $mensajes[] = $fila;
    }
    return $mensajes;
}
// Mensajes enviados por la empresa
public static function obtenerMensajesEnviadosPorEmpresa($conn, $idEmpresa) {
    $sql = "SELECT 
                c.idMensaje,
                c.contenido AS mensaje,
                c.FechaHora AS fecha,
                c.asunto,
                -- Mostramos a quién fue enviado
                CASE 
                    WHEN c.idUsuarioCliente IS NOT NULL THEN CONCAT(cl.nombre, ' ', cl.apellido)
                    ELSE e.nombreEmpresa
                END AS destinatario
            FROM comunica c
            LEFT JOIN cliente cl ON c.idUsuarioCliente = cl.idUsuario
            LEFT JOIN empresa e ON c.idUsuarioCliente = e.idUsuario
            WHERE c.idUsuarioEmisor = ?
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

