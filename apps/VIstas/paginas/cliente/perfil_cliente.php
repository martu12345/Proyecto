<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/controlador/cliente/PerfilControlador.php');

$seccion = $_GET['seccion'] ?? 'perfil';
if ($seccion === "null") {
    $seccion = 'perfil';
}
$_SESSION['idCliente'] = $cliente->getIdUsuario();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manitas - Perfil Cliente</title>
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/cliente/perfil_cliente.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/cliente/perfilsecciones/servicios.css">
</head>
<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

<div class="perfil-container">
    <div class="perfil-box">

        <div class="perfil-opciones">
            <!-- Subida de imagen -->
            <form id="formFoto" action="/Proyecto/apps/controlador/cliente/SubirFotoControlador.php" method="POST" enctype="multipart/form-data">
                <label for="fotoInput" class="foto-circulo">
                    <?php if (!empty($datos['Imagen'])): ?>
                        <img src="<?php echo htmlspecialchars($datos['Imagen']); ?>" class="foto-perfil" alt="Foto Cliente">
                    <?php else: ?>
                        +
                    <?php endif; ?>
                </label>
                <input type="file" id="fotoInput" name="imagen" accept="image/*" style="display:none;" onchange="this.form.submit()">
            </form>

            <div class="nombre-usuario" id="nombreColumna">
                <?php echo htmlspecialchars($datos['Nombre'] . ' ' . $datos['Apellido']); ?>
            </div>

            <div class="opciones-lista">
                <a href="?seccion=perfil" data-seccion="perfil" class="opcion <?php echo $seccion == 'perfil' ? 'activa' : ''; ?>">Mi perfil</a>
                <a href="?seccion=mensajes" data-seccion="mensajes" class="opcion <?php echo $seccion == 'mensajes' ? 'activa' : ''; ?>">Mensajes</a>
                <a href="?seccion=servicios" data-seccion="servicios" class="opcion <?php echo $seccion == 'servicios' ? 'activa' : ''; ?>">Servicios</a>
            </div>
        </div>

        <div class="perfil-info" id="contenido-seccion">
            <?php
            switch ($seccion) {
                case 'perfil':
                    include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/cliente/perfilsecciones/datos.php';
                    break;
                case 'mensajes':
                    include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/cliente/perfilsecciones/mensajes.php';
                    break;
                case 'servicios':
                    include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/cliente/perfilsecciones/servicios.php';
                    break;
                default:
                    echo "<p>Sección no encontrada</p>";
            }
            ?>
        </div>

    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>
<script src="/Proyecto/public/js/cliente/perfil_cliente.js"></script>
</body>
</html>
