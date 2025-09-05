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

        <div class="tipo-cuenta-box">
            <h2>¿Qué tipo de cuenta deseas crear?</h2>
            <div class="tipo-cuenta-buttons">
                <button type="button" onclick="mostrarFormulario('cliente')">Cuenta de Cliente</button>
                <button type="button" onclick="mostrarFormulario('empresa')">Cuenta de Empresa</button>
            </div>
        </div>


        <div class="login-box" id="formulario-registro" style="display:none;">
            <h2>Registro</h2>
            <form action="procesar_registro.php" method="POST"> 

                <!-- Campos para cliente -->
                <div id="campos-cliente" style="display:none;">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Lucas" required>

                    <label for="apellido">Apellido</label>
                    <input type="text" id="apellido" name="apellido" placeholder="Perez" required>

                    <label for="email-cliente">Email</label>
                    <input type="email" id="email-cliente" name="email" placeholder="yolucas@gmail.com" required>

                    <label for="password-cliente">Contraseña</label>
                    <input type="password" id="password-cliente" name="password" placeholder="lucas123" required>
                </div>

        
                <div id="campos-empresa" style="display:none;">
                    <label for="nombre_empresa">Nombre</label>
                    <input type="text" id="nombre_empresa" name="nombre_empresa" placeholder="Empresa S.A." required>

                    <label for="email-empresa">Email</label>
                    <input type="email" id="email-empresa" name="email" placeholder="contacto@empresaejemplo.com" required>

                    <label for="password-empresa">Contraseña</label>
                    <input type="password" id="password-empresa" name="password" placeholder="empresa123" required>

                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" placeholder="+598 91234567" required>


                    <label for="calle">Calle</label>
                    <input type="text" id="calle" name="calle" placeholder="Boulevar" required>

                    <label for="numero">Número de calle</label>
                    <input type="text" id="numero" name="numero" placeholder="1103" required>
                </div>

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
            document.querySelector('.tipo-cuenta-box').style.display = 'none';
            document.getElementById('formulario-registro').style.display = 'block';

            if(tipo === 'cliente') {
                document.getElementById('campos-cliente').style.display = 'block';
                document.getElementById('campos-empresa').style.display = 'none';
            } else if(tipo === 'empresa') {
                document.getElementById('campos-empresa').style.display = 'block';
                document.getElementById('campos-cliente').style.display = 'none';
            }
        }
    </script>
</body>
</html>
