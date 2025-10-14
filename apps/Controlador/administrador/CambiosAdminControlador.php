<?php
// apps/Controlador/propietario/CambiosAdminControlador.php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Administra.php');

// Obtener todos los cambios de administradores
$cambios = Administra::obtenerTodos($conn);

// Pasar los datos a la vista
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Vistas/paginas/propietario/historial_cambios.php');
