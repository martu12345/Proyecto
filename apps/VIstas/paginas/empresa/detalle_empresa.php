<?php
$usuario_id = $_SESSION['idUsuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de la Empresa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/empresa/Detalle_Empresa.css">
</head>
<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

<div class="detalle-empresa-container">
    <?php if ($mensajeError): ?>
        <div class="mensaje-error"><?= htmlspecialchars($mensajeError) ?></div>
        <a class="boton-volver" href="/Proyecto/apps/vistas/paginas/servicio/listaServicios.php">← Volver</a>
    <?php else: ?>
        <div class="detalle-empresa">
            <div class="imagen-contenedor">
                <?php
                $imagen = $empresa->getImagen();
                $ruta = "/Proyecto/public/imagen/empresas/$imagen";
                if (!$imagen || !file_exists($_SERVER['DOCUMENT_ROOT'] . $ruta)) {
                    $ruta = "/Proyecto/public/imagen/empresas/placeholder.png";
                }
                ?>
                <img src="<?= $ruta ?>" alt="<?= htmlspecialchars($empresa->getNombreEmpresa()) ?>">
            </div>
            <div class="info-contenedor">
                <h2><?= htmlspecialchars($empresa->getNombreEmpresa()) ?></h2>
                <p><strong>Email:</strong> <?= htmlspecialchars($empresa->getEmail()) ?></p>
                <p><strong>Dirección:</strong> <?= htmlspecialchars($empresa->getCalle()) ?> <?= htmlspecialchars($empresa->getNumero()) ?></p>

                <div class="botones-empresa">
                    <?php if (!empty($idServicio)): ?>
                        <a href="/Proyecto/apps/controlador/servicio/DetallesServicioControlador.php?id=<?= $idServicio ?>" class="boton-volver">
                            <img src="/Proyecto/public/imagen/icono/icono_volver.png" alt="Volver">
                        </a>
                    <?php else: ?>
                        <a href="/Proyecto/apps/vistas/paginas/servicio/listaServicios.php" class="boton-volver">
                            <img src="/Proyecto/public/imagen/icono/icono_volver.png" alt="Volver">
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>
</body>
</html>
