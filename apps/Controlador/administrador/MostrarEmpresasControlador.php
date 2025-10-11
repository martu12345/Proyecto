<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');

class EmpresasAdminControlador {
    private $conn; // Conexión privada

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerTodosConTelefonos() {
        // Obtener todas las empresas
        $empresas = Empresa::obtenerTodos($this->conn); // Debes crear este método en Empresa
        $data = [];

        foreach ($empresas as $e) {
            $data[] = [
                'id' => $e->getIdUsuario(), // Cambiado de getId()
                'nombreEmpresa' => $e->getNombreEmpresa(),
                'email' => $e->getEmail($this->conn),
                'telefonos' => $e->obtenerTelefonos($this->conn)
            ];
        }

        return $data;
    }
}
