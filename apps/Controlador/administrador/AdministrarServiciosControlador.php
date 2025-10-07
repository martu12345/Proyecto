<?php
// Archivo: /Proyecto/apps/Controlador/administrador/ServiciosAdminControlador.php

require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/conexion.php');

class ServiciosAdminControlador {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Obtener todos los servicios
    public function obtenerTodos() {
        $sql = "SELECT * FROM servicio";
        $resultado = $this->conn->query($sql);
        $servicios = [];

        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $servicios[] = new Servicio(
                    $row['IdServicio'] ?? null,
                    $row['Titulo'] ?? '',
                    $row['Categoria'] ?? '',
                    $row['Descripcion'] ?? '',
                    $row['Precio'] ?? 0,
                    $row['departamento'] ?? '',
                    $row['imagen'] ?? '',
                    $row['Duracion'] ?? 0
                );
            }
        }

        return $servicios;
    }

    // Eliminar servicio por ID
    public function eliminarServicio($idServicio) {
        $sql = "DELETE FROM servicio WHERE IdServicio = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $idServicio);
        return $stmt->execute();
    }

    // Obtener servicio por ID (para editar)
    public function obtenerServicioPorId($idServicio) {
        return Servicio::obtenerPorId($this->conn, $idServicio);
    }

    // Actualizar un servicio
    public function actualizarServicio(Servicio $servicio) {
        return $servicio->actualizar($this->conn);
    }
}
?>
