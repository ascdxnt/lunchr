<?php

class AsistenciaMetodos
{
	function Buscar($id)
	{
		$asistencia = new Asistencia();
		$conexion = new Conexion();

		$sql = "SELECT * FROM `ASISTENCIA` WHERE `ID` = '$id'";
		$resultado = $conexion->Ejecutar($sql);

		if (mysqli_num_rows($resultado) > 0) {
			while ($fila = $resultado->fetch_assoc()) {
				$asistencia->setId($fila["ID"]);
				$asistencia->setIdEstudiante($fila["IDESTUDIANTE"]);
				$asistencia->setIdProfesor($fila["IDPROFESOR"]);
				$asistencia->setFecha($fila["FECHA"]);
				$asistencia->setEstado($fila["ESTADO"]);
			}
		} else {
			$asistencia = null;
		}
		$conexion->Cerrar();
		return $asistencia;
	}

	function BuscarAsistenciasEstudiante($idEstudiante)
	{
		$todosAsistencias = array();
		$conexion = new Conexion();

		$sql = "SELECT * FROM `ASISTENCIA` WHERE `IDESTUDIANTE` = ".$idEstudiante;
		$resultado = $conexion->Ejecutar($sql);
		if (mysqli_num_rows($resultado) > 0) {
			while ($fila = $resultado->fetch_assoc()) {
				$asistencia = new Asistencia();
				$asistencia->setId($fila["ID"]);
				$asistencia->setIdEstudiante($fila["IDESTUDIANTE"]);
				$asistencia->setIdProfesor($fila["IDPROFESOR"]);
				$asistencia->setFecha($fila["FECHA"]);
				$asistencia->setEstado($fila["ESTADO"]);
				$todosAsistencias[] = $asistencia;
			}
		} else {
			$todosAsistencias = null;
		}
		$conexion->Cerrar();
		return $todosAsistencias;
	}

	function CantidadAsistencias($idEstudiante, $idProfesor, $fechaInicio, $fechaFin)
	{
		$conexion = new Conexion();
		
		if ($idEstudiante != null)
				$sql = "SELECT COUNT(*) FROM `ASISTENCIA` WHERE IDESTUDIANTE = ".$idEstudiante." AND ESTADO = 1 AND FECHA >= '".$fechaInicio."' AND FECHA <= '".$fechaFin."'";
		else
				$sql = "SELECT COUNT(*) FROM `ASISTENCIA` WHERE IDPROFESOR = ".$idProfesor." AND ESTADO = 1 AND FECHA >= '".$fechaInicio."' AND FECHA <= '".$fechaFin."'";

		$resultado = $conexion->Ejecutar($sql);
		$fila = $resultado->fetch_assoc();
		$resultado = $fila["COUNT(*)"];

		if ($resultado != null)
			return $resultado;
		else
			return false;
	}

	
	function BuscarAsistenciasProfesor($idProfesor)
	{
		$todosAsistencias = array();
		$conexion = new Conexion();

		$sql = "SELECT * FROM `ASISTENCIA` WHERE `IDPROFESOR` = ".$idProfesor;
		$resultado = $conexion->Ejecutar($sql);
		if (mysqli_num_rows($resultado) > 0) {
			while ($fila = $resultado->fetch_assoc()) {
				$asistencia = new Asistencia();
				$asistencia->setId($fila["ID"]);
				$asistencia->setIdEstudiante($fila["IDESTUDIANTE"]);
				$asistencia->setIdProfesor($fila["IDPROFESOR"]);
				$asistencia->setFecha($fila["FECHA"]);
				$asistencia->setEstado($fila["ESTADO"]);
				$todosAsistencias[] = $asistencia;
			}
		} else {
			$todosAsistencias = null;
		}
		$conexion->Cerrar();
		return $todosAsistencias;
	}

	function BuscarTodos()
	{
		$todosAsistencias = array();
		$conexion = new Conexion();

		$sql = "SELECT * FROM `ASISTENCIA`";
		$resultado = $conexion->Ejecutar($sql);
		if (mysqli_num_rows($resultado) > 0) {
			while ($fila = $resultado->fetch_assoc()) {
				$asistencia = new Asistencia();
				$asistencia->setId($fila["ID"]);
				$asistencia->setIdEstudiante($fila["IDESTUDIANTE"]);
				$asistencia->setIdProfesor($fila["IDPROFESOR"]);
				$asistencia->setFecha($fila["FECHA"]);
				$asistencia->setEstado($fila["ESTADO"]);
				$todosAsistencias[] = $asistencia;
			}
		} else {
			$todosAsistencias = null;
		}
		$conexion->Cerrar();
		return $todosAsistencias;
	}

	public function Crear(Asistencia $asistencia)
	{
		$est = false;
		$conexion = new Conexion();

		$sql = "INSERT INTO `ASISTENCIA` (`IDESTUDIANTE`, `IDPROFESOR`, `FECHA`, `ESTADO`)
                    VALUES(" . $asistencia->getIdEstudiante() . ",
                           " . $asistencia->getIdProfesor() . ",
                           '" . $asistencia->getFecha() . "',
                            " . $asistencia->getEstado() . ")";

		if ($conexion->Ejecutar($sql)) {
			$est = true;
		}
		$conexion->Cerrar();
		return $est;
	}

	function Modificar(Asistencia $asistencia)
	{
		$estado = false;
		$conexion = new Conexion();

		$sql = "UPDATE ASISTENCIA SET  IDESTUDIANTE='" . $asistencia->getIdEstudiante() . "',
                                        IDPROFESOR='" . $asistencia->getIdProfesor() . "',
                                        FECHA='" . $asistencia->getFecha() . "',
                                        ESTADO='" . $asistencia->getEstado() . "'
                                        Where `ID` =" . $asistencia->getId();
		if ($conexion->Ejecutar($sql)) {
			$estado = true;
		}
		$conexion->Cerrar();
		return $estado;
	}
}
