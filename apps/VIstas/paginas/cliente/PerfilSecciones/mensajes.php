<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
<link rel="stylesheet" href="/Proyecto/public/css/layout/mensajes_empresa.css">
<link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
</head>
<body>
    
<h2>Mis mensajes</h2>
    <div class="botones-mensajes">
        <button id="btnRecibidos" onclick="mostrarRecibidos()" class="active">Recibidos</button>
        <button id="btnEnviados" onclick="mostrarEnviados()">Enviados</button>
    </div>

    <!-- Recibidos -->
    <div id="recibidos" class="mensajes-lista">
        <?php if (empty($mensajesRecibidos)): ?>
            <p>No hay mensajes recibidos.</p>
        <?php else: ?>
            <?php foreach ($mensajesRecibidos as $msg):
                $mensajePadre = !empty($msg['IdMensajePadre']) ? Comunica::obtenerMensajePorId($conn, $msg['IdMensajePadre']) : null;
            ?>
                <div class="mensaje-item">
                    <strong>De: <?= htmlspecialchars($msg['emisor']) ?></strong><br>
                    <?php if ($mensajePadre): ?>
                        <span class="respuesta-indicador">
                            💬 Responde a <?= htmlspecialchars($mensajePadre['Emisor']) ?>:
                            "<?= htmlspecialchars(substr($mensajePadre['Asunto'], 0, 50)) ?><?= strlen($mensajePadre['Contenido']) > 50 ? '…' : '' ?>"
                        </span><br>
                    <?php endif; ?>
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
            <?php foreach ($mensajesEnviados as $msg):
                $mensajePadre = !empty($msg['IdMensajePadre']) ? Comunica::obtenerMensajePorId($conn, $msg['IdMensajePadre']) : null;
            ?>
                <div class="mensaje-item">
                    <strong>A: <?= htmlspecialchars($msg['destinatario']) ?></strong><br>
                    <?php if ($mensajePadre): ?>
                        <span class="respuesta-indicador">
                            💬 Respondiste a <?= htmlspecialchars($mensajePadre['Emisor']) ?>:
                            "<?= htmlspecialchars(substr($mensajePadre['Asunto'], 0, 50)) ?><?= strlen($mensajePadre['Contenido']) > 50 ? '…' : '' ?>"
                        </span><br>
                    <?php endif; ?>
                    <span class="preview"><?= htmlspecialchars(substr($msg['mensaje'], 0, 50)) ?>...</span><br>
                    <span class="fecha"><?= htmlspecialchars($msg['fecha']) ?></span><br>
                    <a href="/Proyecto/apps/Controlador/Mensaje/detalleMensajeControlador.php?id=<?= $msg['idMensaje'] ?>">Ver más</a>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="/Proyecto/public/js/cliente/mensajes.js"></script>

</body>
</html>