<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manitas</title>
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/frase.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/secciones.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/footer.css">
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/empresa/empresa_home.css">


</head>
<body>
    
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/frase.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/secciones.php'; ?>

<!-- Botón flotante -->
<button id="btnCrearServicio" class="btn-float">+</button>

<!-- Modal -->
<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/modal_servicio.php'; ?>

<!-- Scripts al final, DESPUÉS del botón y modal -->
<script src="/Proyecto/public/js/secciones.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btnCrear = document.getElementById('btnCrearServicio');
    const modal = document.getElementById('modalServicio');
    const btnCerrar = document.querySelector('#modalServicio .cerrar');

    if (!btnCrear || !modal || !btnCerrar) {
        console.error('No se encontraron elementos del modal o del botón. Revisa IDs y clases.');
        return;
    }

    // Abrir modal
    btnCrear.addEventListener('click', () => {
        modal.style.display = 'block';
    });

    // Cerrar modal
    btnCerrar.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Cerrar al hacer click fuera del modal
    window.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
    });
});
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/footer.php'; ?>
