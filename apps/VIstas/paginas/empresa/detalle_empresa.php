<?php
$usuario_id = $_SESSION['idUsuario'] ?? null;
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
                    <p><strong>Dirección:</strong> <?= htmlspecialchars($empresa->getCalle()) ?> <?= htmlspecialchars($empresa->getNumero()) ?></p>
                </div>

                <div class="botones-empresa">
   <!-- Botón para abrir modal -->
            <button id="openModalBtn" class="boton-empresa">
              <img src="/Proyecto/public/imagen/icono/icono_queja.png" alt="Bandera">
            </button>


                    <?php if ($idServicio): ?>
                        <a href="/Proyecto/apps/controlador/servicio/DetallesServicioControlador.php?idServcio=<?= $idServicio ?>" class="boton-empresa">
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
<!-- Modal para denunciar empresa -->
<div id="denunciaModal" class="modal">
  <div class="modal-content">
    <span id="closeModalBtn" class="close">&times;</span>
    <h2>Denunciar Empresa</h2>
    <form id="denunciaForm">
      <input type="hidden" name="usuario_id" value="<?= $usuario_id ?>">
<input type="hidden" name="empresa_id" value="<?= $idEmpresa ?>">

      <label for="motivo">Motivo de la denuncia:</label>
      <select id="motivo" name="motivo" required>
        <option value="">-- Selecciona un motivo --</option>
        <option value="estafa">Estafa / fraude</option>
        <option value="mal_servicio">Mal servicio / incumplimiento</option>
        <option value="publicidad_engañosa">Publicidad engañosa</option>
        <option value="trato_incorrecto">Trato incorrecto / abuso</option>
        <option value="producto_defectuoso">Producto defectuoso</option>
        <option value="otros">Otros</option>
      </select>

      <label for="detalle">Detalles adicionales (opcional):</label>
      <textarea id="detalle" name="detalle" rows="4" placeholder="Escribe más sobre tu denuncia..."></textarea>

      <button type="submit" class="btn-submit">Enviar denuncia</button>
    </form>
  </div>
</div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>
    <script src="/Proyecto/public/js/denuncia/denuncia.js"></script>

</body>

</html>
