<?php
require_once './Modelo/Conexion.php';
require_once './Modelo/Entidades/Especialidad.php';
require_once './Modelo/Metodos/EspecialidadMetodos.php';

class EspecialidadesControlador
{

	public function Index($vista)
	{
		if ($vista == "main") {
			$especialidadMetodos = new EspecialidadMetodos();
			$todosEspecialidad = $especialidadMetodos->BuscarTodos();

			if($todosEspecialidad != null){
				$especialidades = array();
				for($i = 0; $i < sizeof($todosEspecialidad); $i++){
						$especialidades[$i] = array("id" => $todosEspecialidad[$i]->getId());
						$especialidades[$i] += array("descripcion" => $todosEspecialidad[$i]->getDescripcion());
						$especialidades[$i] += array("estado" => $todosEspecialidad[$i]->getEstado());
				}
			}

			if(isset($especialidades))
				$especialidades = json_encode($especialidades);
			else
				$especialidades = null;

			require_once "./Vista/views/admin/Especialidades.php";
		} else if ($vista == "crear")
			require_once "./Vista/views/admin/EspecialidadesCrear.php";
		else if ($vista == "modificar")
			require_once "./Vista/views/admin/EspecialidadesModificar.php";
	}

	public function Crear()
	{
		$especialidad = new Especialidad();
		$especialidadMetodos = new EspecialidadMetodos();
		$nombre = $_POST['especialidad'];

		$especialidad = $especialidadMetodos->BuscarPorDescripcion($nombre);
		if($especialidad != null && $especialidad->getEstado() == 1)
			header('Location: ./?dir=admin&controlador=Especialidades&accion=Index&id=main&alerta=error');
		else{
				$especialidad = new Especialidad();
				$especialidad->setDescripcion($nombre);
				$especialidad->setEstado(1);
				if ($especialidad->getDescripcion() != null) {
					if ($especialidadMetodos->Crear($especialidad)) {
						header('Location: ./?dir=admin&controlador=Especialidades&accion=Index&id=main&alerta=success');
					} else {
						header('Location: ./?dir=admin&controlador=Especialidades&accion=Index&id=main&alerta=error');
					}
				}
		}
	}

	public function Modificar()
	{
		$especialidad = new Especialidad();
		$especialidadMetodos = new EspecialidadMetodos();

		$id = $_POST['idModificar'];
		$nombre = $_POST['especialidadModificar'];
		$estado = $_POST['estadoModificar'];

		$especialidad->setId($id);
		$especialidad->setDescripcion($nombre);
		$especialidad->setEstado($estado);

		if ($especialidad->getId() != null && $especialidad->getDescripcion() != null && $especialidad->getEstado() != null) {
			if ($especialidadMetodos->Modificar($especialidad)) {
				header('Location: ./?dir=admin&controlador=Especialidades&accion=Index&id=main&alerta=success');
			} else {
				header('Location: ./?dir=admin&controlador=Especialidades&accion=Index&id=main&alerta=error');
			}
		}
	}

	public function VerificarNombre()
	{
		$datos = json_decode(file_get_contents('php://input'), true);
		$nombreEspecialidad = $datos['nombreEspecialidad'];
		$especialidad = new Especialidad();
		$especialidadMetodos = new EspecialidadMetodos();

		if ($especialidad = $especialidadMetodos->BuscarPorDescripcion($nombreEspecialidad)) {
				if($especialidad->getEstado() == 1)
					echo '{"message":"error"}';
				else 
					echo '{"message":"exito"}';
		} else
			echo '{"message":"exito"}';
	}

	public function CambiarEstado($parametros)
	{
		$estado = $parametros[0];
		$perfil = $parametros[1];
		if (!isset($_REQUEST['idsArr'])) {
			header('Location: ./?dir=admin&controlador=Especialidades&accion=Index&id=main');
		} else {
			$arrayIds = $_REQUEST['idsArr'];
			$lengthArray = $_REQUEST['lengthArray'];
			$especialidadMetodos = new EspecialidadMetodos();
			$volver = false;

			for ($i = 0; $i < $lengthArray; $i++) {
				$especialidad = new Especialidad();
				$especialidad = $especialidadMetodos->Buscar($arrayIds[$i]);
				$especialidad->setEstado($estado);
				if ($especialidadMetodos->Modificar($especialidad)) {
					$volver = true;
				}
			}

			if ($estado == 0) {
				if ($volver) {
					header('Location: ./?dir=admin&controlador=Especialidades&accion=Index&id=main&alerta=success');
				} else {
					header('Location: ./?dir=admin&controlador=Especialidades&accion=Index&id=main&alerta=error');
				}
			} else {
				if ($volver) {
					header('Location: ./?dir=admin&controlador=Especialidades&accion=Index&id=main&alerta=success');
				} else {
					header('Location: ./?dir=admin&controlador=Especialidades&accion=Index&id=main&alerta=error');
				}
			}
		}
	}
}
