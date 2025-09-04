<?
class Cliente {
    private $idUsuario; // pk
    private $nombre;
    private $apellido;
    public function __construct($idUsuario, $nombreEmpresa, $calle, $numero) {
        //rne
    $this->idUsuario = $idUsuario;
    $this->nombreEmpresa = $nombreEmpresa;
    $this->calle = $calle;
    $this->numero = $numero; 
    }
        // getters
    public function getIdUsuario() {
         return $this->idUsuario;
         }
    public function getNombreEmpresa() {
         return $this->nombreEmpresa;
         }
    public function getCalle() {
         return $this->calle;
         }
  public function getNumero() {
         return $this->numero;
         }
    // setters
     public function setNombreEmpresa($nombreEmpresa) {
         $this->nombreEmpresa = $nombreEmpresa;
         }
    public function setCalle($calle) {
         $this->calle = $calle;
         }

    public function setNumero($numero) { 
        $this->numero = $numero; 
    }

}
?>