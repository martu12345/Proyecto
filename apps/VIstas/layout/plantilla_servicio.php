<div class="servicio"> 
    <?php if (!empty($servicio['imagen'])): ?>
        <img src="/Proyecto/public/imagen/servicios/<?= htmlspecialchars($servicio['imagen']) ?>"
             alt="<?= htmlspecialchars($servicio['Titulo']) ?>" class="imagen-servicio">
    <?php else: ?>
        <div class="imagen-servicio no-imagen"></div>
    <?php endif; ?>

    <div class="info-servicio">
        <div class="texto-servicio">
            <h3 class="nombre-servicio"><?= htmlspecialchars($servicio['Titulo']) ?></h3>
            <p class="descripcion-servicio"><?= htmlspecialchars(substr($servicio['Descripcion'], 0, 100)) ?>...</p>
            <p class="categoria-servicio"><?= htmlspecialchars($servicio['Categoria']) ?></p>
            <p class="precio-servicio">$<?= htmlspecialchars($servicio['Precio']) ?></p>
        </div>

      <form action="/Proyecto/apps/vistas/paginas/servicio/DetallesServicio.php" method="get">
<input type="hidden" name="idServicio" value="<?= $servicio['IdServicio'] ?? $servicio['idServicio'] ?? '' ?>">
    <button type="submit" class="btn-ver-mas">Ver más +</button>
</form>


    </div>
</div>
