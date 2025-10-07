<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/administrador.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');

$email = $_POST['email'] ?? '';

if (empty($email)) {
    die("El email no puede estar vacío");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email inválido");
}

$resultado = Administrador::darDeBajaAdministrador($conn, $email);

if ($resultado) {
    echo "Administrador eliminado correctamente.";
} else {
    echo "Error al eliminar el administrador.";
}
