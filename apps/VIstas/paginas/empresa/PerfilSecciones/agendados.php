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


<script>
let filaSeleccionada = null;

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
                <div class="botones-acciones">
                    <button class="btn-aceptar">✔</button>
                    <button class="btn-rechazar">✖</button>
                </div>
            </td>
        `;
        tbodyPend.appendChild(tr);
    }  else if(item.Estado === "En proceso"){
    tr.innerHTML = `
        <td>${item.NombreServicio}</td>
        <td>${item.NombreCliente}</td>
        <td>${item.Fecha}</td>
        <td>${item.Hora}</td>
        <td>
            
            <button class="btn-cancelar-empresa" data-id="${item.IdCita}" title="Cancelar servicio">✖</button>
        </td>
    `;
    tbodyProc.appendChild(tr);
}
// ------------------ MODAL CANCELAR (EMPRESA) ------------------
let citaCancelarEmpresa = null;
const modalCancelarEmpresa = document.getElementById('modalCancelarEmpresa');
const btnConfirmCancelEmpresa = document.getElementById('confirmCancelEmpresa');
const btnCancelCancelEmpresa = document.getElementById('cancelCancelEmpresa');
const mensajeModalEmpresa = document.getElementById('mensajeModalEmpresa');

// Detectar clic en botón cancelar dentro de la tabla
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('btn-cancelar-empresa')) {
        citaCancelarEmpresa = e.target.dataset.id;
        mensajeModalEmpresa.style.display = 'none';
        modalCancelarEmpresa.style.display = 'flex';
    }
});

btnCancelCancelEmpresa.onclick = () => modalCancelarEmpresa.style.display = 'none';

btnConfirmCancelEmpresa.onclick = () => {
    if (!citaCancelarEmpresa) return;

    fetch('/Proyecto/apps/Controlador/empresa/cancelarServicioEmpresa.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `idCita=${citaCancelarEmpresa}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const fila = document.querySelector(`.btn-cancelar-empresa[data-id="${citaCancelarEmpresa}"]`)?.closest('tr');
            if (fila) fila.remove();
            mensajeModalEmpresa.style.display = 'block';
            mensajeModalEmpresa.textContent = 'Servicio cancelado';
            setTimeout(() => modalCancelarEmpresa.style.display = 'none', 1500);
        } else {
            mensajeModalEmpresa.style.display = 'block';
            mensajeModalEmpresa.textContent = 'Error: ' + data.error;
        }
    })
    .catch(() => {
        mensajeModalEmpresa.style.display = 'block';
        mensajeModalEmpresa.textContent = 'Error en la solicitud';
    });
};

});



        const btnActivo = document.querySelector('.filtro-btn.activa');
        const estadoActivo = btnActivo ? btnActivo.dataset.estado : "Pendiente";
        document.getElementById('tablaPendiente').style.display = estadoActivo === "Pendiente" ? 'table' : 'none';
        document.getElementById('tablaProceso').style.display = estadoActivo === "En proceso" ? 'table' : 'none';
    })
    .catch(err => console.error('Error al cargar agendados:', err));
}

document.querySelectorAll('.filtro-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('activa'));
        btn.classList.add('activa');

        const estado = btn.dataset.estado;
        document.getElementById('tablaPendiente').style.display = estado === "Pendiente" ? 'table' : 'none';
        document.getElementById('tablaProceso').style.display = estado === "En proceso" ? 'table' : 'none';
    });
});

document.addEventListener('click', function(e){
    // --- Aceptar ---
    if(e.target.classList.contains('btn-aceptar')){
        filaSeleccionada = e.target.closest('tr');
        const servicio = filaSeleccionada.children[0].innerText;
        const usuario = filaSeleccionada.children[1].innerText;
        const fecha = filaSeleccionada.children[2].innerText;
        const hora = filaSeleccionada.children[3].innerText;

        document.getElementById('detalleServicio').innerHTML = `
            <strong>Servicio:</strong> ${servicio}<br>
            <strong>Usuario:</strong> ${usuario}<br>
            <strong>Fecha:</strong> ${fecha}<br>
            <strong>Hora:</strong> ${hora}
        `;
        document.getElementById('modalConfirmar').style.display = 'flex';
    }

    // --- Rechazar --- 
    if(e.target.classList.contains('btn-rechazar')){
        filaSeleccionada = e.target.closest('tr');
        const idContrata = filaSeleccionada.dataset.id;
        const clienteNombre = filaSeleccionada.children[1].innerText;

        document.getElementById('modalRechazo').style.display = 'flex';
        document.getElementById('clienteNombre').innerText = clienteNombre;
        document.getElementById('idContrataInput').value = idContrata;
    }
});

// --- Confirmar reserva ---
document.getElementById('btnConfirmar').addEventListener('click', function(){
    if(!filaSeleccionada) return;
    const idContrata = filaSeleccionada.dataset.id;

    fetch('/Proyecto/apps/controlador/empresa/AgendadosControlador.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `idContrata=${idContrata}&accion=aceptar`
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) cargarAgendados();
        else alert(data.error || 'Error al actualizar estado');
    })
    .catch(err => console.error(err))
    .finally(() => {
        document.getElementById('modalConfirmar').style.display = 'none';
        filaSeleccionada = null;
    });
});

// --- Enviar rechazo ---
document.getElementById('formRechazo').addEventListener('submit', function(e){
    e.preventDefault();
    const idContrata = document.getElementById('idContrataInput').value;
    const contenido = document.getElementById('contenido').value.trim();
    const asunto = 'Rechazo de reserva';

    if(!contenido){
        alert('Debes escribir un mensaje de rechazo');
        return;
    }

    fetch('/Proyecto/apps/controlador/empresa/RechazoControlador.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `idContrata=${idContrata}&asunto=${encodeURIComponent(asunto)}&contenido=${encodeURIComponent(contenido)}`
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            cargarAgendados();
            document.getElementById('modalRechazo').style.display = 'none';
            document.getElementById('formRechazo').reset();
        } else {
            alert(data.error || 'Error al enviar rechazo');
        }
    })
    .catch(err => console.error(err));
});

document.querySelectorAll('.modal .close, .modal .cerrar').forEach(span => {
    span.addEventListener('click', () => {
        document.querySelectorAll('.modal').forEach(modal => modal.style.display = 'none');
        filaSeleccionada = null;
    });
});

const contenidoTextarea = document.getElementById('contenido');
const contador = document.getElementById('contadorMensaje');
if(contenidoTextarea){
    contenidoTextarea.addEventListener('input', () => {
        contador.textContent = `${contenidoTextarea.value.length} / 1000 caracteres`;
    });
}

cargarAgendados();

</script>
