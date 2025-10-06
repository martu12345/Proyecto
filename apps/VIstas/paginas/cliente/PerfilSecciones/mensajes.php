<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/comunica.php');

$idCliente = $_SESSION['idUsuario'] ?? 0; // id del cliente logueado

// Mensajes recibidos y enviados
$mensajesRecibidos = Comunica::obtenerMensajesRecibidosPorCliente($conn, $idCliente);
$mensajesEnviados = Comunica::obtenerMensajesEnviadosPorCliente($conn, $idCliente);
?>

<h2>Mis mensajes</h2>
<button onclick="mostrarRecibidos()">Recibidos</button>
<button onclick="mostrarEnviados()">Enviados</button>

<!-- Recibidos -->
<div id="recibidos" class="mensajes-lista">
<?php if (empty($mensajesRecibidos)): ?>
    <p>No hay mensajes recibidos.</p>
<?php else: ?>
    <?php foreach ($mensajesRecibidos as $msg): ?>
        <div class="mensaje-item">
            <strong>De: <?= htmlspecialchars($msg['emisor']) ?></strong><br>
            <span class="preview"><?= htmlspecialchars(substr($msg['mensaje'], 0, 50)) ?>...</span><br>
            <span class="fecha"><?= htmlspecialchars($msg['fecha']) ?></span><br>
            <a href="/Proyecto/apps/Controlador/Mensaje/detalleMensajeControlador.php?id=<?= $msg['idMensaje'] ?>">Ver más</a>
        </div>
        <hr>
    <?php endforeach; ?>
<?php endif; ?>
</div>

<!-- Enviados -->
<div id="enviados" class="mensajes-lista" style="display:none;">
<?php if (empty($mensajesEnviados)): ?>
    <p>No hay mensajes enviados.</p>
<?php else: ?>
    <?php foreach ($mensajesEnviados as $msg): ?>
        <div class="mensaje-item">
            <strong>A: <?= htmlspecialchars($msg['destinatario']) ?></strong><br>
            <span class="preview"><?= htmlspecialchars(substr($msg['mensaje'], 0, 50)) ?>...</span><br>
            <span class="fecha"><?= htmlspecialchars($msg['fecha']) ?></span><br>
            <a href="/Proyecto/apps/Controlador/Mensaje/detalleMensajeControlador.php?id=<?= $msg['idMensaje'] ?>">Ver más</a>
        </div>
        <hr>
    <?php endforeach; ?>
<?php endif; ?>
</div>

<script>
function mostrarRecibidos() {
    document.getElementById('recibidos').style.display = 'block';
    document.getElementById('enviados').style.display = 'none';
}
function mostrarEnviados() {
    document.getElementById('recibidos').style.display = 'none';
    document.getElementById('enviados').style.display = 'block';
}
</script>

<link rel="stylesheet" href="/Proyecto/public/css/layout/mensajes_empresa.css">
<link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
