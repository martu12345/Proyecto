<?php
session_start();
require_once('../Modelos/Usuario.php');
require_once('../Modelos/conexion.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];
    $contrasenaIngresada = $_POST['contrasena'];

    // busco usuario
    $usuario = Usuario::buscarPorEmail($conn, $email);

   if ($usuario && $usuario->verificarContrasena($contrasenaIngresada))  {
        $_SESSION['idUsuario'] = $usuario->getIdUsuario();
        $_SESSION['email'] = $usuario->getEmail();
    }


        // veo si es cliente
        $stmt = $conn->prepare("SELECT * FROM cliente WHERE idUsuario = ?");
        $stmt->bind_param("i", $usuario->getIdUsuario());
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $_SESSION['rol'] = "cliente";
            header("Location: ../Vistas/paginas/empresa/cliente_home.php"); // (no existe todavia)
            exit();
        }

        // veo si es empresa
        $stmt = $conn->prepare("SELECT * FROM empresa WHERE idUsuario = ?");
        $stmt->bind_param("i", $usuario->getIdUsuario());
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $_SESSION['rol'] = "empresa";
            header("Location: ../Vistas/paginas/empresa/empresa_home.php");  
            exit();
        }

        // veo si es admin
        $stmt = $conn->prepare("SELECT * FROM admin WHERE idUsuario = ?");
        $stmt->bind_param("i", $usuario->getIdUsuario());
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $_SESSION['rol'] = "admin";
            header("Location: ../Vistas/paginas/empresa/admin_home.php"); // (no existe todavia)
            exit();
        }

}
?>