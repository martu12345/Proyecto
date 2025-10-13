<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/modelos/Conexion.php');

class Denuncia {
    private $idDenuncia;
    private $idCliente;
    private $idEmpresa;
    private $asunto;  
    private $motivo;  
    private $fecha;

    public function __construct($idCliente, $idEmpresa, $asunto, $motivo = null, $fecha = null, $idDenuncia = null) {
        $this->idDenuncia = $idDenuncia;
        $this->idCliente = $idCliente;
        $this->idEmpresa = $idEmpresa;
        $this->asunto = $asunto;
        $this->motivo = $motivo;
        $this->fecha = $fecha ?? date('Y-m-d H:i:s');
    }

    // 🔹 Getters
    public function getIdDenuncia() { return $this->idDenuncia; }
    public function getIdCliente() { return $this->idCliente; }
    public function getIdEmpresa() { return $this->idEmpresa; }
    public function getAsunto() { return $this->asunto; }
    public function getMotivo() { return $this->motivo; }
    public function getFecha() { return $this->fecha; }

    // 🔹 Setters
    public function setIdCliente($idCliente) { $this->idCliente = $idCliente; }
    public function setIdEmpresa($idEmpresa) { $this->idEmpresa = $idEmpresa; }
    public function setAsunto($asunto) { $this->asunto = $asunto; }
    public function setMotivo($motivo) { $this->motivo = $motivo; }
    public function setFecha($fecha) { $this->fecha = $fecha; }

   
    //  Guardar denuncia en la base de datos
    public function guardar() {
        global $conn; 

        $sql = "INSERT INTO denuncia (idCliente, idEmpresa, asunto, motivo, fecha)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("iisss",
            $this->idCliente,
            $this->idEmpresa,
            $this->asunto,
            $this->motivo,
            $this->fecha
        );

        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }

    // Traer denuncias por asunto
    public static function obtenerPorAsunto($asunto) {
        global $conn;
        $sql = "SELECT * FROM denuncia WHERE asunto = ? ORDER BY fecha DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $asunto);
        $stmt->execute();
        $result = $stmt->get_result();
        $denuncias = [];
        while ($row = $result->fetch_assoc()) {
            $denuncias[] = new Denuncia(
                $row['idCliente'],
                $row['idEmpresa'],
                $row['asunto'],
                $row['motivo'],
                $row['fecha'],
                $row['idDenuncia']
            );
        }
        $stmt->close();
        return $denuncias;
    }

}
