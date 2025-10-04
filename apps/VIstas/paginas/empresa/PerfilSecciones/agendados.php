<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Contrata.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');

$empresa = Empresa::obtenerPorId($conn, $_SESSION['idUsuario'] ?? 0);
?>

<h2>Agendados</h2>

<!-- Botones de filtro -->
<div class="botones-filtro">
    <button class="filtro-btn activa" data-estado="Pendiente">Pendiente</button>
    <button class="filtro-btn" data-estado="En proceso">En Proceso</button>
</div>

<!-- Tablas -->
<div class="agendados-container">
    <!-- Tabla de pendientes -->
    <table id="tablaPendiente" class="tabla-agendados activa">
       <tbody>
    <tr data-id="123">
        <td>
            <strong>Servicio:</strong> 
        </td>
        <td>
            <strong>Usuario:</strong> 
        </td>
        <td>
            <strong>Fecha:</strong> 
        </td>
        <td>
            <strong>Hora:</strong> 
        </td>
        <td>
            <button class="btn-aceptar">Aceptar</button>
            <button class="btn-rechazar">Rechazar</button>
        </td>
    </tr>
</tbody>

    </table>

    <!-- Tabla en proceso -->
    <table id="tablaProceso" class="tabla-agendados">
        <tbody>
    <tr data-id="123">
        <td>
            <strong>Servicio:</strong> 
        </td>
        <td>
            <strong>Usuario:</strong> 
        </td>
        <td>
            <strong>Fecha:</strong> 
        </td>
        <td>
            <strong>Hora:</strong> 
        </td>
        <td>
            <button class="btn-aceptar">Aceptar</button>
            <button class="btn-rechazar">Rechazar</button>
        </td>
    </tr>
</tbody>

    </table>
</div>

<script>
function cargarAgendados() {
    fetch('/Proyecto/apps/controlador/empresa/AgendadosControlador.php')
    .then(res => res.json())
    .then(data => {
        const tbodyPend = document.querySelector('#tablaPendiente tbody');
        const tbodyProc = document.querySelector('#tablaProceso tbody');
        tbodyPend.innerHTML = '';
        tbodyProc.innerHTML = '';

        if(data.error){
            tbodyPend.innerHTML = `<tr><td colspan="5">${data.error}</td></tr>`;
            tbodyProc.innerHTML = `<tr><td colspan="5">${data.error}</td></tr>`;
            return;
        }

        data.forEach(item => {
            const tr = document.createElement('tr');
            tr.dataset.id = item.IdCita;

            if(item.Estado === "Pendiente"){
                tr.innerHTML = `
                    <td>${item.NombreServicio}</td>
                    <td>${item.NombreCliente}</td>
                    <td>${item.Fecha}</td>
                    <td>${item.Hora}</td>
                    <td>
                        <button class="btn-aceptar">Aceptar</button>
                        <button class="btn-rechazar">Rechazar</button>
                    </td>
                `;
                tbodyPend.appendChild(tr);
            } else if(item.Estado === "En proceso"){
                tr.innerHTML = `
                    <td>${item.NombreServicio}</td>
                    <td>${item.NombreCliente}</td>
                    <td>${item.Fecha}</td>
                    <td>${item.Hora}</td>
                    <td>${item.Estado}</td>
                `;
                tbodyProc.appendChild(tr);
            }
        });
    })
    .catch(err => console.error('Error al cargar agendados:', err));
}

// Cambiar entre tablas
// Cambiar entre tablas
document.querySelectorAll('.filtro-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        // Activar/desactivar botones
        document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('activa'));
        btn.classList.add('activa');

        // Mostrar la tabla correspondiente
        const estado = btn.dataset.estado;
        const tablaPend = document.getElementById('tablaPendiente');
        const tablaProc = document.getElementById('tablaProceso');

        if(estado === "Pendiente") {
            tablaPend.style.display = 'table';
            tablaProc.style.display = 'none';
        } else if(estado === "En proceso") {
            tablaPend.style.display = 'none';
            tablaProc.style.display = 'table';
        }
    });
}); 

// Inicial: mostrar solo tabla de pendientes
document.getElementById('tablaPendiente').style.display = 'table';
document.getElementById('tablaProceso').style.display = 'none';

// Aceptar / Rechazar
document.addEventListener('click', function(e){
    if(e.target.classList.contains('btn-aceptar') || e.target.classList.contains('btn-rechazar')){
        const fila = e.target.closest('tr');
        const idContrata = fila.dataset.id;
        const accion = e.target.classList.contains('btn-aceptar') ? 'aceptar' : 'rechazar';
        
        fetch('/Proyecto/apps/controlador/empresa/AgendadosControlador.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `idContrata=${idContrata}&accion=${accion}`
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) cargarAgendados();
            else alert(data.error || 'Error al actualizar estado');
        })
        .catch(err => console.error(err));
    }
});

// Inicial: mostrar solo tabla de pendientes
cargarAgendados();
</script>
