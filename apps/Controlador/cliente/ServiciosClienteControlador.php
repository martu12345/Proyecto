<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/Contrata.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/Servicio.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/Brinda.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/Usuario.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/Empresa.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Proyecto/apps/Modelos/Comunica.php');

class ServiciosClienteControlador
{
    private $idUsuario;
    private $conn;

    public function __construct($conn, $idUsuario)
    {
        $this->conn = $conn;
        $this->idUsuario = $idUsuario;
    }

    //  PENDIENTES 
    public function obtenerPendientes()
    {
        $contratas = Contrata::obtenerPorUsuarioYEstado($this->conn, $this->idUsuario, 'Pendiente');
        return $this->armarArrayConNombres($contratas);
    }

    //  EN PROCESO 
    public function obtenerEnProceso()
    {
        $contratas = Contrata::obtenerPorUsuarioYEstado($this->conn, $this->idUsuario, 'En proceso');
        return $this->armarArrayConNombres($contratas);
    }

    //  FINALIZADOS 
    public function obtenerFinalizados()
    {
        $contratas = Contrata::obtenerPorUsuarioYEstado($this->conn, $this->idUsuario, 'Finalizado');
        return $this->armarArrayConNombres($contratas);
    }

    //  CANCELADOS 
    public function obtenerCancelados()
    {
        $contratas = Contrata::obtenerPorUsuarioYEstado($this->conn, $this->idUsuario, 'Cancelado');
        return $this->armarArrayConNombres($contratas);
    }

    public function obtenerFinalizadosConCalificacion() {
    return Contrata::obtenerFinalizadosConCalificacion($this->conn, $this->idUsuario);
}


    //  FUNCION AUXILIAR 
    private function armarArrayConNombres($contratas)
    {
        $resultado = [];
        foreach ($contratas as $c) {
            $idServicio = $c['IdServicio'];
            $idCita = $c['IdCita'];
            $fecha = $c['Fecha'];
            $hora = $c['Hora'];
            $estado = $c['Estado'];

            $servicio = Servicio::obtenerPorId($this->conn, $idServicio);

            $idEmpresa = Brinda::obtenerIdEmpresaPorServicio($this->conn, $idServicio);
            $empresa = $idEmpresa ? Empresa::obtenerPorId($this->conn, $idEmpresa) : null;

            $nombreEmpresa = $empresa ? $empresa->getNombreEmpresa() : 'Sin empresa';

            //  MENSAJE DE CANCELACION 
            $mensajeCancelacion = null;
            if ($estado === 'Cancelado' && $empresa) {
                $mensajeCancelacion = $this->obtenerMensajeCancelacion($this->idUsuario, $empresa->getIdUsuario(), $fecha);
            }



            $resultado[] = [
                'idCita' => $idCita,
                'tituloServicio' => $servicio->getTitulo(),
                'fecha' => $fecha,
                'hora' => $hora,
                'estado' => $estado,
                'nombreEmpresa' => $nombreEmpresa,
                'mensajeCancelacion' => $mensajeCancelacion
            ];
        }
        return $resultado;
    }

    //  OBTENER MENSAJE DE CANCELACION 
    private $mensajesUsados = [];

    private function obtenerMensajeCancelacion($idUsuarioCliente, $idUsuarioEmpresa, $fechaServicio)
    {
        $mensajes = Comunica::obtenerMensajesParaEmpresa($this->conn, $idUsuarioEmpresa);

        $fechaServicioTs = strtotime($fechaServicio);
        $mensajeValido = null;
        $menorDiferencia = PHP_INT_MAX;

        foreach ($mensajes as $m) {

            if (in_array($m['idMensaje'], $this->mensajesUsados)) continue;

            if (
                $m['idUsuarioEmisor'] == $idUsuarioEmpresa
                && $m['idUsuarioCliente'] == $idUsuarioCliente
                && $m['asunto'] === 'Rechazo de reserva'
            ) {

                $mensajeFechaTs = strtotime($m['fecha']);
                $diferencia = abs($mensajeFechaTs - $fechaServicioTs);

                if ($diferencia < $menorDiferencia) {
                    $menorDiferencia = $diferencia;
                    $mensajeValido = $m['mensaje'];
                    $mensajeId = $m['idMensaje'];
                }
            }
        }

        if ($mensajeValido !== null) {
            $this->mensajesUsados[] = $mensajeId;
            return $mensajeValido;
        }

        return 'Sin mensaje de cancelación';
    }

    //  CANCELAR SERVICIO 
    public function cancelarServicio($idCita)
    {
        $cita = Contrata::obtenerPorId($this->conn, $idCita);
        if (!$cita) return false;

        return $cita->actualizarEstado($this->conn, 'Cancelado');
    }
}
