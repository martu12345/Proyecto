<?php
session_start(); 
$usuario_id = $_SESSION['idUsuario'] ?? null; 
$hasServicio = (isset($servicio) && is_object($servicio));
$mensajeError = $errorMessage ?? (!$hasServicio ? 'No se encontró el servicio solicitado.' : null);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Servicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/servicio/DetallesServicio.css">
</head>

<body>
    
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

    <div class="detalle-servicio-container">
        <?php if ($mensajeError): ?>
            <div class="mensaje-error"><?= htmlspecialchars($mensajeError) ?></div>
            <p>Probá volver atrás o buscá el servicio nuevamente.</p>
            <a class="boton-volver" href="javascript:history.back()">← Volver</a>
        <?php else: ?>
            <div class="detalle-servicio">
                <div class="imagen-contenedor">
                    <?php
                    $imagen = $servicio->getImagen();
                    $ruta = "/Proyecto/public/imagen/servicios/$imagen";
                    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $ruta)) {
                        $ruta = "/Proyecto/public/imagen/servicios/placeholder.png";
                    }
                    ?>
                    <img src="<?= $ruta ?>" alt="<?= htmlspecialchars($servicio->getTitulo()) ?>" style="display:block; margin:0 auto;">
                </div>

                <div class="info-contenedor">
                    <h2><?= htmlspecialchars($servicio->getTitulo()) ?></h2>
                    <p class="precio">$<?= number_format($servicio->getPrecio(), 0, '', '.') ?></p>
                    <p><strong>Categoría:</strong> <?= htmlspecialchars($servicio->getCategoria()) ?></p>
                    <p><strong>Departamento:</strong> <?= htmlspecialchars($servicio->getDepartamento()) ?></p>
                    <p><strong>Descripción:</strong></p>
                    <p><?= nl2br(htmlspecialchars($servicio->getDescripcion())) ?></p>

                    <?php if (!empty($empresa)): ?>
                        <p><strong>Empresa:</strong> <?= htmlspecialchars($empresa['NombreEmpresa']) ?></p>
                    <?php else: ?>
                        <p><strong>Empresa:</strong> No disponible</p>
                    <?php endif; ?>

                    <div class="botones-servicio">
                    <a href="#" class="boton-servicio mensaje" title="Mensaje">
                  <img src="/Proyecto/public/imagen/icono/icono_mensaje.png" alt="Mensaje">
                    </a>


                        <a href="#" class="boton-servicio agendar" title="Agendar"><img src="/Proyecto/public/imagen/icono/icono_mas.png" alt="Agendar"></a>
                        <a href="javascript:history.back()" class="boton-servicio volver" title="Volver"><img src="/Proyecto/public/imagen/icono/icono_volver.png" alt="Volver"></a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
 <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/VIstas/layout/modal_mensaje.php'; ?>
<link rel="stylesheet" href="/Proyecto/public/css/layout/modal_mensaje.css">
<script src="/Proyecto/public/js/mensaje/modal_mensaje.js"></script>


    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>

</body>

</html>