<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manitas - Crear Administrador</title>
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/registro.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">

</head>

<body class="historial-cambios">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>


    <div class="login-background">
        <div class="login-box">
            <h2>Crear Administrador</h2>
            <form action="/Proyecto/apps/Controlador/administrador/AdministradorControlador.php" method="POST">
                <input type="hidden" name="accion" value="crear">

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="admin@manitas.com" required>

                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" placeholder="099123456" pattern="[0-9]{9}" title="Ingrese un número uruguayo de 9 dígitos" maxlength="9" required>


                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" placeholder="********" required>


                <div class="login-buttons">
                    <button type="submit">Crear</button>
                    <button type="button" onclick="window.location.href='/Proyecto/apps/vistas/home_propietario.php'">Cancelar</button>
                </div>

                <div id="mensaje-error-admin" style="color:red; margin-top:10px;"></div>
            </form>
        </div>
    </div>
    <script src="/Proyecto/public/js/propietario/crear_admin.js"></script>
    <script src="/Proyecto/public/js/validaciones/validacionRegistroAdmin.js"></script>

</body>

</html>