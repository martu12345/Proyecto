const idUsuarioCliente = document.getElementById('chat').dataset.idCliente;
const idUsuarioSesion = Number(document.getElementById('chat').dataset.idSesion);

function cargarMensajes() {
    fetch(`/Proyecto/apps/controladores/obtenerMensajes.php?idUsuarioCliente=${idUsuarioCliente}`)
        .then(res => res.json())
        .then(data => {
            const chat = document.getElementById('chat');
            chat.innerHTML = '';

            data.forEach(m => {
                const esEmpresa = m.idUsuarioEmisor !== Number(idUsuarioCliente);

                const div = document.createElement('div');
                div.classList.add('mensaje');
                div.classList.add(esEmpresa ? 'empresa' : 'cliente');
                div.innerHTML = `<strong>${m.emisor}:</strong> ${m.mensaje}`;
                chat.appendChild(div);
            });

            chat.scrollTop = chat.scrollHeight;
        })
        .catch(err => console.error('Error al cargar mensajes:', err));
}

// Llamamos a la función al cargar la página
cargarMensajes();

// Actualizamos cada 3 segundos
setInterval(cargarMensajes, 3000);
