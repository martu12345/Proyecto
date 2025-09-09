<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = [];

// Destruir la sesión
session_destroy();

// Redirigir al inicio público
header("Location: /Proyecto/public/index.php");
exit();
