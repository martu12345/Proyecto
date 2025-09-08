<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manitas - Login</title>
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/login.css">
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

    <div class="login-background">
        <div class="login-box">
            <h2>Inicio de sesión</h2>
            <form action="procesar_login.php" method="POST"> <!-- Despues hay que redireccionar a el php correcto -->

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Tu email" required>

                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Tu contraseña" required>

                <div class="login-buttons">
                    <button type="submit">Iniciar sesión</button>
                    <button type="button" onclick="window.location.href='registro.php'"> Registrarse</button>
                    <button type="button" onclick="window.location.href='/Proyecto/public/index.php'">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>
</body>
</html>
