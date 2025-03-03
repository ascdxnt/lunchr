<?php
require_once './Modelo/Conexion.php';
require_once './Modelo/Entidades/Estudiante.php';
require_once './Modelo/Metodos/EstudianteMetodos.php';
require_once './Modelo/Metodos/TransaccionMetodos.php';
require_once './Modelo/Entidades/Transaccion.php';

class EstudianteCobrosControlador
{
	public function Index()
	{
		$estudianteMetodos = new EstudianteMetodos();
		$todosEstudiantes = $estudianteMetodos->BuscarTodos();

		if ($todosEstudiantes != null) {
			$estudiantes = array();
			for ($i = 0; $i < sizeof($todosEstudiantes); $i++) {
				if ($todosEstudiantes[$i]->getEstado() == 1) {
					$estudiantes[$i] = array("id" => $todosEstudiantes[$i]->getId());
					$estudiantes[$i] += array("nombre" => $todosEstudiantes[$i]->getNombre());
					$estudiantes[$i] += array("apellido1" => $todosEstudiantes[$i]->getPrimerApellido());
					$estudiantes[$i] += array("apellido2" => $todosEstudiantes[$i]->getSegundoApellido());
					$estudiantes[$i] += array("cedula" => $todosEstudiantes[$i]->getCedula());
					$estudiantes[$i] += array("comidas" => $todosEstudiantes[$i]->getComidas());
					$estudiantes[$i] += array("becado" => $todosEstudiantes[$i]->getBecado());
				}
			}
		}

		if (isset($estudiantes))
			$estudiantes = json_encode($estudiantes);
		else
			$estudiantes = null;

		require_once "./Vista/views/cobros/BuscarEstudiante.php";
	}

	public function AgregarComidas()
	{
		$idEstudiante = $_POST['idEstudiante'];
		$comidas = $_POST['comidas'];
		$hora = $_POST['hora'];
		$fechaHoy = $_POST['fechaHoy'];
		$fechaHoy = date("Y-m-d", strtotime($fechaHoy));
		$transaccion = new Transaccion();
		$transaccionMetodos = new TransaccionMetodos();

		$estudiante = new Estudiante();
		$estudianteMetodos = new EstudianteMetodos();

		if ($comidas <= 0)
			header('Location: ./?dir=cobros&controlador=EstudianteCobros&accion=Index&alerta=error');
		else if ($estudiante = $estudianteMetodos->Buscar($idEstudiante)) {
			$estudiante->setComidas($estudiante->getComidas() + $comidas);
			if ($estudianteMetodos->Modificar($estudiante)) {
				if ($estudiante->getBecado() != 1) {
					$transaccion->setIdProfesor(0);
					$transaccion->setIdEstudiante($estudiante->getId());
					$transaccion->setFecha($fechaHoy);
					$transaccion->setHora($hora);
					$transaccion->setEstado(1);
					$transaccion->setComidas($comidas);
					$transaccionMetodos->Crear($transaccion);
				}
				header('Location: ./?dir=cobros&controlador=EstudianteCobros&accion=Index&alerta=success');
			} else
				header('Location: ./?dir=cobros&controlador=EstudianteCobros&accion=Index&alerta=error');
		} else
			header('Location: ./?dir=cobros&controlador=EstudianteCobros&accion=Index&alerta=error');
	}
}
