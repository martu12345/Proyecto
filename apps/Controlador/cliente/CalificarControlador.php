<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Contrata.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');

class CalificarServicioControlador {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function calificarServicio($idCita, $calificacion, $resena) {
        $contrata = new Contrata(null, null, $idCita, null, null, $calificacion, $resena);
        if($contrata->guardarCalificacionResena($this->conn)){
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "No se pudo guardar la calificación."]);
        }
    }

}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCita = $_POST['idCita'] ?? null;
    $calificacion = $_POST['calificacion'] ?? null;
    $resena = $_POST['resena'] ?? null;

    $controlador = new CalificarServicioControlador($conn);
    $controlador->calificarServicio($idCita, $calificacion, $resena);
}


?>
