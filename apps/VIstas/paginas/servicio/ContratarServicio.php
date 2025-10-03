<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/controlador/servicio/ContratarServicioControlador.php');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Contratar Servicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/servicio/ContratarServicio.css">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

    <div class="contratar-servicio-container">
        <h2><?= htmlspecialchars($servicio->getTitulo()) ?></h2>
        <p>Precio: $<?= number_format($servicio->getPrecio(), 0, '', '.') ?></p>
        <p>Empresa: <?= htmlspecialchars($empresa['NombreEmpresa'] ?? 'No disponible') ?></p>

        <form action="" method="POST" id="formServicio">
            <input type="hidden" name="idServicio" value="<?= $servicio->getIdServicio() ?>">
            <input type="hidden" name="dia" id="inputDia">
            <input type="time" name="hora" id="hora" required>
            <button type="submit">Agendar Servicio</button>
        </form>
        
        <div id="errorHora"></div>

        <div class="calendario-header">
            <span id="prevMes" class="flecha">&#8592;</span>
            <div id="tituloMes" class="tituloMes"></div>
            <span id="nextMes" class="flecha">&#8594;</span>
        </div>
        <div id="calendario" class="calendario"></div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>

    <!-- Variables PHP para JS externo -->
    <script>
        window.citasOcupadas = <?= json_encode($citasOcupadas) ?>;
        window.duracionServicio = <?= $duracion ?>;
        window.meses = <?= json_encode($meses) ?>;
    </script>

    <script src="/Proyecto/public/js/servicio/contratarServicio.js"></script>
</body>

</html>