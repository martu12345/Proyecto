document.addEventListener("DOMContentLoaded", function () {
    const btnEditar = document.getElementById("btnEditar");
    const btnGuardar = document.getElementById("btnGuardar");
    const btnCancelar = document.getElementById("btnCancelar");
    const campos = document.querySelectorAll(".campo-perfil");
    const form = document.getElementById("formPerfil");

    // Solo ejecutar si todos existen
    if (btnEditar && btnGuardar && btnCancelar && campos.length > 0 && form) {

        btnEditar.addEventListener("click", () => {
    campos.forEach(campo => {
        campo.classList.add("editando");
        const input = campo.querySelector(".input-campo");
        const texto = campo.querySelector(".texto");
        const aviso = campo.querySelector(".aviso-editar"); // <- capturamos el small

        if(input) input.style.display = "inline-block";
        if(texto) texto.style.display = "none";
        if(aviso) aviso.style.display = "block"; // <- mostramos el aviso
    });
    btnEditar.style.display = "none";
    btnGuardar.style.display = "inline-block";
    btnCancelar.style.display = "inline-block";
});

btnCancelar.addEventListener("click", () => {
    campos.forEach(campo => {
        campo.classList.remove("editando");
        const input = campo.querySelector(".input-campo");
        const texto = campo.querySelector(".texto");
        const aviso = campo.querySelector(".aviso-editar"); // <- capturamos el small

        if(input) {
            input.style.display = "none";
            input.value = texto.textContent; // reset valor
        }
        if(texto) texto.style.display = "inline-block";
        if(aviso) aviso.style.display = "none"; // <- ocultamos aviso
    });
    btnEditar.style.display = "inline-block";
    btnGuardar.style.display = "none";
    btnCancelar.style.display = "none";
});


        btnGuardar.addEventListener("click", (e) => {
            e.preventDefault();
            const formData = new FormData(form);

            if (!formData.get("email")) {
                alert("Email es obligatorio");
                return;
            }

            fetch("/Proyecto/apps/controlador/cliente/EditarPerfilControlador.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.ok){
                    campos.forEach(campo => {
                        const input = campo.querySelector(".input-campo");
                        const texto = campo.querySelector(".texto");
                        const name = input.name;

                        if(data[name] !== undefined){
                            texto.textContent = data[name];
                            input.value = data[name];
                        }

                        texto.style.display = "inline-block";
                        input.style.display = "none";
                        campo.classList.remove("editando");
                    });

                    document.getElementById("nombreColumna").textContent = data.nombre + " " + data.apellido;

                    btnEditar.style.display = "inline-block";
                    btnGuardar.style.display = "none";
                    btnCancelar.style.display = "none";
                } else {
                    alert("Error: " + (data.error || "Error desconocido"));
                }
            })
            .catch(err => console.error("Error fetch:", err));
        });
    }
});
