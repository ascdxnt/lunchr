<?php

class EstudianteMetodos
{
	function Buscar($id)
	{
		$estudiante = new Estudiante();
		$conexion = new Conexion();

		$sql = "SELECT * FROM `ESTUDIANTE` WHERE `ID` = '$id'";
		$resultado = $conexion->Ejecutar($sql);

		if (mysqli_num_rows($resultado) > 0) {
			while ($fila = $resultado->fetch_assoc()) {
				$estudiante->setId($fila["ID"]);
				$estudiante->setNombre($fila["NOMBRE"]);
				$estudiante->setPrimerApellido($fila["PRIMERAPELLIDO"]);
				$estudiante->setSegundoApellido($fila["SEGUNDOAPELLIDO"]);
				$estudiante->setCedula($fila["CEDULA"]);
				$estudiante->setComidas($fila["COMIDAS"]);
				$estudiante->setIdEspecialidad($fila["IDESPECIALIDAD"]);
				$estudiante->setIdSeccion($fila["IDSECCION"]);
				$estudiante->setCorreo($fila["CORREO"]);
				$estudiante->setContrasena($fila["CONTRASENA"]);
				$estudiante->setEstado($fila["ESTADO"]);
				$estudiante->setBecado($fila["BECADO"]);
				$estudiante->setPerfil($fila["PERFIL"]);
				$estudiante->setFotoPerfil($fila["FOTOPERFIL"]);
			}
		} else {
			$estudiante = null;
		}
		$conexion->Cerrar();
		return $estudiante;
	}

	function BuscarPorCedula($cedula)
	{
		$estudiante = new Estudiante();
		$conexion = new Conexion();

		$sql = "SELECT * FROM `ESTUDIANTE` WHERE `CEDULA` = '$cedula'";
		$resultado = $conexion->Ejecutar($sql);

		if (mysqli_num_rows($resultado) > 0) {
			while ($fila = $resultado->fetch_assoc()) {
				$estudiante->setId($fila["ID"]);
				$estudiante->setNombre($fila["NOMBRE"]);
				$estudiante->setPrimerApellido($fila["PRIMERAPELLIDO"]);
				$estudiante->setSegundoApellido($fila["SEGUNDOAPELLIDO"]);
				$estudiante->setCedula($fila["CEDULA"]);
				$estudiante->setComidas($fila["COMIDAS"]);
				$estudiante->setIdEspecialidad($fila["IDESPECIALIDAD"]);
				$estudiante->setIdSeccion($fila["IDSECCION"]);
				$estudiante->setPerfil($fila["PERFIL"]);
				$estudiante->setCorreo($fila["CORREO"]);
				$estudiante->setContrasena($fila["CONTRASENA"]);
				$estudiante->setBecado($fila["BECADO"]);
				$estudiante->setEstado($fila["ESTADO"]);
				$estudiante->setFotoPerfil($fila["FOTOPERFIL"]);
			}
		} else {
			$estudiante = null;
		}
		$conexion->Cerrar();
		return $estudiante;
	}

	function BuscarPorCorreo($correo)
	{
		$estudiante = new Estudiante();
		$conexion = new Conexion();

		$sql = "SELECT * FROM `ESTUDIANTE` WHERE `CORREO` = '$correo'";
		$resultado = $conexion->Ejecutar($sql);

		if (mysqli_num_rows($resultado) > 0) {
			while ($fila = $resultado->fetch_assoc()) {
				$estudiante->setId($fila["ID"]);
				$estudiante->setNombre($fila["NOMBRE"]);
				$estudiante->setPrimerApellido($fila["PRIMERAPELLIDO"]);
				$estudiante->setSegundoApellido($fila["SEGUNDOAPELLIDO"]);
				$estudiante->setCedula($fila["CEDULA"]);
				$estudiante->setComidas($fila["COMIDAS"]);
				$estudiante->setIdEspecialidad($fila["IDESPECIALIDAD"]);
				$estudiante->setIdSeccion($fila["IDSECCION"]);
				$estudiante->setCorreo($fila["CORREO"]);
				$estudiante->setContrasena($fila["CONTRASENA"]);
				$estudiante->setEstado($fila["ESTADO"]);
				$estudiante->setBecado($fila["BECADO"]);
				$estudiante->setPerfil($fila["PERFIL"]);
				$estudiante->setFotoPerfil($fila["FOTOPERFIL"]);
			}
		} else {
			$estudiante = null;
		}
		$conexion->Cerrar();
		return $estudiante;
	}

