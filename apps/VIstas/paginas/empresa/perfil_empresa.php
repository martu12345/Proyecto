<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/controlador/empresa/PerfilEmpresaControlador.php');

$seccion = $_GET['seccion'] ?? 'perfil';
if ($seccion === "null") {
    $seccion = 'perfil';
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manitas - Perfil Empresa</title>
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/empresa/perfil_empresa.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/empresa/perfilsecciones/servicios.css">
</head>
<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

<div class="perfil-container">
    <div class="perfil-box">

        <div class="perfil-opciones">
            <div class="foto-circulo">
                <?php if(!empty($datos['Imagen'])): ?>
                    <img src="/Proyecto/public/imagen/empresas/<?php echo htmlspecialchars($datos['Imagen']); ?>" class="foto-perfil" alt="Foto Empresa">
                <?php else: ?>
                    +
                <?php endif; ?>
            </div>

            <form id="formFoto" action="/Proyecto/apps/controlador/empresa/EditarFotoEmpresaControlador.php" method="POST" enctype="multipart/form-data" style="display:none;">
                <input type="file" id="fotoInput" name="imagen" accept="image/*">
            </form>

            <div class="nombre-usuario" id="nombreColumna">
                <?php echo htmlspecialchars($datos['NombreEmpresa']); ?>
            </div>

           <div class="opciones-lista">
    <a href="?seccion=perfil" data-seccion="perfil" class="opcion <?php echo $seccion=='perfil' ? 'activa' : ''; ?>">Mi perfil</a>
    <a href="?seccion=mensajes" data-seccion="mensajes" class="opcion <?php echo $seccion=='mensajes' ? 'activa' : ''; ?>">Mensajes</a>
    <a href="?seccion=servicios" data-seccion="servicios" class="opcion <?php echo $seccion=='servicios' ? 'activa' : ''; ?>">Servicios</a>
</div>

        </div>

        <div class="perfil-info" id="contenido-seccion">
            <?php
            // Cargar la sección correspondiente
            switch($seccion){
                case 'perfil':
                    include $_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Vistas/paginas/empresa/PerfilSecciones/datos.php';
                    break;
                case 'mensajes':
                    include $_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Vistas/paginas/empresa/PerfilSecciones/mensajes.php';
                    break;
                case 'servicios':
                    include $_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Vistas/paginas/empresa/PerfilSecciones/servicios.php';
                    break;
                default:
                    echo "<p>Sección no encontrada</p>";
            }
            ?>
        </div>

    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>

<script src="/Proyecto/public/js/empresa/perfil_empresa.js"></script>
</body>
</html>
