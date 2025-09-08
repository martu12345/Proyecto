<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>
<nav class="navbar">

    <div class="navbar-left">
        <div class="logo">
            <a href="/Proyecto/public/index.php">
                <img src="/Proyecto/public/imagen/logo/logo_manitas.png" alt="Logo">
            </a>
        </div>

        <div class="departamentos">
            <form action="departamento.php" method="POST">
                <select name="departamento">
                    <option value="">Departamento</option>
                    <option value="montevideo">Montevideo</option>
                    <option value="canelones">Canelones</option>
                    <option value="maldonado">Maldonado</option>
                    <option value="colonia">Colonia</option>
                    <option value="rocha">Rocha</option>
                    <option value="salto">Salto</option>
                    <option value="artigas">Artigas</option>
                </select>
            </form>
        </div>
    </div>

    <div class="navbar-right">
        <div class="busqueda">
            <form action="/Proyecto/apps/controlador/buscar.php"  method="POST">
                <input type="text" name="q" placeholder="Buscar...">
                <button type="submit">
                    <img src="/Proyecto/public/imagen/icono/icono_busqueda.png" alt="Buscar">
                </button>
            </form>
        </div>

                    


        <div class="perfil">
            <div class="perfil-btn">
                <img src="/Proyecto/public/imagen/icono/icono_avatar.png" alt="Mi perfil">
            </div>
            <div class="perfil-menu">
                <a href=" /Proyecto/apps/Controlador/buscar.php">Iniciar sesión</a>
                <a href="/Proyecto/apps/vistas/autenticacion/registro.php">Registrarse</a>
            </div>
        </div>

    </div>
</nav>
