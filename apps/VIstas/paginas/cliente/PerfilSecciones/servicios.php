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
                    <button class="btn-calificar" data-id="<?= $f['idCita'] ?>">Calificar</button>
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

    <!-- MODAL cancelar -->

    <div id="modalCancelar" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>¿Seguro querés cancelar este servicio?</p>
            <div class="modal-buttons">

                <button id="confirmCancel" class="btn-confirm"> ✔</button>
                <button id="cancelCancel" class="btn-cancel">X </button>

            </div>
            <div id="mensajeModal" class="mensaje-modal" style="display:none;"></div>
        </div>
    </div>

    <!-- MODAL CALIFICAR -->
    <div id="modalCalificar" class="modal">
        <div class="modal-content">
            <h3>Calificar Servicio</h3>
            <div class="stars" id="stars">
                <span class="star" data-value="1">&#9733;</span>
                <span class="star" data-value="2">&#9733;</span>
                <span class="star" data-value="3">&#9733;</span>
                <span class="star" data-value="4">&#9733;</span>
                <span class="star" data-value="5">&#9733;</span>
            </div>
            <input type="hidden" id="calificacionInput">

            <textarea id="resenaInput" placeholder="Escribí tu reseña aquí..."></textarea>

            <div class="modal-buttons">
                <button id="btnEnviarCalificacion" class="btn-confirm">✔</button>
                <button id="btnCancelarCalificacion" class="btn-cancel">X</button>
            </div>

            <div id="mensajeModalCalificar" class="mensaje-modal" style="display:none;"></div>
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

            // MODAL CANCELAR
            let citaAEliminar = null;
            const modalCancelar = document.getElementById('modalCancelar');
            const btnConfirmCancel = document.getElementById('confirmCancel');
            const btnCancelCancel = document.getElementById('cancelCancel');
            const spanCloseCancel = modalCancelar.querySelector('.close');
            const mensajeModalCancel = document.getElementById('mensajeModal');

            document.querySelectorAll('.btn-cancelar').forEach(btn => {
                btn.addEventListener('click', () => {
                    citaAEliminar = btn.dataset.id;
                    mensajeModalCancel.style.display = 'none';
                    modalCancelar.style.display = 'flex';
                });
            });

            btnCancelCancel.onclick = () => modalCancelar.style.display = 'none';
            spanCloseCancel.onclick = () => modalCancelar.style.display = 'none';

            btnConfirmCancel.onclick = () => {
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
                            mensajeModalCancel.style.display = 'block';
                            mensajeModalCancel.textContent = 'Servicio cancelado ';
                            setTimeout(() => modalCancelar.style.display = 'none', 1500);
                        } else {
                            mensajeModalCancel.style.display = 'block';
                            mensajeModalCancel.textContent = 'Error: ' + data.error;
                        }
                    })
                    .catch(() => {
                        mensajeModalCancel.style.display = 'block';
                        mensajeModalCancel.textContent = 'Error en la solicitud';
                    });
            };

            // MODAL CALIFICAR
            const modal = document.getElementById('modalCalificar');
            const stars = document.querySelectorAll('.star');
            const calificacionInput = document.getElementById('calificacionInput');
            const resenaInput = document.getElementById('resenaInput');
            const btnEnviar = document.getElementById('btnEnviarCalificacion');
            const btnCancelar = document.getElementById('btnCancelarCalificacion');
            const mensajeModal = document.getElementById('mensajeModalCalificar');

            let calificacion = 0;
            let idCitaActual = null;

            document.querySelectorAll('.btn-calificar').forEach(btn => {
                btn.addEventListener('click', () => {
                    idCitaActual = btn.dataset.id;
                    calificacion = 0;
                    calificacionInput.value = '';
                    resenaInput.value = '';
                    mensajeModal.style.display = 'none';
                    stars.forEach(s => s.classList.remove('selected'));
                    resenaInput.style.height = 'auto'; // reset altura
                    modal.style.display = 'flex';
                });
            });

            // ESTRELLAS
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    calificacion = parseInt(star.dataset.value);
                    calificacionInput.value = calificacion;
                    stars.forEach(s => s.classList.remove('selected'));
                    for (let i = 0; i < calificacion; i++) {
                        stars[i].classList.add('selected');
                    }
                });

                star.addEventListener('mouseover', () => {
                    stars.forEach(s => s.classList.remove('hovered'));
                    for (let i = 0; i <= index; i++) {
                        stars[i].classList.add('hovered');
                    }
                });

                star.addEventListener('mouseout', () => {
                    stars.forEach(s => s.classList.remove('hovered'));
                });
            });

            resenaInput.addEventListener('input', () => {
                resenaInput.style.height = 'auto';
                resenaInput.style.height = resenaInput.scrollHeight + 'px';
            });

            btnEnviar.addEventListener('click', () => {
                const resena = resenaInput.value.trim();
                if (calificacion === 0) {
                    mensajeModal.style.display = 'block';
                    mensajeModal.textContent = 'Por favor, seleccioná una calificación.';
                    return;
                }
                if (resena === '') {
                    mensajeModal.style.display = 'block';
                    mensajeModal.textContent = 'Por favor, escribí una reseña.';
                    return;
                }

                fetch('/Proyecto/apps/Controlador/cliente/CalificarControlador.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `idCita=${idCitaActual}&calificacion=${calificacion}&resena=${encodeURIComponent(resena)}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            mensajeModal.style.display = 'block';
                            mensajeModal.textContent = 'Servicio calificado ';
                            setTimeout(() => modal.style.display = 'none', 1500);
                        } else {
                            mensajeModal.style.display = 'block';
                            mensajeModal.textContent = 'Error: ' + data.error;
                        }
                    })
                    .catch(() => {
                        mensajeModal.style.display = 'block';
                        mensajeModal.textContent = 'Error en la solicitud';
                    });
            });

            btnCancelar.addEventListener('click', () => modal.style.display = 'none');

            window.addEventListener('click', (event) => {
                if (event.target === modal) modal.style.display = 'none';
            });

            document.addEventListener('keydown', (e) => {
                if (modal.style.display === 'flex' && e.key === 'Escape') modal.style.display = 'none';
            });
        });
    </script>

</body>

</html>