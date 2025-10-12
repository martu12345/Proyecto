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



      // Método para eliminar empresa + usuario + teléfonos
    public static function eliminarPorId($conn, $idEmpresa) {
        try {
            // Primero eliminar teléfonos asociados
            $stmtTel = $conn->prepare("DELETE FROM telefono WHERE IdUsuario = ?");
            $stmtTel->bind_param("i", $idEmpresa);
            $stmtTel->execute();
            $stmtTel->close();

            // Luego eliminar de la tabla empresa
            $stmtEmp = $conn->prepare("DELETE FROM empresa WHERE IdUsuario = ?");
            $stmtEmp->bind_param("i", $idEmpresa);
            $stmtEmp->execute();
            $stmtEmp->close();

            // Por último, eliminar de la tabla usuario
            $stmtUsu = $conn->prepare("DELETE FROM usuario WHERE IdUsuario = ?");
            $stmtUsu->bind_param("i", $idEmpresa);
            $stmtUsu->execute();
            $stmtUsu->close();

            return true;
        } catch (Exception $e) {
            error_log("Error al eliminar empresa: " . $e->getMessage());
            return false;
        }
    }


// Método para actualizar empresa (nombre, email y teléfono)
public function actualizarDatosEmpresa($conn, $telefono = null) {
    try {
        // Actualizar email en tabla usuario
        $stmt1 = $conn->prepare("UPDATE usuario SET Email = ? WHERE IdUsuario = ?");
        $email = $this->getEmail();
        $stmt1->bind_param("si", $email, $this->idUsuario);
        $stmt1->execute();
        $stmt1->close();

        // Actualizar nombre en tabla empresa
        $stmt2 = $conn->prepare("UPDATE empresa SET NombreEmpresa = ? WHERE IdUsuario = ?");
        $stmt2->bind_param("si", $this->nombreEmpresa, $this->idUsuario);
        $stmt2->execute();
        $stmt2->close();

        // Actualizar teléfono si se pasó
        if ($telefono !== null) {
            require_once('Telefono.php');
            Telefono::actualizarTelefono($conn, $this->idUsuario, $telefono);
        }

        return true;
    } catch (Exception $e) {
        error_log("Error al actualizar empresa: " . $e->getMessage());
        return false;
    }
}


}