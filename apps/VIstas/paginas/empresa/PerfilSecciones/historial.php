<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Contrata.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');

$empresa = Empresa::obtenerPorId($conn, $_SESSION['idUsuario'] ?? 0);
?>

<h2>Historial de Servicios</h2>

<div class="botones-filtro">
    <button class="filtro-btn activa" data-estado="Finalizado">Finalizados</button>
    <button class="filtro-btn" data-estado="Rechazado">Rechazados</button>
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

    <!-- Tabla de rechazados -->
    <table id="tablaRechazado" class="tabla-agendados">
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

<script>
function cargarHistorial() {
    fetch('/Proyecto/apps/controlador/empresa/AgendadosControlador.php')
    .then(res => res.json())
    .then(data => {
        const tbodyFinal = document.querySelector('#tablaFinalizado tbody');
        const tbodyRech = document.querySelector('#tablaRechazado tbody');
        tbodyFinal.innerHTML = '';
        tbodyRech.innerHTML = '';

        if(data.error){
            tbodyFinal.innerHTML = `<tr><td colspan="7">${data.error}</td></tr>`;
            tbodyRech.innerHTML = `<tr><td colspan="5">${data.error}</td></tr>`;
            return;
        }

        data.forEach(item => {
            const tr = document.createElement('tr');
            tr.dataset.id = item.IdCita;

            if(item.Estado === "Finalizado"){
                tr.innerHTML = `
                    <td>${item.NombreServicio}</td>
                    <td>${item.NombreCliente}</td>
                    <td>${item.Fecha}</td>
                    <td>${item.Hora}</td>
                    <td>${item.Estado}</td>
                    <td>${item.Calificacion ? '⭐'.repeat(item.Calificacion) : 'No hay'}</td>
                    <td>${item.Resena ? item.Resena : 'No hay'}</td>
                `;
                tbodyFinal.appendChild(tr);
            } else if(item.Estado === "Rechazado"){
                tr.innerHTML = `
                    <td>${item.NombreServicio}</td>
                    <td>${item.NombreCliente}</td>
                    <td>${item.Fecha}</td>
                    <td>${item.Hora}</td>
                    <td>${item.Estado}</td>
                `;
                tbodyRech.appendChild(tr);
            }
        });

        const btnActivo = document.querySelector('.filtro-btn.activa');
        const estadoActivo = btnActivo ? btnActivo.dataset.estado : "Finalizado";
        document.getElementById('tablaFinalizado').style.display = estadoActivo === "Finalizado" ? 'table' : 'none';
        document.getElementById('tablaRechazado').style.display = estadoActivo === "Rechazado" ? 'table' : 'none';
    })
    .catch(err => console.error('Error al cargar historial:', err));
}

// --- Filtro de tablas ---
document.querySelectorAll('.filtro-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('activa'));
        btn.classList.add('activa');

        const estado = btn.dataset.estado;
        document.getElementById('tablaFinalizado').style.display = estado === "Finalizado" ? 'table' : 'none';
        document.getElementById('tablaRechazado').style.display = estado === "Rechazado" ? 'table' : 'none';
    });
});

cargarHistorial();
</script>
