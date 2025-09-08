<div id="modalServicio" class="modal">
    <div class="modal-content">
        <span class="cerrar">&times;</span>
        <h2>Crear Servicio</h2>

        <form id="formServicio" action="/Proyecto/apps/controlador/ServicioControlador.php" method="POST" enctype="multipart/form-data">
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