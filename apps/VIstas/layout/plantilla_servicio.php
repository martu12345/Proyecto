<div class="servicio"> 
    <?php if (!empty($servicio['imagen'])): ?> 
        <img src="/Proyecto/public/imagen/servicios/<?= htmlspecialchars($servicio['imagen']) ?>" alt="<?= htmlspecialchars($servicio['Titulo']) ?>" class="imagen-servicio"> 
    <?php endif; ?> 
    <div class="info-servicio"> 
        <div class="texto-servicio"> 
            <h3 class="nombre-servicio"><?= htmlspecialchars($servicio['Titulo']) ?></h3> 
            <p class="descripcion-servicio"><?= htmlspecialchars($servicio['Descripcion']) ?></p> 
            <p class="categoria-servicio"><?= htmlspecialchars($servicio['Categoria']) ?></p> 
            <p class="precio-servicio">$<?= htmlspecialchars($servicio['Precio']) ?></p> 
        </div> 

        <!-- Formulario POST para Ver más --> 
        <form action="/Proyecto/apps/vistas/paginas/DetallesServicio.php" method="post"> 
            <input type="hidden" name="idServicio" value="<?= $servicio['idServicio'] ?? '' ?>"> 
            <button type="submit" class="btn-ver-mas"> 
                Ver más <span class="plus">+</span> 
            </button> 
        </form> 
    </div> 
</div>
