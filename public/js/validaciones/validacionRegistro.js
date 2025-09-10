document.addEventListener("DOMContentLoaded", () => {
    // ======== CLIENTE ========
    const formCliente = document.getElementById("formulario-cliente");
    if(formCliente){
        const emailCliente = document.getElementById("email");
        
        formCliente.addEventListener("submit", async (e) => {
            e.preventDefault();

            const nombre = document.getElementById("nombre").value.trim();
            const apellido = document.getElementById("apellido").value.trim();
            const email = emailCliente.value.trim();
            const telefono = document.getElementById("telefono").value.trim();
            const contrasena = document.getElementById("contrasena").value;

            if(nombre.length < 2 || !/^[a-zA-Z]+$/.test(nombre)){ alert("Nombre inválido"); return; }
            if(apellido.length < 2 || !/^[a-zA-Z]+$/.test(apellido)){ alert("Apellido inválido"); return; }
            if(!/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(email)){ alert("Email inválido"); return; }
            if(!/^[0-9]{9}$/.test(telefono)){ alert("Teléfono inválido"); return; }
            if(contrasena.length < 8){ alert("Contraseña inválida"); return; }

            try {
                const res = await fetch(`/Proyecto/apps/Controlador/VerificarEmail.php?email=${encodeURIComponent(email)}`);
                const data = await res.json();
                if(data.existe){
                    alert("Este email ya está registrado. Usa otro.");
                    emailCliente.focus();
                    return;
                }
            } catch(err) {
                console.error("Error al verificar email:", err);
                alert("No se pudo verificar el email. Intenta de nuevo.");
                return;
            }

            formCliente.submit();
        });
    }

    // ======== EMPRESA ========
    const formEmpresa = document.getElementById("formulario-empresa");
    if(formEmpresa){
        const emailEmpresa = document.getElementById("emailEmpresa");

        formEmpresa.addEventListener("submit", async (e) => {
            e.preventDefault();

            const nombreEmpresa = document.getElementById("nombreEmpresa").value.trim();
            const email = emailEmpresa.value.trim();
            const contrasena = document.getElementById("contrasenaEmpresa").value;
            const telefono = document.getElementById("telefonoEmpresa").value.trim();
            const calle = document.getElementById("calle").value.trim();
            const numero = document.getElementById("numero").value.trim();

            if(nombreEmpresa.length < 2){ alert("Nombre de empresa inválido"); return; }
            if(!/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(email)){ alert("Email inválido"); return; }
            if(contrasena.length < 8){ alert("Contraseña inválida"); return; }
            if(!/^[0-9]+$/.test(telefono.replace(/\D/g,''))){ alert("Teléfono inválido"); return; }
            if(calle.length < 2){ alert("Calle inválida"); return; }
            if(numero.length < 1){ alert("Número de calle inválido"); return; }

            try {
                const res = await fetch(`/Proyecto/apps/Controlador/VerificarEmail.php?email=${encodeURIComponent(email)}`);
                const data = await res.json();
                if(data.existe){
                    alert("Este email ya está registrado. Usa otro.");
                    emailEmpresa.focus();
                    return;
                }
            } catch(err) {
                console.error("Error al verificar email:", err);
                alert("No se pudo verificar el email. Intenta de nuevo.");
                return;
            }

            formEmpresa.submit();
        });
    }
});
