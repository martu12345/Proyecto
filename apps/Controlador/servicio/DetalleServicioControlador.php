<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/servicio.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/brinda.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php';

class DetalleServicioController {
    public function obtenerServicio($idServicio) {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM Servicio WHERE idServicio = ?");
        $stmt->bind_param("i", $idServicio);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        $datos = $result->fetch_assoc();

        // Empresa que brinda
        $stmt2 = $conn->prepare("
            SELECT u.nombre AS empresa 
            FROM Brinda b 
            JOIN Usuario u ON b.idUsuario = u.idUsuario 
            WHERE b.idServicio = ?
        ");
        $stmt2->bind_param("i", $idServicio);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $empresa = ($result2->num_rows > 0) ? $result2->fetch_assoc()['empresa'] : 'Desconocida';

        $datos['empresa'] = $empresa;
        return $datos;
    }
}

// Uso con POST
if (!isset($_POST['idServicio'])) {
    die("Servicio no especificado");
}

$controller = new DetalleServicioController();
$servicio = $controller->obtenerServicio(intval($_POST['idServicio']));

if (!$servicio) {
    die("Servicio no encontrado");
}
?>
