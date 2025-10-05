<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Controlador/cliente/ServiciosClienteControlador.php');

$controlador = new ServiciosClienteControlador($conn, $idUsuario);

$pendientes = $controlador->obtenerPendientes();
$enProceso = $controlador->obtenerEnProceso();
$finalizados = $controlador->obtenerFinalizados();
$rechazados = $controlador->obtenerCancelados();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis Servicios</title>
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/cliente/PerfilSecciones/servicios.css">

</head>

<body>

    <h1>Mis Servicios</h1>

    <div class="tabs">
        <div class="tab active" data-tab="Pendientes">Pendientes</div>
        <div class="tab" data-tab="En-proceso">En Proceso</div>
        <div class="tab" data-tab="Finalizado">Finalizados</div>
        <div class="tab" data-tab="Cancelado">Cancelados</div>
    </div>

    <!-- PENDIENTES -->
    <div class="tab-content active" id="Pendientes">
        <?php if (count($pendientes) === 0): ?>
            <p>No hay servicios pendientes.</p>
        <?php else: ?>
            <?php foreach ($pendientes as $p): ?>
                <div class="card">
                    <div class="nombre"><?= htmlspecialchars($p['tituloServicio']) ?></div>
                    <div class="empresa">Empresa: <?= htmlspecialchars($p['nombreEmpresa']) ?></div>
                    <div class="fecha">Fecha: <?= htmlspecialchars($p['fecha']) ?> Hora: <?= htmlspecialchars($p['hora']) ?></div>
                    <button class="btn-cancelar" data-id="<?= $p['idCita'] ?>"> x </button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- EN PROCESO -->
    <div class="tab-content" id="En-proceso">
        <?php if (count($enProceso) === 0): ?>
            <p>No hay servicios en proceso.</p>
        <?php else: ?>
            <?php foreach ($enProceso as $s): ?>
                <div class="card">
                    <div class="nombre"><?= htmlspecialchars($s['tituloServicio']) ?></div>
                    <div class="empresa">Empresa: <?= htmlspecialchars($s['nombreEmpresa']) ?></div>
                    <div class="fecha">Fecha: <?= htmlspecialchars($s['fecha']) ?> Hora: <?= htmlspecialchars($s['hora']) ?></div>
                    <button class="btn-cancelar" data-id="<?= $s['idCita'] ?>"> x </button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- FINALIZADOS -->
    <div class="tab-content" id="Finalizado">
        <?php if (count($finalizados) === 0): ?>
            <p>No hay servicios finalizados.</p>
        <?php else: ?>
            <?php foreach ($finalizados as $f): ?>
                <div class="card">
                    <div class="nombre"><?= htmlspecialchars($f['tituloServicio']) ?></div>
                    <div class="empresa">Empresa: <?= htmlspecialchars($f['nombreEmpresa']) ?></div>
                    <div class="fecha">Fecha: <?= htmlspecialchars($f['fecha']) ?> Hora: <?= htmlspecialchars($f['hora']) ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- CANCELADOS -->
    <div class="tab-content" id="Cancelado">
        <?php if (count($rechazados) === 0): ?>
            <p>No hay servicios cancelados.</p>
        <?php else: ?>
            <?php foreach ($rechazados as $r): ?>
                <div class="card">
                    <div class="nombre"><?= htmlspecialchars($r['tituloServicio']) ?></div>
                    <div class="empresa">Empresa: <?= htmlspecialchars($r['nombreEmpresa']) ?></div>
                    <div class="fecha">Fecha: <?= htmlspecialchars($r['fecha']) ?> Hora: <?= htmlspecialchars($r['hora']) ?></div>
                    <?php if (!empty($r['mensajeCancelacion'])): ?>
                        <div class="mensaje-rechazo"><strong>Motivo de la cancelación:</strong> <?= htmlspecialchars($r['mensajeCancelacion']) ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="modalCancelar" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>¿Seguro querés cancelar este servicio?</p>
            <div class="modal-buttons">

                <button id="cancelCancel" class="btn-cancel">✔ </button>
                <button id="confirmCancel" class="btn-confirm"> X </button>
            </div>
            <div id="mensajeModal" class="mensaje-modal" style="display:none;"></div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // PESTAÑAS
            const tabs = document.querySelectorAll('.tab');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    contents.forEach(c => c.classList.remove('active'));
                    document.getElementById(tab.dataset.tab).classList.add('active');
                });
            });

            // MODAL DE CONFIRMACIÓN

            let citaAEliminar = null;
            const modal = document.getElementById('modalCancelar');
            const btnConfirm = document.getElementById('confirmCancel');
            const btnCancel = document.getElementById('cancelCancel');
            const spanClose = document.querySelector('.close');
            const mensajeModal = document.getElementById('mensajeModal');

            document.querySelectorAll('.btn-cancelar').forEach(btn => {
                btn.addEventListener('click', () => {
                    citaAEliminar = btn.dataset.id;
                    mensajeModal.style.display = 'none';
                    modal.style.display = 'flex';
                });
            });

            btnCancel.onclick = () => modal.style.display = 'none';
            spanClose.onclick = () => modal.style.display = 'none';

            btnConfirm.onclick = () => {
                if (!citaAEliminar) return;

                fetch('/Proyecto/apps/controlador/cliente/cancelarServicioCliente.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `idCita=${citaAEliminar}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const card = document.querySelector(`.btn-cancelar[data-id="${citaAEliminar}"]`)?.closest('.card');
                            if (card) card.remove();
                            mensajeModal.style.display = 'block';
                            mensajeModal.textContent = 'Servicio cancelado ✅';
                            setTimeout(() => modal.style.display = 'none', 1500);
                        } else {
                            mensajeModal.style.display = 'block';
                            mensajeModal.textContent = 'Error: ' + data.error;
                        }
                    })
                    .catch(err => {
                        mensajeModal.style.display = 'block';
                        mensajeModal.textContent = 'Error en la solicitud';
                    });
            };

            window.onclick = (event) => {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            };

            document.addEventListener('keydown', (e) => {
                if (modal.style.display === 'flex') {
                    if (e.key === 'Enter') btnConfirm.click();
                    if (e.key === 'Escape') modal.style.display = 'none';
                }
            });
        });
    </script>

</body>

</html>