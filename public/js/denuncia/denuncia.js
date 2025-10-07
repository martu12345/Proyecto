const modal = document.getElementById("denunciaModal");
const openBtn = document.getElementById("openModalBtn");
const closeBtn = document.getElementById("closeModalBtn");

// Abrir modal solo al hacer click
openBtn.onclick = () => modal.style.display = "block";

// Cerrar modal al hacer click en la X
closeBtn.onclick = () => modal.style.display = "none";

// Cerrar modal al hacer click fuera del contenido
window.onclick = (event) => {
  if (event.target == modal) modal.style.display = "none";
}