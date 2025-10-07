// --- Variables desde PHP ---
const citasOcupadas = window.citasOcupadas || [];
const duracionServicio = parseFloat(window.duracionServicio || 1); // duración real desde PHP
const meses = window.meses || {};

// --- Elementos del DOM ---
const inputHora = document.getElementById('hora');
const inputDia = document.getElementById('inputDia');
const errorHora = document.getElementById('errorHora');
const form = document.getElementById('formServicio');
const calendarioDiv = document.getElementById('calendario');
const tituloMes = document.getElementById('tituloMes');
const prevMes = document.getElementById('prevMes');
const nextMes = document.getElementById('nextMes');

errorHora.style.whiteSpace = 'pre-line';

// --- Funciones auxiliares ---
function minutosAHora(minutos) {
    const h = Math.floor(minutos / 60);
    const m = Math.round(minutos % 60);
    return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
}

function horaAMinutos(horaStr) {
    const [hStr, mStr] = horaStr.split(':');
    return parseInt(hStr, 10) * 60 + parseInt(mStr, 10);
}

// --- Calcula horarios ocupados del día ---
function calcularOcupados(dia) {
    return citasOcupadas
        .filter(c => c.Fecha === dia)
        .map(c => {
            const [h, m] = c.Hora.split(':').map(Number);
            const inicio = h * 60 + m;
            const fin = inicio + parseFloat(c.Duracion) * 60;
            return { inicio, fin };
        })
        .sort((a, b) => a.inicio - b.inicio);
}

// --- Validación de hora ---
function validarHora() {
    const diaSeleccionado = inputDia.value;
    const horaSeleccionada = inputHora.value;

    if (!diaSeleccionado) {
        errorHora.textContent = "⚠️ Seleccioná un día primero.";
        return false;
    }

    if (!horaSeleccionada) {
        errorHora.textContent = "⚠️ Ingresá una hora.";
        return false;
    }

    const inicio = horaAMinutos(horaSeleccionada);
    const fin = inicio + duracionServicio * 60;

    // Verificar si el servicio se pasa del día
    if (fin > 24 * 60) {
        errorHora.textContent = `⚠️ Tu servicio dura ${duracionServicio}h y no entra en este día.`;
        return false;
    }

    const ocupadosDia = calcularOcupados(diaSeleccionado);

    for (const c of ocupadosDia) {
        if (inicio < c.fin && fin > c.inicio) {
            errorHora.textContent = `⚠️ Tu servicio dura ${duracionServicio}h y choca con otro servicio de ${minutosAHora(c.inicio)} a ${minutosAHora(c.fin)}.`;
            return false;
        }
    }

    errorHora.textContent = "";
    return true;
}

// --- Evento submit ---
form.addEventListener('submit', function (e) {
    if (!validarHora()) e.preventDefault();
});

// --- Validación en tiempo real ---
inputHora.addEventListener('input', validarHora);

// --- Inicializar calendario ---
let fechaActual = new Date();
let mesVisible = fechaActual.getMonth() + 1;
let anioVisible = fechaActual.getFullYear();

function renderCalendario(mes, anio) {
    calendarioDiv.innerHTML = "";
    tituloMes.textContent = meses[mes] + " " + anio;

    const diasSemana = ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"];
    diasSemana.forEach(d => {
        const div = document.createElement("div");
        div.className = "dias-semana";
        div.textContent = d;
        calendarioDiv.appendChild(div);
    });

    const primerDia = new Date(anio, mes - 1, 1).getDay() || 7;
    const diasMes = new Date(anio, mes, 0).getDate();
    const hoyStr = new Date().toISOString().split('T')[0];

    for (let i = 1; i < primerDia; i++) calendarioDiv.appendChild(document.createElement("div"));

    for (let d = 1; d <= diasMes; d++) {
        const fecha = `${anio}-${String(mes).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
        const div = document.createElement("div");
        div.className = (fecha < hoyStr) ? "dia pasado" : "dia";
        div.dataset.fecha = fecha;
        div.textContent = d;

        if (div.classList.contains("dia")) {
            div.addEventListener("click", function () {
                document.querySelectorAll(".dia").forEach(d => d.classList.remove("seleccionado"));
                div.classList.add("seleccionado");
                inputDia.value = fecha;
                errorHora.textContent = "";
            });
        }

        calendarioDiv.appendChild(div);
    }
}

prevMes.addEventListener("click", function () {
    mesVisible--;
    if (mesVisible < 1) { mesVisible = 12; anioVisible--; }
    renderCalendario(mesVisible, anioVisible);
});

nextMes.addEventListener("click", function () {
    mesVisible++;
    if (mesVisible > 12) { mesVisible = 1; anioVisible++; }
    renderCalendario(mesVisible, anioVisible);
});

// --- Inicializar ---
renderCalendario(mesVisible, anioVisible);
