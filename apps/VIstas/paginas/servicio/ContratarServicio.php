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
    <style>
        .dia { cursor: pointer; padding: 10px; border: 1px solid #ccc; display: inline-block; width: 30px; text-align: center; margin: 2px; }
        .pasado { background-color: #eee; cursor: not-allowed; }
        .ocupado { background-color: #f88; cursor: not-allowed; }
        .seleccionado { background-color: #6c6; }
    </style>
</head>
<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

<div class="contratar-servicio-container">
    <h2><?= htmlspecialchars($servicio->getTitulo()) ?></h2>
    <p>Precio: $<?= number_format($servicio->getPrecio(), 0, '', '.') ?></p>
    <p>Empresa: <?= htmlspecialchars($empresa['NombreEmpresa'] ?? 'No disponible') ?></p>

    <form action="" method="POST">
        <input type="hidden" name="idServicio" value="<?= $servicio->getIdServicio() ?>">
        <input type="hidden" name="dia" id="inputDia">
        <input type="time" name="hora" id="hora" required>

        <div class="calendario-titulo"><?= $mesNombre ?> <?= $anioActual ?></div>
        <div class="calendario">
            <div class="dias-semana">Lun</div>
            <div class="dias-semana">Mar</div>
            <div class="dias-semana">Mié</div>
            <div class="dias-semana">Jue</div>
            <div class="dias-semana">Vie</div>
            <div class="dias-semana">Sáb</div>
            <div class="dias-semana">Dom</div>

            <?php
            // Espacios antes del primer día
            for ($i=1; $i<$primerDia; $i++) {
                echo "<div></div>";
            }

            // Días del mes
            foreach(range(1, $diasMes) as $d) {
                $fecha = sprintf("%04d-%02d-%02d", $anioActual, $mesActual, $d);
                $clase = ($fecha < $hoy) ? 'dia pasado' : 'dia';
                echo "<div class='$clase' data-fecha='$fecha'>$d</div>";
            }
            ?>
        </div>

        <button type="submit">Agendar Servicio</button>
    </form>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>

<script>
const citasOcupadas = <?= json_encode($citasOcupadas) ?>; // {Fecha, Hora, Duracion}
const duracionServicio = <?= $duracion ?>; // duración en horas

document.querySelectorAll('.dia').forEach(dia => {
    if (dia.classList.contains('pasado')) return;

    dia.addEventListener('click', function() {
        document.querySelectorAll('.dia').forEach(d => d.classList.remove('seleccionado'));
        this.classList.add('seleccionado');
        document.getElementById('inputDia').value = this.dataset.fecha;
        document.getElementById('hora').value = ''; // limpiar hora al cambiar de día
    });
});

document.getElementById('hora').addEventListener('input', function() {
    const diaSeleccionado = document.getElementById('inputDia').value;
    if (!diaSeleccionado) return;

    const horaStr = this.value;
    if (!horaStr) return;

    const [h, m] = horaStr.split(':').map(Number);
    const inicio = h * 60 + m;
    const fin = inicio + duracionServicio * 60;

    // Validar si se pasa del final del día
    if (fin > 24 * 60) {
        alert('⚠️ Este servicio excede el final del día. Elija otra hora.');
        this.value = '';
        return;
    }

    for (let c of citasOcupadas) {
        if (c.Fecha !== diaSeleccionado) continue;

        const [ch, cm] = c.Hora.split(':').map(Number);
        const citaInicio = ch * 60 + cm;
        const citaFin = citaInicio + c.Duracion * 60;

        // ✅ Solo bloqueamos si hay solapamiento real
        if (!(fin <= citaInicio || inicio >= citaFin)) {
            alert(`Este horario se solapa con otra cita que va de ${c.Hora} a ${String(Math.floor(citaFin/60)).padStart(2,'0')}:${String(citaFin%60).padStart(2,'0')}`);
            this.value = '';
            return;
        }
    }
});


</script>
</body>
</html>
