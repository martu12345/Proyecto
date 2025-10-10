<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/comunica.php');

$idMensaje = $_GET['id'] ?? 0;
$mensaje = Comunica::obtenerMensajePorId($conn, $idMensaje);

// Validar que el mensaje exista
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
        <h2><?= htmlspecialchars($mensaje['Asunto']) ?></h2>
        <p><strong>Emisor:</strong> <?= htmlspecialchars($mensaje['Emisor']) ?></p>
        <p><strong>Fecha:</strong> <?= htmlspecialchars($mensaje['FechaHora']) ?></p>
        <div class="contenido">
            <?= nl2br(htmlspecialchars($mensaje['Contenido'])) ?>
        </div>

        <!-- Botón Volver -->
        <button onclick="history.back()" class="btn-volver">Volver</button>

        <!-- Botón Responder con data-attributes -->
        <button 
            class="btn-responder" 
            id="abrirModalResponder"
            data-emisor="<?= htmlspecialchars($mensaje['IdUsuarioEmisor']) ?>"
            data-id="<?= $mensaje['IdUsuarioEmisor'] ?>"
            data-asunto="<?= htmlspecialchars($mensaje['Asunto']) ?>"
        >Responder</button>
    </div>
</div>

<!-- Modal de mensaje -->
<div id="modalMensaje" class="modal">
    <div class="modal-content">
        <span class="cerrar">&times;</span>
        <h2>Enviar Mensaje a <span id="empresaNombre"></span></h2>
        <form id="formMensaje" 
        action="/Proyecto/apps/controlador/ComunicaControlador.php" 
        method="POST">
            <input type="hidden" id="empresaIdInput" name="empresa_id" value="">
            <input type="hidden" name="idMensajeRespondido" id="idMensajeRespondido" value="">
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

<?php include $_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/vistas/layout/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("modalMensaje");
    const btnResponder = document.getElementById("abrirModalResponder");
    document.getElementById("idMensajeRespondido").value = btnResponder.dataset.id;

    const cerrar = document.querySelector("#modalMensaje .cerrar");
    const empresaIdInput = document.getElementById("empresaIdInput");
    const empresaNombre = document.getElementById("empresaNombre");
    const asuntoInput = document.getElementById("asunto");
    const contenidoTextarea = document.getElementById("contenido");
    const contador = document.getElementById("contadorMensaje");

    // Abrir modal al hacer clic en "Responder"
    btnResponder.addEventListener("click", () => {
        empresaNombre.textContent = btnResponder.dataset.emisor;
        empresaIdInput.value = btnResponder.dataset.id;
        asuntoInput.value = "Re: " + btnResponder.dataset.asunto;
        contenidoTextarea.value = "";
        contador.textContent = "0 / 1000 caracteres";
        modal.style.display = "block";
    });

    // Cerrar modal al hacer clic en X
    cerrar.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Cerrar modal al hacer clic afuera
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // Contador de caracteres
    contenidoTextarea.addEventListener("input", () => {
        contador.textContent = `${contenidoTextarea.value.length} / 1000 caracteres`;
    });
});
</script>

</body>
</html>
