<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Controlador/cliente/ServiciosClienteControlador.php');

$controlador = new ServiciosClienteControlador($conn, $idUsuario);
$servicios = $controlador->obtenerServicios();

$pendientes = $servicios['pendientes'];
$enProceso = $servicios['enProceso'];
$finalizados = $servicios['finalizados'];
$rechazados = $servicios['rechazados'];
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

                <button id="confirmCancel" class="btn-confirm"> ✔</button>
                <button id="cancelCancel" class="btn-cancel"> X </button>

            </div>
            <div id="mensajeModal" class="mensaje-modal" style="display:none;"></div>
        </div>
    </div>

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

    <div id="modalVerCalificacion" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Mi calificación</h3>
            <div class="stars" id="verStars"></div>
            <p id="verResena"></p>
        </div>
    </div>

    <script src="/Proyecto/public/js/cliente/servicios.js"></script>

</body>
</html>