<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manitas</title>
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
    <link rel="stylesheet" href="/Proyecto/public/css/index.css">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

    <section class="hero">
        <div class="hero-title">Porque siempre hay algo que arreglar</div>

        <div class="hero-buttons">
            <a href="/Proyecto/apps/vistas/autenticacion/login.php" class="hero-btn">Iniciar sesión</a>
            <a href="/Proyecto/apps/vistas/autenticacion/registro.php" class="hero-btn">Registrarse</a>
        </div>

        <div class="categories">
            <div class="category"><img src="/Proyecto/public/imagen/icono/icono_casita.png" alt="Hogar"><span>Hogar</span></div>
            <div class="category"><img src="/Proyecto/public/imagen/icono/icono_coche.png" alt="Autos"><span>Autos</span></div>
            <div class="category"><img src="/Proyecto/public/imagen/icono/icono_crema.png" alt="Belleza"><span>Belleza</span></div>
            <div class="category"><img src="/Proyecto/public/imagen/icono/icono_cuidar.png"alt="Cuidados"><span>Cuidados</span></div>
            <div class="category"><img src="/Proyecto/public/imagen/icono/icono_digital.png" alt="Digital"><span>Digital</span></div>
            <div class="category"><img src="/Proyecto/public/imagen/icono/icono_cubiertos.png" alt="Cocina"><span>Cocina</span></div>
            <div class="category"><img src="/Proyecto/public/imagen/icono/icono_salud.png"alt="Salud"><span>Salud</span></div>
            <div class="category"><img src="/Proyecto/public/imagen/icono/icono_mascotas.png" alt="Mascotas"><span>Mascotas</span></div>
            <div class="category"><img src="/Proyecto/public/imagen/icono/icono_eventos.png" alt="Eventos"><span>Eventos</span></div>
            <div class="category"><img src="/Proyecto/public/imagen/icono/icono_educacion.png" alt="Educación"><span>Educación</span></div>
            <div class="category"><img src="/Proyecto/public/imagen/icono/icono_transporte.png" alt="Transporte"><span>Transporte</span></div>
            <div class="category"><img src="/Proyecto/public/imagen/icono/icono_arte.png"alt="Arte y Cultura"><span>Arte y Cultura</span></div>
        </div>
    </section>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>
</body>
</html>
