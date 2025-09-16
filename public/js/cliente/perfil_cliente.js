// Manejo del perfil de cliente (editar datos + cambiar imagen)
document.addEventListener("DOMContentLoaded", function () {
    const btnEditar = document.getElementById("btnEditar");
    const btnGuardar = document.getElementById("btnGuardar");
    const btnCancelar = document.getElementById("btnCancelar");
    const campos = document.querySelectorAll(".campo-perfil");
    const form = document.getElementById("formPerfil");
    const inputImagen = document.getElementById("inputImagen"); // input type="file"
    const imgPerfil = document.getElementById("imgPerfil"); // <img> actual

    // ---- EDITAR CAMPOS ----
    btnEditar.addEventListener("click", function () {
        campos.forEach(campo => {
            campo.classList.add("editando");
            campo.querySelector(".input-campo").style.display = "inline-block";
            campo.querySelector(".texto").style.display = "none";
        });
        btnEditar.style.display = "none";
        btnGuardar.style.display = "inline-block";
        btnCancelar.style.display = "inline-block";
    });

    btnCancelar.addEventListener("click", function () {
        campos.forEach(campo => {
            campo.classList.remove("editando");
            campo.querySelector(".input-campo").style.display = "none";
            campo.querySelector(".texto").style.display = "inline-block";

            const input = campo.querySelector(".input-campo");
            const texto = campo.querySelector(".texto");
            input.value = texto.textContent;
        });
        btnEditar.style.display = "inline-block";
        btnGuardar.style.display = "none";
        btnCancelar.style.display = "none";
    });

    btnGuardar.addEventListener("click", function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        if (!formData.get("email") || !formData.get("contrasena")) {
            alert("Email y contraseña son obligatorios");
            return;
        }

        fetch("/Proyecto/apps/controlador/cliente/EditarPerfilControlador.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    campos.forEach(campo => {
                        const texto = campo.querySelector(".texto");
                        const input = campo.querySelector(".input-campo");
                        texto.textContent = input.value;
                        texto.style.display = "inline-block";
                        input.style.display = "none";
                        campo.classList.remove("editando");
                    });

                    const nombreCol = document.getElementById("nombreColumna");
                    const nombre = formData.get("nombre");
                    const apellido = formData.get("apellido");
                    nombreCol.textContent = nombre + " " + apellido;

                    btnEditar.style.display = "inline-block";
                    btnGuardar.style.display = "none";
                    btnCancelar.style.display = "none";

                } else {
                    alert("Error al guardar los datos: " + (data.error || "Error desconocido"));
                }
            })
            .catch(err => console.error("Error fetch:", err));
    });

    // ---- CAMBIO DE IMAGEN ----
    if (inputImagen) {
        inputImagen.addEventListener("change", function () {
            const file = inputImagen.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append("imagen", file);

            fetch("/Proyecto/apps/controlador/cliente/ClienteImagenControlador.php", {
                method: "POST",
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.ok) {
                        // refrescar imagen en pantalla
                        imgPerfil.src = "/Proyecto/public/imagen/clientes/" + data.imagen + "?t=" + new Date().getTime();
                    } else {
                        alert("Error al subir imagen: " + (data.error || "Error desconocido"));
                    }
                })
                .catch(err => console.error("Error subida imagen:", err));
        });
    }
});
