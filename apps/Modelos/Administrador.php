<?php
require_once('Usuario.php');
require_once('Telefono.php');

class Administrador extends Usuario
{
    public function __construct($idUsuario, $email, $contrasena)
    {
        parent::__construct($idUsuario, $email, $contrasena);
    }

    public function guardarAdministrador($conn, $idPropietario, $telefono = null)
    {
        if (parent::guardar($conn)) {
            $this->idUsuario = $conn->insert_id;

            $sql = "INSERT INTO administrador (idUsuarioAdministrador, idUsuarioPropietario) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $this->idUsuario, $idPropietario);

            if (!$stmt->execute()) {
                echo "Error insertando administrador: " . $stmt->error;
                return false;
            }

            if ($telefono !== null) {
                Telefono::insertarTelefono($conn, $this->idUsuario, $telefono);
            }

            return true;
        } else {
            return false;
        }
    }





    public static function darDeBajaAdministrador($conn, $email)
    {
        $sql = "SELECT IdUsuario FROM usuario WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return false;
        }

        $idUsuario = $result->fetch_assoc()['IdUsuario'];

        $sql = "DELETE FROM telefono WHERE IdUsuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        $sql = "DELETE FROM administrador WHERE IdPropietario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        $sql = "DELETE FROM usuario WHERE IdUsuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        return $stmt->execute();
    }
}
