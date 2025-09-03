<?
class Usuario {
    private $idUsuario; // pk
    private $email;
    private $contraseña;
    private $telefono;
    public function __construct($idUsuario, $email, $contraseña, $telefono)
// los if son las rne
    $this->idUsuario = $idUsuario;
    $this->email = $email;
    $this->contraseña = $contraseña;

}
?>