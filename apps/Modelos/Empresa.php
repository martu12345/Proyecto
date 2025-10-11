<?php
require_once('Usuario.php');
require_once('Telefono.php');

class Empresa extends Usuario
{
    private $nombreEmpresa;
    private $calle;
    private $numero;
    private $imagen; // atributo para la foto

    public function __construct($idUsuario, $email, $contrasena, $telefono, $nombreEmpresa, $calle, $numero, $imagen = null)
    {
        parent::__construct($idUsuario, $email, $contrasena, $telefono);
        $this->nombreEmpresa = $nombreEmpresa;
        $this->calle = $calle;
        $this->numero = $numero;
        $this->imagen = $imagen;
    }

    // getters y setters
    public function getNombreEmpresa()
    {
        return $this->nombreEmpresa;
    }
    public function getCalle()
    {
        return $this->calle;
    }
    public function getNumero()
    {
        return $this->numero;
    }
    public function getImagen()
    {
        return $this->imagen;
    }
    

    public function setNombreEmpresa($nombreEmpresa)
    {
        $this->nombreEmpresa = $nombreEmpresa;
    }
    public function setCalle($calle)
    {
        $this->calle = $calle;
    }
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }


    public static function obtenerPorId($conn, $idUsuario)
    {
        $stmt = $conn->prepare("
      SELECT u.IdUsuario, u.Email, u.Contraseña,
       e.NombreEmpresa, e.Calle, e.Numero, e.Imagen
FROM empresa e
JOIN usuario u ON e.IdUsuario = u.IdUsuario
WHERE e.IdUsuario = ?

    ");
        if (!$stmt) die("Error prepare Empresa: " . $conn->error);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $datos = $res->fetch_assoc();
        $stmt->close();

        if ($datos) {
            return new Empresa(
                $datos['IdUsuario'],
                $datos['Email'],
                $datos['Contraseña'],
                null, // Teléfono null
                $datos['NombreEmpresa'],
                $datos['Calle'],
                $datos['Numero'],
                $datos['Imagen']
            );
        }
        return null;
    }


    // Actualiza los datos de la empresa (incluyendo imagen)
    public function actualizarEmpresa($conn)
    {
        $sql = "UPDATE empresa SET NombreEmpresa = ?, Calle = ?, Numero = ?, Imagen = ? WHERE IdUsuario = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) die("Error prepare update empresa: " . $conn->error);
        $stmt->bind_param("ssssi", $this->nombreEmpresa, $this->calle, $this->numero, $this->imagen, $this->idUsuario);
        return $stmt->execute();
    }

    // Actualiza solo la imagen
    public function actualizarImagen($conn)
    {
        if (!$this->imagen) return false;
        $stmt = $conn->prepare("UPDATE empresa SET Imagen = ? WHERE IdUsuario = ?");
        $stmt->bind_param("si", $this->imagen, $this->idUsuario);
        return $stmt->execute();
    }

    //guardar empresa en la base y telefono 
    public function guardarEmpresa($conn, $telefono = null)
    {
        if (parent::guardar($conn)) {
            $this->idUsuario = $conn->insert_id;
            $sql = "INSERT INTO empresa (IdUsuario, NombreEmpresa, Calle, Numero) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $this->idUsuario, $this->nombreEmpresa, $this->calle, $this->numero);
            Telefono::insertarTelefono($conn, $this->idUsuario, $telefono);
            return $stmt->execute();
        } else {
            return false;
        }
    }
    // Obtener todas las empresas
public static function obtenerTodos($conn) {
    $sql = "SELECT u.IdUsuario, u.Email, e.NombreEmpresa, e.Calle, e.Numero, e.Imagen
            FROM empresa e
            JOIN usuario u ON e.IdUsuario = u.IdUsuario";
    $res = $conn->query($sql);
    $empresas = [];
    while ($row = $res->fetch_assoc()) {
        // instanciamos Empresa sin pasar password
        $empresa = new Empresa(
            $row['IdUsuario'],
            $row['Email'],
            '',  // pasamos string vacío en vez de null
            null,
            $row['NombreEmpresa'],
            $row['Calle'],
            $row['Numero'],
            $row['Imagen']
        );
        $empresas[] = $empresa;
    }
    return $empresas;
}


// Obtener todos los teléfonos de la empresa
public function obtenerTelefonos($conn) {
    return Telefono::obtenerPorUsuario($conn, $this->idUsuario); 
    // Telefono::obtenerPorUsuario debe devolver un array de strings
}

// Eliminar empresa junto con teléfonos y usuario
public function eliminarEmpresa($conn) {
    // 1. Eliminar teléfonos
    Telefono::eliminarPorUsuario($conn, $this->idUsuario);

    // 2. Eliminar empresa
    $stmt = $conn->prepare("DELETE FROM empresa WHERE IdUsuario = ?");
    $stmt->bind_param("i", $this->idUsuario);
    if (!$stmt->execute()) return false;

    // 3. Eliminar usuario padre
    return parent::eliminar($conn); // Usuario::eliminar() debe existir
}


}
