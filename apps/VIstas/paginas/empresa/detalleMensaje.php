<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del mensaje</title>
<link rel="stylesheet" href="../../../public/css/fonts.css">
<link rel="stylesheet" href="../../../public/css/layout/navbar.css">
<link rel="stylesheet" href="../../../public/css/layout/footer.css">
<link rel="stylesheet" href="../../../public/css/layout/detalles_Mensaje.css">

</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

    <div class="detalle-mensaje">
        <div class="mensaje-contenedor">
            <h2><?= htmlspecialchars($mensaje['Asunto']) ?></h2>
            <p><strong>Emisor:</strong> <?= htmlspecialchars($mensaje['Emisor']) ?></p>
            <p><strong>Fecha:</strong> <?= htmlspecialchars($mensaje['FechaHora']) ?></p>
            <div class="contenido">
                <?= nl2br(htmlspecialchars($mensaje['Contenido'])) ?>
            </div>
                        <button onclick="history.back()" class=btn-volver>Volver</button>

        </div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>
</body>
</html>
