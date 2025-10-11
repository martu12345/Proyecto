<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Cliente.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');


class ClientesAdminControlador {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerTodos() {
        return Cliente::obtenerTodos($this->conn);
    }
}
