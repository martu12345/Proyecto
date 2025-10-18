document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formulario-admin");
    if (!form) return;

    const emailInput = document.getElementById("email");
    const telefonoInput = document.getElementById("telefono");
    const contrasenaInput = document.getElementById("contrasena");
    const mensaje = document.getElementById("mensaje-error-admin");

    form.addEventListener("submit", async (e) => {
        e.preventDefault(); // ⚡ evita envío tradicional
        mensaje.textContent = "";
        mensaje.style.color = "red";

        const email = emailInput.value.trim();
        const telefono = telefonoInput.value.trim();
        const contrasena = contrasenaInput.value;

        // ===== VALIDACIONES LOCALES =====
        if (!/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(email)) {
            mensaje.textContent = "❌ Email inválido.";
            emailInput.focus();
            return;
        }

        if (!/^09[0-9]{7}$/.test(telefono)) {
            mensaje.textContent = "❌ Teléfono inválido. Debe ser uruguayo (09xxxxxxx)";
            telefonoInput.focus();
            return;
        }

        if (contrasena.length < 8) {
            mensaje.textContent = "❌ La contraseña debe tener al menos 8 caracteres.";
            contrasenaInput.focus();
            return;
        }

        try {
            // ===== VERIFICAR EMAIL POR POST =====
            let formData = new FormData();
            formData.append("accion", "verificar");
            formData.append("email", email);

            const resVerif = await fetch("/Proyecto/apps/Controlador/administrador/AdministradorControlador.php", {
                method: "POST",
                body: formData
            });

            if (!resVerif.ok) throw new Error("Error HTTP: " + resVerif.status);
            const dataVerif = await resVerif.json();

            if (dataVerif.existe) {
                mensaje.textContent = "❌ Este email ya está registrado.";
                emailInput.focus();
                return;
            }

            // ===== ENVIAR FORMULARIO REAL =====
            formData = new FormData(form); // ahora sí incluye todo el form
            const res = await fetch(form.action, {
                method: "POST",
                body: formData
            });

            if (!res.ok) throw new Error("Error HTTP: " + res.status);
            const data = await res.json();

            if (data.success) {
                mensaje.style.color = "green";
                mensaje.textContent = "✅ Administrador creado correctamente!";
                form.reset();
            } else if (data.error) {
                mensaje.textContent = "❌ " + data.error;
            } else {
                mensaje.textContent = "❌ Ocurrió un error inesperado.";
            }

        } catch (err) {
            console.error("Error:", err);
            mensaje.textContent = "❌ Error al procesar la solicitud.";
        }
    });
});
