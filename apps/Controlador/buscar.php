
<?php
session_start(); // Veriica que para buscar algo el usuario este registrado y haya inciado sesion

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Proyecto/apps/vistas/autenticacion/login.php");
    exit();
}

// despues hay que cambiar por  header("Location: /Proyecto/apps/vistas/autenticacion/login.php");
// para que no deje buscar si no esyas logeado - /Proyecto/apps/vistas/paginas/busqueda.php"

// agregar codigo de filtrado de busqueda 





?>
