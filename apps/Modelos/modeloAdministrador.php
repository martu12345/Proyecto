<?php
class Administrador {
    private $idUsuario;

    public function __construct($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    // Getter
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    // Setter
    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }
}
?>
