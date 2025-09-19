<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Empresa.php');

$idRaw = $_POST['IdServicio'] ?? null;
$servicio = null;
$empresa = null;
$errorMessage = null;

// Validar que se reciba el ID por POST
if ($idRaw === null) {
    $errorMessage = "No se recibió el Id del servicio. Asegurate de llamar a este controlador desde el formulario.";
} else {
    $id = intval($idRaw);

    if ($id <= 0) {
        $errorMessage = "Id de servicio inválido.";
    } else {
        // Obtener servicio
        $servicio = Servicio::obtenerPorId($conn, $id);

        if (!$servicio) {
            $errorMessage = "No se encontró el servicio con Id {$id}.";
        } else {
            // Obtener la empresa que brinda este servicio
            $stmt = $conn->prepare("
                SELECT e.IdUsuario, e.NombreEmpresa, e.Calle, e.Numero, e.Imagen
                FROM brinda b
                JOIN empresa e ON b.IdUsuario = e.IdUsuario
                WHERE b.IdServicio = ?
            ");
            if (!$stmt) die("Error prepare empresa: " . $conn->error);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $empresa = $resultado->fetch_assoc();
            $stmt->close();
        }
    }
}

// Llamar a la vista
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/servicio/DetallesServicio.php');
exit;
