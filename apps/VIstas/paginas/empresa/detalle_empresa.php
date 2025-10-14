<?php
$usuario_id = $_SESSION['idUsuario'] ?? null;
$rol = $_SESSION['rol'] ?? null; // <-- agregamos rol
$idServicio = $_GET['idServicio'] ?? null; // <-- Necesario para el botón volver
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalles de la Empresa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/empresa/Detalle_Empresa.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/modal_denuncia.css">

</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

    <div class="detalle-empresa-container">
        <div class="detalle-empresa">

            <!-- Imagen de la empresa -->
            <div class="imagen-contenedor">
                <?php
                $imagen = $empresa->getImagen();
                $ruta = "/Proyecto/public/imagen/empresas/$imagen";
                if (!$imagen || !file_exists($_SERVER['DOCUMENT_ROOT'] . $ruta)) {
                    $ruta = null;
                }
                ?>
                <?php if ($ruta): ?>
                    <img src="<?= $ruta ?>" alt="<?= htmlspecialchars($empresa->getNombreEmpresa()) ?>">
                <?php else: ?>
                    <span class="no-imagen-texto">La empresa no tiene imagen</span>
                <?php endif; ?>
            </div>

            <!-- Info y botones -->
            <div class="info-contenedor">
                <div class="info-texto">
                <h2><?= htmlspecialchars($empresa->getNombreEmpresa()) ?></h2>
                <p><strong>Email:</strong> <?= htmlspecialchars($empresa->getEmail()) ?></p>
               <p><strong>Teléfono:</strong>
    <?php
    $telefonos = Telefono::obtenerPorUsuario($conn, $empresa->getIdUsuario());
    if (!empty($telefonos)) {
        $telefonosFormateados = [];
        foreach ($telefonos as $t) {
            // Elimina todo lo que no sea número
            $soloNumeros = preg_replace('/\D/', '', $t);
            // Divide cada 3 números
            $formateado = implode(' ', str_split($soloNumeros, 3));
            $telefonosFormateados[] = $formateado;
        }
        echo htmlspecialchars(implode(", ", $telefonosFormateados));
    } else {
        echo "No tiene";
    }
    ?>
</p>

                <p><strong>Dirección:</strong> <?= htmlspecialchars($empresa->getCalle()) ?> <?= htmlspecialchars($empresa->getNumero()) ?></p>
            </div>


             <div class="botones-empresa">
    <?php if ($idServicio): ?>
        <a href="/Proyecto/apps/controlador/servicio/DetallesServicioControlador.php?idServicio=<?= $idServicio ?>" class="boton-empresa">
            <img src="/Proyecto/public/imagen/icono/icono_volver.png" alt="Volver">
        </a>
    <?php else: ?>
        <a href="/Proyecto/apps/vistas/paginas/servicio/listaServicios.php" class="boton-empresa">
            <img src="/Proyecto/public/imagen/icono/icono_volver.png" alt="Volver">
        </a>
    <?php endif; ?>
</div>


            </div>

        </div>
    </div>
   
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>

</body>

</html>