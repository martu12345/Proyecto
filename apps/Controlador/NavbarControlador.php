<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Contrata.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');

$logueado = isset($_SESSION['idUsuario']);
$rol = $logueado ? $_SESSION['rol'] : null;
$home_url = "/Proyecto/public/index.php";
$perfil_url = null;
$notificacionesNoLeidas = 0;

if ($logueado) {
    if ($rol === 'admin') {
        $home_url = "/Proyecto/apps/vistas/paginas/admin/dashboard.php";
        $perfil_url = "/Proyecto/apps/vistas/paginas/admin/perfil.php";
    } elseif ($rol === 'empresa') {
        $home_url = "/Proyecto/apps/vistas/paginas/empresa/home_empresa.php";
        $perfil_url = "/Proyecto/apps/vistas/paginas/empresa/perfil_empresa.php";

        $idEmpresa = $_SESSION['idUsuario'];

        // Obtener todos los contratos no leídos
        $contratosNoLeidos = Contrata::obtenerNoLeidos($conn);

        $contador = 0; // Inicializamos contador

        foreach ($contratosNoLeidos as $contrato) {
            $idServicio = $contrato['idServicio'];
            $idEmpresaServicio = Brinda::obtenerIdEmpresaPorServicio($conn, $idServicio);

            // Solo contar si el servicio tiene empresa asignada y coincide con la empresa logueada
            if ($idEmpresaServicio && $idEmpresaServicio == $idEmpresa) {
                $contador++;
            }
        }

        $notificacionesNoLeidas = $contador;

    } elseif ($rol === 'cliente') {
        $home_url = "/Proyecto/apps/vistas/paginas/cliente/home_cliente.php";
        $perfil_url = "/Proyecto/apps/vistas/paginas/cliente/perfil_cliente.php";
    }
}
?>
