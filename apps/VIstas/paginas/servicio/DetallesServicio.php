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
    <link rel="stylesheet" href="/Proyecto/public/css/layout/modal_mensaje.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/modal_denuncia.css">
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
                    <img src="<?= $rutaImagenFinal ?>" alt="<?= htmlspecialchars($servicio->getTitulo()) ?>" style="display:block; margin:0 auto;">
                </div>

                <div class="info-contenedor">
                    <h2><?= htmlspecialchars($servicio->getTitulo()) ?></h2>
                    <p class="precio">$<?= $precioFormateado ?></p>

                    <p class="info-line"><span class="titulo-info">Categoría:</span> <?= htmlspecialchars($servicio->getCategoria()) ?></p>
                    <p class="info-line"><span class="titulo-info">Departamento:</span> <?= htmlspecialchars($servicio->getDepartamento()) ?></p>
                    <p class="info-line"><span class="titulo-info">Duración:</span> <?= htmlspecialchars($servicio->getDuracion()) ?> hora<?= $servicio->getDuracion() != 1 ? 's' : '' ?></p>
                    <p class="info-line"><span class="titulo-info">Descripción:</span> <?= nl2br(htmlspecialchars($servicio->getDescripcion())) ?></p>

                    <?php if ($empresa): ?>
                        <p class="info-line">
                            <span class="titulo-info">Empresa:</span>
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
                        <?php if ($logueado && $rol === 'cliente' && $empresa): ?>
                            <button id="openModalBtn" class="boton-servicio denunciar" title="Denunciar">
                                <img src="/Proyecto/public/imagen/icono/icono_queja.png" alt="Denunciar">
                            </button>

                            <a href="#" class="boton-servicio mensaje" title="Mensaje"
                                data-empresaid="<?= $empresa->getIdUsuario() ?>"
                                data-nombre="<?= htmlspecialchars($empresa->getNombreEmpresa()) ?>">
                                <img src="/Proyecto/public/imagen/icono/icono_mensaje.png" alt="Mensaje">
                            </a>

                            <a href="/Proyecto/apps/vistas/paginas/servicio/ContratarServicio.php?idServicio=<?= $servicio->getIdServicio() ?>"
                                class="boton-servicio agendar" title="Agendar">
                                <img src="/Proyecto/public/imagen/icono/icono_mas.png" alt="Agendar">
                            </a>
                        <?php endif; ?>

                        <a href="/Proyecto/apps/controlador/servicio/BuscarControlador.php" class="boton-servicio volver" title="Volver">
                            <img src="/Proyecto/public/imagen/icono/icono_volver.png" alt="Volver">
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="resenas-container">
        <div class="resenas-header">
            <h3>Reseñas de este servicio</h3>
            <?php if (!empty($resenas)): ?>
                <div class="rating-container">
                    <label>Filtrar por estrellas:</label>
                    <div class="rating" id="filtroEstrellas">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" name="estrellas" id="estrella<?= $i ?>" value="<?= $i ?>">
                            <label for="estrella<?= $i ?>" title="<?= $i ?> estrella<?= $i > 1 ? 's' : '' ?>">★</label>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($resenas)): ?>
            <div id="resenasList">
                <?php foreach ($resenas as $r): ?>
                    <div class="resena-card" data-calificacion="<?= $r['calificacion'] ?>">
                        <div class="resena-usuario">
                            <?php $userImg = !empty($r['imagen']) ? str_replace(" ", "%20", $r['imagen']) : "/Proyecto/public/imagen/icono/default_user.png"; ?>
                            <img src="<?= htmlspecialchars($userImg) ?>" alt="Usuario" class="foto-usuario">
                            <div class="info-usuario">
                                <span class="nombre"><?= htmlspecialchars($r['Nombre'] . ' ' . $r['Apellido']) ?></span>
                                <span class="email"><?= htmlspecialchars($r['Email']) ?></span>
                            </div>
                        </div>
                        <div class="resena-contenido">
                            <span class="estrellas"><?= str_repeat('★', $r['calificacion']) ?></span>
                            <p><?= nl2br(htmlspecialchars($r['resena'])) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-resenas">
                <p>Sin reseñas</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modales -->
    <!-- M. Mensajes -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/modal_mensaje.php'; ?>
    <div id="denunciaModal" class="modal">
        <div class="modal-content">
            <span id="closeModalBtn" class="close">&times;</span>
            <h2>Denunciar Empresa</h2>
            <form id="denunciaForm">
                <input type="hidden" name="usuario_id" value="<?= $usuario_id ?>">
                <input type="hidden" name="idEmpresa" value="<?= $empresa->getIdUsuario() ?? 0 ?>">
                <input type="hidden" name="motivo" value="DenunciarServicio">
                <label for="detalle">Detalles adicionales (opcional):</label>
                <textarea id="detalle" name="detalle" rows="4" placeholder="Escribe más sobre tu denuncia..."></textarea>
                <button type="submit" class="btn-submit">Enviar denuncia</button>
            </form>
            <div id="mensajeDenuncia" class="mensaje-denuncia"></div>
        </div>
    </div>

    <script src="/Proyecto/public/js/servicio/DetallesServicio.js"></script>
    <script src="/Proyecto/public/js/mensaje/modal_mensaje.js"></script>
    <script src="/Proyecto/public/js/denuncia/denunciarservicio.js"></script>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>
</body>

</html>