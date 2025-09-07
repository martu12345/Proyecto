<?php
class Usuario {
    protected $idUsuario; // pk
    private $email;
    private $contrasena;
    private $telefono;

    public function __construct($idUsuario, $email, $contrasena) {
        $this->idUsuario = $idUsuario;
        $this->email = $email;
        $this->contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

    }

    // getters
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function getEmail() {
        return $this->email;
    }


    // setters
        public function setIdUsuario($id) {
        $this->idUsuario = $id;
    }


    public function setEmail($email) {
        $this->email = $email;
    }

    public function setContrasena($contrasena) { 
        $this->contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
    }

    // verificación de contraseña
    public function verificarContrasena($contrasenaIngresada) { 
        return password_verify($contrasenaIngresada, $this->contrasena); 
    }

    /* guardar en la base de datos
    public function guardar($conn) {
        $stmt = $conn->prepare("INSERT INTO usuarios (Email, Contraseña, Telefono) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $this->email, $this->contrasena, $this->telefono);
        return $stmt->execute();
    }
*/
public function guardar($conn) {
    // Preparar la consulta
    $stmt = $conn->prepare("INSERT INTO usuario (Email, Contraseña) VALUES (?, ?)");
    if (!$stmt) {
        die("Error prepare usuarios: " . $conn->error);
    }

    // Asignar parámetros
    $stmt->bind_param("ss", $this->email, $this->contrasena);

    // Ejecutar y verificar
    if (!$stmt->execute()) {
        die("Error execute usuarios: " . $stmt->error);
    }

    // Guardar el id generado automáticamente
    $this->idUsuario = $conn->insert_id;

    $stmt->close();
    return true;
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
            );
            $usuario->contrasena = $resultado['contraseña']; // mantener hash
            return $usuario;
        }
        return null;
    }
}
?>
