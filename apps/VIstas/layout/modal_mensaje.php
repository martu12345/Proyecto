<div id="modalMensaje" class="modal">
    <div class="modal-content">
        <span class="cerrar">&times;</span>
        <h2>Enviar Mensaje</h2>
        <form id="formMensaje" action="/Proyecto/apps/controlador/comunica/ComunicaControlador.php" method="POST">
            <input type="hidden" name="empresa_id" value="<?php echo $empresa_id ?? ''; ?>">

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
