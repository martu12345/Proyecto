<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');

class ServiciosAdminControlador {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Obtener todos los servicios con la empresa asociada
    public function obtenerServiciosConEmpresa() {
        $sql = "SELECT * FROM servicio";
        $resultado = $this->conn->query($sql);
        $servicios = [];

        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $servicio = new Servicio(
                    $row['IdServicio'] ?? null,
                    $row['Titulo'] ?? '',
                    $row['Categoria'] ?? '',
                    $row['Descripcion'] ?? '',
                    $row['Precio'] ?? 0,
                    $row['departamento'] ?? '',
                    $row['imagen'] ?? '',
                    $row['Duracion'] ?? 0
                );

                // Buscar la empresa que brinda ese servicio
                $idEmpresa = Brinda::obtenerIdEmpresaPorServicio($this->conn, $servicio->getIdServicio());
                $empresaObj = $idEmpresa ? Empresa::obtenerPorId($this->conn, $idEmpresa) : null;
                $nombreEmpresa = $empresaObj ? $empresaObj->getNombreEmpresa() : 'Desconocida';

                $servicios[] = [
                    'servicio' => $servicio,
                    'empresa' => $nombreEmpresa
                ];
            }
        }

        return $servicios;
    }

    public function eliminarServicio($idServicio) {
        $sql = "DELETE FROM servicio WHERE IdServicio = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("i", $idServicio);
        return $stmt->execute();
    }

    public function obtenerServicioPorId($idServicio) {
        return Servicio::obtenerPorId($this->conn, $idServicio);
    }

    public function actualizarServicio(Servicio $servicio) {
        return $servicio->actualizar($this->conn);
    }
}
?>
