<?php 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes de la Empresa</title>

</head>
<body>
    <h1>Clientes que contrataron tus servicios</h1>

    <?php if (empty($clientes)): ?>
        <p>No hay clientes registrados todavía.</p>
    <?php else: ?>
        <div class="contenedor-clientes">
            <?php foreach ($clientes as $c): ?>
                <div class="cliente-card">
                    <img src="<?= htmlspecialchars($c['imagen'] ?? '/Proyecto/public/img/default-user.png') ?>" alt="Imagen del cliente">
                    <h3><?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?></h3>
                    <p><strong>Email:</strong> <?= htmlspecialchars($c['email']) ?></p>
                    <p><strong>Calificación:</strong> <?= htmlspecialchars($c['calificacion'] ?? 'Sin calificar') ?></p>
                    <p><strong>Reseña:</strong> <?= htmlspecialchars($c['resena'] ?? 'Sin reseña') ?></p>
                    <button class="btnDenunciar" data-id="<?= $c['idCliente'] ?>">Denunciar</button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Modal denuncia -->
    <div id="modalDenuncia" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Denunciar cliente</h2>
            <form id="formDenuncia" method="POST" action="/Proyecto/apps/controladores/ControladorDenuncia.php">
                <input type="hidden" name="idCliente" id="idCliente">
                <label>Motivo:</label><br>
                <textarea name="motivo" required></textarea><br><br>
                <button type="submit">Enviar denuncia</button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalDenuncia');
        const cerrar = document.querySelector('.close');
        const botones = document.querySelectorAll('.btnDenunciar');
        const inputIdCliente = document.getElementById('idCliente');

        botones.forEach(btn => {
            btn.addEventListener('click', () => {
                inputIdCliente.value = btn.getAttribute('data-id');
                modal.style.display = 'block';
            });
        });

        cerrar.onclick = () => modal.style.display = 'none';
        window.onclick = e => { if (e.target === modal) modal.style.display = 'none'; }
    </script>
</body>
</html>
