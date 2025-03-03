<?php
require_once './Modelo/Conexion.php';
require_once './Modelo/Entidades/Profesor.php';
require_once './Modelo/Metodos/ProfesorMetodos.php';
require_once './Modelo/Metodos/TransaccionMetodos.php';
require_once './Modelo/Entidades/Transaccion.php';

class ProfesorCobrosControlador
{
    public function Index()
    {
				$profesorMetodos = new ProfesorMetodos();
				$todosProfesor = $profesorMetodos->BuscarTodos();

				if($todosProfesor != null){
					$profesores = array();
					for($i = 0; $i < sizeof($todosProfesor); $i++){
						if($todosProfesor[$i]->getEstado() == 1){
							$profesores[$i] = array("id" => $todosProfesor[$i]->getId());
							$profesores[$i] += array("nombre" => $todosProfesor[$i]->getNombre());
							$profesores[$i] += array("apellido1" => $todosProfesor[$i]->getPrimerApellido());
							$profesores[$i] += array("apellido2" => $todosProfesor[$i]->getSegundoApellido());
							$profesores[$i] += array("cedula" => $todosProfesor[$i]->getCedula());
							$profesores[$i] += array("comidas" => $todosProfesor[$i]->getComidas());
						}
					}
				}

				if(isset($profesores))
					$profesores = json_encode($profesores);
				else
					$profesores = null;
			
        require_once "./Vista/views/cobros/BuscarProfesor.php";
    }

		public function AgregarComidas(){
				$idProfesor = $_POST['idProfesor'];
				$comidas = $_POST['comidas'];
				$hora = $_POST['hora'];
				$fechaHoy = $_POST['fechaHoy'];
				$fechaHoy = date("Y-m-d", strtotime($fechaHoy));
				$transaccion = new Transaccion();
				$transaccionMetodos = new TransaccionMetodos();
				
				$profesor = new Profesor();
				$profesorMetodos = new ProfesorMetodos();

				if($comidas <= 0)
								header('Location: ./?dir=cobros&controlador=ProfesorCobros&accion=Index&alerta=error');
				else if($profesor = $profesorMetodos->Buscar($idProfesor)){
						$profesor->setComidas($profesor->getComidas() + $comidas);
						if($profesorMetodos->Modificar($profesor)){
								$transaccion->setIdEstudiante(0);
								$transaccion->setIdProfesor($profesor->getId());
								$transaccion->setFecha($fechaHoy);
								$transaccion->setHora($hora);
								$transaccion->setEstado(1);
								$transaccion->setComidas($comidas);
								$transaccionMetodos->Crear($transaccion);
								header('Location: ./?dir=cobros&controlador=ProfesorCobros&accion=Index&alerta=success');
						}
						else 
							header('Location: ./?dir=cobros&controlador=ProfesorCobros&accion=Index&alerta=error');
				}
				else
					header('Location: ./?dir=cobros&controlador=ProfesorCobros&accion=Index&alerta=error');
		}
}
