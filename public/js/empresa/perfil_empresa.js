document.addEventListener("DOMContentLoaded", function () {
    const btnEditar = document.getElementById("btnEditar");
    const btnGuardar = document.getElementById("btnGuardar");
    const btnCancelar = document.getElementById("btnCancelar");
    const campos = document.querySelectorAll(".campo-perfil");
    const form = document.getElementById("formPerfilEmpresa");

    btnEditar.addEventListener("click", function () {
        campos.forEach(campo => {
            campo.querySelector(".input-campo").style.display = "inline-block";
            campo.querySelector(".texto").style.display = "none";
        });
        btnEditar.style.display = "none";
        btnGuardar.style.display = "inline-block";
        btnCancelar.style.display = "inline-block";
    });

    btnCancelar.addEventListener("click", function () {
        campos.forEach(campo => {
            campo.querySelector(".input-campo").style.display = "none";
            campo.querySelector(".texto").style.display = "inline-block";
            campo.querySelector(".input-campo").value = campo.querySelector(".texto").textContent;
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

        fetch("/Proyecto/apps/controlador/empresa/EditarEmpresaControlador.php", {
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
                    });

                    const nombreCol = document.getElementById("nombreColumna");
                    nombreCol.textContent = formData.get("nombreEmpresa");

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
