<?php
session_start(); // asegurar sesión
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');

$idUsuario = $_SESSION['idUsuario'] ?? null; // empresa logueada
if (!$idUsuario) {
    die("No logueado");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Recibir datos del formulario
    $titulo = $_POST['titulo'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $departamento = $_POST['departamento'] ?? null; 
    $disponibilidad = null; 

    if (!$departamento) {
        die(json_encode(['success' => false, 'mensaje' => 'Debe seleccionar un departamento']));
    }

    // 2. Procesar imagen
    $imagenNombre = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $carpetaDestino = $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/public/imagen/servicios/';
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0755, true);
        }
        $imagenNombre = time() . '_' . basename($_FILES['imagen']['name']);
        $rutaCompleta = $carpetaDestino . $imagenNombre;

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
            die("Error al mover la imagen");
        }
    }

    // 3. Crear objeto Servicio
    $servicio = new Servicio(
        null,
        $titulo,
        $categoria,
        $descripcion,
        $precio,
        $departamento,   
        $disponibilidad,
        $imagenNombre
    );

    // 4. Guardar en BD
    if ($servicio->guardar($conn)) {

        $idServicioCreado = $conn->insert_id;
        $stmt = $conn->prepare("INSERT INTO Brinda (IdServicio, IdUsuario) VALUES (?, ?)");
        if (!$stmt) die("Error prepare Brinda: " . $conn->error);
        $stmt->bind_param("ii", $idServicioCreado, $idUsuario);
        $stmt->execute();
        $stmt->close();

        header('Location: /Proyecto/apps/vistas/paginas/empresa/home_empresa.php'); 
        exit;
    } else {
        die("Error al guardar el servicio");
    }
}
?>
