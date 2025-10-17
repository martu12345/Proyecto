<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Servicio Agendado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/servicio/ConfirmacionServicio.css">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

    <div class="confirmacion-background"></div>

    <div class="confirmacion-wrapper">
        <div class="confirmacion-container">
            <h1>¡Servicio Agendado con Éxito!</h1>

            <?php if ($titulo && $dia && $hora): ?>
                <p><strong>Servicio:</strong> <?= htmlspecialchars($titulo) ?></p>
                <p><strong>Día:</strong> <?= htmlspecialchars($dia) ?></p>
                <p><strong>Hora:</strong> <?= htmlspecialchars($hora) ?></p>
            <?php endif; ?>

            <p>Te avisaremos cuando la empresa confirme el horario.</p>
            <button onclick="window.location.href='/Proyecto/apps/vistas/paginas/busqueda.php'">Volver</button>
        </div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>
</body>

</html>