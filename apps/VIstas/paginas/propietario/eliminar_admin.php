<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manitas - Dar de baja Administrador</title>
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/registro.css">
</head>

<body>
    <div class="login-background">
        <div class="login-box">
            <h2>Dar de baja Administrador</h2>
            <form id="formBorrarAdmin" method="POST">
                <input type="hidden" name="accion" value="eliminar">

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="admin@manitas.com" required>

                <div class="login-buttons">
                    <button type="submit">Eliminar</button>
                    <button type="button" onclick="window.location.href='/Proyecto/apps/vistas/home_propietario.php'">Cancelar</button>
                </div>

                <div id="mensaje-error-admin" style="color:red; margin-top:10px;"></div>
            </form>
        </div>
    </div>

    <!-- Vinculás el script externo -->
    <script src="/Proyecto/public/js/propietario/dar_baja_admin.js"></script>
</body>

</html>

