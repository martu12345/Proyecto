<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/controlador/empresa/PerfilEmpresaControlador.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manitas - Perfil Empresa</title>
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/empresa/perfil_empresa.css">
</head>

<body>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

    <div class="perfil-container">
        <div class="perfil-box">
            <div class="perfil-opciones">
                <button class="foto-circulo">+</button>
                <div class="nombre-usuario" id="nombreColumna">
                    <?php echo htmlspecialchars($datos['NombreEmpresa']); ?>
                </div>
                <div class="opciones-lista">
                    <a href="#" class="opcion activa">Mi perfil</a>
                    <a href="#" class="opcion">Mensajes</a>
                    <a href="#" class="opcion">Servicios</a>
                </div>
            </div>

            <div class="perfil-info">
                <h2>Mi Perfil Empresa</h2>
                <form id="formPerfilEmpresa">
                    <input type="checkbox" id="editarToggle" style="display:none;">

                    <div class="campo-perfil">
                        <label>Nombre Empresa:</label>
                        <span class="texto"><?php echo htmlspecialchars($datos['NombreEmpresa']); ?></span>
                        <input type="text" name="nombreEmpresa" class="input-campo" value="<?php echo htmlspecialchars($datos['NombreEmpresa']); ?>">
                    </div>

                    <div class="campo-perfil">
                        <label>Calle:</label>
                        <span class="texto"><?php echo htmlspecialchars($datos['Calle']); ?></span>
                        <input type="text" name="calle" class="input-campo" value="<?php echo htmlspecialchars($datos['Calle']); ?>">
                    </div>

                    <div class="campo-perfil">
                        <label>Número:</label>
                        <span class="texto"><?php echo htmlspecialchars($datos['Numero']); ?></span>
                        <input type="text" name="numero" class="input-campo" value="<?php echo htmlspecialchars($datos['Numero']); ?>">
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
    <script src="/Proyecto/public/js/empresa/perfil_empresa.js"></script>

</body>

</html>
