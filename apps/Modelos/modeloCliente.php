<?php
require_once('modeloUsuario.php');
require_once('modeloTelefono.php');


class Cliente extends Usuario {
    private $nombre;
    private $apellido;

    public function __construct($idUsuario, $email, $contrasena, $nombre, $apellido) {
        parent::__construct($idUsuario, $email, $contrasena);
        $this->nombre = $nombre;
        $this->apellido = $apellido;
    }

    // getters
    public function getNombre() { return $this->nombre; }
    public function getApellido() { return $this->apellido; }

    // setters
    public function setIdUsuario($IdUsuario) {
    $this->idUsuario;
    }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellido($apellido) { $this->apellido = $apellido; }

    //guardar cliente en la base y telefono 
    public function guardarCliente($conn, $telefono = null) {
        if(parent::guardar($conn)) {
            $this->idUsuario = $conn->insert_id;
            $sql = "INSERT INTO cliente (IdUsuario, Nombre, Apellido) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $this->idUsuario, $this->nombre, $this->apellido);
            Telefono::insertarTelefono($conn, $this->idUsuario, $telefono);
             return $stmt->execute();

        } else {
            return false;
        } 
    } 



}
?>
