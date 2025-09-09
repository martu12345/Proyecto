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
                <form action="/Proyecto/apps/controlador/BuscarControlador.php" method="POST" class="filtro">
                    <div class="rating-container">
                        <label>Filtrar por estrellas:</label>
                        <div class="rating">
                            <input type="radio" name="estrellas" id="estrella5" value="5">
                            <label for="estrella5" title="5 estrellas">★</label>

                            <input type="radio" name="estrellas" id="estrella4" value="4">
                            <label for="estrella4" title="4 estrellas">★</label>

                            <input type="radio" name="estrellas" id="estrella3" value="3">
                            <label for="estrella3" title="3 estrellas">★</label>

                            <input type="radio" name="estrellas" id="estrella2" value="2">
                            <label for="estrella2" title="2 estrellas">★</label>

                            <input type="radio" name="estrellas" id="estrella1" value="1">
                            <label for="estrella1" title="1 estrella">★</label>
                        </div>
                    </div>

                    <button type="submit" class="btn-filtrar">Filtrar</button>
                </form>
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