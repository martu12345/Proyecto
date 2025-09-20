<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manitas - Registro</title>
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/registro.css">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

    <div class="login-background">

        <div class="tipo-cuenta-box" id="tipo-cuenta-box">
            <h2>¿Qué tipo de cuenta deseas crear?</h2>
            <div class="tipo-cuenta-buttons">
                <button type="button" onclick="mostrarFormulario('cliente')">Cuenta de Cliente</button>
                <button type="button" onclick="mostrarFormulario('empresa')">Cuenta de Empresa</button>
            </div>
        </div>

        <!-- Formulario Cliente -->
        <div class="login-box" id="formulario-cliente" style="display:none;">
            <h2>Registro Cliente</h2>
            <form id="formulario-cliente" action="/Proyecto/apps/Controlador/cliente/ClienteControlador.php" method="POST">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Lucas" required>

                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" placeholder="Perez" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="yolucas@gmail.com" required>

                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" placeholder="099123456" pattern="[0-9]{9}" title="Ingrese un número uruguayo de 9 dígitos" maxlength="9" required>


                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" placeholder="lucas123" required>

                <div class="login-buttons">
                    <button type="submit">Registrarse</button>
                    <button type="button" onclick="window.location.href='login.php'">Iniciar sesión</button>
                    <button type="button" onclick="window.location.href='/Proyecto/public/index.php'">Cancelar</button>
                        <div id="mensaje-error-cliente" style="color:red; margin-top:10px;"></div>

                </div>
            </form>
        </div>


        <!-- Formulario Empresa -->
        <div class="login-box" id="formulario-empresa" style="display:none;">
            <h2>Registro Empresa</h2>
            <form action="/Proyecto/apps/Controlador/empresa/EmpresaControlador.php" method="POST">
                <label for="nombreEmpresa">Nombre</label>
                <input type="text" id="nombreEmpresa" name="nombreEmpresa" placeholder="Empresa S.A." required>

                <label for="email_empresa">Email</label>
                <input type="email" id="emailEmpresa" name="emailEmpresa" placeholder="contacto@empresaejemplo.com" required>

                <label for="contrasenaEmpresa">Contraseña</label>
                <input type="password" id="contrasenaEmpresa" name="contrasenaEmpresa" placeholder="empresa123" required>

                <label for="telefonoEmpresa">Teléfono</label>
                <input type="text" id="telefonoEmpresa" name="telefonoEmpresa" placeholder="+598 91234567" required>

                <label for="calle">Calle</label>
                <input type="text" id="calle" name="calle" placeholder="Boulevar" required>

                <label for="numero">Número de calle</label>
                <input type="text" id="numero" name="numero" placeholder="1103" required>

                <div class="login-buttons">
                    <button type="submit" id>Registrarse</button>
                    <button type="button" onclick="window.location.href='login.php'">Iniciar sesión</button>
                    <button type="button" onclick="window.location.href='/Proyecto/public/index.php'">Cancelar</button>
                            <div id="mensaje-error-empresa" style="color:red; margin-top:10px;"></div>

                </div>
            </form> 
        </div>

    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>
    <script src="/Proyecto/public/js/mostrar_formulario.js"></script>

    <script src="/Proyecto/public/js/validaciones/ValidacionRegistro.js"></script> 

</body>

</html>