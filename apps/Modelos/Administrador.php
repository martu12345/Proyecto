<?php
require_once('Usuario.php');
require_once('Telefono.php');

class Administrador extends Usuario
{

    public function __construct($idUsuario, $email, $contrasena)
    {
        parent::__construct($idUsuario, $email, $contrasena);
    }


        public function guardarAdministrador($conn, $telefono = null)
    {
        if (parent::guardar($conn)) {
            $this->idUsuario = $conn->insert_id;
            $sql = "INSERT INTO administrador (IdUsuario) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $this->idUsuario);
            Telefono::insertarTelefono($conn, $this->idUsuario, $telefono);
            return $stmt->execute();
        } else {
            return false;
        }
    }

}
