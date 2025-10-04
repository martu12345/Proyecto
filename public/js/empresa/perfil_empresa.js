// perfil_empresa.js

document.addEventListener("DOMContentLoaded", function () {

    // --- VARIABLES DE PERFIL ---
    const btnEditar = document.getElementById("btnEditar");
    const btnGuardar = document.getElementById("btnGuardar");
    const btnCancelar = document.getElementById("btnCancelar");
    const campos = document.querySelectorAll(".campo-perfil");
    const form = document.getElementById("formPerfilEmpresa");

    // --- VALIDACIÓN: solo ejecutar si el formulario existe ---
    if (form && btnEditar && btnGuardar && btnCancelar) {

        // Previene envío accidental del form
        form.addEventListener("submit", function (e) {
            e.preventDefault();
        });

        // --- EDITAR DATOS ---
        btnEditar.addEventListener("click", () => {
            campos.forEach(campo => {
                const input = campo.querySelector(".input-campo");
                const texto = campo.querySelector(".texto");
                if (input && texto) {
                    input.style.display = "inline-block";
                    texto.style.display = "none";
                }
            });
            btnEditar.style.display = "none";
            btnGuardar.style.display = "inline-block";
            btnCancelar.style.display = "inline-block";
        });

        // --- CANCELAR EDICIÓN ---
        btnCancelar.addEventListener("click", () => {
            campos.forEach(campo => {
                const input = campo.querySelector(".input-campo");
                const texto = campo.querySelector(".texto");
                if (input && texto) {
                    input.value = texto.textContent;
                    input.style.display = "none";
                    texto.style.display = "inline-block";
                }
            });
            btnEditar.style.display = "inline-block";
            btnGuardar.style.display = "none";
            btnCancelar.style.display = "none";
        });

        // --- GUARDAR CAMBIOS ---
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
                            if (input && texto) {
                                texto.textContent = input.value;
                                texto.style.display = "inline-block";
                                input.style.display = "none";
                            }
                        });
                        const nombreColumna = document.getElementById("nombreColumna");
                        if (nombreColumna) nombreColumna.textContent = formData.get("nombreEmpresa");
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

    if (fotoCirculo && fotoInput && formFoto) {
        fotoCirculo.addEventListener('click', () => fotoInput.click());

        fotoInput.addEventListener('change', function () {
            const formData = new FormData(formFoto);

            fetch(formFoto.action, { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.ok) {
                        const img = fotoCirculo.querySelector('img.foto-perfil');
                        const nuevaSrc = `/Proyecto/public/imagen/empresas/${data.imagen}?t=${Date.now()}`;
                        if (img) {
                            img.src = nuevaSrc;
                        } else {
                            fotoCirculo.innerHTML = `
                                <img src="${nuevaSrc}" class="foto-perfil" alt="Foto Empresa">
                                <span class="cambiar-foto">+</span>
                            `;
                        }
                    } else {
                        alert('Error al subir la foto: ' + data.error);
                    }
                })
                .catch(err => console.error(err));
        });
    }

    // --- CAMBIO DE SECCIÓN (menú lateral) ---
    const opciones = document.querySelectorAll('.opcion');
    if (opciones.length > 0) {
        opciones.forEach(opcion => {
            opcion.addEventListener('click', function (e) {
                e.preventDefault();
                const seccion = this.getAttribute('data-seccion');

                // Cambiar clase activa
                opciones.forEach(o => o.classList.remove('activa'));
                this.classList.add('activa');

                // Recargar con la sección seleccionada
                const url = new URL(window.location.href);
                url.searchParams.set('seccion', seccion);
                window.location.href = url.toString();
            });
        });
    }
});
