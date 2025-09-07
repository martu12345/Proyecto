<?php
require_once('modeloUsuario.php');

class Cliente extends Usuario {
    private $nombre;
    private $apellido;

    public function __construct($idUsuario, $email, $contraseña, $telefono, $nombre, $apellido) {
        parent::__construct($idUsuario, $email, $contraseña, $telefono);
        $this->nombre = $nombre;
        $this->apellido = $apellido;
    }

    // getters
    public function getNombre() { return $this->nombre; }
    public function getApellido() { return $this->apellido; }

    // setters
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellido($apellido) { $this->apellido = $apellido; }

    // guardar cliente en la base
    public function guardarCliente($conn) {
        if(parent::guardar($conn)) {
            $this->idUsuario = $conn->insert_id;
            $sql = "INSERT INTO cliente (idUsuario, nombre, apellido) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $this->idUsuario, $this->nombre, $this->apellido);
            return $stmt->execute();
        } else {
            return false;
        }
    }
}
?>
