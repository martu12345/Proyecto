<h2>Mi Perfil</h2>

<form id="formPerfil" method="POST" action="">
    <input type="checkbox" id="editarToggle" style="display:none;">

    <div class="campo-perfil">
        <label>Nombre:</label>
        <span class="texto"><?php echo htmlspecialchars($datos['Nombre']); ?></span>
        <input type="text" name="nombre" class="input-campo" value="<?php echo htmlspecialchars($datos['Nombre']); ?>">
    </div>

    <div class="campo-perfil">
        <label>Apellido:</label>
        <span class="texto"><?php echo htmlspecialchars($datos['Apellido']); ?></span>
        <input type="text" name="apellido" class="input-campo" value="<?php echo htmlspecialchars($datos['Apellido']); ?>">
    </div>

    <div class="campo-perfil">
        <label>Email:</label>
        <span class="texto"><?php echo htmlspecialchars($datos['Email']); ?></span>
        <input type="email" name="email" class="input-campo" value="<?php echo htmlspecialchars($datos['Email']); ?>">
    </div>

    <div class="campo-perfil">
        <label>Contraseña:</label>
        <span class="texto">********</span>
        <input type="password" name="contrasena" class="input-campo" placeholder="Nueva contraseña">
            <small class="aviso-editar" style="display:none;">Dejar vacío para mantener la contraseña actual</small>

    </div>

    <div class="botones-perfil">
        <label id="btnEditar">Editar</label>
        <button type="submit" id="btnGuardar">Guardar</button>
        <label id="btnCancelar">Cancelar</label>
    </div>
</form>
