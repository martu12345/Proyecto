<?php include __DIR__ . '/../../controlador/NavbarControlador.php'; ?>

<nav class="navbar">

    <div class="navbar-left">
        <div class="logo">
            <a href="<?php echo $home_url; ?>">
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
                    <option value="cerro_largo">Cerro Largo</option>
                    <option value="durazno">Durazno</option>
                    <option value="flores">Flores</option>
                    <option value="florida">Florida</option>
                    <option value="lavalleja">Lavalleja</option>
                    <option value="paysandu">Paysandú</option>
                    <option value="rio_negro">Río Negro</option>
                    <option value="rivera">Rivera</option>
                    <option value="san_jose">San José</option>
                    <option value="soriano">Soriano</option>
                    <option value="tacuarembo">Tacuarembó</option>
                    <option value="treinta_y_tres">Treinta y Tres</option>
                </select>
            </form>
        </div>
    </div>

    <div class="navbar-right">
        <div class="busqueda">
            <form action="/Proyecto/apps/controlador/BuscarControlador.php" method="POST">
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
                <?php if ($logueado): ?>
                    <a href="<?php echo $perfil_url; ?>">Mi perfil</a>
                    <a href="/Proyecto/apps/controlador/logout.php">Cerrar sesión</a>
                <?php else: ?>
                    <a href="/Proyecto/apps/vistas/autenticacion/login.php">Iniciar sesión</a>
                    <a href="/Proyecto/apps/vistas/autenticacion/registro.php">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
