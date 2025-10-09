<?php
session_start();
$servicios = $_SESSION['servicios'] ?? [];
$q = $_SESSION['ultima_busqueda'] ?? '';
$departamento = $_SESSION['departamento_seleccionado'] ?? '';
$estrellasSeleccionadas = $_SESSION['estrellas'] ?? 0;
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
                <!-- 🔸 Un solo formulario para palabra + departamento + estrellas -->
                <form action="/Proyecto/apps/controlador/servicio/BuscarControlador.php" method="POST" class="filtro">
                    <input type="hidden" name="q" value="<?= htmlspecialchars($q) ?>">
                    <input type="hidden" name="departamento" value="<?= htmlspecialchars($departamento) ?>">

                    <label>Filtrar por estrellas:</label>
                    <div class="rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" name="estrellas" id="estrella<?= $i ?>" value="<?= $i ?>"
                                <?= ($estrellasSeleccionadas == $i) ? 'checked' : '' ?>
                                onchange="this.form.submit()">
                            <label for="estrella<?= $i ?>">★</label>
                        <?php endfor; ?>
                    </div> <!-- cerramos el div.rating -->

                    <div class="rating-todas">
                        <input type="radio" name="estrellas" id="todas" value="0"
                            <?= ($estrellasSeleccionadas == 0) ? 'checked' : '' ?>
                            onchange="this.form.submit()">
                        <label for="todas">Todas</label>
                    </div>

        </form>
        </aside>

        <section class="columna-derecha">
            <h2 class="titulo-resultados"><?= count($servicios) ?> servicios encontrados</h2>
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