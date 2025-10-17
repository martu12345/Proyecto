<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/Comunica.php');

if (!isset($_SESSION['idUsuario'])) {
    header("Location: /Proyecto/apps/vistas/autenticacion/login.php");
    exit();
}

$idCliente = (int)$_SESSION['idUsuario'];

$mensajesRecibidos = array_reverse(Comunica::obtenerMensajesRecibidosPorCliente($conn, $idCliente));
$mensajesEnviados = array_reverse(Comunica::obtenerMensajesEnviadosPorCliente($conn, $idCliente));

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/cliente/perfilsecciones/mensajes.php');
