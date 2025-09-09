document.addEventListener("DOMContentLoaded", function() {
    const btnEditar = document.getElementById("btnEditar");
    const btnGuardar = document.getElementById("btnGuardar");
    const btnCancelar = document.getElementById("btnCancelar");
    const campos = document.querySelectorAll(".campo-perfil");
    const form = document.getElementById("formPerfil");

    // Función para activar edición
    btnEditar.addEventListener("click", function() {
        campos.forEach(campo => {
            campo.classList.add("editando");
            campo.querySelector(".input-campo").style.display = "inline-block";
            campo.querySelector(".texto").style.display = "none";
        });
        btnEditar.style.display = "none";
        btnGuardar.style.display = "inline-block";
        btnCancelar.style.display = "inline-block";
    });

    // Función para cancelar edición
    btnCancelar.addEventListener("click", function() {
        campos.forEach(campo => {
            campo.classList.remove("editando");
            campo.querySelector(".input-campo").style.display = "none";
            campo.querySelector(".texto").style.display = "inline-block";

            // Volver los inputs a los valores originales
            const input = campo.querySelector(".input-campo");
            const texto = campo.querySelector(".texto");
            input.value = texto.textContent;
        });
        btnEditar.style.display = "inline-block";
        btnGuardar.style.display = "none";
        btnCancelar.style.display = "none";
    });

    // Función para guardar
    btnGuardar.addEventListener("click", function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        // Validaciones simples (email y contraseña obligatorios)
        if (!formData.get("email") || !formData.get("contrasena")) {
            alert("Email y contraseña son obligatorios");
            return;
        }

        fetch("/Proyecto/apps/controlador/GuardarControlador.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                // Actualizar la vista con los valores ingresados
                campos.forEach(campo => {
                    const texto = campo.querySelector(".texto");
                    const input = campo.querySelector(".input-campo");
                    texto.textContent = input.value;
                    texto.style.display = "inline-block";
                    input.style.display = "none";
                    campo.classList.remove("editando");
                });

                // Actualizar nombre en la columna izquierda
                const nombreCol = document.getElementById("nombreColumna");
                const nombre = formData.get("nombre");
                const apellido = formData.get("apellido");
                nombreCol.textContent = nombre + " " + apellido;

                // Volver botones a estado inicial
                btnEditar.style.display = "inline-block";
                btnGuardar.style.display = "none";
                btnCancelar.style.display = "none";

            } else {
                alert("Error al guardar los datos: " + (data.error || "Error desconocido"));
            }
        })
        .catch(err => console.error("Error fetch:", err));
    });
});
