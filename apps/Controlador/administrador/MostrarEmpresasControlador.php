<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/modelos/conexion.php');

class EmpresasAdminControlador {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function obtenerTodosConTelefonos() {
        $empresas = Empresa::obtenerTodos($this->conn);
        $data = [];

        foreach ($empresas as $e) {
            $data[] = [
                'id' => $e->getIdUsuario(), 
                'nombreEmpresa' => $e->getNombreEmpresa(),
                'email' => $e->getEmail($this->conn),
                'telefonos' => $e->obtenerTelefonos($this->conn)
            ];
        }

        return $data;
    }
}
