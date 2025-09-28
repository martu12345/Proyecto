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

    // Recibir datos del formulario
    $idServicio = $_POST['idServicio'] ?? null; // si viene, estamos editando
    $titulo = $_POST['titulo'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $departamento = $_POST['departamento'] ?? null;
    $disponibilidad = null; // por ahora

    if (!$departamento) {
        die(json_encode(['success' => false, 'mensaje' => 'Debe seleccionar un departamento']));
    }

    // Procesar imagen
    $imagenNombre = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $carpetaDestino = $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/public/imagen/servicios/';
        if (!is_dir($carpetaDestino)) mkdir($carpetaDestino, 0755, true);

        $imagenNombre = time() . '_' . basename($_FILES['imagen']['name']);
        $rutaCompleta = $carpetaDestino . $imagenNombre;

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaCompleta)) {
            die("Error al mover la imagen");
        }
    } elseif ($idServicio) {
        // Editando, mantener imagen existente si no suben nueva
        $servicioExistente = Servicio::obtenerPorId($conn, $idServicio);
        $imagenNombre = $servicioExistente ? $servicioExistente->getImagen() : '';
    }

    // Crear objeto Servicio
    $servicio = new Servicio(
        $idServicio, // null → creación, id → edición
        $titulo,
        $categoria,
        $descripcion,
        $precio,
        $departamento,
        $disponibilidad,
        $imagenNombre
    );

    // Guardar o actualizar
    if ($idServicio) {
        // EDITAR
        if ($servicio->actualizar($conn)) {
            header('Location: /Proyecto/apps/vistas/paginas/empresa/perfil_empresa.php?seccion=servicios'); 
            exit;
        } else {
            die("Error al actualizar el servicio");
        }
    } else {
        // CREAR
        if ($servicio->guardar($conn)) {
            $idServicioCreado = $conn->insert_id;

            // Crear relación Brinda
            $brinda = new Brinda($idServicioCreado, $idUsuario);
            if (!$brinda->guardar($conn)) die("Error al guardar la relación Brinda");

            header('Location: /Proyecto/apps/vistas/paginas/empresa/home_empresa.php'); 
            exit;
        } else {
            die("Error al guardar el servicio");
        }
    }
}
?>
