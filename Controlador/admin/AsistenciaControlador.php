<?php
require_once "./Modelo/Conexion.php";
require_once "./Modelo/Entidades/Asistencia.php";
require_once "./Modelo/Entidades/Transaccion.php";
require_once "./Modelo/Entidades/Profesor.php";
require_once "./Modelo/Entidades/Estudiante.php";
require_once "./Modelo/Metodos/AsistenciaMetodos.php";
require_once "./Modelo/Metodos/TransaccionMetodos.php";
require_once "./Modelo/Metodos/ProfesorMetodos.php";
require_once "./Modelo/Metodos/EstudianteMetodos.php";

class AsistenciaControlador
{
	public function Index()
	{
		require_once "./Vista/views/admin/Asistencia.php";
	}

	public function RegistroAsistencia()
	{
		$estudianteMetodos = new EstudianteMetodos();
		$profesorMetodos = new ProfesorMetodos();
		$todosProfesores = $profesorMetodos->BuscarTodos();
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
					$estudiantes[$i] += array("becado" => $todosEstudiantes[$i]->getBecado());
					$estudiantes[$i] += array("perfil" => "Estudiante");
				}
			}
		}

		if(isset($estudiantes))
			$estudiantes = json_encode($estudiantes);
		else
			$estudiantes = null;

		if ($todosProfesores != null) {
			$profesores = array();
			for ($i = 0; $i < sizeof($todosProfesores); $i++) {
				if ($todosProfesores[$i]->getEstado() == 1) {
					$profesores[$i] = array("id" => $todosProfesores[$i]->getId());
					$profesores[$i] += array("nombre" => $todosProfesores[$i]->getNombre());
					$profesores[$i] += array("apellido1" => $todosProfesores[$i]->getPrimerApellido());
					$profesores[$i] += array("apellido2" => $todosProfesores[$i]->getSegundoApellido());
					$profesores[$i] += array("cedula" => $todosProfesores[$i]->getCedula());
					$profesores[$i] += array("perfil" => "Profesor");
				}
			}
		}

		if(isset($profesores))
			$profesores = json_encode($profesores);
		else
			$profesores = null;

		require_once "./Vista/views/admin/AsistenciaRegistro.php";
	}

	public function DetallesAsistencia($id)
	{
		require_once './Controlador/admin/AsistenciaControlador.php';
		$estudianteMetodos = new EstudianteMetodos();
		$profesorMetodos = new ProfesorMetodos();
		$seccionMetodos = new SeccionMetodos();
		$especialidadMetodos = new EspecialidadMetodos();
		$seccion = new Seccion();
		$especialidad = new Especialidad();
		$asistenciaControlador = new AsistenciaControlador();
		$perfil = $_REQUEST['perfil'];

		if ($estudianteMetodos->Buscar($id) && $perfil == "Estudiante") {
			$cliente = $estudianteMetodos->Buscar($id);
			$seccion = $seccionMetodos->Buscar($cliente->getIdSeccion());
			$especialidad = $especialidadMetodos->Buscar($cliente->getIdEspecialidad());
			$registroAsistencias = $asistenciaControlador->FiltrarAsistenciaPorPeriodo(date('Y') . '-01-01', date('Y-m-d'), $id, null);
		} else if ($profesorMetodos->Buscar($id) && $perfil == "Profesor") {
			$cliente = $profesorMetodos->Buscar($id);
			$registroAsistencias = $asistenciaControlador->FiltrarAsistenciaPorPeriodo(date('Y') . '-01-01', date('Y-m-d'), null, $id);
		}

		$registroAsistencias = json_encode($registroAsistencias);
		require_once "./Vista/views/admin/AsistenciaDetalles.php";
	}

	public function EstaPresente($fecha, $asistenciasCliente)
	{
		$presente = false;
		//Este bloque verifica si un estudiante/profesor se encuentra presente en X fecha.
		if ($asistenciasCliente != null) {
			foreach ($asistenciasCliente as $asistencia) {
				if ($asistencia->getFecha() == $fecha)
					$presente = true;
			}
		}

		if (!$presente)
			return false;

		return true;
	}

	public function FiltrarAsistenciaPorPeriodo($fechaInicio, $fechaFin, $idEstudiante, $idProfesor)
	{
		require_once './Controlador/admin/AsistenciaControlador.php';
		require_once './Controlador/funciones/FechaControlador.php';
		$asistenciaControlador = new AsistenciaControlador();
		$asistenciaMetodos = new AsistenciaMetodos();
		$fechaControlador = new FechaControlador();

		if ($idEstudiante != null || $idEstudiante != 0)
			$asistenciasCliente = $asistenciaMetodos->BuscarAsistenciasEstudiante($idEstudiante);
		else
			$asistenciasCliente = $asistenciaMetodos->BuscarAsistenciasProfesor($idProfesor);

		$arrayRegistroAsistencias = array();
		$fechaInicio = strtotime($fechaInicio);
		$fechaFin = strtotime($fechaFin);
		$contador = 0;

		for ($day = $fechaInicio; $day <= $fechaFin; $day += 86400) {
			$fecha = date('Y-m-d', $day);
			if ($fechaControlador->EsUnDiaLectivo($fecha)) {
				if ($asistenciaControlador->EstaPresente($fecha, $asistenciasCliente)) {
					$arrayRegistroAsistencias[$contador] = array('Fecha' => $fecha);
					$arrayRegistroAsistencias[$contador] += array('Estado' => "Presente");
				} else {
					$arrayRegistroAsistencias[$contador] = array('Fecha' => $fecha);
					$arrayRegistroAsistencias[$contador] += array('Estado' => "Ausente");
				}
			}
			$contador++;
		}

		if ($arrayRegistroAsistencias != null)
			return $arrayRegistroAsistencias;
	}

	public function PasarAsistencia()
	{
		require_once './Controlador/admin/AsistenciaControlador.php';
		require_once './Controlador/funciones/FechaControlador.php';
		$asistenciaControlador = new AsistenciaControlador();
		$datos = json_decode(file_get_contents('php://input'), true);
		$fechaControlador = new FechaControlador();
		$cedula = $datos['Cedula'];
		$hora = $datos['Hora'];
		$fechaHoy = $datos['Fecha'];
		$fechaHoy = date("Y-m-d", strtotime($fechaHoy));
		$estudiante = new Estudiante();
		$profesor = new Profesor();
		$estudianteMetodos = new EstudianteMetodos();
		$profesorMetodos = new ProfesorMetodos();
		
		if (!$fechaControlador->EsUnDiaLectivo($fechaHoy)){
			echo '{"message":"Hoy no es un día lectivo."}';
			return;
		}
		
		
		if (!$profesorMetodos->BuscarPorCedula($cedula) && !$estudianteMetodos->BuscarPorCedula($cedula))
			echo '{"message":"Usuario Inexistente."}';

		if ($profesor = $profesorMetodos->BuscarPorCedula($cedula)){
			echo $asistenciaControlador->AsistenciaProfesor($profesor, $fechaHoy, $hora);
			return;
		}

		if ($estudiante = $estudianteMetodos->BuscarPorCedula($cedula)){
			echo $asistenciaControlador->AsistenciaEstudiante($estudiante, $fechaHoy, $hora);
			return;
		}
	}

	private function AsistenciaProfesor(Profesor $profesor, $fechaHoy, $hora)
	{
		$array = array('message' => "", 'Nombre' => $profesor->getNombre(), 'Apellido1' => $profesor->getPrimerApellido(), 'Apellido2' => $profesor->getSegundoApellido(), 'fotoPerfil' => $profesor->getFotoPerfil());

		$asistencia = new Asistencia();
		$transaccion = new Transaccion();
		$asistenciaMetodos = new AsistenciaMetodos();
		$transaccionMetodos = new TransaccionMetodos();
		$profesorMetodos = new ProfesorMetodos();

		$asistenciasProfesor = $asistenciaMetodos->BuscarAsistenciasProfesor($profesor->getId());
		$estado = true;

		//Este pequeño bloque revisa si el día de hoy el profesor ya asistió
		if ($asistenciasProfesor != null) {
			foreach ($asistenciasProfesor as $asistenciaProfesor) {
				if ($asistenciaProfesor->getFecha() == $fechaHoy)
					$estado = false;
			}
		}

		if (!$estado){
			$array['message'] = "Usted ya está presente.";
			return json_encode($array);
		}

		if (!$profesor->getComidas() > 0){
			$array['message'] = "No tiene comidas.";
			return json_encode($array);
		}

		$profesor->setComidas($profesor->getComidas() - 1);
		$profesorMetodos->Modificar($profesor);
		//asistencias
		$asistencia->setIdProfesor($profesor->getId());
		$asistencia->setIdEstudiante(0);
		$asistencia->setFecha($fechaHoy);
		$asistencia->setEstado(1);
		$asistenciaMetodos->Crear($asistencia);
		//transacciones
		$transaccion->setIdProfesor($profesor->getId());
		$transaccion->setIdEstudiante(0);
		$transaccion->setFecha($fechaHoy);
		$transaccion->setHora($hora);
		$transaccion->setEstado(1);
		$transaccion->setComidas(-1);
		$transaccionMetodos->Crear($transaccion);

		$array['message'] = "Pase adelante.";
		return json_encode($array);
	}

	private function AsistenciaEstudiante(Estudiante $estudiante, $fechaHoy, $hora)
	{
		$array = array('message' => "", 'Nombre' => $estudiante->getNombre(), 'Apellido1' => $estudiante->getPrimerApellido(), 'Apellido2' => $estudiante->getSegundoApellido(), 'fotoPerfil' => $estudiante->getFotoPerfil());

		$asistencia = new Asistencia();
		$transaccion = new Transaccion();
		$asistenciaMetodos = new AsistenciaMetodos();
		$transaccionMetodos = new TransaccionMetodos();
		$estudianteMetodos = new EstudianteMetodos();

		$asistenciasEstudiante = $asistenciaMetodos->BuscarAsistenciasEstudiante($estudiante->getId());
		$estado = true;
		if ($asistenciasEstudiante != null) {
			foreach ($asistenciasEstudiante as $asistenciaEstudiante) {
				if ($asistenciaEstudiante->getFecha() == $fechaHoy)
					$estado = false;
			}
		}

		if (!$estado){
			$array['message'] = "Usted ya está presente.";
			return json_encode($array);
		}

		if (!$estudiante->getComidas() > 0 && !$estudiante->getBecado() == 1){
			$array['message'] = "No tiene comidas.";
			return json_encode($array);
		}

		if ($estudiante->getBecado() != 1)
			$estudiante->setComidas($estudiante->getComidas() - 1);

		$estudianteMetodos->Modificar($estudiante);
		$asistencia->setIdProfesor(0);
		$asistencia->setIdEstudiante($estudiante->getId());
		$asistencia->setFecha($fechaHoy);
		$asistencia->setEstado(1);
		$asistenciaMetodos->Crear($asistencia);

		if ($estudiante->getBecado() != 1) {
			$transaccion->setIdProfesor(0);
			$transaccion->setIdEstudiante($estudiante->getId());
			$transaccion->setFecha($fechaHoy);
			$transaccion->setHora($hora);
			$transaccion->setEstado(1);
			$transaccion->setComidas(-1);
			$transaccionMetodos->Crear($transaccion);
		}

		$array['message'] = "Pase adelante.";
		return json_encode($array);
	}
}