	function BuscarTodos()
	{
		$todosEstudiantes = array();
		$conexion = new Conexion();

		$sql = "SELECT * FROM `ESTUDIANTE`";
		$resultado = $conexion->Ejecutar($sql);
		if (mysqli_num_rows($resultado) > 0) {
			while ($fila = $resultado->fetch_assoc()) {
				$estudiante = new Estudiante();
				$estudiante->setId($fila["ID"]);
				$estudiante->setNombre($fila["NOMBRE"]);
				$estudiante->setPrimerApellido($fila["PRIMERAPELLIDO"]);
				$estudiante->setSegundoApellido($fila["SEGUNDOAPELLIDO"]);
				$estudiante->setCedula($fila["CEDULA"]);
				$estudiante->setIdEspecialidad($fila["IDESPECIALIDAD"]);
				$estudiante->setIdSeccion($fila["IDSECCION"]);
				$estudiante->setCorreo($fila["CORREO"]);
				$estudiante->setContrasena($fila["CONTRASENA"]);
				$estudiante->setComidas($fila["COMIDAS"]);
				$estudiante->setEstado($fila["ESTADO"]);
				$estudiante->setBecado($fila["BECADO"]);
				$estudiante->setPerfil($fila["PERFIL"]);
				$estudiante->setFotoPerfil($fila["FOTOPERFIL"]);
				$todosEstudiantes[] = $estudiante;
			}
		} else {
			$todosEstudiantes = null;
		}
		$conexion->Cerrar();
		return $todosEstudiantes;
	}

	function Crear(Estudiante $estudiante)
	{
		$est = false;
		$conexion = new Conexion();

		$sql = "INSERT INTO `ESTUDIANTE`(`NOMBRE`,`PRIMERAPELLIDO`,`SEGUNDOAPELLIDO`, `CEDULA`,`COMIDAS`,`IDESPECIALIDAD`,`IDSECCION`, `CORREO`, `CONTRASENA`, `ESTADO`,`BECADO`, `FOTOPERFIL`, `PERFIL`)
                    VALUES('" . $estudiante->getNombre() . "',
                                '" . $estudiante->getPrimerApellido() . "',
                                '" . $estudiante->getSegundoApellido() . "',
                                '" . $estudiante->getCedula() . "',
                                '" . $estudiante->getComidas() . "',
                                '" . $estudiante->getIdEspecialidad() . "',
                                '" . $estudiante->getIdSeccion() . "',
                                '" . $estudiante->getCorreo() . "',
                                '" . $estudiante->getContrasena() . "',
                                '" . $estudiante->getEstado() . "',
                                '" . $estudiante->getBecado() . "',
                                '" . $estudiante->getFotoPerfil() . "',
                                '" . $estudiante->getPerfil() . "')";

		if ($conexion->Ejecutar($sql)) {
			$est = true;
		}
		$conexion->Cerrar();
		return $est;
	}

	function Modificar(Estudiante $estudiante)
	{
		$est = false;
		$conexion = new Conexion();

		$sql = "UPDATE ESTUDIANTE SET NOMBRE='" . $estudiante->getNombre() . "',PRIMERAPELLIDO='" . $estudiante->getPrimerApellido() . "',
        SEGUNDOAPELLIDO='" . $estudiante->getSegundoApellido() . "',CEDULA='" . $estudiante->getCedula() . "',
        CORREO='" . $estudiante->getCorreo() . "',COMIDAS='" . $estudiante->getComidas() ."',CONTRASENA='" . $estudiante->getContrasena() . "',ESTADO='" . $estudiante->getEstado() . "',
        IDESPECIALIDAD='" . $estudiante->getIdEspecialidad() . "',IDSECCION='" . $estudiante->getIdSeccion() . "',FOTOPERFIL='" . $estudiante->getFotoPerfil() . "',BECADO='" . $estudiante->getBecado() . "' Where `ID` =" . $estudiante->getId();

		if ($conexion->Ejecutar($sql)) {
			$est = true;
		}
		$conexion->Cerrar();
		return $est;
	}

	function ModificarContrasena($contrasena, $estudianteId)
	{
		$estado = false;
		$conexion = new Conexion();

		$sql = "UPDATE ESTUDIANTE SET  CONTRASENA='" . $contrasena . "'
                                        Where `ID` =" . $estudianteId;
		if ($conexion->Ejecutar($sql)) {
			$estado = true;
		}
		$conexion->Cerrar();
		return $estado;
	}
}
