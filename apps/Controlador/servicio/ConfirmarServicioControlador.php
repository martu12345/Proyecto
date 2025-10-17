<?php
session_start();

$titulo = $_SESSION['confirmacionServicio']['titulo'] ?? null;
$dia = $_SESSION['confirmacionServicio']['dia'] ?? null;
$hora = $_SESSION['confirmacionServicio']['hora'] ?? null;

unset($_SESSION['confirmacionServicio']);

// Pasar datos a la vista
require $_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/vistas/paginas/servicio/ConfirmacionServicio.php';
