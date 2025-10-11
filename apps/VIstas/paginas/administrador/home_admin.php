<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manitas - Home Propietario</title>
<link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
<link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
<link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
<link rel="stylesheet" href="/Proyecto/public/css/paginas/homePropietario.css">
<link rel="stylesheet" href="/Proyecto/public/css/paginas/registro.css">
</head>

<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

<div class="login-background">
    <div class="tipo-cuenta-box" id="opciones-admin-box">
        <h2>¿Qué deseas hacer?</h2>
        <div class="tipo-cuenta-buttons">
            <button type="button" onclick="window.location.href='/Proyecto/apps/vistas/paginas/administrador/admin_servicios.php'">
                Administrar Servicios
            </button>
            <button type="button" onclick="window.location.href='/Proyecto/apps/vistas/paginas/administrador/admin_clientes.php'">
                Administrar Clientes
            </button>
            <button type="button" onclick="window.location.href='/Proyecto/apps/vistas/paginas/administrador/admin_empresas.php'">
                Administrar Empresas
            </button>
            <button type="button" onclick="window.location.href='/Proyecto/apps/vistas/paginas/propietario/denuncias.php'">
                Denuncias
            </button>
        </div>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>
</body>

</html>
