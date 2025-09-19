document.addEventListener("DOMContentLoaded", function () {
    const btnEditar = document.getElementById("btnEditar");
    const btnGuardar = document.getElementById("btnGuardar");
    const btnCancelar = document.getElementById("btnCancelar");
    const campos = document.querySelectorAll(".campo-perfil");
    const form = document.getElementById("formPerfil");

    btnEditar.addEventListener("click", () => {
        campos.forEach(campo => {
            campo.classList.add("editando");
            campo.querySelector(".input-campo").style.display = "inline-block";
            campo.querySelector(".texto").style.display = "none";
        });
        btnEditar.style.display = "none";
        btnGuardar.style.display = "inline-block";
        btnCancelar.style.display = "inline-block";
    });

    btnCancelar.addEventListener("click", () => {
        campos.forEach(campo => {
            campo.classList.remove("editando");
            campo.querySelector(".input-campo").style.display = "none";
            campo.querySelector(".texto").style.display = "inline-block";
            campo.querySelector(".input-campo").value = campo.querySelector(".texto").textContent;
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
});
