<div id="modalRechazo" class="modal">
    <div class="modal-content">
        <span class="cerrar">&times;</span>
        <h2>Rechazar Servicio de <span id="clienteNombre"></span></h2>
        <form id="formRechazo">
            <input type="hidden" id="idContrataInput" name="idContrata">

            <label for="asunto">Asunto</label>
            <input type="text" id="asunto" name="asunto" placeholder="Escribe el asunto..." required>

            <label for="contenido">Mensaje</label>
            <textarea id="contenido" name="contenido" rows="4" placeholder="Explica el motivo del rechazo..." required></textarea>
            <div id="contadorMensaje">0 / 1000 caracteres</div>

            <div class="boton-contenedor">
                <button type="submit">Enviar y Rechazar</button>
            </div>
        </form>
    </div>
</div>
