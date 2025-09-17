document.addEventListener("DOMContentLoaded", () => {

    // ======== CLIENTE ========
    const formCliente = document.getElementById("formulario-cliente");
    if (formCliente) {
        const emailCliente = document.getElementById("email");

        formCliente.addEventListener("submit", async (e) => {
            e.preventDefault();

            const nombre = document.getElementById("nombre").value.trim();
            const apellido = document.getElementById("apellido").value.trim();
            const email = emailCliente.value.trim();
            const telefono = document.getElementById("telefono").value.trim();
            const contrasena = document.getElementById("contrasena").value;

            // VALIDACIONES
            if (nombre.length < 2 || !/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/.test(nombre)) {
                alert("Nombre inválido (solo letras, mínimo 2)"); return;
            }
            if (apellido.length < 2 || !/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/.test(apellido)) {
                alert("Apellido inválido (solo letras, mínimo 2)"); return;
            }
            if (!/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(email)) {
                alert("Email inválido"); return;
            }
            // Teléfono uruguayo (09 + 7 dígitos = total 9)
            if (!/^09[0-9]{7}$/.test(telefono)) {
                alert("Teléfono inválido. Debe ser uruguayo y tener 9 dígitos (ej: 09xxxxxxx)"); return;
            }
            // Contraseña fuerte
            if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&.,;:])[A-Za-z\d@$!%*#?&.,;:]{8,}$/.test(contrasena)) {
                alert("La contraseña debe tener al menos 8 caracteres, con mayúscula, minúscula, número y símbolo"); return;
            }

            // VERIFICAR EMAIL
            try {
                const res = await fetch(`/Proyecto/apps/Controlador/VerificarEmail.php?email=${encodeURIComponent(email)}`);
                const data = await res.json();
                if (data.existe) {
                    alert("Este email ya está registrado. Usa otro.");
                    emailCliente.focus();
                    return;
                }
            } catch (err) {
                console.error("Error al verificar email:", err);
                alert("No se pudo verificar el email. Intenta de nuevo.");
                return;
            }

            // TODO OK -> ENVIAR FORM
            console.log("Todo validado. Enviando formulario...");
            e.target.submit();
        });
    }

    // ======== EMPRESA ========
    const formEmpresa = document.getElementById("formulario-empresa");
    if (formEmpresa) {
        const emailEmpresa = document.getElementById("emailEmpresa");

        formEmpresa.addEventListener("submit", async (e) => {
            e.preventDefault();

            const nombreEmpresa = document.getElementById("nombreEmpresa").value.trim();
            const email = emailEmpresa.value.trim();
            const contrasena = document.getElementById("contrasenaEmpresa").value;
            const telefono = document.getElementById("telefonoEmpresa").value.trim();
            const calle = document.getElementById("calle").value.trim();
            const numero = document.getElementById("numero").value.trim();

            // VALIDACIONES
            if (nombreEmpresa.length < 2 || !/^[A-Za-z0-9ÁÉÍÓÚáéíóúñÑ\s]+$/.test(nombreEmpresa)) {
                alert("Nombre de empresa inválido"); return;
            }
            if (!/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(email)) {
                alert("Email inválido"); return;
            }
            // Teléfono uruguayo (09 + 7 dígitos = total 9)
            if (!/^09[0-9]{7}$/.test(telefono)) {
                alert("Teléfono inválido. Debe ser uruguayo y tener 9 dígitos (ej: 09xxxxxxx)"); return;
            }
            if (calle.length < 2 || calle.length > 50) {
                alert("Calle inválida (mínimo 2, máximo 50 caracteres)"); return;
            }
            if (!/^[0-9]{1,5}$/.test(numero)) {
                alert("Número de calle inválido (solo hasta 5 dígitos)"); return;
            }
            // Contraseña fuerte
            if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&.,;:])[A-Za-z\d@$!%*#?&.,;:]{8,}$/.test(contrasena)) {
                alert("La contraseña debe tener al menos 8 caracteres, con mayúscula, minúscula, número y símbolo"); return;
            }

            // VERIFICAR EMAIL
            try {
                const res = await fetch(`/Proyecto/apps/Controlador/VerificarEmail.php?email=${encodeURIComponent(email)}`);
                const data = await res.json();
                if (data.existe) {
                    alert("Este email ya está registrado. Usa otro.");
                    emailEmpresa.focus();
                    return;
                }
            } catch (err) {
                console.error("Error al verificar email:", err);
                alert("No se pudo verificar el email. Intenta de nuevo.");
                return;
            }

            // TODO OK -> ENVIAR FORM
            console.log("Todo validado. Enviando formulario...");
            e.target.submit();
        });
    }
});
