document.addEventListener('DOMContentLoaded', () => {
    const btnCrear = document.getElementById('btnCrearServicio');
    const modal = document.getElementById('modalServicio');
    const btnCerrar = modal.querySelector('.cerrar');
    const inputImagen = document.getElementById('imagen');
    const previewImagen = document.getElementById('previewImagen');
    const textarea = document.getElementById('descripcion');
    const contador = document.getElementById('contador');

    // Abrir 
    btnCrear.addEventListener('click', () => modal.style.display = 'block');

    // Cerrar 
    btnCerrar.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', e => { if (e.target === modal) modal.style.display = 'none'; });

    // prevista de imagen
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

    // Ajuste  del textarea y contador
    textarea.addEventListener('input', () => {
        let words = textarea.value.split(/\s+/).filter(w => w.length > 0);

        // Limitar a 60 palabras 
        if (words.length > 60) {
            textarea.value = words.slice(0, 60).join(' ');
            words = textarea.value.split(/\s+/).filter(w => w.length > 0);
        }

        // Actualizar contador
        if (contador) contador.textContent = `${words.length} / 60 palabras`;

        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    });

    // Bloquear escritura si ya hay 60 palabras
    textarea.addEventListener('keydown', (e) => {
        let words = textarea.value.split(/\s+/).filter(w => w.length > 0);

        const allowedKeys = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Control', 'Meta', 'Shift', 'Tab'];

        if (words.length >= 60 && !allowedKeys.includes(e.key)) {
            e.preventDefault(); // bloquea escribir más
        }
    });
});
