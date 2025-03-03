<?php
require_once './Modelo/Conexion.php';
require_once './Modelo/Entidades/Seccion.php';
require_once './Modelo/Metodos/SeccionMetodos.php';

class SeccionesControlador
{
	public function Index($vista)
	{
		if ($vista == "main") {
			$seccionMetodos = new SeccionMetodos();
			$todosSeccion = $seccionMetodos->BuscarTodos();
			
			if($todosSeccion != null){
				$secciones = array();
				for($i = 0; $i < sizeof($todosSeccion); $i++){
						$secciones[$i] = array("id" => $todosSeccion[$i]->getId());
						$secciones[$i] += array("descripcion" => $todosSeccion[$i]->getDescripcion());
						$secciones[$i] += array("estado" => $todosSeccion[$i]->getEstado());
				}
			}

			if(isset($secciones))
				$secciones = json_encode($secciones);
			else
				$secciones = null;

			require_once "./Vista/views/admin/Secciones.php";
		} else if ($vista == "crear")
			require_once "./Vista/views/admin/SeccionesCrear.php";
		else if ($vista == "modificar")
			require_once "./Vista/views/admin/SeccionesModificar.php";
	}

	public function Crear()
	{
		$seccion = new Seccion();
		$seccionMetodos = new SeccionMetodos();
		$nombre = $_POST['seccion'];

		$seccion = $seccionMetodos->BuscarPorDescripcion($nombre);
		if ($seccion != null && $seccion->getEstado() == 1) {
				header('Location: ./?dir=admin&controlador=Secciones&accion=Index&id=main&alerta=error');
		} else {
			$seccion = new Seccion();
			$seccion->setEstado(1);
			$seccion->setDescripcion($nombre);
			if ($seccion->getDescripcion() != null) {
				if ($seccionMetodos->Crear($seccion))
					header('Location: ./?dir=admin&controlador=Secciones&accion=Index&id=main&alerta=success');
				else
					header('Location: ./?dir=admin&controlador=Secciones&accion=Index&id=main&alerta=error');
			}
		}
	}

	public function VerificarNombre()
	{
		$datos = json_decode(file_get_contents('php://input'), true);
		$nombreSeccion = $datos['nombreSeccion'];
		$seccion = new Seccion();
		$seccionMetodos = new SeccionMetodos();

		if ($seccion = $seccionMetodos->BuscarPorDescripcion($nombreSeccion)) {
				if($seccion->getEstado() == 1)
					echo '{"message":"error"}';
				else 
					echo '{"message":"exito"}';
		} else
			echo '{"message":"exito"}';
	}

	public function Modificar()
	{
		$seccion = new Seccion();
		$seccionMetodos = new SeccionMetodos();


		$id = $_POST['idModificar'];
		$nombre = $_POST['seccionModificar'];
		$estado = $_POST['estadoModificar'];


		$seccion->setId($id);
		$seccion->setDescripcion($nombre);
		$seccion->setEstado($estado);


		if ($seccion->getId() != null && $seccion->getDescripcion() != null && $seccion->getEstado() != null) {
			if ($seccionMetodos->Modificar($seccion)) {
				header('Location: ./?dir=admin&controlador=Secciones&accion=Index&id=main&alerta=success');
			} else {
				header('Location: ./?dir=admin&controlador=Secciones&accion=Index&id=main&alerta=error');
			}
		}
	}

	public function CambiarEstado($parametros)
	{
		$estado = $parametros;
		if (!isset($_REQUEST['idsArr']))
			header('Location: ./?dir=admin&controlador=Secciones&accion=Index&id=main&alerta=error');
		else {
			$arrayIds = $_REQUEST['idsArr'];
			$lengthArray = $_REQUEST['lengthArray'];
			$seccionMetodos = new SeccionMetodos();
			$volver = false;

			for ($i = 0; $i < $lengthArray; $i++) {
				$seccion = new Seccion();
				$seccion = $seccionMetodos->Buscar($arrayIds[$i]);
				$seccion->setEstado($estado);
				if ($seccionMetodos->Modificar($seccion)) {
					$volver = true;
				}
			}

			if ($estado == 0) {
				if ($volver)
					header('Location: ./?dir=admin&controlador=Secciones&accion=Index&id=main&alerta=success');
				else
					header('Location: ./?dir=admin&controlador=Secciones&accion=Index&id=main&alerta=error');
			} else {
				if ($volver)
					header('Location: ./?dir=admin&controlador=Secciones&accion=Index&id=main&alerta=success');
				else
					header('Location: ./?dir=admin&controlador=Secciones&accion=Index&id=main&alerta=error');
			}
		}
	}
}
