<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Controlador/administrador/AdministrarServiciosControlador.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Empresa.php');

$controlador = new ServiciosAdminControlador($conn);
$servicios = $controlador->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administrar Servicios - Administrador</title>
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/administrador/admin_servicios.css">
</head>

<body>

<h1>Administrar Servicios</h1>

<?php if (count($servicios) === 0): ?>
    <p>No hay servicios disponibles.</p>
<?php else: ?>
    <ul class="servicios-list">
        <?php foreach ($servicios as $s): ?>
            <?php
                // Obtenemos el id de la empresa que brinda el servicio
                $idEmpresa = Brinda::obtenerIdEmpresaPorServicio($conn, $s->getIdServicio());
                $empresaObj = $idEmpresa ? Empresa::obtenerPorId($conn, $idEmpresa) : null;
                $nombreEmpresa = $empresaObj ? $empresaObj->getNombreEmpresa() : 'Desconocida';
            ?>
            <li class="servicio-item">
                <div class="info">
                    <span class="nombre"><?= htmlspecialchars($s->getTitulo()) ?></span>
                    <span class="descripcion"><?= htmlspecialchars($s->getDescripcion()) ?></span>
                    <span class="empresa">Empresa: <?= htmlspecialchars($nombreEmpresa) ?></span>
                </div>
                <div class="acciones">
                    <button class="btn-editar" onclick="window.location.href='editar_servicio_admin.php?id=<?= $s->getIdServicio() ?>'">Editar</button>
                    <button class="btn-eliminar" data-id="<?= $s->getIdServicio() ?>">Eliminar</button>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<!-- MODAL ELIMINAR -->
<div id="modalEliminar" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>¿Seguro querés eliminar este servicio?</p>
        <div class="modal-buttons">
            <button id="confirmDelete" class="btn-confirm">✔</button>
            <button id="cancelDelete" class="btn-cancel">X</button>
        </div>
        <div id="mensajeModal" class="mensaje-modal" style="display:none;"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let servicioAEliminar = null;
    const modalEliminar = document.getElementById('modalEliminar');
    const mensajeModal = document.getElementById('mensajeModal');

    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', () => {
            servicioAEliminar = btn.dataset.id;
            mensajeModal.style.display = 'none';
            modalEliminar.style.display = 'flex';
        });
    });

    document.getElementById('cancelDelete').onclick = () => modalEliminar.style.display = 'none';
    modalEliminar.querySelector('.close').onclick = () => modalEliminar.style.display = 'none';

    document.getElementById('confirmDelete').onclick = () => {
        if (!servicioAEliminar) return;

        fetch('/Proyecto/apps/Controlador/administrador/eliminarServicioAdmin.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `idServicio=${servicioAEliminar}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const item = document.querySelector(`.btn-eliminar[data-id="${servicioAEliminar}"]`)?.closest('.servicio-item');
                if (item) item.remove();
                mensajeModal.style.display = 'block';
                mensajeModal.textContent = 'Servicio eliminado correctamente';
                setTimeout(() => modalEliminar.style.display = 'none', 1500);
            } else {
                mensajeModal.style.display = 'block';
                mensajeModal.textContent = 'Error: ' + data.error;
            }
        })
        .catch(() => {
            mensajeModal.style.display = 'block';
            mensajeModal.textContent = 'Error en la solicitud';
        });
    };

    window.addEventListener('click', e => {
        if (e.target === modalEliminar) modalEliminar.style.display = 'none';
    });

    document.addEventListener('keydown', e => {
        if (modalEliminar.style.display === 'flex' && e.key === 'Escape') modalEliminar.style.display = 'none';
    });
});
</script>

</body>
</html>
