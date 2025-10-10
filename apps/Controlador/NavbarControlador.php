<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Contrata.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/Comunica.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto/apps/Modelos/conexion.php');

$logueado = isset($_SESSION['idUsuario']);
$rol = $logueado ? $_SESSION['rol'] : null;

$home_url = "/Proyecto/public/index.php";
$perfil_url = null;

$notificacionesMensajes = 0;
$notificacionesAgendados = 0;

if ($logueado) {

    if ($rol === 'admin') {
        $home_url = "/Proyecto/apps/vistas/paginas/admin/dashboard.php";
        $perfil_url = "/Proyecto/apps/vistas/paginas/admin/perfil.php";

    } elseif ($rol === 'empresa') {
        $home_url = "/Proyecto/apps/vistas/paginas/empresa/home_empresa.php";
        $perfil_url = "/Proyecto/apps/vistas/paginas/empresa/perfil_empresa.php";

        $idEmpresa = $_SESSION['idUsuario'];

        // 🔹 Mensajes no leídos
        try {
            $notificacionesMensajes = Comunica::contarNoLeidosPorEmpresa($conn, $idEmpresa);
        } catch (\Exception $e) {
            // Opcional: loggear el error sin mostrar en pantalla
        }

        // 🔹 Agendados no leídos
        $contratosNoLeidos = Contrata::obtenerNoLeidos($conn);
        foreach ($contratosNoLeidos as $contrato) {
            $idServicio = $contrato['idServicio'];
            $idEmpresaServicio = Brinda::obtenerIdEmpresaPorServicio($conn, $idServicio);

            if ($idEmpresaServicio && $idEmpresaServicio == $idEmpresa) {
                $notificacionesAgendados++;
            }
        }

    } elseif ($rol === 'cliente') {
        $home_url = "/Proyecto/apps/vistas/paginas/cliente/home_cliente.php";
        $perfil_url = "/Proyecto/apps/vistas/paginas/cliente/perfil_cliente.php";
    }
}
?>
