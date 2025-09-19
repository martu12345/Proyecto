<div id="modalServicio" class="modal">
    <div class="modal-content">
        <span class="cerrar">&times;</span>
        <h2>Crear Servicio</h2>

        <form id="formServicio" action="/Proyecto/apps/controlador/servicio/ServicioControlador.php" method="POST" enctype="multipart/form-data">
            <div class="form-top">
                <div class="form-image">
                    <label for="imagen">Foto del servicio</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*">
                    <img id="previewImagen" src="" alt="Preview" />
                </div>
                <div class="form-info">
                    <label for="titulo">Título del servicio</label>
                    <input type="text" id="titulo" name="titulo" required>

                    <label for="categoria">Categoría</label>
                    <select id="categoria" name="categoria" required>
                        <option value="">Selecciona...</option>
                        <option value="Hogar">Hogar</option>
                        <option value="Autos">Autos</option>
                        <option value="Belleza">Belleza</option>
                        <option value="Belleza">Limpieza</option>
                        <option value="Cuidado de niños">Cuidado de niños</option>
                        <option value="Digital">Digital</option>
                        <option value="Cocina">Cocina</option>
                        <option value="Salud">Salud</option>
                        <option value="Mascotas">Mascotas</option>
                        <option value="Eventos">Eventos</option>
                        <option value="Educación">Educación</option>
                        <option value="Transporte">Transporte</option>
                        <option value="Arte y Cultura">Arte y Cultura</option>
                    </select>

                    <label for="departamento">Departamento</label>
                    <select id="departamento" name="departamento" required>
                        <option value="">Selecciona...</option>
                        <option value="Artigas">Artigas</option>
                        <option value="Canelones">Canelones</option>
                        <option value="Cerro Largo">Cerro Largo</option>
                        <option value="Colonia">Colonia</option>
                        <option value="Durazno">Durazno</option>
                        <option value="Flores">Flores</option>
                        <option value="Florida">Florida</option>
                        <option value="Lavalleja">Lavalleja</option>
                        <option value="Maldonado">Maldonado</option>
                        <option value="Montevideo">Montevideo</option>
                        <option value="Paysandú">Paysandú</option>
                        <option value="Río Negro">Río Negro</option>
                        <option value="Rivera">Rivera</option>
                        <option value="Rocha">Rocha</option>
                        <option value="Salto">Salto</option>
                        <option value="San José">San José</option>
                        <option value="Soriano">Soriano</option>
                        <option value="Tacuarembó">Tacuarembó</option>
                        <option value="Treinta y Tres">Treinta y Tres</option>
                    </select>

                    <label for="precio">Precio (UYU)</label>
                    <input type="number" id="precio" name="precio" required min="0" step="0.01">
                </div>
            </div>

            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="3" style="overflow:hidden;"></textarea>
            <div id="contador">0 / 60 palabras</div>

            <div class="boton-contenedor">
                <button type="submit">Guardar</button>
            </div>
        </form>
    </div>
</div>