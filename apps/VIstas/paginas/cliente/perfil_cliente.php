<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/controlador/cliente/PerfilControlador.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manitas - Perfil Cliente</title>
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
    <link rel="stylesheet" href="../../../../public/css/paginas/cliente/perfil_cliente.css">
</head>

<body>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

    <div class="perfil-container">
        <div class="perfil-box">
            <div class="perfil-opciones">
                <button class="foto-circulo">+</button>
                <div class="nombre-usuario" id="nombreColumna">
                    <?php echo htmlspecialchars($datos['Nombre'] . ' ' . $datos['Apellido']); ?>
                </div>
                <div class="opciones-lista">
                    <a href="#" class="opcion activa">Mi perfil</a>
                    <a href="#" class="opcion">Mensajes</a>
                    <a href="#" class="opcion">Servicios</a>
                </div>
            </div>

            <div class="perfil-info">
                <h2>Mi Perfil</h2>
                <form id="formPerfil">
                    <input type="checkbox" id="editarToggle" style="display:none;">

                    <div class="campo-perfil">
                        <label>Nombre:</label>
                        <span class="texto"><?php echo htmlspecialchars($datos['Nombre']); ?></span>
                        <input type="text" name="nombre" class="input-campo" value="<?php echo htmlspecialchars($datos['Nombre']); ?>">
                    </div>

                    <div class="campo-perfil">
                        <label>Apellido:</label>
                        <span class="texto"><?php echo htmlspecialchars($datos['Apellido']); ?></span>
                        <input type="text" name="apellido" class="input-campo" value="<?php echo htmlspecialchars($datos['Apellido']); ?>">
                    </div>

                    <div class="campo-perfil">
                        <label>Email:</label>
                        <span class="texto"><?php echo htmlspecialchars($datos['Email']); ?></span>
                        <input type="email" name="email" class="input-campo" value="<?php echo htmlspecialchars($datos['Email']); ?>">
                    </div>

                    <div class="campo-perfil">
                        <label>Contraseña:</label>
                        <span class="texto">********</span>
                        <input type="password" name="contrasena" class="input-campo" placeholder="Nueva contraseña">
                    </div>

                    <div class="botones-perfil">
                        <label id="btnEditar">Editar</label>
                        <button type="submit" id="btnGuardar">Guardar</button>
                        <label id="btnCancelar">Cancelar</label>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>
    <script src="/Proyecto/public/js/cliente/perfil_cliente.js"></script>

</body>

</html>