<?php
class ProfesorMetodos
{
	function Buscar($id)
	{
		$profesor = new Profesor();
		$conexion = new Conexion();

		$sql = "SELECT *  FROM `PROFESOR` WHERE `ID` = '$id'";
		$resultado = $conexion->Ejecutar($sql);

		if (mysqli_num_rows($resultado) > 0) {
			while ($fila = $resultado->fetch_assoc()) {
				$profesor->setId($fila["ID"]);
				$profesor->setNombre($fila["NOMBRE"]);
				$profesor->setPrimerApellido($fila["PRIMERAPELLIDO"]);
				$profesor->setSegundoApellido($fila["SEGUNDOAPELLIDO"]);
				$profesor->setCedula($fila["CEDULA"]);
				$profesor->setComidas($fila["COMIDAS"]);
				$profesor->setCorreo($fila["CORREO"]);
				$profesor->setContrasena($fila["CONTRASENA"]);
				$profesor->setEstado($fila["ESTADO"]);
				$profesor->setFotoPerfil($fila["FOTOPERFIL"]);
			}
		} else {
			$profesor = null;
		}
		$conexion->Cerrar();
		return $profesor;
	}

	function BuscarPorCorreo($correo)
	{
		$profesor = new Profesor();
		$conexion = new Conexion();

		$sql = "SELECT * FROM `PROFESOR` WHERE `CORREO` = '$correo'";
		$resultado = $conexion->Ejecutar($sql);

		if (mysqli_num_rows($resultado) > 0) {
			while ($fila = $resultado->fetch_assoc()) {
				$profesor->setId($fila["ID"]);
				$profesor->setNombre($fila["NOMBRE"]);
				$profesor->setPrimerApellido($fila["PRIMERAPELLIDO"]);
				$profesor->setSegundoApellido($fila["SEGUNDOAPELLIDO"]);
				$profesor->setCedula($fila["CEDULA"]);
				$profesor->setComidas($fila["COMIDAS"]);
				$profesor->setCorreo($fila["CORREO"]);
				$profesor->setContrasena($fila["CONTRASENA"]);
				$profesor->setEstado($fila["ESTADO"]);
				$profesor->setFotoPerfil($fila["FOTOPERFIL"]);
			}
		} else {
			$profesor = null;
		}
		$conexion->Cerrar();
		return $profesor;
	}

	function BuscarPorCedula($cedula)
	{
		$profesor = new Profesor();
		$conexion = new Conexion();

		$sql = "SELECT * FROM `PROFESOR` WHERE `CEDULA` = '$cedula'";
		$resultado = $conexion->Ejecutar($sql);

		if (mysqli_num_rows($resultado) > 0) {
			while ($fila = $resultado->fetch_assoc()) {
				$profesor->setId($fila["ID"]);
				$profesor->setNombre($fila["NOMBRE"]);
				$profesor->setPrimerApellido($fila["PRIMERAPELLIDO"]);
				$profesor->setSegundoApellido($fila["SEGUNDOAPELLIDO"]);
				$profesor->setCedula($fila["CEDULA"]);
				$profesor->setComidas($fila["COMIDAS"]);
				$profesor->setCorreo($fila["CORREO"]);
				$profesor->setContrasena($fila["CONTRASENA"]);
				$profesor->setEstado($fila["ESTADO"]);
				$profesor->setFotoPerfil($fila["FOTOPERFIL"]);
			}
		} else {
			$profesor = null;
		}
		$conexion->Cerrar();
		return $profesor;
	}

	function BuscarTodos()
	{
		$conexion = new Conexion();

		$sql = "SELECT * FROM `PROFESOR`";
		$resultado = $conexion->Ejecutar($sql);
		if (mysqli_num_rows($resultado) > 0) {
			while ($fila = $resultado->fetch_assoc()) {
				$profesor = new Profesor();
				$profesor->setId($fila["ID"]);
				$profesor->setNombre($fila["NOMBRE"]);
				$profesor->setPrimerApellido($fila["PRIMERAPELLIDO"]);
				$profesor->setSegundoApellido($fila["SEGUNDOAPELLIDO"]);
				$profesor->setCedula($fila["CEDULA"]);
				$profesor->setComidas($fila["COMIDAS"]);
				$profesor->setCorreo($fila["CORREO"]);
				$profesor->setContrasena($fila["CONTRASENA"]);
				$profesor->setEstado($fila["ESTADO"]);
				$profesor->setFotoPerfil($fila["FOTOPERFIL"]);
				$todosProfes[] = $profesor;
			}
		} else {
			$todosProfes = null;
		}
		$conexion->Cerrar();
		return $todosProfes;
	}

	function Crear(Profesor $profesor)
	{
		$est = false;
		$conexion = new Conexion();

		$sql = "INSERT INTO `PROFESOR`(`NOMBRE`,`PRIMERAPELLIDO`,`SEGUNDOAPELLIDO`, `CEDULA`,`COMIDAS`, `CORREO`, `CONTRASENA`, `ESTADO`,`PERFIL`, `FOTOPERFIL`)
                    VALUES('" . $profesor->getNombre() . "',
                                '" . $profesor->getPrimerApellido() . "',
                                '" . $profesor->getSegundoApellido() . "',
                                '" . $profesor->getCedula() . "',
                                '" . $profesor->getComidas() . "',
                                '" . $profesor->getCorreo() . "',
                                '" . $profesor->getContrasena() . "',
                                '" . $profesor->getEstado() . "',
                                '" . $profesor->getPerfil() . "',
                                '" . $profesor->getFotoPerfil() . "')";

		if ($conexion->Ejecutar($sql)) {
			$est = true;
		}
		$conexion->Cerrar();
		return $est;
	}

	public function Modificar(Profesor $profesor)
	{
		$est = false;
		$conexion = new Conexion();

		$sql = "UPDATE PROFESOR SET NOMBRE='" . $profesor->getNombre() . "',PRIMERAPELLIDO='" . $profesor->getPrimerApellido() . "',SEGUNDOAPELLIDO='" . $profesor->getSegundoApellido() . "',CEDULA='" . $profesor->getCedula() . "',COMIDAS='" . $profesor->getComidas() . "',CORREO='" . $profesor->getCorreo() . "',FOTOPERFIL='" . $profesor->getFotoPerfil() . "',CONTRASENA='" . $profesor->getContrasena() . "',ESTADO='" . $profesor->getEstado() . "'Where `ID` =" . $profesor->getId();

		if ($conexion->Ejecutar($sql)) {
			$est = true;
		}
		$conexion->Cerrar();
		return $est;
	}

	function ModificarContrasena($contrasena, $profesorId)
	{
		$estado = false;
		$conexion = new Conexion();

		$sql = "UPDATE PROFESOR SET CONTRASENA='" . $contrasena . "' Where `ID` =" . $profesorId;
		if ($conexion->Ejecutar($sql)) {
			$estado = true;
		}
		$conexion->Cerrar();
		return $estado;
	}
}
