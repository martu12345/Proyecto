<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/brinda.php');

// Obtenemos los servicios que brinda la empresa
$servicios = Brinda::obtenerServiciosPorEmpresa($conn, $idUsuario);
?>

<div class="lista-servicios">
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

<!-- Incluir tu modal existente -->
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/modal_servicio.php'; ?>
<link rel="stylesheet" href="/Proyecto/public/css/layout/modal_servicio.css">

<!-- JS para abrir modal y llenar campos -->
<script>
// Referencias al modal
const modal = document.getElementById('modalServicio');
const cerrar = modal.querySelector('.cerrar');
const modalTitulo = modal.querySelector('h2'); // tu <h2> que dice "Crear Servicio"

// Abrir modal para editar servicio
document.querySelectorAll('.btn-editar').forEach(btn => {
    btn.addEventListener('click', () => {
        modal.style.display = 'block';
        modalTitulo.textContent = 'Editar Servicio';

        // Llenar campos del formulario
        document.getElementById('idServicio').value = btn.dataset.id;
        document.getElementById('titulo').value = btn.dataset.titulo;
        document.getElementById('categoria').value = btn.dataset.categoria;
        document.getElementById('departamento').value = btn.dataset.departamento;
        document.getElementById('precio').value = btn.dataset.precio;
        document.getElementById('descripcion').value = btn.dataset.descripcion;

        // Preview de la imagen
        if (btn.dataset.imagen) {
            document.getElementById('previewImagen').src = '/Proyecto/public/imagen/servicios/' + btn.dataset.imagen;
        } else {
            document.getElementById('previewImagen').src = '';
        }
    });
});

// Cerrar modal 
cerrar.addEventListener('click', () => modal.style.display = 'none');
window.addEventListener('click', e => {
    if (e.target === modal) modal.style.display = 'none';
});


</script>
