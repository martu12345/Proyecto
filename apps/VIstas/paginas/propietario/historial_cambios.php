<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Controlador/administrador/CambiosAdminControlador.php');
?> 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Cambios de Administradores</title>
    <link rel="stylesheet" href="/Proyecto/public/css/paginas/propietario/historial_cambios.css">
    <link rel="stylesheet" href="/Proyecto/public/css/layout/navbar.css">
    <link rel="stylesheet" href="/Proyecto/public/css/fonts.css">
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/layout/navbar.php'; ?>

    <div class="cambios-admin-container">
        <h1>Registro de Cambios de Administradores</h1>
        <table class="tabla-cambios">
            <thead>
                <tr>
                    <th>ID Administrador</th>
                    <th>Email</th>
                    <th>Servicio </th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cambios)): ?>
                    <?php foreach ($cambios as $cambio): ?>
                        <?php
                            // Si no tiene servicio, se eliminó
                            $servicio = !empty($cambio['servicio'])
                                ? htmlspecialchars($cambio['servicio'])
                                : '<span class="servicio-eliminado">Fue eliminado</span>';
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($cambio['idUsuario']) ?></td>
                            <td><?= htmlspecialchars($cambio['email']) ?></td>
                            <td><?= $servicio ?></td>
                            <td><?= htmlspecialchars($cambio['fecha']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No hay cambios registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
