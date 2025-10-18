<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Administra.php');

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Servicio.php');

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo "Acceso denegado";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['idServicio'] ?? null;
    $titulo = $_POST['titulo'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $departamento = $_POST['departamento'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $descripcion = $_POST['descripcion'] ?? '';
    $duracion = $_POST['duracion'] ?? 0;
    $imagen = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = uniqid() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "_", basename($_FILES['imagen']['name']));
        $rutaDestino = $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/public/imagen/servicios/' . $nombreArchivo;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $imagen = $nombreArchivo;
        }
    }

    $servicio = Servicio::obtenerPorId($conn, $id);
    if (!$servicio) {
        echo "<p>Servicio no encontrado</p>";
        exit;
    }

$idAdmin = $_SESSION['idUsuario'];
$administra = new Administra($id, $idAdmin, date('Y-m-d H:i:s'));
if (!$administra->guardar($conn)) {
    echo "<p>Error al registrar en administra</p>";
    exit;
}


    $servicio->setTitulo($titulo);
    $servicio->setCategoria($categoria);
    $servicio->setDepartamento($departamento);
    $servicio->setPrecio($precio);
    $servicio->setDescripcion($descripcion);
    $servicio->setDuracion($duracion);

    if ($imagen) $servicio->setImagen($imagen);

    // Guardar cambios
    if ($servicio->actualizar($conn)) {
        header("Location: /Proyecto/apps/vistas/paginas/administrador/admin_servicios.php");
        exit;
    } else {
        echo "<p>Error al actualizar el servicio con ID $id</p>";
    }
}
