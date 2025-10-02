<div id="modalMensaje" class="modal">
    <div class="modal-content">
        <span class="cerrar">&times;</span>
        <h2>Enviar Mensaje a <span id="empresaNombre"></span></h2>
        <form id="formMensaje" action="/Proyecto/apps/controlador/ComunicaControlador.php" method="POST">
            <!-- Input hidden que recibe el ID de la empresa -->
            <input type="hidden" id="empresaIdInput" name="empresa_id" value="">

            <label for="asunto">Asunto</label>
            <input type="text" id="asunto" name="asunto" placeholder="Escribe el asunto..." required>

            <label for="contenido">Mensaje</label>
            <textarea id="contenido" name="contenido" rows="4" placeholder="Escribe tu mensaje..." required></textarea>
            <div id="contadorMensaje">0 / 1000 caracteres</div>

            <div class="boton-contenedor">
                <button type="submit">Enviar</button>
            </div>
        </form>
    </div>
</div>