<?php
class EstadisticasAdminControlador
{
	public function Index()
	{
		require_once './Controlador/admin/AsistenciaControlador.php';
		require_once './Modelo/Metodos/EstudianteMetodos.php';
		require_once './Modelo/Metodos/ProfesorMetodos.php';
		require_once './Modelo/Metodos/AsistenciaMetodos.php';

		if(isset($_POST['fechaInicio']))
				$fechaInicio = $_POST['fechaInicio'];
		else
				$fechaInicio = date('Y-m-')."01";
		if(isset($_POST['fechaFin']))
				$fechaFin = $_POST['fechaFin'];
		else
				$fechaFin = date('Y-m-d');
		if(isset($_POST['perfil']))
				$perfil = $_POST['perfil'];
		else
				$perfil = "estudiante";
		if(isset($_POST['beca']))
				$beca = $_POST['beca'];
		else
				$beca = "cualquiera";

		$datosFiltro = array("FechaInicio" => $fechaInicio, "FechaFin" => $fechaFin, "Perfil" => $perfil, "Beca" => $beca);
		$estudianteMetodos = new EstudianteMetodos();
		$profesorMetodos = new ProfesorMetodos();
		$asistenciaMetodos = new AsistenciaMetodos();
		$asistenciaControlador = new AsistenciaControlador();
		$todosEstudiantes = $estudianteMetodos->BuscarTodos();
		$todosProfesor = $profesorMetodos->BuscarTodos();
		$registroAsistencias = array();
		$clientesMasAusentes = array();
		$cantidadAsistencias = 0;
		$secciones = $this->consultarSecciones();
		$especialidades = $this->consultarEspecialidades();

		if($todosEstudiantes != null && $perfil == "estudiante"){
				foreach ($todosEstudiantes as $estudiante){
						if($estudiante->getEstado() == 1 && ($estudiante->getBecado() == $beca || $beca == "cualquiera")){
								//Aquí contamos las ausencias del estudiante y las almacenamos en un array
							//SACAR LAS SECCIONES Y ESPECIALIDADES
								$cantidadAsistencias = $asistenciaMetodos->CantidadAsistencias($estudiante->getId(), null, $fechaInicio, $fechaFin);
								array_push($clientesMasAusentes, ['Nombre' => $estudiante->getNombre(), 'Apellido1' => $estudiante->getPrimerApellido(), 'Apellido2' => $estudiante->getSegundoApellido(), "Cedula" => $estudiante->getCedula(), "Asistencias" => $cantidadAsistencias, "IdSeccion" => $estudiante->getIdSeccion(), "IdEspecialidad" => $estudiante->getIdEspecialidad()]);
								//Aquí sacamos las ausencias y asistencias y las guardamos en un array
								array_push($registroAsistencias, json_encode($asistenciaControlador->FiltrarAsistenciaPorPeriodo($fechaInicio, $fechaFin, $estudiante->getId(), null)));
						}
				}
		}

		if($todosProfesor != null && $perfil == "profesor"){
				foreach ($todosProfesor as $profesor){
						if($profesor->getEstado() == 1){
								$cantidadAsistencias = $asistenciaMetodos->CantidadAsistencias(null, $profesor->getId(), $fechaInicio, $fechaFin);
										array_push($clientesMasAusentes, ['Nombre' => $profesor->getNombre(), 'Apellido1' => $profesor->getPrimerApellido(), 'Apellido2' => $profesor->getSegundoApellido(), "Cedula" => $profesor->getCedula(), "Asistencias" => $cantidadAsistencias]);
								array_push($registroAsistencias, json_encode($asistenciaControlador->FiltrarAsistenciaPorPeriodo($fechaInicio, $fechaFin, null, $profesor->getId())));
						}
				}
		}

		require_once "./Vista/views/admin/Estadisticas.php";
	}

	private function consultarSecciones() {
		require_once './Modelo/Metodos/SeccionMetodos.php';
		require_once './Modelo/Entidades/Seccion.php';

		$seccionMetodos = new SeccionMetodos();
		$todosSeccion = $seccionMetodos->BuscarTodos();

		if ($todosSeccion == null) return null;

		$secciones = array();
		for ($i = 0; $i < sizeof($todosSeccion); $i++) {
				if ($todosSeccion[$i]->getEstado() == 0) continue;

				$secciones[$i] = array("id" => $todosSeccion[$i]->getId());
				$secciones[$i] += array("descripcion" => $todosSeccion[$i]->getDescripcion());
		}
		
		return $secciones;
	}
	
	private function consultarEspecialidades() {
		require_once './Modelo/Metodos/EspecialidadMetodos.php';
		require_once './Modelo/Entidades/Especialidad.php';

		$especialidadMetodos = new EspecialidadMetodos();
		$todosEspecialidad = $especialidadMetodos->BuscarTodos();

		if ($todosEspecialidad == null) return null;

		$especialidades = array();
		for ($i = 0; $i < sizeof($todosEspecialidad); $i++) {
				if ($todosEspecialidad[$i]->getEstado() == 0) continue;

				$especialidades[$i] = array("id" => $todosEspecialidad[$i]->getId());
				$especialidades[$i] += array("descripcion" => $todosEspecialidad[$i]->getDescripcion());
		}
		
		return $especialidades;
	}

}
