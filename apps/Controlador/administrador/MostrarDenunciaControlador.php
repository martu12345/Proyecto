<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Denuncia.php');

class AdminDenunciaController {
    public $denuncias = [];

    public function __construct() {
        $tipo = $_GET['tipo'] ?? 'DenunciarServicio';
        $this->denuncias = Denuncia::obtenerPorAsunto($tipo);
    }
}

// Crear instancia y obtener denuncias
$adminController = new AdminDenunciaController();
$denuncias = $adminController->denuncias; 
