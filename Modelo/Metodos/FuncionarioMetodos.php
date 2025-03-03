<?php

class FuncionarioMetodos
{
	function Buscar($id)
	{
		$funcionario = new Funcionario();
		$conexion = new Conexion();

		$sql = "SELECT *  FROM `FUNCIONARIO` WHERE `ID` = '$id'";
		$resultado = $conexion->Ejecutar($sql);

		if (mysqli_num_rows($resultado) > 0) {
			while ($fila = $resultado->fetch_assoc()) {
				$funcionario->setId($fila["ID"]);
				$funcionario->setPerfil($fila["PERFIL"]);
				$funcionario->setNombre($fila["NOMBRE"]);
				$funcionario->setPrimerApellido($fila["PRIMERAPELLIDO"]);
				$funcionario->setSegundoApellido($fila["SEGUNDOAPELLIDO"]);
				$funcionario->setCorreo($fila["CORREO"]);
				$funcionario->setContrasena($fila["CONTRASENA"]);
				$funcionario->setEstado($fila["ESTADO"]);
			}
		} else {
			$funcionario = null;
		}
		$conexion->Cerrar();
		return $funcionario;
	}

	function BuscarPorCorreo($correo)
	{
		$funcionario = new Funcionario();
		$conexion = new Conexion();

		$sql = "SELECT * FROM `FUNCIONARIO` WHERE `CORREO` = '$correo'";
		$resultado = $conexion->Ejecutar($sql);

		if (mysqli_num_rows($resultado) > 0) {
			while ($fila = $resultado->fetch_assoc()) {
				$funcionario->setId($fila["ID"]);
				$funcionario->setNombre($fila["NOMBRE"]);
				$funcionario->setPrimerApellido($fila["PRIMERAPELLIDO"]);
				$funcionario->setSegundoApellido($fila["SEGUNDOAPELLIDO"]);
				$funcionario->setPerfil($fila["PERFIL"]);
				$funcionario->setCorreo($fila["CORREO"]);
				$funcionario->setContrasena($fila["CONTRASENA"]);
				$funcionario->setEstado($fila["ESTADO"]);
			}
		} else {
			$funcionario = null;
		}
		$conexion->Cerrar();
		return $funcionario;
	}

	function BuscarTodos()
	{
		$todosFuncionarios = array();
		$conexion = new Conexion();

		$sql = "SELECT * FROM `FUNCIONARIO`";
		$resultado = $conexion->Ejecutar($sql);
		if (mysqli_num_rows($resultado) > 0) {
			while ($fila = $resultado->fetch_assoc()) {
				$funcionario = new Funcionario();
				$funcionario->setId($fila["ID"]);
				$funcionario->setPerfil($fila["PERFIL"]);
				$funcionario->setNombre($fila["NOMBRE"]);
				$funcionario->setPrimerApellido($fila["PRIMERAPELLIDO"]);
				$funcionario->setSegundoApellido($fila["SEGUNDOAPELLIDO"]);
				$funcionario->setCorreo($fila["CORREO"]);
				$funcionario->setContrasena($fila["CONTRASENA"]);
				$funcionario->setEstado($fila["ESTADO"]);
				$todosFuncionarios[] = $funcionario;
			}
		} else {
			$todosFuncionarios = null;
		}
		$conexion->Cerrar();
		return $todosFuncionarios;
	}

	function BuscarTodosPorPerfil($perfil)
	{
		$todosFuncionarios = array();
		$conexion = new Conexion();

		$sql = "SELECT * FROM `FUNCIONARIO` WHERE `PERFIL` = '$perfil'";
		$resultado = $conexion->Ejecutar($sql);
		if (mysqli_num_rows($resultado) > 0) {
			while ($fila = $resultado->fetch_assoc()) {
				$funcionario = new Funcionario();
				$funcionario->setId($fila["ID"]);
				$funcionario->setPerfil($fila["PERFIL"]);
				$funcionario->setNombre($fila["NOMBRE"]);
				$funcionario->setPrimerApellido($fila["PRIMERAPELLIDO"]);
				$funcionario->setSegundoApellido($fila["SEGUNDOAPELLIDO"]);
				$funcionario->setCorreo($fila["CORREO"]);
				$funcionario->setContrasena($fila["CONTRASENA"]);
				$funcionario->setEstado($fila["ESTADO"]);
				$todosFuncionarios[] = $funcionario;
			}
		} else {
			$todosFuncionarios = null;
		}
		$conexion->Cerrar();
		return $todosFuncionarios;
	}

	function Crear(Funcionario $funcionario)
	{
		$estado = false;
		$conexion = new Conexion();

		$sql = "INSERT INTO `FUNCIONARIO`(`PERFIL`,`NOMBRE`,`PRIMERAPELLIDO`,`SEGUNDOAPELLIDO`, `CORREO`, `CONTRASENA`, `ESTADO`)
                    VALUES('" . $funcionario->getPerfil() . "',
                           '" . $funcionario->getNombre() . "',
                            '" . $funcionario->getPrimerApellido() . "',
                            '" . $funcionario->getSegundoApellido() . "',
                            '" . $funcionario->getCorreo() . "',
                            '" . $funcionario->getContrasena() . "',
                            '" . $funcionario->getEstado() . "')";
		if ($conexion->Ejecutar($sql)) {
			$estado = true;
		}
		$conexion->Cerrar();
		return $estado;
	}

	function Modificar(Funcionario $funcionario)
	{
		$estado = false;
		$conexion = new Conexion();

		$sql = "UPDATE FUNCIONARIO SET  PERFIL='" . $funcionario->getPerfil() . "',
                                        NOMBRE='" . $funcionario->getNombre() . "',
                                        PRIMERAPELLIDO='" . $funcionario->getPrimerApellido() . "',
                                        SEGUNDOAPELLIDO='" . $funcionario->getSegundoApellido() . "',
                                        CORREO='" . $funcionario->getCorreo() . "',
                                        ESTADO='" . $funcionario->getEstado() . "'
                                        Where `ID` =" . $funcionario->getId();
		if ($conexion->Ejecutar($sql)) {
			$estado = true;
		}
		$conexion->Cerrar();
		return $estado;
	}

	function ModificarContrasena($contrasena, $funcionarioId)
	{
		$estado = false;
		$conexion = new Conexion();

		$sql = "UPDATE FUNCIONARIO SET  CONTRASENA='" . $contrasena . "'
                                        Where `ID` =" . $funcionarioId;
		if ($conexion->Ejecutar($sql)) {
			$estado = true;
		}
		$conexion->Cerrar();
		return $estado;
	}
}
