<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/brinda.php');

// Obtenemos los servicios que brinda la empresa
$servicios = Brinda::obtenerServiciosPorEmpresa($conn, $idUsuario);
?>

<?php if (empty($servicios)): ?>
    <p>La empresa no tiene servicios registrados.</p>
<?php else: ?>
    <?php foreach ($servicios as $servicio): ?>
        <div class="servicio">
            <?php $imagen = $servicio->getImagen(); ?>
            <?php if (!empty($imagen)): ?>
                <img src="/Proyecto/public/imagen/servicios/<?= htmlspecialchars($imagen) ?>"
                    alt="<?= htmlspecialchars($servicio->getTitulo()) ?>" class="imagen-servicio">
            <?php else: ?>
                <img src="/Proyecto/public/imagen/servicios/placeholder.png" alt="Sin imagen" class="imagen-servicio">
            <?php endif; ?>

            <div class="info-servicio">
                <div class="texto-servicio">
                    <h3 class="nombre-servicio"><?= htmlspecialchars($servicio->getTitulo()) ?></h3>
                    <p class="descripcion-servicio"><?= htmlspecialchars(substr($servicio->getDescripcion(), 0, 100)) ?>...</p>
                    <p class="categoria-servicio"><?= htmlspecialchars($servicio->getCategoria()) ?></p>
                    <p class="precio-servicio">$<?= htmlspecialchars($servicio->getPrecio()) ?></p>
                </div>

                <!-- Botón de editar que por ahora no hace nada -->
                <button class="btn-editar">
                    Editar
                </button>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
