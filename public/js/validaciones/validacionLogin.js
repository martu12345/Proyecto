/* const formLogin = document.getElementById("formulario-login");
if (formLogin) {
    formLogin.addEventListener("submit", async (e) => {
        e.preventDefault();

        const email = document.getElementById("email").value.trim();
        const contrasena = document.getElementById("contrasena").value;

        // Validaciones básicas
        if (!/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(email)) {
            alert("Email inválido");
            return;
        }
        try {
            // Verifico si existe el email antes de enviar
            const resEmail = await fetch(`/Proyecto/apps/Controlador/VerificarEmail.php?email=${encodeURIComponent(email)}`);
            const dataEmail = await resEmail.json();

            if (!dataEmail.existe) {
                alert("No existe ninguna cuenta registrada con ese email");
                return;
            }

            // Intento login
            const resLogin = await fetch("/Proyecto/apps/Controlador/loginControlador.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `email=${encodeURIComponent(email)}&contrasena=${encodeURIComponent(contrasena)}`
            });
            const mensaje = await resLogin.text(); // 👈 leemos texto plano

            // Si el mensaje es vacío, asumimos que se redirigió correctamente
            if (mensaje) {
                alert("contraseña incorrecta"); // muestra "Contraseña incorrecta" o cualquier otro mensaje del PHP
            }

        } catch (err) {
            console.error("Error al verificar email o login:", err);
            alert("No se pudo verificar el email o iniciar sesión. Intenta de nuevo.");
        
    }
    });
}
