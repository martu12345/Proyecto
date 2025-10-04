<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/brinda.php');

$servicios = Brinda::obtenerServiciosPorEmpresa($conn, $idUsuario);
?>
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

                <!-- Botón de editar con data-atributos -->
                <button 
                    class="btn-editar"
                    data-id="<?= $servicio->getIdServicio() ?>"
                    data-titulo="<?= htmlspecialchars($servicio->getTitulo(), ENT_QUOTES) ?>"
                    data-categoria="<?= htmlspecialchars($servicio->getCategoria(), ENT_QUOTES) ?>"
                    data-departamento="<?= htmlspecialchars($servicio->getDepartamento(), ENT_QUOTES) ?>"
                    data-precio="<?= htmlspecialchars($servicio->getPrecio(), ENT_QUOTES) ?>"
                    data-descripcion="<?= htmlspecialchars($servicio->getDescripcion(), ENT_QUOTES) ?>"
                    data-imagen="<?= htmlspecialchars($servicio->getImagen(), ENT_QUOTES) ?>"
                >
                    Editar
                </button>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>

<!-- inlcuimos el modal  -->
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/modal_servicio.php'; ?>
<link rel="stylesheet" href="/Proyecto/public/css/layout/modal_servicio.css">
<script src="/Proyecto/public/js/empresa/editar_servicios.js"></script>
