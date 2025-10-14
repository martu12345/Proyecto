<h2>Mi Perfil </h2>
<form id="formPerfilEmpresa">
    <input type="checkbox" id="editarToggle" style="display:none;">

    <div class="campo-perfil">
        <label>Nombre:</label>
        <span class="texto"><?php echo htmlspecialchars($datos['NombreEmpresa']); ?></span>
        <input type="text" name="nombreEmpresa" class="input-campo" value="<?php echo htmlspecialchars($datos['NombreEmpresa']); ?>">
    </div>

    <div class="campo-perfil">
        <label>Calle:</label>
        <span class="texto"><?php echo htmlspecialchars($datos['Calle']); ?></span>
        <input type="text" name="calle" class="input-campo" value="<?php echo htmlspecialchars($datos['Calle']); ?>">
    </div>

    <div class="campo-perfil">
        <label>Número de calle:</label>
        <span class="texto"><?php echo htmlspecialchars($datos['Numero']); ?></span>
<input type="text" name="numero" class="input-campo" value="<?php echo htmlspecialchars($datos['Numero']); ?>" maxlength="4" pattern="\d{1,4}" title="Máximo 4 números">
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
    <button type="button" id="btnEditar">Editar</button>
    <button type="submit" id="btnGuardar">Guardar</button>
    <button type="button" id="btnCancelar">Cancelar</button>
</div>
</form>
