<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Controlador/cliente/ServiciosClienteControlador.php');

$controlador = new ServiciosClienteControlador($conn, $idUsuario);

$pendientes = $controlador->obtenerPendientes();
$enProceso = $controlador->obtenerEnProceso();
$finalizados = $controlador->obtenerFinalizadosConCalificacion($idUsuario);
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
                    <div class="nombre"><?= htmlspecialchars($f['titulo']) ?></div>
                    <div class="empresa">Empresa: <?= htmlspecialchars($f['nombreEmpresa']) ?></div>
                    <div class="fecha">Fecha: <?= htmlspecialchars($f['fecha']) ?> Hora: <?= htmlspecialchars($f['hora']) ?></div>

                    <?php if (isset($f['calificacion']) && $f['calificacion'] > 0): ?>
                        <button class="btn-ver-calificacion"
                            data-id="<?= $f['idCita'] ?>"
                            data-calificacion="<?= $f['calificacion'] ?>"
                            data-resena="<?= htmlspecialchars($f['resena'], ENT_QUOTES) ?>">
                            Ver mi calificación
                        </button>
                    <?php else: ?>
                        <button class="btn-calificar" data-id="<?= $f['idCita'] ?>">Calificar</button>
                    <?php endif; ?>


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
                <button id="cancelCancel" class="btn-cancel"> X </button>

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

    <!-- MODAL VER CALIFICACIÓN -->
    <div id="modalVerCalificacion" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Mi calificación</h3>
            <div class="stars" id="verStars"></div>
            <p id="verResena"></p>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // ------------------ PESTAÑAS ------------------
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

            // ------------------ MODAL CANCELAR ------------------
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

                fetch('/Proyecto/apps/Controlador/cliente/cancelarServicioCliente.php', {
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
                            mensajeModalCancel.textContent = 'Servicio cancelado';
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

            // ------------------ MODAL CALIFICAR ------------------
            const modalCalificar = document.getElementById('modalCalificar');
            const stars = document.querySelectorAll('#stars .star');
            const calificacionInput = document.getElementById('calificacionInput');
            const resenaInput = document.getElementById('resenaInput');
            const btnEnviar = document.getElementById('btnEnviarCalificacion');
            const btnCancelar = document.getElementById('btnCancelarCalificacion');
            const mensajeModal = document.getElementById('mensajeModalCalificar');

            let calificacion = 0;
            let idCitaActual = null;

            function abrirModalCalificar(idCita) {
                idCitaActual = idCita;
                calificacion = 0;
                calificacionInput.value = '';
                resenaInput.value = '';
                mensajeModal.style.display = 'none';
                stars.forEach(s => s.classList.remove('selected'));
                resenaInput.style.height = 'auto';
                modalCalificar.style.display = 'flex';
            }

            document.querySelectorAll('.btn-calificar').forEach(btn => {
                btn.addEventListener('click', () => abrirModalCalificar(btn.dataset.id));
            });

            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    calificacion = parseInt(star.dataset.value);
                    calificacionInput.value = calificacion;
                    stars.forEach(s => s.classList.remove('selected'));
                    for (let i = 0; i < calificacion; i++) stars[i].classList.add('selected');
                });
                star.addEventListener('mouseover', () => {
                    stars.forEach(s => s.classList.remove('hovered'));
                    for (let i = 0; i <= index; i++) stars[i].classList.add('hovered');
                });
                star.addEventListener('mouseout', () => stars.forEach(s => s.classList.remove('hovered')));
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
                            mensajeModal.textContent = 'Servicio calificado correctamente';

                            // --------- CAMBIAR BOTÓN AUTOMÁTICAMENTE ---------
                            const card = document.querySelector(`.btn-calificar[data-id="${idCitaActual}"]`)?.closest('.card');
                            if (card) {
                                const btnCalificar = card.querySelector('.btn-calificar');

                                const nuevoBtn = btnCalificar.cloneNode(true);
                                nuevoBtn.textContent = 'Ver mi calificación';
                                nuevoBtn.classList.remove('btn-calificar');
                                nuevoBtn.classList.add('btn-ver-calificacion');
                                nuevoBtn.dataset.calificacion = calificacion;
                                nuevoBtn.dataset.resena = resena;

                                btnCalificar.replaceWith(nuevoBtn);

                                nuevoBtn.addEventListener('click', () => mostrarCalificacion(nuevoBtn.dataset.calificacion, nuevoBtn.dataset.resena));
                            }


                            setTimeout(() => modalCalificar.style.display = 'none', 200);
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

            btnCancelar.addEventListener('click', () => modalCalificar.style.display = 'none');
            window.addEventListener('click', e => {
                if (e.target === modalCalificar) modalCalificar.style.display = 'none';
            });
            document.addEventListener('keydown', e => {
                if (modalCalificar.style.display === 'flex' && e.key === 'Escape') modalCalificar.style.display = 'none';
            });

            // ------------------ MODAL VER CALIFICACIÓN ------------------
            const modalVer = document.getElementById('modalVerCalificacion');
            const verStarsContainer = document.getElementById('verStars');
            const verResena = document.getElementById('verResena');
            const spanCloseVer = modalVer.querySelector('.close');

            function mostrarCalificacion(cal, res) {
                verStarsContainer.innerHTML = '';
                for (let i = 1; i <= 5; i++) {
                    const star = document.createElement('span');
                    star.innerHTML = '&#9733;';
                    star.style.color = i <= cal ? 'gold' : 'lightgray';
                    verStarsContainer.appendChild(star);
                }
                verResena.textContent = res;
                modalVer.style.display = 'flex';
            }

            document.querySelectorAll('.btn-ver-calificacion').forEach(btn => {
                btn.addEventListener('click', () => mostrarCalificacion(btn.dataset.calificacion, btn.dataset.resena));
            });

            spanCloseVer.onclick = () => modalVer.style.display = 'none';
            window.addEventListener('click', e => {
                if (e.target === modalVer) modalVer.style.display = 'none';
            });
            document.addEventListener('keydown', e => {
                if (modalVer.style.display === 'flex' && e.key === 'Escape') modalVer.style.display = 'none';
            });

        });
    </script>

</body>

</html>