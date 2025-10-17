<?php
class Usuario
{
    protected $idUsuario;
    private $email;
    private $contrasena;

    public function __construct($idUsuario, $email, $contrasena)
    {
        $this->idUsuario = $idUsuario;
        $this->email = $email;
        if ($contrasena !== null) {
            $this->contrasena = password_hash($contrasena, PASSWORD_BCRYPT);
        } else {
            $this->contrasena = null;
        }
    }

    // getters
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function getEmail()
    {
        return $this->email;
    }


    // setters
    public function setIdUsuario($id)
    {
        $this->idUsuario = $id;
    }


    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setContrasena($contrasena)
    {
        $this->contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
    }

    public function verificarContrasena($contrasenaIngresada)
    {
        return password_verify($contrasenaIngresada, $this->contrasena);
    }

    public function guardar($conn)
    {
        $stmt = $conn->prepare("INSERT INTO usuario (Email, Contraseña) VALUES (?, ?)");
        if (!$stmt) {
            die("Error prepare usuarios: " . $conn->error);
        }

        $stmt->bind_param("ss", $this->email, $this->contrasena);

        if (!$stmt->execute()) {
            die("Error execute usuarios: " . $stmt->error);
        }

        $this->idUsuario = $conn->insert_id;

        $stmt->close();
        return true;
    }


    public static function buscarPorEmail($conn, $email)
    {
        $stmt = $conn->prepare("SELECT * FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        if ($resultado) {
            $usuario = new Usuario(
                $resultado['IdUsuario'],
                $resultado['email'],
                $resultado['contraseña']
            );
            $usuario->contrasena = $resultado['contraseña'];
            return $usuario;
        }
        return null;
    }

    public static function existeEmail($conn, $email)
    {
        $stmt = $conn->prepare("SELECT IdUsuario FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function actualizarContrasena($conn, $nuevaContrasena)
    {
        $hash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuario SET Contraseña = ? WHERE IdUsuario = ?");
        if (!$stmt) die("Error prepare update contrasena: " . $conn->error);
        $stmt->bind_param("si", $hash, $this->idUsuario);
        $resultado = $stmt->execute();
        $stmt->close();
        if ($resultado) {
            $this->contrasena = $hash;
        }
        return $resultado;
    }
}
