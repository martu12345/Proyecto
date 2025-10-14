<?php 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes de la Empresa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FF914D;
            color: #333;
            text-align: center;
        }

        .contenedor-clientes {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .cliente-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            width: 280px;
            padding: 15px;
            text-align: center;
        }

        .cliente-card img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
        }

        .cliente-card h3 {
            margin: 10px 0 5px 0;
        }

        .cliente-card p {
            margin: 4px 0;
        }

        button {
            background-color: #FF914D;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #e67c33;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            width: 50%;
            border-radius: 10px;
            text-align: left;
        }

        .close {
            float: right;
            font-size: 20px;
            cursor: pointer;
        }

        textarea {
            width: 100%;
            height: 100px;
            resize: none;
        }
    </style>
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
