<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Contrata.php'); // para las reseñas

$mensajeError = null;

$idServicio = $_GET['idServicio'] ?? null;
if (!$idServicio) {
    $mensajeError = "No se recibió el Id del servicio.";
} else {
    $idServicio = intval($idServicio);
    $servicio = Servicio::obtenerPorId($conn, $idServicio);
    if (!$servicio) {
        $mensajeError = "Servicio no encontrado.";
    } else {
        // Obtener empresa a través de Brinda
        $idEmpresa = Brinda::obtenerIdEmpresaPorServicio($conn, $idServicio);
        $empresa = $idEmpresa ? Empresa::obtenerPorId($conn, $idEmpresa) : null;

        // Obtener reseñas
        $resenas = Contrata::obtenerResenasPorServicio($conn, $idServicio);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalles del Servicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/servicio/DetallesServicio.css">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

    <div class="detalle-servicio-container">
        <?php if ($mensajeError): ?>
            <div class="mensaje-error"><?= htmlspecialchars($mensajeError) ?></div>
            <a class="boton-volver" href="/Proyecto/apps/vistas/paginas/servicio/listaServicios.php">← Volver</a>
        <?php else: ?>
            <div class="detalle-servicio">
                <div class="imagen-contenedor">
                    <?php
                    $imagen = $servicio->getImagen();
                    $ruta = "/Proyecto/public/imagen/servicios/$imagen";
                    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $ruta) || empty($imagen)) {
                        $tieneImagen = false;
                    } else {
                        $tieneImagen = true;
                    }
                    ?>
                    <?php if ($tieneImagen): ?>
                        <img src="<?= $ruta ?>" alt="<?= htmlspecialchars($servicio->getTitulo()) ?>" style="display:block; margin:0 auto;">
                    <?php else: ?>
                        <div class="no-imagen">El servicio no tiene imagen</div>
                    <?php endif; ?>
                </div>


                <div class="info-contenedor">
                    <h2><?= htmlspecialchars($servicio->getTitulo()) ?></h2>
                    <p class="precio">$<?= number_format($servicio->getPrecio(), 0, '', '.') ?></p>

                    <p class="info-line">
                        <span class="titulo-info">Categoría:</span>
                        <span><?= htmlspecialchars($servicio->getCategoria()) ?></span>
                    </p>

                    <p class="info-line">
                        <span class="titulo-info">Departamento:</span>
                        <span><?= htmlspecialchars($servicio->getDepartamento()) ?></span>
                    </p>

                    <p class="info-line">
                        <span class="titulo-info">Duración:</span>
                        <span><?= htmlspecialchars($servicio->getDuracion()) ?> hora<?= $servicio->getDuracion() != 1 ? 's' : '' ?></span>
                    </p>

                    <p class="info-line">
                        <span class="titulo-info">Descripción:</span>
                        <span><?= nl2br(htmlspecialchars($servicio->getDescripcion())) ?></span>
                    </p>

                    <?php if (!empty($empresa)): ?>
                        <p class="empresa-line">
                            <span>Empresa:</span>
                        <form action="/Proyecto/apps/controlador/empresa/DetalleEmpresaControlador.php" method="get">
                            <input type="hidden" name="idEmpresa" value="<?= $empresa->getIdUsuario() ?>">
                            <input type="hidden" name="idServicio" value="<?= $servicio->getIdServicio() ?>">
                            <button type="submit" class="empresa-btn"><?= htmlspecialchars($empresa->getNombreEmpresa()) ?></button>
                        </form>
                        </p>

                    <?php else: ?>
                        <p>Empresa: No disponible</p>
                    <?php endif; ?>



                    <div class="botones-servicio">
                        <a href="#"
                            class="boton-servicio mensaje"
                            title="Mensaje"
                            data-empresaid="<?= $empresa ? $empresa->getIdUsuario() : '' ?>"
                            data-nombre="<?= $empresa ? htmlspecialchars($empresa->getNombreEmpresa()) : '' ?>">
                            <img src="/Proyecto/public/imagen/icono/icono_mensaje.png" alt="Mensaje">
                        </a>
                        <a href="/Proyecto/apps/vistas/paginas/servicio/ContratarServicio.php?idServicio=<?= $servicio->getIdServicio() ?>"
                            class="boton-servicio agendar" title="Agendar">
                            <img src="/Proyecto/public/imagen/icono/icono_mas.png" alt="Agendar">
                        </a>
                        <a href="/Proyecto/apps/vistas/paginas/busqueda.php" class="boton-servicio volver" title="Volver">
                            <img src="/Proyecto/public/imagen/icono/icono_volver.png" alt="Volver">
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>



    <div class="resenas-container">
        <h3>Reseñas de este servicio</h3>

        <?php if (!empty($resenas)): ?>
            <!-- Botón filtrar -->
            <div class="filtro-estrellas-container">
                <button id="filtrarEstrellas">Filtrar por estrellas</button>
                <select id="filtroSelector" style="display:none;">
                    <option value="1">1 estrella</option>
                    <option value="2">2 estrellas</option>
                    <option value="3">3 estrellas</option>
                    <option value="4">4 estrellas</option>
                    <option value="5">5 estrellas</option>
                </select>
            </div>

            <!-- Reseñas -->
            <div id="resenasList">
                <?php foreach ($resenas as $r): ?>
                    <div class="resena-card" data-calificacion="<?= $r['calificacion'] ?>">
                        <div class="resena-usuario">
                            <?php
                            $userImg = !empty($r['imagen'])
                                ? str_replace(" ", "%20", $r['imagen'])   // reemplaza espacios
                                : "/Proyecto/public/imagen/icono/default_user.png";
                            ?>
                            <img src="<?= htmlspecialchars($userImg) ?>" alt="Usuario" class="foto-usuario">


                            <div class="info-usuario">
                                <span class="nombre"><?= htmlspecialchars($r['Nombre'] . ' ' . $r['Apellido']) ?></span>
                                <span class="email"><?= htmlspecialchars($r['Email']) ?></span>
                            </div>
                        </div>
                        <div class="resena-contenido">
                            <span class="estrellas"><?= str_repeat('⭐', $r['calificacion']) ?></span>
                            <p><?= nl2br(htmlspecialchars($r['resena'])) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No hay reseñas todavía.</p>
        <?php endif; ?>
    </div>


    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/modal_mensaje.php'; ?>
    <link rel="stylesheet" href="/Proyecto/public/css/layout/modal_mensaje.css">
    <script src="/Proyecto/public/js/mensaje/modal_mensaje.js"></script>
    <div id="mensajeExito" style="display:none; color:green; margin-top:10px;">
        ✅ Mensaje enviado correctamente
    </div>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>
</body>

</html>