<?
require_once('modeloUsuario.php')
class Empresa extends Usuario {
    private $idUsuario; // pk
    private $nombre;
    private $apellido;
    public function __construct($idUsuario, $email, $contraseña, $telefono, $nombreEmpresa, $calle, $numero) {
     parent::__construct($idUsuario, $email, $contraseña, $telefono);
     // rne
    $this->nombreEmpresa = $nombreEmpresa;
    $this->calle = $calle;
    $this->numero = $numero; 
    }
        // getters
 
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