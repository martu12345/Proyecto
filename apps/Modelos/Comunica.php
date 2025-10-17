<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/conexion.php');

class Comunica
{
    private $idUsuarioCliente;
    private $idUsuarioEmpresa;
    private $idMensaje;
    private $asunto;
    private $contenido;
    private $fecha;
    private $idUsuarioEmisor;
    private $notificacion;
    private $idMensajePadre;

    public function __construct($idUsuarioCliente, $idUsuarioEmpresa, $idMensaje, $asunto, $contenido, $fecha, $idUsuarioEmisor, $notificacion = null, $idMensajePadre = null)
    {
        $this->idUsuarioCliente = $idUsuarioCliente;
        $this->idUsuarioEmpresa = $idUsuarioEmpresa;
        $this->idMensaje = $idMensaje;
        $this->asunto = $asunto;
        $this->contenido = $contenido;
        $this->fecha = $fecha;
        $this->idUsuarioEmisor = $idUsuarioEmisor;
        $this->notificacion = $notificacion;
        $this->idMensajePadre = $idMensajePadre;
    }

    // Getters
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
    public function getAsunto()
    {
        return $this->asunto;
    }
    public function getContenido()
    {
        return $this->contenido;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getIdUsuarioEmisor()
    {
        return $this->idUsuarioEmisor;
    }
    public function getNotificacion()
    {
        return $this->notificacion;
    }
    public function getIdMensajePadre()
    {
        return $this->idMensajePadre;
    }

    // Setters
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
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;
    }
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;
    }
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    public function setIdUsuarioEmisor($idUsuarioEmisor)
    {
        $this->idUsuarioEmisor = $idUsuarioEmisor;
    }
    public function setNotificacion($notificacion)
    {
        $this->notificacion = $notificacion;
    }
    public function setIdMensajePadre($idMensajePadre)
    {
        $this->idMensajePadre = $idMensajePadre;
    }

    // Enviar mensaje (con IdMensajePadre)
    public function enviar($conn)
    {
        $sql = "INSERT INTO comunica (IdUsuarioCliente, idUsuarioEmpresa, asunto, contenido, FechaHora, idUsuarioEmisor, notificacion, IdMensajePadre) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $idPadre = $this->idMensajePadre ?: null;

        $stmt->bind_param(
            "iisssisi",
            $this->idUsuarioCliente,
            $this->idUsuarioEmpresa,
            $this->asunto,
            $this->contenido,
            $this->fecha,
            $this->idUsuarioEmisor,
            $this->notificacion,
            $idPadre
        );

        if (!$stmt) {
            file_put_contents("C:/wamp64/www/Proyecto/debug.txt", "Error prepare: " . $conn->error . PHP_EOL, FILE_APPEND);
        }

        return $stmt->execute();
    }


    public static function obtenerMensajesRecibidosPorEmpresa($conn, $idEmpresa)
    {
        $sql = "SELECT 
                    c.idMensaje,
                    c.contenido AS mensaje,
                    c.FechaHora AS fecha,
                    c.asunto,
                    c.notificacion,
                    c.IdMensajePadre,
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
                notificacion,
                IdMensajePadre
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

    public static function obtenerMensajesRecibidosPorCliente($conn, $idCliente)
    {
        $sql = "SELECT 
                    c.idMensaje,
                    c.contenido AS mensaje,
                    c.FechaHora AS fecha,
                    c.asunto,
                    c.IdMensajePadre,
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

    public static function obtenerMensajesEnviadosPorCliente($conn, $idCliente)
    {
        $sql = "SELECT 
                    c.idMensaje,
                    c.contenido AS mensaje,
                    c.FechaHora AS fecha,
                    c.asunto,
                    c.IdMensajePadre,
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

    public static function obtenerMensajesEnviadosPorEmpresa($conn, $idEmpresa)
    {
        $sql = "SELECT 
                    c.idMensaje,
                    c.contenido AS mensaje,
                    c.FechaHora AS fecha,
                    c.asunto,
                    c.IdMensajePadre,
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

    public static function contarNoLeidosPorEmpresa($conn, $idEmpresa)
    {
        $sql = "
        SELECT COUNT(*) as total
        FROM comunica
        WHERE idUsuarioEmpresa = ? 
          AND idUsuarioEmisor != ? 
          AND notificacion = 'no leido'
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $idEmpresa, $idEmpresa);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function marcarComoLeidoPorEmpresa($conn, $idEmpresa)
    {
        $sql = "
        UPDATE comunica
        SET notificacion = 'leido'
        WHERE idUsuarioEmpresa = ?
          AND idUsuarioEmisor != ?
          AND notificacion = 'no leido'
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $idEmpresa, $idEmpresa);
        $stmt->execute();
    }



    public static function contarNoLeidosPorCliente($conn, $idCliente)
    {
        $sql = "
        SELECT COUNT(*) as total
        FROM comunica c
        JOIN comunica mp ON c.IdMensajePadre = mp.IdMensaje
        WHERE mp.idUsuarioEmisor = ? AND c.notificacion = 'no leido'
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idCliente);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public static function marcarComoLeidoPorCliente($conn, $idCliente)
    {
        $sql = "
        UPDATE comunica c
        JOIN comunica mp ON c.IdMensajePadre = mp.IdMensaje
        SET c.notificacion = 'leido'
        WHERE mp.idUsuarioEmisor = ?
    ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idCliente);
        $stmt->execute();
    }
}
