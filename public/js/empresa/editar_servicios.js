document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById('modalServicio');
    const cerrar = modal.querySelector('.cerrar');
    const modalTitulo = modal.querySelector('h2');

    const idInput = document.getElementById('idServicio');
    const tituloInput = document.getElementById('titulo');
    const categoriaInput = document.getElementById('categoria');
    const departamentoInput = document.getElementById('departamento');
    const precioInput = document.getElementById('precio');
    const descripcionInput = document.getElementById('descripcion');
    const duracionInput = document.getElementById('duracion');
    const previewImagen = document.getElementById('previewImagen');
    const inputImagen = document.getElementById('imagen'); 

    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', () => {
            modal.style.display = 'block';
            modalTitulo.textContent = 'Editar Servicio';

            idInput.value = btn.dataset.id;
            tituloInput.value = btn.dataset.titulo;
            categoriaInput.value = btn.dataset.categoria;
            departamentoInput.value = btn.dataset.departamento;
            precioInput.value = btn.dataset.precio;
            descripcionInput.value = btn.dataset.descripcion;
            duracionInput.value = parseFloat(btn.dataset.duracion) || 0;

            if (btn.dataset.imagen && btn.dataset.imagen.trim() !== "") {
                previewImagen.src = '/Proyecto/public/imagen/servicios/' + btn.dataset.imagen;
                previewImagen.style.display = 'block';
            } else {
                previewImagen.src = '';
                previewImagen.style.display = 'none';
            }

            if(inputImagen) inputImagen.value = "";
        });
    });

    if(inputImagen){
        inputImagen.addEventListener('change', () => {
            const file = inputImagen.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    previewImagen.src = e.target.result;
                    previewImagen.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                previewImagen.src = '';
                previewImagen.style.display = 'none';
            }
        });
    }

    cerrar.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', e => { if(e.target === modal) modal.style.display = 'none'; });
});
