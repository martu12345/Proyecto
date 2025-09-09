<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manitas</title>
        <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
        <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
        <link rel="stylesheet" href="/Proyecto/public/css/layout/frase.css">
        <link rel="stylesheet" href="/Proyecto/public/css/layout/secciones.css">
        <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
        <link rel="stylesheet" href="/Proyecto/public/css/paginas/empresa/empresa_home.css">
    </head>
    <body>
    
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/frase.php'; ?>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/secciones.php'; ?>

        <button id="btnCrearServicio" class="btn-float">+</button>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/modal_servicio.php'; ?>
        <link rel="stylesheet" href="/Proyecto/public/css/layout/modal_servicio.css">

        <script src="/Proyecto/public/js/secciones.js"></script>
        <script src="/Proyecto/public/js/modal_servicio.js"></script>


        <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>
    <body>
</html>