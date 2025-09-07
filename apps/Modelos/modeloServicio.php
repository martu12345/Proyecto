<?php
class Servicio {
    private $idServicio;
    private $titulo;
    private $categoria;
    private $descripcion;
    private $precio;
    private $disponibilidad;
    private $imagen;           

    public function __construct($idServicio, $titulo, $categoria, $descripcion, $precio, $disponibilidad, $imagen = null) {
        $this->idServicio = $idServicio;
        $this->titulo = $titulo;
        $this->categoria = $categoria;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->disponibilidad = $disponibilidad;
        $this->imagen = $imagen ?? ''; 
    }

    // Getters
    public function getIdServicio() { return $this->idServicio; }
    public function getTitulo() { return $this->titulo; }
    public function getCategoria() { return $this->categoria; }
    public function getDescripcion() { return $this->descripcion; }
    public function getPrecio() { return $this->precio; }
    public function getDisponibilidad() { return $this->disponibilidad; }
    public function getImagen() { return $this->imagen; }

    // Setters
    public function setIdServicio($idServicio) { $this->idServicio = $idServicio; }
    public function setTitulo($titulo) { $this->titulo = $titulo; }
    public function setCategoria($categoria) { $this->categoria = $categoria; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function setPrecio($precio) { $this->precio = $precio; }
    public function setDisponibilidad($disponibilidad) { $this->disponibilidad = $disponibilidad; }
    public function setImagen($imagen) { $this->imagen = $imagen; }

    // Guardar en BD
    public function guardar($conn) {
        $sql = "INSERT INTO Servicio (titulo, categoria, descripcion, precio, disponibilidad, imagen) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param(
            "sssdis", 
            $this->titulo, 
            $this->categoria, 
            $this->descripcion, 
            $this->precio, 
            $this->disponibilidad, 
            $this->imagen
        );

        if ($stmt->execute()) {
            return true;
        } else {
            die("Error al guardar servicio: " . $stmt->error);
        }
    }
}
?>
