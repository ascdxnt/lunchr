<?php
require_once './Modelo/Entidades/DiaNoLectivo.php';
require_once './Modelo/Metodos/DiaNoLectivoMetodos.php';
require_once './Modelo/Conexion.php';

class FechaControlador
{
	public function EsUnDiaLectivo($fecha)
	{
			$diasNoLectivosMetodos = new DiaNoLectivoMetodos();
			$diasNoLectivos = $diasNoLectivosMetodos->BuscarTodos();
			$noLectivo = false;
			$diaDeLaSemana = date('D', strtotime($fecha));

			if($diaDeLaSemana == 'Sat' || $diaDeLaSemana == 'Sun')
				$noLectivo = true;
			else if($diasNoLectivos != null){
					$fecha = substr($fecha, 5);
					foreach ($diasNoLectivos as $diaNoLectivo){
							if($diaNoLectivo->getEstado() == 1){
									if($diaNoLectivo->getFecha() == $fecha)
										$noLectivo = true;
							}
					}
			}
			else 
					echo $noLectivo = false;

			if(!$noLectivo)
					return true;
			else
					return false;
	}
}

