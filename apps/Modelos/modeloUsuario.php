<?php
class Usuario {
    private $idUsuario; // pk
    private $email;
    private $contrasena;
    private $telefono;

    public function __construct($idUsuario, $email, $contrasena, $telefono) {
        $this->idUsuario = $idUsuario;
        $this->email = $email;
        $this->contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        $this->telefono = $telefono;
    }

    // getters
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    // setters
    public function setEmail($email) {
        $this->email = $email;
    }

    public function setContrasena($contrasena) { 
        $this->contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
    }

    public function setTelefono($telefono) { 
        $this->telefono = $telefono; 
    }

    // verificación de contraseña
    public function verificarContrasena($contrasenaIngresada) { 
        return password_verify($contrasenaIngresada, $this->contrasena); 
    }

    // guardar en la base de datos
    public function guardar($conn) {
        $stmt = $conn->prepare("INSERT INTO usuarios (Email, Contraseña, Telefono) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $this->email, $this->contrasena, $this->telefono);
        return $stmt->execute();
    }

    // buscar usuario por email
    public static function buscarPorEmail($conn, $email) {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("sss", $email);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        if($resultado) {
            $usuario = new Usuario(
                $resultado['idUsuario'], 
                $resultado['email'], 
                $resultado['contraseña'], 
                $resultado['telefono']
            );
            $usuario->contrasena = $resultado['contraseña']; // mantener hash
            return $usuario;
        }
        return null;
    }
}
?>
