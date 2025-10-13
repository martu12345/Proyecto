<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/controlador/administrador/MostrarDenunciaControlador.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Denuncias Admin</title>
<link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">

<link rel="stylesheet" href="/Proyecto/public/css/paginas/administrador/admin_denuncias.css">

</head>
<body>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

<h1>Denuncias</h1>


<!-- Botones para filtrar -->
<div class="modal-buttons">
    <a href="?tipo=DenunciarServicio">
        <button class="<?= ($_GET['tipo'] ?? 'DenunciarServicio') === 'DenunciarServicio' ? 'selected' : '' ?>">Denuncias de Servicio</button>
    </a>
    <a href="?tipo=DenunciarCliente">
        <button class="<?= ($_GET['tipo'] ?? 'DenunciarServicio') === 'DenunciarCliente' ? 'selected' : '' ?>">Denuncias de Cliente</button>
    </a>
</div>


<ul class="servicios-list">
<?php if(count($denuncias ?? []) === 0): ?>
    <p class="no-denuncias">No hay denuncias de este tipo.</p>
<?php else: ?>
    <?php foreach($denuncias as $denuncia): ?>
        <li class="servicio-item">
            <div class="info">
                <span class="nombre">ID Denuncia: <?= $denuncia->getIdDenuncia() ?></span>
                <span class="descripcion">Motivo: <?= $denuncia->getMotivo() ?></span>
                <span class="empresa">Cliente ID: <?= $denuncia->getIdCliente() ?> | Empresa ID: <?= $denuncia->getIdEmpresa() ?></span>
                <span class="empresa">Fecha: <?= $denuncia->getFecha() ?></span>
            </div>
        </li>
    <?php endforeach; ?>
<?php endif; ?>
</ul>
</body>
</html>
