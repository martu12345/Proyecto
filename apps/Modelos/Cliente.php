<?php
require_once('Usuario.php');
require_once('Telefono.php');

class Cliente extends Usuario
{
    private $nombre;
    private $apellido;
    private $imagen; 
    public function __construct($idUsuario, $email, $contrasena, $nombre, $apellido, $imagen = null)
    {
        parent::__construct($idUsuario, $email, $contrasena);
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->imagen = $imagen;
    }

    // getters
    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    // setters
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    // actualizar datos existentes
    public function actualizarCliente($conn)
    {
        // actualizar  usuario (solo email)
        $stmt1 = $conn->prepare("UPDATE usuario SET Email = ? WHERE IdUsuario = ?");
        if(!$stmt1) die("Error prepare usuario: ".$conn->error);

        $email = $this->getEmail();
        $stmt1->bind_param("si", $email, $this->idUsuario);

        if(!$stmt1->execute()) die("Error update usuario: ".$stmt1->error);
        $stmt1->close();

        // actualizar  cliente (nombre, apellido, imagen)
        $stmt2 = $conn->prepare("UPDATE cliente SET Nombre = ?, Apellido = ?, Imagen = ? WHERE IdUsuario = ?");
        if(!$stmt2) die("Error prepare cliente: ".$conn->error);

        $stmt2->bind_param("sssi", $this->nombre, $this->apellido, $this->imagen, $this->idUsuario);

        if(!$stmt2->execute()) die("Error update cliente: ".$stmt2->error);
        $stmt2->close();

        return true;
    }

    // guardar cliente nuevo
    public function guardarCliente($conn, $telefono = null)
    {
        if (parent::guardar($conn)) {
            $this->idUsuario = $conn->insert_id;
            $sql = "INSERT INTO cliente (IdUsuario, Nombre, Apellido, Imagen) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $this->idUsuario, $this->nombre, $this->apellido, $this->imagen);
            Telefono::insertarTelefono($conn, $this->idUsuario, $telefono);
            return $stmt->execute();
        } else {
            return false;
        }
    }
// Método para obtener todos los teléfonos de un cliente
public function obtenerTelefonos($conn) {
    $telefonos = [];
    $stmt = $conn->prepare("SELECT Telefono FROM telefono WHERE IdUsuario = ?");
    $stmt->bind_param("i", $this->idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $telefonos[] = $row['Telefono'];
    }
    return $telefonos;
}

    // actualizar solo la imagen
    public function actualizarImagen($conn)
    {
        $stmt = $conn->prepare("UPDATE cliente SET Imagen = ? WHERE IdUsuario = ?");
        if(!$stmt) die("Error prepare update imagen: ".$conn->error);

        $stmt->bind_param("si", $this->imagen, $this->idUsuario);
        return $stmt->execute();
    }
public static function obtenerTodos($conn) {
    $stmt = $conn->prepare("
        SELECT c.IdUsuario, c.Nombre, c.Apellido, c.Imagen, u.Email
        FROM cliente c
        INNER JOIN usuario u ON c.IdUsuario = u.IdUsuario
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $clientes = [];
    while ($row = $result->fetch_assoc()) {
$clientes[] = new Cliente(
    $row['IdUsuario'],   // idUsuario
    $row['Email'] ?? null, // email
    null,                 // contrasena (no necesitamos ahora)
    $row['Nombre'],       // nombre
    $row['Apellido'],     // apellido
    $row['Imagen'] ?? null // imagen

        );
    }
    return $clientes;
}
public static function eliminarPorId($conn, $idCliente) {
    try {
        // Primero eliminar teléfonos asociados si los tenés
        $stmtTel = $conn->prepare("DELETE FROM telefono WHERE IdUsuario = ?");
        $stmtTel->bind_param("i", $idCliente);
        $stmtTel->execute();
        $stmtTel->close();

        // Luego eliminar de la tabla cliente
        $stmtCliente = $conn->prepare("DELETE FROM cliente WHERE IdUsuario = ?");
        $stmtCliente->bind_param("i", $idCliente);
        $stmtCliente->execute();
        $stmtCliente->close();

        // Por último, eliminar de la tabla usuario
        $stmtUsuario = $conn->prepare("DELETE FROM usuario WHERE IdUsuario = ?");
        $stmtUsuario->bind_param("i", $idCliente);
        $stmtUsuario->execute();
        $stmtUsuario->close();

        return true;
    } catch (Exception $e) {
        error_log("Error al eliminar cliente: " . $e->getMessage());
        return false;
    }
}



}
