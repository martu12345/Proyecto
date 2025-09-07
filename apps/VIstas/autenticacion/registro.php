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
    <?php include '../layout/navbar.php'; ?>

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
            <form action="../../Controlador/controladorCliente.php" method="POST"> 
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Lucas" required>

                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" placeholder="Perez" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="yolucas@gmail.com" required>

                <label for="contraseña">Contraseña</label>
                <input type="password" id="contraseña" name="contraseña" placeholder="lucas123" required>

                <div class="login-buttons">
                    <button type="submit">Registrarse</button>
                    <button type="button" onclick="window.location.href='login.php'">Iniciar sesión</button>
                    <button type="button" onclick="window.location.href='/Proyecto/public/index.php'">Cancelar</button>
                </div>
            </form>
        </div>

        <!-- Formulario Empresa -->
        <div class="login-box" id="formulario-empresa" style="display:none;">
            <h2>Registro Empresa</h2>
            <form action="procesar_empresa.php" method="POST"> 
                <label for="nombre_empresa">Nombre</label>
                <input type="text" id="nombre_empresa" name="nombre_empresa" placeholder="Empresa S.A." required>

                <label for="email_empresa">Email</label>
                <input type="email" id="email_empresa" name="email" placeholder="contacto@empresaejemplo.com" required>

                <label for="password_empresa">Contraseña</label>
                <input type="password" id="password_empresa" name="password" placeholder="empresa123" required>

                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" placeholder="+598 91234567" required>

                <label for="calle">Calle</label>
                <input type="text" id="calle" name="calle" placeholder="Boulevar" required>

                <label for="numero">Número de calle</label>
                <input type="text" id="numero" name="numero" placeholder="1103" required>

                <div class="login-buttons">
                    <button type="submit">Registrarse</button>
                    <button type="button" onclick="window.location.href='login.php'">Iniciar sesión</button>
                    <button type="button" onclick="window.location.href='/Proyecto/public/index.php'">Cancelar</button>
                </div>
            </form>
        </div>

    </div>

    <?php include '../layout/footer.php'; ?>

    <script>
        function mostrarFormulario(tipo) {
            document.getElementById('tipo-cuenta-box').style.display = 'none';

            if(tipo === 'cliente') {
                document.getElementById('formulario-cliente').style.display = 'block';
            } else if(tipo === 'empresa') {
                document.getElementById('formulario-empresa').style.display = 'block';
            }
        }
    </script>
</body>
</html>
