document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formulario-admin");
    if (!form) return;

    const emailAdmin = document.getElementById("email");
    const mensajeAdmin = document.getElementById("mensaje-error-admin");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        mensajeAdmin.textContent = "";
        mensajeAdmin.style.color = "red"; // color por defecto

        const email = emailAdmin.value.trim();
        const telefono = document.getElementById("telefono").value.trim();
        const contrasena = document.getElementById("contrasena").value;

        // ===== VALIDACIONES =====
        if (!/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(email)) {
            mensajeAdmin.textContent = "❌ Email inválido.";
            emailAdmin.focus();
            return;
        }
        if (!/^09[0-9]{7}$/.test(telefono)) {
            mensajeAdmin.textContent = "❌ Teléfono inválido. Debe ser uruguayo (09xxxxxxx)";
            return;
        }
        if (contrasena.length < 8) {
            mensajeAdmin.textContent = "❌ La contraseña debe tener al menos 8 caracteres.";
            return;
        }

        try {
            // ===== VERIFICAR EMAIL ANTES DE ENVIAR =====
            const resVerif = await fetch(`/Proyecto/apps/Controlador/VerificarEmail.php?email=${encodeURIComponent(email)}`);
            const dataVerif = await resVerif.json();

            if (dataVerif.existe) {
                mensajeAdmin.textContent = "❌ Este email ya está registrado.";
                emailAdmin.focus();
                return;
            }

            // ===== ENVIAR FORMULARIO =====
            const formData = new FormData(form);
            const res = await fetch(form.action, {
                method: "POST",
                body: formData
            });

            const data = await res.json(); // siempre parsear JSON

            if (data.success) {
                mensajeAdmin.style.color = "green";
                mensajeAdmin.textContent = "✅ Administrador creado correctamente!";
                form.reset();
            } else if (data.error) {
                mensajeAdmin.style.color = "red";
                mensajeAdmin.textContent = "❌ " + data.error;
            } else {
                mensajeAdmin.style.color = "red";
                mensajeAdmin.textContent = "❌ Ocurrió un error inesperado.";
            }

        } catch (err) {
            console.error("Error:", err);
            mensajeAdmin.style.color = "red";
            mensajeAdmin.textContent = "❌ Error al procesar la solicitud.";
        }
    });
});
