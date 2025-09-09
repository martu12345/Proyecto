<?php
session_start();
require_once('../Modelos/Usuario.php');
require_once('../Modelos/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $contrasenaIngresada = $_POST['contrasena'];

    // busco usuario
    $usuario = Usuario::buscarPorEmail($conn, $email);

    if ($usuario && $usuario->verificarContrasena($contrasenaIngresada)) {
        $_SESSION['idUsuario'] = $usuario->getIdUsuario();
        $_SESSION['email'] = $usuario->getEmail();

        // veo si es cliente
        $stmt = $conn->prepare("SELECT 1 FROM cliente WHERE idUsuario = ?");
        $stmt->bind_param("i", $usuario->getIdUsuario());
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $_SESSION['rol'] = "cliente";
            header("Location: /Proyecto/apps/vistas/paginas/cliente/home_cliente.php");
            exit();
        }

        // veo si es empresa
        $stmt = $conn->prepare("SELECT 1 FROM empresa WHERE idUsuario = ?");
        $stmt->bind_param("i", $usuario->getIdUsuario());
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $_SESSION['rol'] = "empresa";
            header("Location: /Proyecto/apps/vistas/paginas/empresa/home_empresa.php");
            exit();
        }

        // veo si es admin
        $stmt = $conn->prepare("SELECT 1 FROM admin WHERE idUsuario = ?");
        $stmt->bind_param("i", $usuario->getIdUsuario());
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $_SESSION['rol'] = "admin";
            header("Location: /Proyecto/apps/vistas/paginas/admin/home_admin.php");
            exit();
        }

        // si no pertenece a ningún rol
        echo "El usuario no tiene rol asignado.";
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
}
?>
