<?php
class Telefono
{
    private $idUsuario;
    private $telefono;

    public function __construct($idUsuario, $telefono)
    {
        $this->idUsuario = $idUsuario; // acá solo guardamos el valor
        $this->telefono = $telefono;
    }

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    // Inserta un teléfono en la base de datos
    public static function insertarTelefono($conn, $idUsuario, $telefono)
    {
        $stmt = $conn->prepare("INSERT INTO telefono (IdUsuario, telefono) VALUES (?, ?)");
        $stmt->bind_param("is", $idUsuario, $telefono);
        if (!$stmt->execute()) {
            echo "Error al insertar teléfono: " . $conn->error;
            return false;
        }
        return true;
    }

    // Nuevo método: obtiene todos los teléfonos de un usuario
    public static function obtenerPorUsuario($conn, $idUsuario)
    {
        $stmt = $conn->prepare("SELECT telefono FROM telefono WHERE IdUsuario = ?");
        if (!$stmt) die("Error prepare Telefono: " . $conn->error);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $telefonos = [];
        while ($fila = $res->fetch_assoc()) {
            $telefonos[] = $fila['telefono'];
        }
        $stmt->close();
        return $telefonos; // array de strings con los teléfonos
    }
}
