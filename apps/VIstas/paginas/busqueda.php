<?php
session_start();

// trae la info del controlador
$servicios = isset($_SESSION['servicios']) ? $_SESSION['servicios'] : [];
unset($_SESSION['servicios']); // esto limpia la sesion despues de usarla
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Servicios</title>

    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/busqueda.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/plantilla_servicio.css">
</head>
<body>

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'); ?>

    <main>
        <div class="contenedor-principal">

            <aside class="columna-izquierda">
                <div class="filtro">
                    <h3>Filtro</h3>
                    <label for="estrellas">Filtrar por estrellas:</label>
                    <select id="estrellas" name="estrellas">
                        <option value="">Todas</option>
                        <option value="5">5 estrellas</option>
                        <option value="4">4 estrellas</option>
                        <option value="3">3 estrellas</option>
                        <option value="2">2 estrellas</option>
                        <option value="1">1 estrella</option>
                    </select>
                </div>
            </aside>

            <section class="columna-derecha">
                <h2 class="titulo-resultados"><?= count($servicios) ?> servicios</h2>
                <div id="resultados">
                    <?php if (!empty($servicios)): ?>
                        <?php foreach ($servicios as $servicio): ?>
                            <?php include($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/plantilla_servicio.php'); ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No se encontraron servicios.</p>
                    <?php endif; ?>
                </div>
            </section>

        </div>
    </main>

    <?php include_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'); ?>

</body>
</html>
