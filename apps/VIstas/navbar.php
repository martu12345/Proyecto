<nav class="navbar">
    <!-- Logo -->
    <div class="logo">
        <a href="index.php">
            <img src="/Proyecto/public/imagen/logo/logo_manitas.png" >
        </a>
    </div>

    <!-- Eleccion de departamentos -->
    <div class="departamentos">
        <form action="departamento.php" method="POST">
            <select name="departamento">
                <option value="">Seleccione departamento</option>
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

    <!-- Barra de búsqueda -->
    <div class="busqueda">
        <form action="buscar.php" method="POST">
            <input type="text" name="q" placeholder="Buscar...">
            <button type="submit">
                <img src="/Proyecto/public/imagen/icono/icono_busqueda.png">
            </button>
        </form>
    </div>

    <!-- Perfil -->
    <div class="perfil">
        <form action="perfil.php" method="POST">
            <button type="submit">
                <img src="/Proyecto/public/imagen/icono/icono_avatar.png" alt="Mi perfil">
            </button>
        </form>
    </div>
</nav>
