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

public static function darDeBajaAdministrador($conn, $email)
{
    // 1️⃣ Buscamos el IdUsuario según el email
    $sql = "SELECT IdUsuario FROM usuario WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return false;

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return false; // no existe ese email
    }

    $idUsuario = $result->fetch_assoc()['IdUsuario'];

    // 2️⃣ Borramos los teléfonos del usuario (si los hay)
    $sql = "DELETE FROM telefono WHERE IdUsuario = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return false;
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();

    // 3️⃣ Borramos al administrador
    $sql = "DELETE FROM administrador WHERE IdUsuario = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return false;
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();

    // 4️⃣ Borramos al usuario
    $sql = "DELETE FROM usuario WHERE IdUsuario = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return false;
    $stmt->bind_param("i", $idUsuario);
    return $stmt->execute();
}

}
