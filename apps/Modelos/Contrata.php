<?php
class Contrata
{
    private $idUsuario;
    private $idServicio;
    private $idCita;
    private $fecha;
    private $hora;
    private $calificacion;
    private $resena;
    private $estado;
    private $notificacion; // <- Nueva propiedad

   public function __construct(
    $idUsuario, 
    $idServicio, 
    $idCita = null, 
    $fecha = null, 
    $hora = null, 
    $calificacion = null, 
    $resena = null, 
    $estado = 'Pendiente',     // sigue igual
    $notificacion = 'no_leido' // sigue igual
) {
    $this->idUsuario = $idUsuario;
    $this->idServicio = $idServicio;
    $this->idCita = $idCita;
    $this->fecha = $fecha;
    $this->hora = $hora;
    $this->calificacion = $calificacion;
    $this->resena = $resena;
    $this->estado = $estado;
    $this->notificacion = $notificacion;
}


    // GETTERS
    public function getIdUsuario() { return $this->idUsuario; }
    public function getIdServicio() { return $this->idServicio; }
    public function getIdCita() { return $this->idCita; }
    public function getFecha() { return $this->fecha; }
    public function getHora() { return $this->hora; }
    public function getCalificacion() { return $this->calificacion; }
    public function getResena() { return $this->resena; }
    public function getEstado() { return $this->estado; }
    public function getNotificacion() { return $this->notificacion; } // <- Nuevo getter

