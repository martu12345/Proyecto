<?php
class Brinda
{
    private $idServicio;
    private $idUsuario;
    private $notificacion; // Nuevo atributo

    public function __construct($idServicio, $idUsuario, $notificacion = null)
    {
        $this->idServicio = $idServicio;
        $this->idUsuario = $idUsuario;
        $this->notificacion = $notificacion; // inicializamos
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

    public function getNotificacion()
    {
        return $this->notificacion;
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

    public function setNotificacion($notificacion)
    {
        $this->notificacion = $notificacion;
    }

    // Método para guardar en la BD
    public function guardar($conn)
    {
        $stmt = $conn->prepare("INSERT INTO Brinda (IdServicio, IdUsuario, notificacion) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Error prepare Brinda: " . $conn->error);
        }
        $stmt->bind_param("iis", $this->idServicio, $this->idUsuario, $this->notificacion);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public static function obtenerIdEmpresaPorServicio($conn, $idServicio)
    {
        $stmt = $conn->prepare("SELECT IdUsuario FROM brinda WHERE IdServicio = ?");
        if (!$stmt) die("Error prepare Brinda: " . $conn->error);
        $stmt->bind_param("i", $idServicio);
        $stmt->execute();
        $res = $stmt->get_result();
        $fila = $res->fetch_assoc();
        $stmt->close();
        return $fila['IdUsuario'] ?? null;
    }

    // Obtener servicios de una empresa
    public static function obtenerServiciosPorEmpresa($conn, $idUsuario)
    {
        $sql = "SELECT s.*
                FROM Servicio s
                INNER JOIN Brinda b ON s.IdServicio = b.IdServicio
                WHERE b.IdUsuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Error prepare obtenerServiciosPorEmpresa: " . $conn->error);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $servicios = [];
        while ($fila = $resultado->fetch_assoc()) {
            $servicios[] = new Servicio(
                $fila['IdServicio'],
                $fila['Titulo'],
                $fila['Categoria'],
                $fila['Descripcion'],
                $fila['Precio'],
                $fila['departamento'],
                $fila['imagen']
            );
        }
        $stmt->close();
        return $servicios;
    }
}
