<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/brinda.php');

$servicios = Brinda::obtenerServiciosPorEmpresa($conn, $idUsuario);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manitas - Perfil Empresa</title>
    
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/empresa/perfilsecciones/servicios.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/modal_servicio.css">

</head>

<body>
<div class="seccion-servicios">
    <h2>Mis servicios</h2>

    <div class="lista-servicios">
        <?php if (empty($servicios)): ?>
            <p>La empresa no tiene servicios registrados.</p>
        <?php else: ?>
            <?php foreach ($servicios as $servicio): ?>
                <div class="servicio">
                    <?php $imagen = $servicio->getImagen(); ?>
                    <div class="imagen-servicio-contenedor">
                        <?php if (!empty($imagen)): ?>
                            <img src="/Proyecto/public/imagen/servicios/<?= htmlspecialchars($imagen) ?>"
                                alt="<?= htmlspecialchars($servicio->getTitulo()) ?>" class="imagen-servicio">
                        <?php endif; ?>
                    </div>

                    <div class="info-servicio">
                        <div class="texto-servicio">
                            <h3 class="nombre-servicio"><?= htmlspecialchars($servicio->getTitulo()) ?></h3>
                            <p class="descripcion-servicio"><?= htmlspecialchars(substr($servicio->getDescripcion(), 0, 100)) ?>...</p>
                            <p class="categoria-servicio"><?= htmlspecialchars($servicio->getCategoria()) ?></p>
                            <p class="precio-servicio">$<?= htmlspecialchars($servicio->getPrecio()) ?></p>
                        </div>

                        <div class="botones-servicio">
                            <button 
                                class="btn-editar"
                                data-id="<?= $servicio->getIdServicio() ?>"
                                data-titulo="<?= htmlspecialchars($servicio->getTitulo(), ENT_QUOTES) ?>"
                                data-categoria="<?= htmlspecialchars($servicio->getCategoria(), ENT_QUOTES) ?>"
                                data-departamento="<?= htmlspecialchars($servicio->getDepartamento(), ENT_QUOTES) ?>"
                                data-precio="<?= htmlspecialchars($servicio->getPrecio(), ENT_QUOTES) ?>"
                                data-descripcion="<?= htmlspecialchars($servicio->getDescripcion(), ENT_QUOTES) ?>"
                                data-imagen="<?= htmlspecialchars($servicio->getImagen(), ENT_QUOTES) ?>"
                                data-duracion="<?= htmlspecialchars((string)$servicio->getDuracion(), ENT_QUOTES) ?>"
                            >
                                Editar
                            </button>

                            <button 
                                class="btn-eliminar-empresa"
                                data-id="<?= $servicio->getIdServicio() ?>"
                            >
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Modal de servicio -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/modal_servicio.php'; ?>

    <!-- Modal de eliminación -->
    <div id="modalEliminarEmpresa" class="modal">
        <div class="modal-content">
            <p>¿Seguro querés eliminar este servicio?</p>
            <div class="modal-buttons">
                <button id="confirmDeleteEmpresa" class="btn-confirm">✔</button>
                <button id="cancelDeleteEmpresa" class="btn-cancel">X</button>
            </div>
            <div id="mensajeModalEmpresa" class="mensaje-modal" style="display:none;"></div>
        </div>
    </div>
</div>

<script src="/Proyecto/public/js/servicio/editar_servicios.js"></script>
<script defer src="/Proyecto/public/js/empresa/eliminar_servicios.js"></script>
</body>
</html>
