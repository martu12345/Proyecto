<?
class Cliente {
    private $idUsuario; // pk
    private $nombre;
    private $apellido;
    public function __construct($idUsuario, $nombre, $apellido) {
// los if son las rne (hay que hacerlas)
    $this->idUsuario = $idUsuario;
    $this->nombre = $nombre;
    $this->apellido = $apellido;
    }
        // getters
    public function getIdUsuario() {
         return $this->idUsuario;
         }
    public function getNombre() {
         return $this->nombre;
         }
    public function getApellido() {
         return $this->apellido;
         }

    // setters
    public function setNombre($nombre) {
         $this->nombre = $nombre;
         }

    public function setApellido($apellido) { 
        $this->apellido = $apellido; 
    }

}
?>