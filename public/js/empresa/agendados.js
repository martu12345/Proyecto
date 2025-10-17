document.addEventListener("DOMContentLoaded", function () {
    let filaSeleccionada = null;

    const tablaPendiente = document.getElementById('tablaPendiente').querySelector('tbody');
    const tablaProceso = document.getElementById('tablaProceso').querySelector('tbody');

    const modalConfirmar = document.getElementById('modalConfirmar');
    const modalRechazo = document.getElementById('modalRechazo');
    const modalCancelarEmpresa = document.getElementById('modalCancelarEmpresa');

    const contenidoTextarea = document.getElementById('contenido');
    const contador = document.getElementById('contadorMensaje');

    // --- Función para cargar agendados ---
    function cargarAgendados() {
        fetch('/Proyecto/apps/controlador/empresa/AgendadosControlador.php')
            .then(res => res.json())
            .then(data => {
                tablaPendiente.innerHTML = '';
                tablaProceso.innerHTML = '';

                if (data.error) {
                    tablaPendiente.innerHTML = `<tr><td colspan="5">${data.error}</td></tr>`;
                    tablaProceso.innerHTML = `<tr><td colspan="5">${data.error}</td></tr>`;
                    return;
                }

                data.forEach(item => {
                    const estado = item.Estado.toLowerCase().trim();
                    if (estado === 'pendiente') {
                        tablaPendiente.innerHTML += `
                            <tr data-id="${item.IdCita}">
                                <td>${item.Titulo}</td>
                                <td>${item.EmailCliente}</td>
                                <td>${item.Fecha}</td>
                                <td>${item.Hora}</td>
                                <td>
                                    <button class="btn-aceptar">✔</button>
                                    <button class="btn-rechazar">✖</button>
                                </td>
                            </tr>`;
                    } else if (estado === 'en proceso') {
                        tablaProceso.innerHTML += `
                            <tr data-id="${item.IdCita}">
                                <td>${item.Titulo}</td>
                                <td>${item.EmailCliente}</td>
                                <td>${item.Fecha}</td>
                                <td>${item.Hora}</td>
                                <td>
                                    <button class="btn-cancelar-empresa" data-id="${item.IdCita}">✖</button>
                                </td>
                            </tr>`;
                    }
                });
            })
            .catch(err => console.error('Error al cargar agendados:', err));
    }

    // --- Filtros ---
    document.querySelectorAll('.filtro-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('activa'));
            btn.classList.add('activa');

            const estado = btn.dataset.estado;
            document.getElementById('tablaPendiente').style.display = estado === 'Pendiente' ? 'table' : 'none';
            document.getElementById('tablaProceso').style.display = estado === 'En proceso' ? 'table' : 'none';
        });
    });

    // --- Manejo de botones dentro de las tablas ---
    document.addEventListener('click', function(e) {
        const tr = e.target.closest('tr');
        if (!tr) return;

        const idCita = tr.dataset.id;

        // --- Aceptar ---
        if (e.target.classList.contains('btn-aceptar')) {
            filaSeleccionada = tr;
            const detalles = `
                <strong>Servicio:</strong> ${tr.children[0].innerText}<br>
                <strong>Usuario:</strong> ${tr.children[1].innerText}<br>
                <strong>Fecha:</strong> ${tr.children[2].innerText}<br>
                <strong>Hora:</strong> ${tr.children[3].innerText}
            `;
            document.getElementById('detalleServicio').innerHTML = detalles;
            modalConfirmar.style.display = 'flex';
        }

        // --- Rechazar ---
        if (e.target.classList.contains('btn-rechazar')) {
            filaSeleccionada = tr;
            document.getElementById('clienteNombre').innerText = tr.children[1].innerText;
            document.getElementById('idContrataInput').value = idCita;
            modalRechazo.style.display = 'flex';
        }

        // --- Cancelar servicio en proceso ---
        if (e.target.classList.contains('btn-cancelar-empresa')) {
            modalCancelarEmpresa.style.display = 'flex';

            document.getElementById('confirmCancelEmpresa').onclick = () => {
                fetch('/Proyecto/apps/Controlador/empresa/cancelarServicioEmpresa.php', {
                    method: 'POST',
                    headers: {'Content-Type':'application/x-www-form-urlencoded'},
                    body: `idCita=${idCita}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) tr.remove();
                    modalCancelarEmpresa.style.display = 'none';
                })
                .catch(() => alert('Error al cancelar'));
            };

            document.getElementById('cancelCancelEmpresa').onclick = () => modalCancelarEmpresa.style.display = 'none';
        }
    });

    // --- Confirmar ---
    document.getElementById('btnConfirmar').addEventListener('click', function(){
        if (!filaSeleccionada) return;
        fetch('/Proyecto/apps/controlador/empresa/AgendadosControlador.php', {
            method:'POST',
            headers:{'Content-Type':'application/x-www-form-urlencoded'},
            body:`idContrata=${filaSeleccionada.dataset.id}&accion=aceptar`
        })
        .then(res=>res.json())
        .then(data=>{
            if (data.success) cargarAgendados();
            else alert(data.error || 'Error al actualizar estado');
        })
        .finally(()=>{
            modalConfirmar.style.display='none';
            filaSeleccionada = null;
        });
    });

    // --- Rechazo ---
    document.getElementById('formRechazo').addEventListener('submit', function(e){
        e.preventDefault();
        const idContrata = document.getElementById('idContrataInput').value;
        const contenido = contenidoTextarea.value.trim();
        if (!contenido) return alert('Debes escribir un mensaje de rechazo');

        fetch('/Proyecto/apps/controlador/empresa/RechazoControlador.php', {
            method:'POST',
            headers:{'Content-Type':'application/x-www-form-urlencoded'},
            body:`idContrata=${idContrata}&contenido=${encodeURIComponent(contenido)}`
        })
        .then(res=>res.json())
        .then(data=>{
            if (data.success) {
                cargarAgendados();
                modalRechazo.style.display = 'none';
                document.getElementById('formRechazo').reset();
            } else alert(data.error || 'Error al enviar rechazo');
        });
    });

    // --- Contador de caracteres ---
    if(contenidoTextarea){
        contenidoTextarea.addEventListener('input', ()=>{
            contador.textContent = `${contenidoTextarea.value.length} / 1000 caracteres`;
        });
    }

    // --- Cerrar modales ---
    document.querySelectorAll('.modal .close, .modal .cerrar').forEach(span=>{
        span.addEventListener('click', ()=>{
            document.querySelectorAll('.modal').forEach(m=>m.style.display='none');
            filaSeleccionada=null;
        });
    });

    cargarAgendados();
});
