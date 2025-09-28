document.addEventListener("DOMContentLoaded", function () {

    // --- ELEMENTOS DEL PERFIL ---
    const btnEditar = document.getElementById("btnEditar");
    const btnGuardar = document.getElementById("btnGuardar");
    const btnCancelar = document.getElementById("btnCancelar");
    const campos = document.querySelectorAll(".campo-perfil");
    const form = document.getElementById("formPerfilEmpresa");
    const emailInput = document.querySelector('input[name="email"]'); // <--- asegurarse que el name sea "email"

    if(!emailInput){
        console.error("No se encontró el input de email");
        return;
    }

    // --- Previene envío accidental del form ---
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

    // GUARDAR DATOS 
  btnGuardar.addEventListener("click", (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    const email = formData.get("email")?.trim();

    if (!email) {
        alert("El email es obligatorio");
        emailInput.focus();
        return;
    }

    const emailValido = /^[a-zA-Z0-9._%+-]+@(gmail\.com|hotmail\.com)$/i;
    if (!emailValido.test(email)) {
        alert("El email debe terminar en @gmail.com o @hotmail.com");
        emailInput.focus();
        return; // <--- importante, corta la ejecución aquí
    }

    // Solo si pasó la validación, hacemos el fetch
    fetch("/Proyecto/apps/controlador/empresa/EditarEmpresaControlador.php", {
        method: "POST",
        body: formData
    })

    // --- SUBIR FOTO ---
    const fotoCirculo = document.querySelector('.foto-circulo');
    const fotoInput = document.getElementById('fotoInput');
    const formFoto = document.getElementById('formFoto');

    fotoCirculo.addEventListener('click', () => fotoInput.click());

    fotoInput.addEventListener('change', function() {
        if(!fotoInput.files.length) return;

        const formData = new FormData(formFoto);

        fetch(formFoto.action, { method: 'POST', body: formData })
        .then(async res => {
            const text = await res.text();
            try {
                return JSON.parse(text);
            } catch(e) {
                throw new Error("Respuesta no es JSON: " + text);
            }
        })
        .then(data => {
            if(data.ok){
                let img = fotoCirculo.querySelector('img.foto-perfil');
                if(img){
                    img.src = `/Proyecto/public/imagen/empresas/${data.imagen}?t=${Date.now()}`;
                } else {
                    fotoCirculo.innerHTML = `<img src="/Proyecto/public/imagen/empresas/${data.imagen}?t=${Date.now()}" class="foto-perfil" alt="Foto Empresa"><span class="cambiar-foto">+</span>`;
                }
                console.log("Foto subida correctamente:", data.imagen);
            } else {
                alert('Error al subir la foto: ' + data.error);
            }
        })
        .catch(err => console.error("Error fetch subir foto:", err));
    });

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
