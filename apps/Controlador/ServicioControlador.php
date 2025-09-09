<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Recibir datos del formulario
    $titulo = $_POST['titulo'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $departamento = $_POST['departamento'] ?? null; // nuevo campo
    $disponibilidad = null; // DB pone default 1 si no se pasa

    if (!$departamento) {
        die(json_encode(['success' => false, 'mensaje' => 'Debe seleccionar un departamento']));
    }

    // 2. Procesar imagen
    $imagenNombre = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $carpetaDestino = '../../public/imagen/servicios/';
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
        $departamento,   // ahora obligatorio
        $disponibilidad,
        $imagenNombre
    );

    // 4. Guardar en BD
    if ($servicio->guardar($conn)) {
        if ($servicio->guardar($conn)) {
            header('Location: /Proyecto/apps/vistas/paginas/empresa/perfil_empresa.php'); // por ejemplo
            exit;
        }
    }
}
