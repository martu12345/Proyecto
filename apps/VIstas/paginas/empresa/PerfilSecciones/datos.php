<h2>Mi Perfil Empresa</h2>
<form id="formPerfilEmpresa">
    <input type="checkbox" id="editarToggle" style="display:none;">

    <div class="campo-perfil">
        <label>Nombre Empresa:</label>
        <span class="texto"><?php echo htmlspecialchars($datos['NombreEmpresa']); ?></span>
        <input type="text" name="nombreEmpresa" class="input-campo" value="<?php echo htmlspecialchars($datos['NombreEmpresa']); ?>">
    </div>

    <div class="campo-perfil">
        <label>Calle:</label>
        <span class="texto"><?php echo htmlspecialchars($datos['Calle']); ?></span>
        <input type="text" name="calle" class="input-campo" value="<?php echo htmlspecialchars($datos['Calle']); ?>">
    </div>

    <div class="campo-perfil">
        <label>Número:</label>
        <span class="texto"><?php echo htmlspecialchars($datos['Numero']); ?></span>
        <input type="text" name="numero" class="input-campo" value="<?php echo htmlspecialchars($datos['Numero']); ?>">
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
    </div>

    <div class="botones-perfil">
        <label id="btnEditar">Editar</label>
        <button type="submit" id="btnGuardar">Guardar</button>
        <label id="btnCancelar">Cancelar</label>
    </div>
</form>
