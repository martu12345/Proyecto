document.addEventListener("DOMContentLoaded", function () {

    // --- ELEMENTOS DEL FORMULARIO DE PERFIL ---
    const form = document.getElementById("formPerfilEmpresa");
    const btnEditar = document.getElementById("btnEditar");
    const btnGuardar = document.getElementById("btnGuardar");
    const btnCancelar = document.getElementById("btnCancelar");
    const campos = document.querySelectorAll(".campo-perfil");

    // --- Solo si existe el formulario ---
    if (form && btnEditar && btnGuardar && btnCancelar && campos.length > 0) {

        // --- Previene envío accidental del form ---
        form.addEventListener("submit", function(e){
            e.preventDefault();
        });

        // --- EDITAR DATOS ---
        btnEditar.addEventListener("click", () => {
            campos.forEach(campo => {
                const input = campo.querySelector(".input-campo");
                const texto = campo.querySelector(".texto");
                if(input && texto){
                    input.style.display = "inline-block";
                    texto.style.display = "none";
                }
            });
            btnEditar.style.display = "none";
            btnGuardar.style.display = "inline-block";
            btnCancelar.style.display = "inline-block";
        });

        // --- CANCELAR ---
        btnCancelar.addEventListener("click", () => {
            campos.forEach(campo => {
                const input = campo.querySelector(".input-campo");
                const texto = campo.querySelector(".texto");
                if(input && texto){
                    input.value = texto.textContent;
                    input.style.display = "none";
                    texto.style.display = "inline-block";
                }
            });
            btnEditar.style.display = "inline-block";
            btnGuardar.style.display = "none";
            btnCancelar.style.display = "none";
        });

        // --- GUARDAR ---
        btnGuardar.addEventListener("click", (e) => {
            e.preventDefault();

            const formData = new FormData(form);

            if (!formData.get("email")) {
                alert("El email es obligatorio");
                return;
            }

            fetch("/Proyecto/apps/controlador/empresa/EditarEmpresaControlador.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    campos.forEach(campo => {
                        const input = campo.querySelector(".input-campo");
                        const texto = campo.querySelector(".texto");
                        if(input && texto){
                            texto.textContent = input.value;
                            texto.style.display = "inline-block";
                            input.style.display = "none";
                        }
                    });
                    const nombreColumna = document.getElementById("nombreColumna");
                    if(nombreColumna) nombreColumna.textContent = formData.get("nombreEmpresa");
                    btnEditar.style.display = "inline-block";
                    btnGuardar.style.display = "none";
                    btnCancelar.style.display = "none";
                } else {
                    alert("Error al guardar: " + (data.error || "Desconocido"));
                }
            })
            .catch(err => console.error("Error fetch:", err));
        });
    }

    // --- SUBIR FOTO ---
    const fotoCirculo = document.querySelector('.foto-circulo');
    const fotoInput = document.getElementById('fotoInput');
    const formFoto = document.getElementById('formFoto');

    if(fotoCirculo && fotoInput && formFoto){
        fotoCirculo.addEventListener('click', () => fotoInput.click());

        fotoInput.addEventListener('change', function() {
            const formData = new FormData(formFoto);

            fetch(formFoto.action, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.ok){
                    const img = fotoCirculo.querySelector('img.foto-perfil');
                    if(img){
                        img.src = `/Proyecto/public/imagen/empresas/${data.imagen}?t=${Date.now()}`;
                    } else {
                        fotoCirculo.innerHTML = `<img src="/Proyecto/public/imagen/empresas/${data.imagen}?t=${Date.now()}" class="foto-perfil" alt="Foto Empresa"><span class="cambiar-foto">+</span>`;
                    }
                } else {
                    alert('Error al subir la foto: ' + data.error);
                }
            })
            .catch(err => console.error(err));
        });
    }

    // --- CAMBIO DE SECCIÓN ---
    const opciones = document.querySelectorAll('.opcion');
    opciones.forEach(opcion => {
        opcion.addEventListener('click', function(e){
            e.preventDefault();
            const seccion = this.getAttribute('data-seccion');

            // Cambiar clase activa
            opciones.forEach(o => o.classList.remove('activa'));
            this.classList.add('activa');

            // Recargar página con la sección correspondiente
            const url = new URL(window.location.href);
            url.searchParams.set('seccion', seccion);
            window.location.href = url.toString();
        });
    });

});