    // SETTERS
    public function setIdUsuario($idUsuario) { $this->idUsuario = $idUsuario; }
    public function setIdServicio($idServicio) { $this->idServicio = $idServicio; }
    public function setIdCita($idCita) { $this->idCita = $idCita; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
    public function setHora($hora) { $this->hora = $hora; }
    public function setCalificacion($calificacion) { $this->calificacion = $calificacion; }
    public function setResena($resena) { $this->resena = $resena; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setNotificacion($notificacion) { $this->notificacion = $notificacion; } // <- Nuevo setter

 // GUARDAR
public function guardar($conn)
{
    $estado = $this->estado ?? 'Pendiente';
$notificacion = $this->notificacion ?? 'no_leido';

    $stmt = $conn->prepare("
        INSERT INTO Contrata (IdUsuario, IdServicio, Fecha, Hora, Estado, Notificacion) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        error_log("Error preparando la consulta: " . $conn->error);
        return false;
    }

    $stmt->bind_param(
        "iissss", 
        $this->idUsuario, 
        $this->idServicio, 
        $this->fecha, 
        $this->hora, 
        $estado,
        $notificacion
    );

    $resultado = $stmt->execute();

    if (!$resultado) {
        error_log("Error ejecutando la consulta: " . $stmt->error);
    }

    $stmt->close();
    return $resultado;
}


    public static function estaDisponible($conn, $idServicio, $fecha, $hora, $duracion)
    {
        $stmt = $conn->prepare("
            SELECT c.Hora, s.Duracion
            FROM Contrata c
            INNER JOIN Servicio s ON c.IdServicio = s.IdServicio
            WHERE c.IdServicio = ? AND c.Fecha = ?
        ");
        $stmt->bind_param("is", $idServicio, $fecha);
        $stmt->execute();
        $resultado = $stmt->get_result();

        [$h, $m] = array_map('intval', explode(':', $hora));
        $inicioNuevo = $h * 60 + $m;
        $finNuevo = $inicioNuevo + $duracion * 60;

        while ($row = $resultado->fetch_assoc()) {
            [$ch, $cm] = array_map('intval', explode(':', $row['Hora']));
            $inicioExistente = $ch * 60 + $cm;
            $finExistente = $inicioExistente + (int)$row['Duracion'] * 60;

            if (!($finNuevo <= $inicioExistente || $inicioNuevo >= $finExistente)) {
                return false;
            }
        }

        $stmt->close();
        return true;
    }

    public static function obtenerCitasPorServicio($conn, $idServicio)
    {
        $stmt = $conn->prepare("
            SELECT c.Fecha, c.Hora, s.Duracion, c.Estado
            FROM Contrata c
            INNER JOIN Servicio s ON c.IdServicio = s.IdServicio
            WHERE c.IdServicio = ?
        ");
        $stmt->bind_param("i", $idServicio);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $citas = [];
        while ($row = $resultado->fetch_assoc()) {
            $row['Duracion'] = (int)$row['Duracion'];
            $citas[] = $row;
        }

        $stmt->close();
        return $citas;
    }

    public static function obtenerPorId($conn, $idContrata)
    {
        $stmt = $conn->prepare("SELECT * FROM Contrata WHERE IdCita = ?");
        $stmt->bind_param("i", $idContrata);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        $stmt->close();

        if ($fila) {
            $contrata = new Contrata(
                $fila['IdUsuario'],
                $fila['IdServicio'],
                $fila['IdCita'],
                $fila['Fecha'],
                $fila['Hora'],
                $fila['Calificacion'] ?? null, 
                $fila['Resena'] ?? null,
                $fila['Estado'],
                $fila['Notificacion'] ?? null // <- asignación nueva
            );
            return $contrata;
        }
        return null;
    }

public function actualizarEstado($conn, $nuevoEstado)
{
    $stmt = $conn->prepare("
        UPDATE Contrata
        SET Estado = ?
        WHERE IdCita = ?
    ");
    $stmt->bind_param("si", $nuevoEstado, $this->idCita);
    $resultado = $stmt->execute();
    if ($resultado) $this->estado = $nuevoEstado;
    $stmt->close();
    return $resultado;
}


    

public static function obtenerAgendadosPorEmpresa($conn, $idUsuario)
{
    $stmt = $conn->prepare("
        SELECT 
            c.IdCita, c.Fecha, c.Hora, c.Estado,
            c.Calificacion, c.Resena,          -- <- Asegurate que estén acá
            s.Titulo AS NombreServicio,
            u.Email AS NombreCliente
        FROM Contrata c
        INNER JOIN Servicio s ON c.IdServicio = s.IdServicio
        INNER JOIN Usuario u ON c.IdUsuario = u.IdUsuario
        INNER JOIN Brinda b ON s.IdServicio = b.IdServicio
        WHERE b.IdUsuario = ?
        ORDER BY c.Fecha, c.Hora
    ");

    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $agendados = [];
    while ($row = $resultado->fetch_assoc()) {
        $agendados[] = $row;
    }

    $stmt->close();
    return $agendados;
}



public static function finalizarServiciosVencidos($conn) {
    date_default_timezone_set('America/Montevideo');
    $now = date('Y-m-d H:i:s');

    $sql = "UPDATE Contrata c
            JOIN Servicio s ON c.IdServicio = s.IdServicio
            SET c.Estado = 'Finalizado'
            WHERE c.Estado = 'En proceso'
            AND DATE_ADD(STR_TO_DATE(CONCAT(c.Fecha, ' ', c.Hora), '%Y-%m-%d %H:%i:%s'), INTERVAL s.Duracion HOUR) <= ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Error preparando la consulta: " . $conn->error);
        return 0;
    }

    $stmt->bind_param("s", $now);
    $stmt->execute();

    $afectados = $stmt->affected_rows;
    $stmt->close();

    return $afectados;
}

public static function obtenerPorUsuarioYEstado($conn, $idUsuario, $estado) {
    $stmt = $conn->prepare("SELECT * FROM Contrata WHERE IdUsuario = ? AND Estado = ? ORDER BY Fecha, Hora");
    $stmt->bind_param("is", $idUsuario, $estado);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $contratas = [];
    while ($row = $resultado->fetch_assoc()) {
        $contratas[] = $row;  
    }
    $stmt->close();
    return $contratas;
}

public function guardarCalificacionResena($conn) {
    $sql = "UPDATE contrata SET calificacion = ?, resena = ? WHERE idCita = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $this->calificacion, $this->resena, $this->idCita);
    return $stmt->execute();
}
public static function obtenerFinalizadosConCalificacion($conn, $IdUsuario) {
    $sql = "SELECT c.idCita, c.idServicio, c.fecha, c.hora, c.calificacion, c.resena, 
                   s.titulo,
                   e.nombreEmpresa
            FROM contrata c
            JOIN servicio s ON c.idServicio = s.idServicio
            JOIN brinda b ON s.idServicio = b.idServicio
            JOIN empresa e ON b.idUsuario = e.idUsuario
            WHERE c.idUsuario = ? AND c.estado = 'Finalizado'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $IdUsuario);
    $stmt->execute();
    $result = $stmt->get_result();

    $finalizados = [];
    while ($row = $result->fetch_assoc()) {
        $finalizados[] = $row;
    }
    return $finalizados;
}

 public static function obtenerResenasPorServicio($conn, $idServicio)
    {
        $stmt = $conn->prepare("
            SELECT 
                c.calificacion,
                c.resena,
                u.Email,
                cl.Imagen AS imagen,
                cl.Nombre,
                cl.Apellido
            FROM Contrata c
            INNER JOIN Usuario u ON c.IdUsuario = u.IdUsuario
            INNER JOIN Cliente cl ON u.IdUsuario = cl.IdUsuario
            WHERE c.IdServicio = ? AND c.calificacion IS NOT NULL
            ORDER BY c.Fecha DESC
        ");
        $stmt->bind_param("i", $idServicio);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $resenas = [];
        while ($row = $resultado->fetch_assoc()) {
            $resenas[] = $row;
        }

        $stmt->close();
        return $resenas;
    }

 public static function obtenerNoLeidos($conn)
    {
        $sql = "SELECT IdServicio, IdUsuario FROM contrata WHERE notificacion = 'no_leido'";
        $resultado = $conn->query($sql);
        if (!$resultado) return [];
        $contratos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $contratos[] = [
                'idServicio' => $fila['IdServicio'],
                'idUsuario' => $fila['IdUsuario']
            ];
        }
        return $contratos;
    }

    public static function marcarComoLeido($conn, $idEmpresa)
{
    $sql = "UPDATE Contrata c
            JOIN Brinda b ON c.idServicio = b.idServicio
            SET c.notificacion = 'leido'
            WHERE b.idUsuario = ? AND c.notificacion = 'no_leido'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idEmpresa);
    $stmt->execute();
    $stmt->close();
}

}


