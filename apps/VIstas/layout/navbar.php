<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Controlador/NavbarControlador.php');

// Para departamentos y búsqueda
$departamento_seleccionado = $_SESSION['departamento_seleccionado'] ?? '';
$ultima_busqueda = $_SESSION['ultima_busqueda'] ?? '';
?>

<nav class="navbar">

    <!-- IZQUIERDA: LOGO + DEPARTAMENTOS -->
    <div class="navbar-left">

        <!-- Logo -->
        <div class="logo">
            <a href="<?= $home_url ?>">
                <img src="/Proyecto/public/imagen/logo/logo_manitas.png" alt="Logo">
            </a>
        </div>

        <!-- Selector de Departamento -->
        <div class="departamentos">
            <form id="form-departamento" action="/Proyecto/apps/controlador/servicio/BuscarControlador.php" method="POST">
                <select name="departamento" onchange="document.getElementById('form-departamento').submit()">
                    <option value="">Departamento</option>
                    <?php
                    $departamentos = [
                        "montevideo"=>"Montevideo","canelones"=>"Canelones","maldonado"=>"Maldonado",
                        "colonia"=>"Colonia","rocha"=>"Rocha","salto"=>"Salto","artigas"=>"Artigas",
                        "cerro_largo"=>"Cerro Largo","durazno"=>"Durazno","flores"=>"Flores","florida"=>"Florida",
                        "lavalleja"=>"Lavalleja","paysandu"=>"Paysandú","rio_negro"=>"Río Negro","rivera"=>"Rivera",
                        "san_jose"=>"San José","soriano"=>"Soriano","tacuarembo"=>"Tacuarembó","treinta_y_tres"=>"Treinta y Tres"
                    ];
                    foreach ($departamentos as $key => $nombre) {
                        $selected = ($departamento_seleccionado === $key) ? 'selected' : '';
                        echo "<option value='$key' $selected>$nombre</option>";
                    }
                    ?>
                </select>
                <input type="hidden" name="q" value="<?= htmlspecialchars($ultima_busqueda) ?>">
            </form>
        </div>

    </div>

    <!-- DERECHA: BUSCADOR + PERFIL -->
    <div class="navbar-right">

        <!-- Barra de búsqueda -->
        <div class="busqueda">
            <form id="form-buscador" action="/Proyecto/apps/controlador/servicio/BuscarControlador.php" method="POST">
                <input type="text" name="q" id="buscador" placeholder="Buscar..." value="<?= htmlspecialchars($ultima_busqueda) ?>">
                <input type="hidden" name="departamento" value="<?= $departamento_seleccionado ?>">
                <button type="submit">
                    <img src="/Proyecto/public/imagen/icono/icono_busqueda.png" alt="Buscar">
                </button>
            </form>
        </div>

        <!-- Perfil -->
        <div class="perfil">
            <div class="perfil-btn">
                <a href="<?= $perfil_url ?>" class="icono-notificacion-navbar">
                    <img src="/Proyecto/public/imagen/icono/icono_avatar.png" alt="Mi perfil">

                    <!-- Puntitos rojos de notificaciones -->
                    <?php if($notificacionesMensajes > 0): ?>
                        <span class="punto-rojo mensajes"></span>
                    <?php endif; ?>
                    <?php if($notificacionesAgendados > 0): ?>
                        <span class="punto-rojo agendados"></span>
                    <?php endif; ?>

                </a>
            </div>

            <div class="perfil-menu">
                <?php if ($logueado): ?>
                    <a href="<?= $perfil_url ?>">Mi perfil</a>
                    <a href="/Proyecto/apps/controlador/LogoutControlador.php">Cerrar sesión</a>
                <?php else: ?>
                    <a href="/Proyecto/apps/vistas/autenticacion/login.php">Iniciar sesión</a>
                    <a href="/Proyecto/apps/vistas/autenticacion/registro.php">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <script src="/Proyecto/public/js/servicio/buscador.js" defer></script>

</nav>
