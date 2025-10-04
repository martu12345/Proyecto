<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Contrata.php');

header('Content-Type: text/plain; charset=utf-8');

$cantidad = Contrata::finalizarServiciosVencidos($conn);

echo "Servicios finalizados automáticamente: {$cantidad}\n";

$conn->close();
