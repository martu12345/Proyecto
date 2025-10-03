const citasOcupadas = window.citasOcupadas;
const duracionServicio = window.duracionServicio;
const meses = window.meses;

const inputHora = document.getElementById('hora');
const inputDia = document.getElementById('inputDia');
const errorHora = document.getElementById('errorHora');
const form = document.getElementById('formServicio');
const calendarioDiv = document.getElementById('calendario');
const tituloMes = document.getElementById('tituloMes');
const prevMes = document.getElementById('prevMes');
const nextMes = document.getElementById('nextMes');

errorHora.style.whiteSpace = 'pre-line';

let fechaActual = new Date();
let mesVisible = fechaActual.getMonth() + 1;
let anioVisible = fechaActual.getFullYear();

function renderCalendario(mes, anio) {
    calendarioDiv.innerHTML = "";
    tituloMes.textContent = meses[mes] + " " + anio;

    const diasSemana = ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"];
    diasSemana.forEach(d => {
        let div = document.createElement("div");
        div.className = "dias-semana";
        div.textContent = d;
        calendarioDiv.appendChild(div);
    });

    const primerDia = new Date(anio, mes - 1, 1).getDay() || 7;
    const diasMes = new Date(anio, mes, 0).getDate();
    const hoyStr = new Date().toISOString().split('T')[0];

    for (let i = 1; i < primerDia; i++) calendarioDiv.appendChild(document.createElement("div"));

    for (let d = 1; d <= diasMes; d++) {
        const fecha = `${anio}-${String(mes).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const div = document.createElement("div");
        div.className = (fecha < hoyStr) ? "dia pasado" : "dia";
        div.dataset.fecha = fecha;
        div.textContent = d;

        if (div.classList.contains("dia")) {
            div.addEventListener("click", function() {
                document.querySelectorAll(".dia").forEach(d => d.classList.remove("seleccionado"));
                div.classList.add("seleccionado");
                inputDia.value = fecha;
                errorHora.textContent = "";
            });
        }
        calendarioDiv.appendChild(div);
    }
}

prevMes.addEventListener("click", function() {
    mesVisible--;
    if (mesVisible < 1) {
        mesVisible = 12;
        anioVisible--;
    }
    renderCalendario(mesVisible, anioVisible);
});

nextMes.addEventListener("click", function() {
    mesVisible++;
    if (mesVisible > 12) {
        mesVisible = 1;
        anioVisible++;
    }
    renderCalendario(mesVisible, anioVisible);
});

function minutosAHora(minutos) {
    let h = Math.floor(minutos / 60);
    let m = minutos % 60;
    return `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}`;
}

function calcularLibres(diaSeleccionado) {
    let ocupadosDia = citasOcupadas
        .filter(c => c.Fecha === diaSeleccionado)
        .map(c => {
            const [ch, cm] = c.Hora.split(':').map(Number);
            const inicio = ch * 60 + cm;
            const fin = inicio + c.Duracion * 60;
            return {inicio, fin};
        })
        .sort((a, b) => a.inicio - b.inicio);

    let libres = [];
    let start = 0;
    for (let c of ocupadosDia) {
        if (c.inicio - start >= duracionServicio * 60) libres.push({inicio:start, fin:c.inicio});
        start = c.fin;
    }
    if(24*60 - start >= duracionServicio*60) libres.push({inicio:start, fin:24*60});
    return {ocupadosDia, libres};
}

form.addEventListener('submit', function(e){
    e.preventDefault(); // Siempre detener envío para controlar errores
    errorHora.textContent = '';

    const diaSeleccionado = inputDia.value;
    if(!diaSeleccionado){
        errorHora.textContent = "⚠️ Debes seleccionar un día.";
        return;
    }

    const partes = inputHora.value.split(':');
    if(partes.length !== 2){
        errorHora.textContent = "⚠️ Formato de hora inválido.";
        return;
    }

    const [h, m] = partes.map(Number);
    if(isNaN(h) || isNaN(m) || m < 0 || m > 59){
        errorHora.textContent = "⚠️ Minutos inválidos. Deben estar entre 00 y 59.";
        return;
    }

    
    const inicio = h*60 + m;
    const fin = inicio + duracionServicio*60;

    if(fin > 24*60){
        errorHora.textContent = "⚠️ No se puede agendar este horario, se pasa del final del día.";
        return;
    }

    const {ocupadosDia, libres} = calcularLibres(diaSeleccionado);
    let choque = ocupadosDia.find(c => inicio < c.fin && fin > c.inicio);

    if(choque){
        let msg = `⚠️ Este horario no se puede agendar porque tu servicio de ${duracionServicio}h choca con servicios existentes:\n`;
        ocupadosDia.forEach(c => {
            if(inicio < c.fin && fin > c.inicio) msg += `• ${minutosAHora(c.inicio)} - ${minutosAHora(c.fin)}\n`;
        });
        msg += "\nHorarios libres para tu servicio:\n";
        libres.forEach(l => {
            msg += `• ${minutosAHora(l.inicio)} - ${minutosAHora(l.fin)}\n`;
        });
        errorHora.textContent = msg;
        return;
    }

    // Si todo está bien, se envía el formulario manualmente
    form.submit();
});

renderCalendario(mesVisible, anioVisible);
