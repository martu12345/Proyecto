document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  const mensaje = document.getElementById("mensaje-error-admin");

  form.addEventListener("submit", function (e) {
    e.preventDefault(); // evita recarga

    const email = document.getElementById("email").value.trim();
    const telefono = document.getElementById("telefono").value.trim();
    const contrasena = document.getElementById("contrasena").value.trim();

    // 🔹 Validaciones
    if (!email || !telefono || !contrasena) {
      mensaje.style.color = "red";
      mensaje.innerHTML = "Todos los campos son obligatorios.";
      return;
    }

    // Email válido
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      mensaje.style.color = "red";
      mensaje.innerHTML = "El email ingresado no es válido.";
      return;
    }

    // Teléfono válido (9 dígitos)
    const telRegex = /^[0-9]{9}$/;
    if (!telRegex.test(telefono)) {
      mensaje.style.color = "red";
      mensaje.innerHTML = "Ingrese un número uruguayo de 9 dígitos.";
      return;
    }

    // Contraseña mínima 6 caracteres (opcional, podés ajustar)
    if (contrasena.length < 6) {
      mensaje.style.color = "red";
      mensaje.innerHTML = "La contraseña debe tener al menos 6 caracteres.";
      return;
    }

    // 🔹 Enviar datos con fetch
    const formData = new FormData(this);

    fetch("/Proyecto/apps/Controlador/administrador/AdministradorControlador.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.text())
      .then(data => {
        mensaje.style.color = data.includes("creado") ? "green" : "red";
        mensaje.innerHTML = data;
      })
      .catch(err => {
        mensaje.style.color = "red";
        mensaje.innerHTML = "Error al comunicarse con el servidor.";
        console.error(err);
      });
  });
});
