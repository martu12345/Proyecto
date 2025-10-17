<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manitas - Home Propietario</title>
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/registro.css">

</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>


    <div class="login-background">
        <div class="tipo-cuenta-box" id="opciones-admin-box">
            <h2>¿Qué deseas hacer?</h2>
            <div class="tipo-cuenta-buttons">
                <button type="button" onclick="window.location.href='/Proyecto/apps/vistas/paginas/propietario/crear_admin.php'">
                    Crear Administrador
                </button>
                <button type="button" onclick="window.location.href='/Proyecto/apps/vistas/paginas/propietario/eliminar_admin.php'">
                    Dar de Baja Administrador
                </button>
                <button type="button" onclick="window.location.href='/Proyecto/apps/vistas/paginas/propietario/historial_cambios.php'">
                    Ver Cambios de Admin
                </button>
            </div>

        </div>
    </div>


    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>
</body>


</html>