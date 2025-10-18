<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Contrata.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');

$empresa = Empresa::obtenerPorId($conn, $_SESSION['idUsuario'] ?? 0);
?>

<h2>Agendados</h2>

<div class="botones-filtro">
    <button class="filtro-btn activa" data-estado="Pendiente">Pendiente</button>
    <button class="filtro-btn" data-estado="En proceso">En Proceso</button>
</div>

<div class="agendados-container">

    <!-- Tabla de pendientes -->
    <table id="tablaPendiente" class="tabla-agendados activa">
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <!-- Tabla en proceso -->
    <table id="tablaProceso" class="tabla-agendados">
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

</div>

<!-- Modal de confirmación -->
<div id="modalConfirmar" class="modal">
    <div class="modal-content">
        <span id="cerrarModal" class="close">&times;</span>
        <h2>Confirmar Servicio</h2>
        <p id="detalleServicio"></p>
        <button id="btnConfirmar">Confirmar</button>
    </div>
</div>

<!-- Modal de rechazo con mensaje -->
<div id="modalRechazo" class="modal">
    <div class="modal-content">
        <span class="close cerrar">&times;</span>
        <h2>Rechazar Reserva</h2>
        <p>Enviar mensaje al cliente: <strong id="clienteNombre"></strong></p>
        <form id="formRechazo">
            <textarea id="contenido" placeholder="Escribe el mensaje..." maxlength="1000" required></textarea>
            <p id="contadorMensaje">0 / 1000 caracteres</p>
            <input type="hidden" id="idContrataInput">
            <button type="submit">Enviar Rechazo</button>
        </form>
    </div>
</div>

<!-- MODAL cancelar (servicio en proceso) -->
<div id="modalCancelarEmpresa" class="modal">
    <div class="modal-content">
        <p>¿Seguro querés cancelar este servicio?</p>
        <div class="modal-buttons">
            <button id="confirmCancelEmpresa" class="btn-confirm">✔</button>
            <button id="cancelCancelEmpresa" class="btn-cancel">✖</button>
        </div>
        <div id="mensajeModalEmpresa" class="mensaje-modal" style="display:none;"></div>
    </div>
</div>


<script src="/Proyecto/public/js/empresa/agendados.js"></script>

