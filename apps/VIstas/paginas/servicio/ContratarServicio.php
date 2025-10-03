<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/controlador/servicio/ContratarServicioControlador.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Contratar Servicio</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
<link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
<link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
<link rel="stylesheet" href="/Proyecto/public/css/paginas/servicio/ContratarServicio.css">
</head>
<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

<div class="contratar-servicio-container">
    <h2><?= htmlspecialchars($servicio->getTitulo()) ?></h2>
    <p>Precio: $<?= number_format($servicio->getPrecio(),0,'','.') ?></p>
    <p>Empresa: <?= htmlspecialchars($empresa['NombreEmpresa'] ?? 'No disponible') ?></p>

    <form action="" method="POST" id="formServicio">
        <input type="hidden" name="idServicio" value="<?= $servicio->getIdServicio() ?>">
        <input type="hidden" name="dia" id="inputDia">
        <input type="time" name="hora" id="hora" required>
        <button type="submit">Agendar Servicio</button>
        <div id="errorHora" style="color:red; text-align:center; margin-top:10px;"></div>
    </form>

    <div class="calendario-titulo"><?= $mesNombre ?> <?= $anioActual ?></div>
    <div class="calendario">
        <div class="dias-semana">Lun</div><div class="dias-semana">Mar</div>
        <div class="dias-semana">Mié</div><div class="dias-semana">Jue</div>
        <div class="dias-semana">Vie</div><div class="dias-semana">Sáb</div>
        <div class="dias-semana">Dom</div>

        <?php
        for ($i=1; $i<$primerDia; $i++) echo "<div></div>";
        foreach(range(1, $diasMes) as $d) {
            $fecha = sprintf("%04d-%02d-%02d", $anioActual, $mesActual, $d);
            $clase = ($fecha < $hoy) ? 'dia pasado' : 'dia';
            echo "<div class='$clase' data-fecha='$fecha'>$d</div>";
        }
        ?>
    </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>

<script>
const citasOcupadas = <?= json_encode($citasOcupadas) ?>;
const duracionServicio = <?= $duracion ?>;

const inputHora = document.getElementById('hora');
const inputDia = document.getElementById('inputDia');
const errorHora = document.getElementById('errorHora');
const form = document.getElementById('formServicio');

// Asegurarse de que los saltos de línea se vean
errorHora.style.whiteSpace = 'pre-line';

// Función para convertir minutos a HH:MM
function minutosAHora(minutos) {
    let h = Math.floor(minutos / 60);
    let m = minutos % 60;
    return `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}`;
}

// Calcular intervalos libres considerando duración del servicio
function calcularLibres(diaSeleccionado) {
    let ocupadosDia = citasOcupadas
        .filter(c => c.Fecha === diaSeleccionado)
        .map(c => {
            const [ch, cm] = c.Hora.split(':').map(Number);
            const inicio = ch*60 + cm;
            const fin = inicio + c.Duracion*60;
            return {inicio, fin};
        })
        .sort((a,b) => a.inicio - b.inicio);

    let libres = [];
    let start = 0;
    for (let c of ocupadosDia) {
        if (c.inicio - start >= duracionServicio*60) {
            libres.push({inicio:start, fin:c.inicio});
        }
        start = c.fin;
    }
    if (24*60 - start >= duracionServicio*60) {
        libres.push({inicio:start, fin:24*60});
    }
    return {ocupadosDia, libres};
}

// Selección de día
document.querySelectorAll('.dia').forEach(dia => {
    if (dia.classList.contains('pasado')) return;
    dia.addEventListener('click', function() {
        document.querySelectorAll('.dia').forEach(d => d.classList.remove('seleccionado'));
        this.classList.add('seleccionado');
        inputDia.value = this.dataset.fecha;
        inputHora.value = '';
        errorHora.textContent = '';
    });
});

// Validación de hora
inputHora.addEventListener('input', function() {
    errorHora.textContent = '';
    const diaSeleccionado = inputDia.value;
    if (!diaSeleccionado) return;

    const [h, m] = this.value.split(':').map(Number);
    const inicio = h*60 + m;
    const fin = inicio + duracionServicio*60;

    if (fin > 24*60) {
        errorHora.textContent = "⚠️ No se puede agendar este horario, se pasa del final del día.";
        return;
    }

    const {ocupadosDia, libres} = calcularLibres(diaSeleccionado);
    let choque = ocupadosDia.find(c => inicio < c.fin && fin > c.inicio);

    if (choque) {
        // Mensaje más legible con saltos y viñetas
        let msg = `⚠️ Este horario no se puede agendar porque tu servicio de ${duracionServicio}h choca con servicios existentes:\n`;
        ocupadosDia.forEach(c => {
            if (inicio < c.fin && fin > c.inicio) {
                msg += `• ${minutosAHora(c.inicio)} - ${minutosAHora(c.fin)}\n`;
            }
        });

        msg += "\nHorarios libres para tu servicio:\n";
        libres.forEach(l => {
            msg += `• ${minutosAHora(l.inicio)} - ${minutosAHora(l.fin)}\n`;
        });

        errorHora.textContent = msg;
    }
});

// Evitar enviar si hay error
form.addEventListener('submit', function(e){
    if (errorHora.textContent.startsWith('⚠️')) e.preventDefault();
});
</script>
</body> 
</html>
