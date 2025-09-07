<?php
// Comando para que mysql permita crear tablas de seervicio sin el idempresa mientras no tenemos el registro
// ALTER TABLE Servicio MODIFY COLUMN IdUsuarioEmpresa INT NULL;

// Estos dos comandos son para restaurar la base de datos a como tiene que ser originalmente
// ALTER TABLE Servicio 
// MODIFY COLUMN IdUsuarioEmpresa INT NOT NULL;


require_once '../modelos/modeloServicio.php';
require_once '../modelos/conexion.php'; 


ini_set('display_errors', 1);
error_reporting(E_ALL);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Recibir datos del formulario
    $titulo = $_POST['titulo'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $disponibilidad = 1; 

    // 2. Procesar imagen
    $imagenNombre = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $carpetaDestino = '../../public/imagenes/servicios/'; 
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
        $disponibilidad,
        $imagenNombre
    );

    // 4. Guardar en BD
    if ($servicio->guardar($conn)) {
        echo json_encode(['success' => true, 'mensaje' => 'Servicio creado correctamente']);
    }
}
?>
