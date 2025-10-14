<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/comunica.php');

$idMensaje = $_GET['id'] ?? 0;
$mensaje = Comunica::obtenerMensajePorId($conn, $idMensaje);

if (!$mensaje) {
    echo "Mensaje no encontrado";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del mensaje</title>
    <link rel="stylesheet" href="../../../public/css/fonts.css">
    <link rel="stylesheet" href="../../../public/css/layout/navbar.css">
    <link rel="stylesheet" href="../../../public/css/layout/footer.css">
    <link rel="stylesheet" href="../../../public/css/layout/detalles_Mensaje.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/modal_mensaje.css">
</head>
<body>

<?php include $_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/vistas/layout/navbar.php'; ?>

<div class="detalle-mensaje">
    <div class="mensaje-contenedor">

        <!-- Cabecera del mensaje -->
        <div class="mensaje-header">
            <h2><?= htmlspecialchars($mensaje['Asunto']) ?></h2>
            <p><strong>Emisor:</strong> <?= htmlspecialchars($mensaje['Emisor']) ?></p>
            <p><strong>Fecha:</strong> <?= htmlspecialchars($mensaje['FechaHora']) ?></p>
        </div>

        <!-- Contenido del mensaje -->
        <div class="mensaje-body">
            <p><?= nl2br(htmlspecialchars($mensaje['Contenido'])) ?></p>
        </div>

        <!-- Botones -->
        <div class="mensaje-footer">
            <button onclick="history.back()" class="btn-volver">Volver</button>

            <?php if ($puedeResponder): ?>
            <button 
                class="btn-responder" 
                id="abrirModalResponder"
                data-emisor="<?= htmlspecialchars($mensaje['Emisor']) ?>"
                data-usuario="<?= $mensaje['IdUsuarioEmisor'] ?>"
                data-mensaje="<?= $mensaje['IdMensaje'] ?>"
                data-asunto="<?= htmlspecialchars($mensaje['Asunto']) ?>"
            >Responder</button>
            <?php endif; ?>

        </div>

    </div>
</div>

<!-- Modal de mensaje -->
<div id="modalMensaje" class="modal">
    <div class="modal-content">
        <span class="cerrar">&times;</span>
        <h2>Responder Mensaje a <span id="empresaNombre"></span></h2>
        <form id="formMensaje" action="/Proyecto/apps/controlador/ComunicaControlador.php" method="POST">
            <input type="hidden" id="empresaIdInput" name="empresa_id" value="">
            <input type="hidden" name="idMensajeRespondido" id="idMensajeRespondido" value="">
            <label for="asunto">Asunto</label>
            <input type="text" id="asunto" name="asunto" placeholder="Escribe el asunto..." required>
            <label for="contenido">Mensaje</label>
            <textarea id="contenido" name="contenido" rows="4" placeholder="Escribe tu mensaje..." required></textarea>
            <div id="contadorMensaje">0 / 1000 caracteres</div>
            <div id="mensajeExito" class="mensaje-exito" style="display: none;">
                ¡Mensaje enviado correctamente!
            </div>
            <div class="boton-contenedor">
                <button type="submit">Enviar</button>
            </div>
        </form>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/vistas/layout/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("modalMensaje");
    const btnResponder = document.getElementById("abrirModalResponder");
    const cerrar = document.querySelector("#modalMensaje .cerrar");
    const empresaIdInput = document.getElementById("empresaIdInput");
    const empresaNombre = document.getElementById("empresaNombre");
    const asuntoInput = document.getElementById("asunto");
    const contenidoTextarea = document.getElementById("contenido");
    const contador = document.getElementById("contadorMensaje");
    const idMensajeInput = document.getElementById("idMensajeRespondido");

    btnResponder.addEventListener("click", () => {
        empresaNombre.textContent = btnResponder.dataset.emisor;
        empresaIdInput.value = btnResponder.dataset.usuario; // empresa original
        idMensajeInput.value = btnResponder.dataset.mensaje;  // ID del mensaje
        asuntoInput.value = "Re: " + btnResponder.dataset.asunto;
        contenidoTextarea.value = "";
        contador.textContent = "0 / 1000 caracteres";
        modal.style.display = "block";
    });

    cerrar.addEventListener("click", () => { modal.style.display = "none"; });
    window.addEventListener("click", (event) => { if (event.target === modal) modal.style.display = "none"; });

    contenidoTextarea.addEventListener("input", () => {
        contador.textContent = `${contenidoTextarea.value.length} / 1000 caracteres`;
    });

    // Enviar mensaje vía fetch
    const form = document.getElementById("formMensaje");
    const mensajeExito = document.getElementById("mensajeExito");

    form.addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch(form.action, {
            method: form.method,
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if(data === "ok"){
                mensajeExito.style.display = "block";
                form.asunto.value = "";
                form.contenido.value = "";
                contador.textContent = "0 / 1000 caracteres";

                setTimeout(() => {
                    mensajeExito.style.display = "none";
                    modal.style.display = "none";
                }, 2000);
            } else {
                alert("Error al enviar el mensaje");
            }
        })
        .catch(error => console.error("Error:", error));
    });
});
</script>
</body>
</html>
