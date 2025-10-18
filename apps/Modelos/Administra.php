<?php
class Administra
{
    private $idAdministra;
    private $idServicio;
    private $idUsuario;
    private $fecha;

    public function __construct($idServicio, $idUsuario, $fecha = null, $idAdministra = null)
    {
        $this->idAdministra = $idAdministra;
        $this->idServicio = $idServicio;
        $this->idUsuario = $idUsuario;
        $this->fecha = $fecha;
    }

    // Getters
    public function getIdAdministra()
    {
        return $this->idAdministra;
    }
    public function getIdServicio()
    {
        return $this->idServicio;
    }
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }
    public function getFecha()
    {
        return $this->fecha;
    }

    // Guardar nuevo registro
    public function guardar($conn)
    {
        $sql = "INSERT INTO administra (idServicio, idUsuario, fecha) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Error prepare Administra: " . $conn->error);

        $stmt->bind_param("iis", $this->idServicio, $this->idUsuario, $this->fecha);
        if (!$stmt->execute()) die("Error execute Administra: " . $stmt->error);

        $this->idAdministra = $conn->insert_id; // guarda el nuevo ID generado
        return true;
    }

    // Obtener todos los registros
    public static function obtenerTodos($conn)
    {
        $sql = "SELECT 
                    a.idAdministra, 
                    u.IdUsuario, 
                    u.email, 
                    s.Titulo AS servicio, 
                    a.fecha
                FROM administra a
                LEFT JOIN usuario u ON a.idUsuario = u.IdUsuario
                LEFT JOIN Servicio s ON a.idServicio = s.IdServicio
                ORDER BY a.fecha DESC";

        $result = $conn->query($sql);
        if (!$result) die("Error en consulta Administra: " . $conn->error);

        $cambios = [];
        while ($row = $result->fetch_assoc()) {
            $cambios[] = [
                'idAdministra' => $row['idAdministra'],
                'idUsuario' => $row['IdUsuario'],
                'email' => $row['email'],
                'servicio' => $row['servicio'],
                'fecha' => $row['fecha']
            ];
        }

        return $cambios;
    }
}