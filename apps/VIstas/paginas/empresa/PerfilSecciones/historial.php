<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Contrata.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');


$empresa = Empresa::obtenerPorId($conn, $_SESSION['idUsuario'] ?? 0);

// Traer servicios finalizados y cancelados de la empresa
$serviciosFinalizados = Contrata::obtenerPorEmpresaYEstado($conn, $empresa->getIdUsuario(), 'Finalizado');
$serviciosCancelados  = Contrata::obtenerPorEmpresaYEstado($conn, $empresa->getIdUsuario(), 'Cancelado');

?>



<h2>Historial de Servicios</h2>
    <link rel="stylesheet" href="/Proyecto/public/css/layout/modal_denuncia.css">

<div class="botones-filtro">
    <button class="filtro-btn activa" data-estado="Finalizado">Finalizados</button>
    <button class="filtro-btn" data-estado="Cancelado">Cancelados</button>
</div>


<div class="agendados-container">

    <!-- Tabla de finalizados -->
    <table id="tablaFinalizado" class="tabla-agendados activa">
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
                <th>Calificación</th>
                <th>Reseña</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($serviciosFinalizados)): ?>
                <?php foreach ($serviciosFinalizados as $servicio): ?>
                    <tr>
                        <td><?= htmlspecialchars($servicio['nombre_servicio']) ?></td>
                        <td><?= htmlspecialchars($servicio['usuario_email']) ?></td>
                        <td><?= htmlspecialchars($servicio['Fecha']) ?></td>
                        <td><?= htmlspecialchars($servicio['Hora']) ?></td>
                        <td><?= htmlspecialchars($servicio['Estado']) ?></td>
                        <td>
                            <?php 
                                if (!empty($servicio['Calificacion'])) {
                                    echo str_repeat("⭐", $servicio['Calificacion']);
                                } else {
                                    echo '-';
                                }
                            ?>
                        </td>
                        <td><?= $servicio['Resena'] ?? '-' ?></td>
                        <td>
    <button class="btn-denunciar" data-idcliente="<?= $servicio['IdUsuario'] ?>">Denunciar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8">No hay servicios finalizados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Tabla de cancelados -->
    <table id="tablaCancelado" class="tabla-agendados">
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($serviciosCancelados)): ?>
                <?php foreach ($serviciosCancelados as $servicio): ?>
                    <tr>
                        <td><?= htmlspecialchars($servicio['nombre_servicio']) ?></td>
                        <td><?= htmlspecialchars($servicio['usuario_email']) ?></td>
                        <td><?= htmlspecialchars($servicio['Fecha']) ?></td>
                        <td><?= htmlspecialchars($servicio['Hora']) ?></td>
                        <td><?= htmlspecialchars($servicio['Estado']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No hay servicios cancelados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<!-- Modal de denuncia de cliente -->
<div id="denunciaModal" class="modal">
    <div class="modal-content">
        <span id="closeModalBtn" class="cerrar">&times;</span>
        <h2>Denunciar Cliente</h2>
        <form id="denunciaForm">
            <input type="hidden" name="idCliente" value="">
            <input type="hidden" name="idEmpresa" value="<?= $_SESSION['idUsuario'] ?>">
            <input type="hidden" name="asunto" value="DenunciarCliente">
            <label for="detalle">Detalles adicionales (opcional):</label>
            <textarea id="detalle" name="detalle" rows="4" placeholder="Escribe más sobre tu denuncia..."></textarea>
            <button type="submit" class="btn-submit">Enviar denuncia</button>
        </form>
        <div id="mensajeDenuncia" class="mensaje-denuncia"></div>
    </div>
</div>

<script src="/Proyecto/public/js/empresa/historial.js"></script>
