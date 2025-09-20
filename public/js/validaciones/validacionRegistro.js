document.addEventListener("DOMContentLoaded", () => {

    // ======== CLIENTE ========
    const formCliente = document.getElementById("formulario-cliente");
    if (formCliente) {
        const emailCliente = document.getElementById("email");
        const mensajeErrorCliente = document.getElementById("mensaje-error-cliente");

        formCliente.addEventListener("submit", async (e) => {
            e.preventDefault();
            mensajeErrorCliente.textContent = ""; // limpio mensajes previos

            const nombre = document.getElementById("nombre").value.trim();
            const apellido = document.getElementById("apellido").value.trim();
            const email = emailCliente.value.trim();
            const telefono = document.getElementById("telefono").value.trim();
            const contrasena = document.getElementById("contrasena").value;

            // VALIDACIONES
            if (nombre.length < 2 || !/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/.test(nombre)) {
                mensajeErrorCliente.textContent = "Nombre inválido (solo letras, mínimo 2)"; return;
            }
            if (apellido.length < 2 || !/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/.test(apellido)) {
                mensajeErrorCliente.textContent = "Apellido inválido (solo letras, mínimo 2)"; return;
            }
            if (!/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(email)) {
                mensajeErrorCliente.textContent = "Email inválido"; return;
            }
            if (!/^09[0-9]{7}$/.test(telefono)) {
                mensajeErrorCliente.textContent = "Teléfono inválido. Debe ser uruguayo y tener 9 dígitos (ej: 09xxxxxxx)"; return;
            }
            if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&.,;:])[A-Za-z\d@$!%*#?&.,;:]{8,}$/.test(contrasena)) {
                mensajeErrorCliente.textContent = "La contraseña debe tener al menos 8 caracteres, con mayúscula, minúscula, número y símbolo"; return;
            }

            // VERIFICAR EMAIL
            try {
                const res = await fetch(`/Proyecto/apps/Controlador/VerificarEmail.php?email=${encodeURIComponent(email)}`);
                const data = await res.json();
                if (data.existe) {
                    mensajeErrorCliente.textContent = "Este email ya está registrado. Usa otro.";
                    emailCliente.focus();
                    return;
                }
            } catch (err) {
                console.error("Error al verificar email:", err);
                mensajeErrorCliente.textContent = "No se pudo verificar el email. Intenta de nuevo.";
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
        const mensajeErrorEmpresa = document.getElementById("mensaje-error-empresa");

        formEmpresa.addEventListener("submit", async (e) => {
            e.preventDefault();
            mensajeErrorEmpresa.textContent = "";

            const nombreEmpresa = document.getElementById("nombreEmpresa").value.trim();
            const email = emailEmpresa.value.trim();
            const contrasena = document.getElementById("contrasenaEmpresa").value;
            const telefono = document.getElementById("telefonoEmpresa").value.trim();
            const calle = document.getElementById("calle").value.trim();
            const numero = document.getElementById("numero").value.trim();

            // VALIDACIONES
            if (nombreEmpresa.length < 2 || !/^[A-Za-z0-9ÁÉÍÓÚáéíóúñÑ\s]+$/.test(nombreEmpresa)) {
                mensajeErrorEmpresa.textContent = "Nombre de empresa inválido"; return;
            }
            if (!/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(email)) {
                mensajeErrorEmpresa.textContent = "Email inválido"; return;
            }
            if (!/^09[0-9]{7}$/.test(telefono)) {
                mensajeErrorEmpresa.textContent = "Teléfono inválido. Debe ser uruguayo y tener 9 dígitos (ej: 09xxxxxxx)"; return;
            }
            if (calle.length < 2 || calle.length > 50) {
                mensajeErrorEmpresa.textContent = "Calle inválida (mínimo 2, máximo 50 caracteres)"; return;
            }
            if (!/^[0-9]{1,5}$/.test(numero)) {
                mensajeErrorEmpresa.textContent = "Número de calle inválido (solo hasta 5 dígitos)"; return;
            }
            if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&.,;:])[A-Za-z\d@$!%*#?&.,;:]{8,}$/.test(contrasena)) {
                mensajeErrorEmpresa.textContent = "La contraseña debe tener al menos 8 caracteres, con mayúscula, minúscula, número y símbolo"; return;
            }

            // VERIFICAR EMAIL
            try {
                const res = await fetch(`/Proyecto/apps/Controlador/VerificarEmail.php?email=${encodeURIComponent(email)}`);
                const data = await res.json();
                if (data.existe) {
                    mensajeErrorEmpresa.textContent = "Este email ya está registrado. Usa otro.";
                    emailEmpresa.focus();
                    return;
                }
            } catch (err) {
                console.error("Error al verificar email:", err);
                mensajeErrorEmpresa.textContent = "No se pudo verificar el email. Intenta de nuevo.";
                return;
            }

            // TODO OK -> ENVIAR FORM
            console.log("Todo validado. Enviando formulario...");
            e.target.submit();
        });
    }
});
