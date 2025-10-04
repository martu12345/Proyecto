<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/comunica.php');

$idEmpresa = $_SESSION['idUsuario'] ?? 0; // id de la empresa logueada

// Obtenemos todos los mensajes que llegaron a la empresa
$mensajes = Comunica::obtenerMensajesParaEmpresa($conn, $idEmpresa);
?>

<h2>Mensajes recibidos</h2>
<div class="mensajes-lista">
<?php if (empty($mensajes)): ?>
    <p>No hay mensajes.</p>
<?php else: ?>
    <?php foreach ($mensajes as $msg): ?>
        <div class="mensaje-item">
            <strong>De: <?= htmlspecialchars($msg['emisor']) ?></strong><br>
            <span class="preview"><?= htmlspecialchars(substr($msg['mensaje'], 0, 50)) ?>...</span><br>
            <span class="fecha"><?= htmlspecialchars($msg['fecha']) ?></span><br>
            <a href="/Proyecto/apps/vistas/paginas/empresa/detalleMensaje.php?id=<?= $msg['idMensaje'] ?>">Ver más</a>
        </div>
        <hr>
    <?php endforeach; ?>
<?php endif; ?>
</div>

<link rel="stylesheet" href="/Proyecto/public/css/layout/mensajes_empresa.css">
<link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
