<?php
require_once './Modelo/Conexion.php';
require_once './Modelo/Entidades/DiaNoLectivo.php';
require_once './Modelo/Metodos/DiaNoLectivoMetodos.php';

class DiaNoLectivoControlador
{
  public function Crear()
	{
		$diaNoLectivo = new DiaNoLectivo();
		$diaNoLectivoMetodos = new DiaNoLectivoMetodos();
		$fecha = $_POST['diaEspecifico'];
    $nombre = $_POST['nombreDiaEspecifico'];
		$fechaCrear = date('m-d', strtotime($fecha));

		if ($diaNoLectivoMetodos->BuscarPorFecha($fechaCrear)) {
				header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=error2');
		} else {
			$diaNoLectivo = new DiaNoLectivo();
			$diaNoLectivo->setEstado(1);
			$diaNoLectivo->setFecha($fechaCrear);
      $diaNoLectivo->setNombre($nombre);
			if ($diaNoLectivo->getFecha() != null) {
				if ($diaNoLectivoMetodos->Crear($diaNoLectivo))
					header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=success');
				else
					header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=error');
			}
			else
				header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=error');
		}
	}
	public function CrearLapso()
	{
		require_once './Controlador/funciones/FechaControlador.php';

		$diaNoLectivo = new DiaNoLectivo();
		$diaNoLectivoMetodos = new DiaNoLectivoMetodos();
		$fechaInicio = $_POST['inicioLapsoTiempo'];
		$fechaFinal = $_POST['finLapsoTiempo'];
    $nombre = $_POST['nombreLapsoTiempo'];
		$fechaInicio = strtotime($fechaInicio);
		$fechaFinal = strtotime($fechaFinal);
		$fechaControlador= new FechaControlador();
		$estado=false;
		
		for ($day = $fechaInicio; $day <= $fechaFinal; $day += 86400){
			$fechaInicio = date('Y-m-d', $day);
			$fechaCrear = date('m-d', $day);
				$diaNoLectivo = new DiaNoLectivo();
				$diaNoLectivo->setEstado(1);
				$diaNoLectivo->setFecha($fechaCrear);
				$diaNoLectivo->setNombre($nombre);
				
				if(!$diaNoLectivoMetodos->BuscarPorFecha($fechaCrear)){
					if ($diaNoLectivoMetodos->Crear($diaNoLectivo))
						$estado=true;
					else
						header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=error');
				}
				else
					header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=error2');
		}

		if($estado)
		header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=success');
		else{
			header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=error');
		}
	}
	public function Modificar()
	{
		$diaNoLectivo = new DiaNoLectivo();
		$diaNoLectivoMetodos = new DiaNoLectivoMetodos();

		$id = $_POST['idFechaDiaEspecifico'];
		$fecha = $_POST['modificarDiaEspecifico'];
		$nombre = $_POST['modificarNombreDiaEspecifico'];
		$fechaModificar = date('m-d', strtotime($fecha));

		$diaNoLectivo->setId($id);
		$diaNoLectivo->setFecha($fechaModificar);
		$diaNoLectivo->setNombre($nombre);
		$diaNoLectivo->setEstado(1);

		if($diaNoLectivoMetodos->BuscarPorFecha($fechaModificar)){
					header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=error2');
		}
		else{
			if ($diaNoLectivo->getId() != null && $diaNoLectivo->getFecha() != null && $diaNoLectivo->getNombre() != null ) {
				if ($diaNoLectivoMetodos->Modificar($diaNoLectivo)) {
					header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=success');
				} else {
					header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=error');
				}
			}
			else
					header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=error');
		}
	}

    public  function  CambiarEstado()
    {
        $id = $_POST['idFecha'];
        $diaNoLectivo = new DiaNoLectivo();
        $diaNoLectivoMetodos = new DiaNoLectivoMetodos();

        $diaNoLectivo->setId($id);

        if ($diaNoLectivo->getId() != null) {
            if ($diaNoLectivoMetodos->Eliminar($diaNoLectivo)) {
                header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=success');
            } else {
                header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=error');
            }
        }
    }

//	public function CambiarEstado($parametros)
//	{
//		$estado = $parametros[0];
//		if (!isset($_REQUEST['idsArr']))
//			header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=error');
//		else {
//			$arrayIds = $_REQUEST['idsArr'];
//			$lengthArray = $_REQUEST['lengthArray'];
//			$diaNoLectivoMetodos = new DiaNoLectivoMetodos();
//			$volver = false;
//
//			for ($i = 0; $i < $lengthArray; $i++) {
//				$diaNoLectivo = new DiaNoLectivo();
//				$diaNoLectivo = $diaNoLectivoMetodos->Buscar($arrayIds[$i]);
//				$diaNoLectivo->setEstado($estado);
//				if ($diaNoLectivoMetodos->Modificar($diaNoLectivo)) {
//					$volver = true;
//				}
//			}
//
//			if ($estado == 0) {
//				if ($volver)
//					header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=success');
//				else
//					header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=error');
//			} else {
//				if ($volver)
//					header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=success');
//				else
//					header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&id=main&alerta=error');
//			}
//		}
//	}

}   
