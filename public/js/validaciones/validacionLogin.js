// validacionLogin.js

document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.getElementById("formulario-login");
    const mensajeError = document.getElementById("mensaje-error");

    formulario.addEventListener("submit", async (e) => {
        e.preventDefault(); // Evita que se recargue la página

        mensajeError.textContent = ""; // limpio mensajes previos

        const formData = new FormData(formulario);

        try {
            const response = await fetch('/Proyecto/apps/Controlador/LoginControlador.php', {
                method: 'POST',
                body: formData
            });

            // Verifico que la respuesta sea OK
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            // Intento parsear el JSON
            const data = await response.json();

            if (data.exito) {
                // Si todo OK, redirijo
                window.location.href = data.redirect;
            } else {
                // Muestro mensaje de error
                mensajeError.textContent = data.mensaje;
            }

        } catch (error) {
            console.error("Error al verificar email o login:", error);
            mensajeError.textContent = "Ocurrió un error al iniciar sesión. Intenta nuevamente.";
        }
    });
});
