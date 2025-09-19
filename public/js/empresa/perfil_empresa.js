document.addEventListener("DOMContentLoaded", function () {

    const btnEditar = document.getElementById("btnEditar");
    const btnGuardar = document.getElementById("btnGuardar");
    const btnCancelar = document.getElementById("btnCancelar");
    const campos = document.querySelectorAll(".campo-perfil");
    const form = document.getElementById("formPerfilEmpresa");

    // Previene envío accidental del form
    form.addEventListener("submit", function(e){
        e.preventDefault();
    });

    // --- EDITAR DATOS ---
    btnEditar.addEventListener("click", () => {
        campos.forEach(campo => {
            campo.querySelector(".input-campo").style.display = "inline-block";
            campo.querySelector(".texto").style.display = "none";
        });
        btnEditar.style.display = "none";
        btnGuardar.style.display = "inline-block";
        btnCancelar.style.display = "inline-block";
    });

    btnCancelar.addEventListener("click", () => {
        campos.forEach(campo => {
            const input = campo.querySelector(".input-campo");
            input.value = campo.querySelector(".texto").textContent;
            input.style.display = "none";
            campo.querySelector(".texto").style.display = "inline-block";
        });
        btnEditar.style.display = "inline-block";
        btnGuardar.style.display = "none";
        btnCancelar.style.display = "none";
    });

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
                    texto.textContent = input.value;
                    texto.style.display = "inline-block";
                    input.style.display = "none";
                });
                document.getElementById("nombreColumna").textContent = formData.get("nombreEmpresa");
                btnEditar.style.display = "inline-block";
                btnGuardar.style.display = "none";
                btnCancelar.style.display = "none";
            } else {
                alert("Error al guardar: " + (data.error || "Desconocido"));
            }
        })
        .catch(err => console.error("Error fetch:", err));
    });

    // --- SUBIR FOTO ---
    const fotoCirculo = document.querySelector('.foto-circulo');
    const fotoInput = document.getElementById('fotoInput');
    const formFoto = document.getElementById('formFoto');

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

});
