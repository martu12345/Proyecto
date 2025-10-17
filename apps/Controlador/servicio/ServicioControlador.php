<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');

$idUsuario = $_SESSION['idUsuario'] ?? null;
if (!$idUsuario) {
    die("No logueado");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Datos del formulario
    $idServicio = $_POST['idServicio'] ?? null;
    $titulo = $_POST['titulo'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $departamento = $_POST['departamento'] ?? null;
    $duracion = $_POST['duracion'] ?? 0;

    if (!$departamento) {
        die(json_encode(['success' => false, 'mensaje' => 'Debe seleccionar un departamento']));
    }

    // Imagen
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
        // Mantiene la imagen  si no suben una nueva
        $servicioExistente = Servicio::obtenerPorId($conn, $idServicio);
        $imagenNombre = $servicioExistente ? $servicioExistente->getImagen() : '';
    }

    $servicio = new Servicio(
        $idServicio,
        $titulo,
        $categoria,
        $descripcion,
        $precio,
        $departamento,
        $imagenNombre,
        $duracion
    );

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

            $brinda = new Brinda($idServicioCreado, $idUsuario);
            if (!$brinda->guardar($conn)) die("Error al guardar la relación Brinda");

            header('Location: /Proyecto/apps/vistas/paginas/empresa/home_empresa.php');
            exit;
        } else {
            die("Error al guardar el servicio");
        }
    }
}
