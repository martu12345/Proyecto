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
</head>
<body>

    <?php include '../layout/navbar.php'; ?>

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
                                            <!-- Falta hacer que funcione -->

                    </select>
                </div>
            </aside>

            <section class="columna-derecha">
                <h2 class="titulo-resultados">18 servicios</h2>
                                    <!-- Falta hacer que el numero se cuente con js -->


                <div id="resultados">
                    <div class="servicio">
                        <img src="/Proyecto/public/img/servicio1.jpg" alt="Servicio 1">
                        <div class="info-servicio">
                            <h3>Nombre del Servicio</h3>
                            <p>Descripción breve del servicio...</p>
                        </div>
                        <button class="btn-mas">+</button>
                    </div>

                    <div class="servicio">
                        <img src="/Proyecto/public/img/servicio2.jpg" alt="Servicio 2">
                        <div class="info-servicio">
                            <h3>Nombre del Servicio</h3>
                            <p>Descripción breve del servicio...</p>
                        </div>
                        <button class="btn-mas">+</button>
                    </div>

                    <!-- Falta hacer una plantilla aparte y llenar los datos con js -->

                </div>
            </section>

        </div>
    </main>

    <?php include '../layout/footer.php'; ?>

</body>
</html>
