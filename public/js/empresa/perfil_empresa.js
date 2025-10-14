document.addEventListener("DOMContentLoaded", function () {

    // --- ELEMENTOS DEL FORMULARIO DE PERFIL ---
    const form = document.getElementById("formPerfilEmpresa");
    const btnEditar = document.getElementById("btnEditar");
    const btnGuardar = document.getElementById("btnGuardar");
    const btnCancelar = document.getElementById("btnCancelar");
    const campos = document.querySelectorAll(".campo-perfil");

    // --- Solo si existe el formulario ---
    if (form && btnEditar && btnGuardar && btnCancelar && campos.length > 0) {

        form.addEventListener("submit", function(e){
            e.preventDefault();
        });

        btnEditar.addEventListener("click", () => {
            campos.forEach(campo => {
                const input = campo.querySelector(".input-campo");
                const texto = campo.querySelector(".texto");
                if(input && texto){
                    input.style.display = "inline-block";
                    texto.style.display = "none";
                }

                // Mostrar aviso solo en el campo de contraseña
                const aviso = campo.querySelector(".aviso-editar");
                if(aviso) aviso.style.display = "inline";
            });

            btnEditar.style.display = "none";
            btnGuardar.style.display = "inline-block";
            btnCancelar.style.display = "inline-block";
        });

        btnCancelar.addEventListener("click", () => {
            campos.forEach(campo => {
                const input = campo.querySelector(".input-campo");
                const texto = campo.querySelector(".texto");
                if(input && texto){
                    input.value = texto.textContent;
                    input.style.display = "none";
                    texto.style.display = "inline-block";
                }

                // Ocultar aviso de contraseña al cancelar
                const aviso = campo.querySelector(".aviso-editar");
                if(aviso) aviso.style.display = "none";
            });

            btnEditar.style.display = "inline-block";
            btnGuardar.style.display = "none";
            btnCancelar.style.display = "none";
        });

        btnGuardar.addEventListener("click", (e) => {
            e.preventDefault();

            const formData = new FormData(form);

            if (!formData.get("email")) {
                alert("El email es obligatorio");
                return;
            }

            fetch("/Proyecto/apps/controlador/empresa/EditarEmpresaControlador.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    campos.forEach(campo => {
                        const input = campo.querySelector(".input-campo");
                        const texto = campo.querySelector(".texto");
                        if(input && texto){
                            texto.textContent = input.value;
                            texto.style.display = "inline-block";
                            input.style.display = "none";
                        }

                        // Ocultar aviso después de guardar
                        const aviso = campo.querySelector(".aviso-editar");
                        if(aviso) aviso.style.display = "none";
                    });

                    const nombreColumna = document.getElementById("nombreColumna");
                    if(nombreColumna) nombreColumna.textContent = formData.get("nombreEmpresa");

                    btnEditar.style.display = "inline-block";
                    btnGuardar.style.display = "none";
                    btnCancelar.style.display = "none";
                } else {
                    alert("Error al guardar: " + (data.error || "Desconocido"));
                }
            })
            .catch(err => console.error("Error fetch:", err));
        });
    }

    // --- SUBIR FOTO ---
    const fotoCirculo = document.querySelector('.foto-circulo');
    const fotoInput = document.getElementById('fotoInput');
    const formFoto = document.getElementById('formFoto');

    if(fotoCirculo && fotoInput && formFoto){
        fotoCirculo.addEventListener('click', () => fotoInput.click());

        fotoInput.addEventListener('change', function() {
            const formData = new FormData(formFoto);

            fetch(formFoto.action, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(data.ok){
                    const img = fotoCirculo.querySelector('img.foto-perfil');
                    if(img){
                        img.src = `/Proyecto/public/imagen/empresas/${data.imagen}?t=${Date.now()}`;
                    } else {
                        fotoCirculo.innerHTML = `<img src="/Proyecto/public/imagen/empresas/${data.imagen}?t=${Date.now()}" class="foto-perfil" alt="Foto Empresa"><span class="cambiar-foto">+</span>`;
                    }
                } else {
                    alert('Error al subir la foto: ' + data.error);
                }
            })
            .catch(err => console.error(err));
        });
    }

    // --- CAMBIO DE SECCIÓN ---
    const opciones = document.querySelectorAll('.opcion');
    opciones.forEach(opcion => {
        opcion.addEventListener('click', function(e){
            e.preventDefault();
            const seccion = this.getAttribute('data-seccion');

            opciones.forEach(o => o.classList.remove('activa'));
            this.classList.add('activa');

            const url = new URL(window.location.href);
            url.searchParams.set('seccion', seccion);
            window.location.href = url.toString();
        });
    });

    function cargarHistorial() {
        const tbodyFinal = document.querySelector('#tablaFinalizado tbody');
        const tbodyCancelado = document.querySelector('#tablaCancelado tbody');

        if(!tbodyFinal || !tbodyCancelado) return;

        fetch('/Proyecto/apps/controlador/empresa/AgendadosControlador.php')
        .then(res => res.json())
        .then(data => {
            tbodyFinal.innerHTML = '';
            tbodyCancelado.innerHTML = '';

            if(data.error){
                tbodyFinal.innerHTML = `<tr><td colspan="7">${data.error}</td></tr>`;
                tbodyCancelado.innerHTML = `<tr><td colspan="5">${data.error}</td></tr>`;
                return;
            }

            data.forEach(item => {
                const tr = document.createElement('tr');
                tr.dataset.id = item.IdCita;

                if(item.Estado === "Finalizado"){
                    tr.innerHTML = `
                        <td>${item.NombreServicio}</td>
                        <td>${item.NombreCliente}</td>
                        <td>${item.Fecha}</td>
                        <td>${item.Hora}</td>
                        <td>${item.Estado}</td>
                        <td>${item.Calificacion ? '⭐'.repeat(item.Calificacion) : 'No hay'}</td>
                        <td>${item.Resena ? item.Resena : 'No hay'}</td>
                    `;
                    tbodyFinal.appendChild(tr);
                } else if(item.Estado === "Cancelado"){
                    tr.innerHTML = `
                        <td>${item.NombreServicio}</td>
                        <td>${item.NombreCliente}</td>
                        <td>${item.Fecha}</td>
                        <td>${item.Hora}</td>
                        <td>${item.Estado}</td>
                    `;
                    tbodyCancelado.appendChild(tr);
                }
            });

            const btnActivo = document.querySelector('.filtro-btn.activa');
            const estadoActivo = btnActivo ? btnActivo.dataset.estado : "Finalizado";
            document.getElementById('tablaFinalizado').style.display = estadoActivo === "Finalizado" ? 'table' : 'none';
            document.getElementById('tablaCancelado').style.display = estadoActivo === "Cancelado" ? 'table' : 'none';
        })
        .catch(err => console.error('Error al cargar historial:', err));
    }

    // --- FILTRO DE TABLAS ---
    document.querySelectorAll('.filtro-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('activa'));
            btn.classList.add('activa');

            const estado = btn.dataset.estado;
            document.getElementById('tablaFinalizado').style.display = estado === "Finalizado" ? 'table' : 'none';
            document.getElementById('tablaCancelado').style.display = estado === "Cancelado" ? 'table' : 'none';
        });
    });

    // Llamada inicial
    cargarHistorial();
});
