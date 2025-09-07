<?php
class Telefono {
    private $idUsuario;
    private $telefono;

    public function __construct($idUsuario, $telefono) {
        $this->idUsuario = $idUsuario;
        $this->telefono = $telefono;
    }

    // getter
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    // setter
    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }
}
?>
