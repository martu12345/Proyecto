<?php
class Telefono
{
    private $idUsuario;
    private $telefono;

    public function __construct($idUsuario, $telefono)
    {
        $this->idUsuario = $conn->insert_id;
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
}
