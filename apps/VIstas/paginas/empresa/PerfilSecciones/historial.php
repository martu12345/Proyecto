<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Contrata.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');

$empresa = Empresa::obtenerPorId($conn, $_SESSION['idUsuario'] ?? 0);
?>

<h2>Historial de Servicios</h2>

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
            </tr>
        </thead>
        <tbody></tbody>
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
        <tbody></tbody>
    </table>

</div>


