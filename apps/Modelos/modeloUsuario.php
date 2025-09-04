<?
class Usuario {
    private $idUsuario; // pk
    private $email;
    private $contraseña;
    private $telefono;
    public function __construct($idUsuario, $email, $contraseña, $telefono) {
// los if son las rne (hay que hacerlas)
    $this->idUsuario = $idUsuario;
    $this->email = $email;
    $this->contraseña = password_hash($contraseña, PASSWORD_DEFAULT);
    $this->telefono = $telefono;
    }
        // getters
    public function getIdUsuario() {
         return $this->idUsuario;
         }
    public function getEmail() {
         return $this->email;
         }
    public function getTelefono() {
         return $this->telefono;
         }

    // setters
    public function setEmail($email) {
         $this->email = $email;
         }
    public function setContraseña($contraseña) { 

        $this->contraseña = password_hash($contraseña, PASSWORD_DEFAULT);
         // dios sabra si esto esta bien
    }
    public function setTelefono($telefono) { 
        $this->telefono = $telefono; 
    }
    public function verificarContraseña($contraseñaIngresada) {
        return password_verify($contraseñaIngresada, $this->contraseña);
// esto tampoco se si esta bien
//faltan las funciones del diagrama de clases 
}
}
?>