document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formBorrarAdmin");
  const mensaje = document.getElementById("mensaje-error-admin");

  form.addEventListener("submit", function (e) {
    e.preventDefault(); // evita recarga

    const formData = new FormData(this);

    fetch("/Proyecto/apps/Controlador/administrador/BorrarAdminControlador.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.text())
      .then(data => {
        mensaje.style.color = data.includes("correctamente") ? "green" : "red";
        mensaje.innerHTML = data;
      })
      .catch(err => {
        mensaje.style.color = "red";
        mensaje.innerHTML = "Error: " + err;
      });
  });
});
