<?php
class Cliente {
    private $idServicio; // pk
    private $titulo;
    private $categoria;
    private $descripcion;
    private $precio;
    private $disponibilidad;

    public function __construct($idServicio, $titulo, $categoria, $descripcion, $precio, $disponibilidad) {
        $this->idServicio = $idServicio;
        $this->titulo = $titulo;
        $this->categoria = $categoria;
        $this->descripcion = $descripcion; 
        $this->precio = $precio; 
        $this->disponibilidad = $disponibilidad; 
    }

    // getters
    public function getIdServicio() {
        return $this->idServicio;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getCategoria() {
        return $this->categoria;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function getDisponibilidad() {
        return $this->disponibilidad;
    }

    // setters
    public function setIdServicio($idServicio) {
        $this->idServicio = $idServicio;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function setDisponibilidad($disponibilidad) {
        $this->disponibilidad = $disponibilidad;
    }
}
?>
