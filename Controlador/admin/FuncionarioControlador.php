<?php
require_once './Modelo/Conexion.php';
require_once './Modelo/Entidades/Funcionario.php';
require_once './Modelo/Metodos/FuncionarioMetodos.php';
require_once './Controlador/funciones/CorreoControlador.php';
require_once './Controlador/funciones/ContrasenaControlador.php';

class FuncionarioControlador
{
	public function VistasAdmin($vista)
	{
		$perfil = 1;
		if ($vista == "main") {
			$funcionarioMetodos = new FuncionarioMetodos();
			$todosAdministrador = $funcionarioMetodos->BuscarTodosPorPerfil($perfil);

			if($todosAdministrador != null){
				$administradores = array();
				for($i = 0; $i < sizeof($todosAdministrador); $i++){
						$administradores[$i] = array("id" => $todosAdministrador[$i]->getId());
						$administradores[$i] += array("nombre" => $todosAdministrador[$i]->getNombre());
						$administradores[$i] += array("apellido1" => $todosAdministrador[$i]->getPrimerApellido());
						$administradores[$i] += array("apellido2" => $todosAdministrador[$i]->getSegundoApellido());
						$administradores[$i] += array("correo" => $todosAdministrador[$i]->getCorreo());
						$administradores[$i] += array("estado" => $todosAdministrador[$i]->getEstado());
				}
			}

			if(isset($administradores))
				$administradores = json_encode($administradores);
			else
				$administradores = null;
			
			require_once "./Vista/views/admin/Administradores.php";
		} else if ($vista == "crear")
			require_once "./Vista/views/admin/AdministradoresCrear.php";
		else if ($vista == "modificar")
			require_once "./Vista/views/admin/AdministradoresModificar.php";
	}

	public function VistasCobros($vista)
	{
		$perfil = 2;
		if ($vista == "main") {
			$funcionarioMetodos = new FuncionarioMetodos();
			$todosCobrador = $funcionarioMetodos->BuscarTodosPorPerfil($perfil);

			$cobradores = array();
			if($todosCobrador != null){
				for($i = 0; $i < sizeof($todosCobrador); $i++){
						$cobradores[$i] = array("id" => $todosCobrador[$i]->getId());
						$cobradores[$i] += array("nombre" => $todosCobrador[$i]->getNombre());
						$cobradores[$i] += array("apellido1" => $todosCobrador[$i]->getPrimerApellido());
						$cobradores[$i] += array("apellido2" => $todosCobrador[$i]->getSegundoApellido());
						$cobradores[$i] += array("correo" => $todosCobrador[$i]->getCorreo());
						$cobradores[$i] += array("estado" => $todosCobrador[$i]->getEstado());
				}
			}

			if(isset($cobradores))
				$cobradores = json_encode($cobradores);
			else
				$cobradores = null;
			
			require_once "./Vista/views/admin/Cobros.php";
		} else if ($vista == "crear")
			require_once "./Vista/views/admin/CobrosCrear.php";
		else if ($vista == "modificar")
			require_once "./Vista/views/admin/CobrosModificar.php";
	}

	public function Crear($perfil)
	{
		$funcionarioMetodos = new FuncionarioMetodos();
		$funcionario = new Funcionario();
		$plantillaCorreo = new CorreoControlador();
		$generadorContrasenas = new ContrasenaControlador();

		$nombre = $_POST['nombre'];
		$primerApellido = $_POST['primerApellido'];
		$segundoApellido = $_POST['segundoApellido'];
		$correo = $_POST['correo'];

		//Generación de contraseña aleatoria
		$contrasena = $generadorContrasenas->ContrasenaAleatoria();
		$contrasenaEncriptada = password_hash($contrasena, PASSWORD_DEFAULT);

		$funcionario->setNombre($nombre);
		$funcionario->setPrimerApellido($primerApellido);
		$funcionario->setSegundoApellido($segundoApellido);
		if (filter_var($correo, FILTER_VALIDATE_EMAIL))
			$funcionario->setCorreo($correo);
		$funcionario->setContrasena($contrasenaEncriptada);
		$funcionario->setEstado(1);
		$funcionario->setPerfil($perfil);


		if ($funcionario->getNombre() != null && $funcionario->getPrimerApellido() != null &&  $funcionario->getSegundoApellido() != null  && $funcionario->getCorreo() != null) {
			if ($funcionarioMetodos->Crear($funcionario)) {
				$titulo = "Cuenta de Comedor";
				$plantillaCorreo->CorreoIndividual($funcionario->getCorreo(), $titulo, $contrasena, $funcionario->getNombre(), $funcionario->getPrimerApellido());
				if ($perfil == 1)
					header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main&alerta=success');
				else 
					header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main&alerta=success');
			} else {
				if ($perfil == 1)
					header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main&alerta=error');
				else 
					header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main&alerta=error');
			}
		}
	}

	public function CrearPorJSON($perfil)
	{
		$funcionarioMetodos = new FuncionarioMetodos();
		$plantillaCorreo = new CorreoControlador();
		$generadorContrasenas = new ContrasenaControlador();
		$objectArray = json_decode(json_decode($_POST['PostJson']));
		$estado = true;

		for ($i = 0; $i < count($objectArray); $i++) {
			$funcionario = new Funcionario();

			//Inicialización de la mayoría de atributos del profesor
			$count = 0;
			foreach ($objectArray[$i] as $clave => $valor) {
				if ($count == 0) $funcionario->setNombre($valor);
				else if ($count == 1) $funcionario->setPrimerApellido($valor);
				else if ($count == 2) $funcionario->setSegundoApellido($valor);
				else if ($count == 3) {
					if (filter_var($valor, FILTER_VALIDATE_EMAIL))
						$funcionario->setCorreo($valor);
				} else if ($count > 3) break; //Probar si esta línea funciona
				$count++;
			}

			//Generación de contraseña aleatoria
			$contrasena = $generadorContrasenas->ContrasenaAleatoria();
			$contrasenaEncriptada = password_hash($contrasena, PASSWORD_DEFAULT);

			$funcionario->setContrasena($contrasenaEncriptada);
			$funcionario->setEstado(1);
			$funcionario->setPerfil($perfil);

			if ($funcionario->getNombre() != null && $funcionario->getPrimerApellido() != null && $funcionario->getSegundoApellido() != null && $funcionario->getContrasena() != null) {
				if ($funcionarioMetodos->Crear($funcionario)) {
					$titulo = "Cuenta de Comedor";
					$plantillaCorreo->CorreoIndividual($funcionario->getCorreo(), $titulo, $contrasena, $funcionario->getNombre(), $funcionario->getPrimerApellido());
				} else $estado = false;
			} else $estado = false;
		}

		if ($perfil == 1) {
			if ($estado) 
				header("Location: ./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main&alerta=success"); 
			else 
				header("Location: ./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main&alerta=warning"); 
		} else {
			if ($estado) 
				header("Location: ./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main&alerta=success"); 
			else 
				header("Location: ./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main&alerta=warning"); 
		}
	}

	public function Modificar($perfil)
	{
		$funcionario = new Funcionario();
		$funcionarioMetodos = new FuncionarioMetodos();

		$id = $_POST['idModificar'];
		$nombre = $_POST['nombreModificar'];
		$primerApellido = $_POST['primerApellidoModificar'];
		$segundoApellido = $_POST['segundoApellidoModificar'];
		$correo = $_POST['correoModificar'];
		$contrasena = $_POST['contrasenaModificar'];
		$estado = $_POST['estadoModificar'];

		if($contrasena != null)
				$contrasenaModificarCifrada = password_hash($contrasena, PASSWORD_DEFAULT);

		$funcionario->setId($id);
		$funcionario->setNombre($nombre);
		$funcionario->setPrimerApellido($primerApellido);
		$funcionario->setSegundoApellido($segundoApellido);
		if (filter_var($correo, FILTER_VALIDATE_EMAIL))
			$funcionario->setCorreo($correo);
		if($contrasena != null){
			$funcionario->setContrasena($contrasenaModificarCifrada);
			$funcionarioMetodos->ModificarContrasena($contrasenaModificarCifrada, $id);
		}
				
		$funcionario->setEstado($estado);
		$funcionario->setPerfil($perfil);

		if ($funcionario->getNombre() != null && $funcionario->getPrimerApellido() != null && $funcionario->getSegundoApellido() != null && $funcionario->getCorreo() != null) {

			if ($funcionarioMetodos->Modificar($funcionario)) {
				if ($estado == 1) {
					if ($perfil == 1)
						header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main&alerta=success');
					else 
						header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main&alerta=success');
				} else {
					if ($perfil == 1)
						header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main&estados=0&alerta=success');
					else 
						header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main&estados=0&alerta=success');
				}
			} else {
				if ($estado == 1) {
					if ($perfil == 1)
						header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main&alerta=error');
					else 
						header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main&alerta=error');
				} else {
					if ($perfil == 1)
						header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main&estados=0&alerta=error');
					else 
						header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main&estados=0&alerta=error');
				}
			}
		} else {
			if ($estado == 1) {
				if ($perfil == 1)
					header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main&alerta=error');
				else 
					header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main&alerta=error');
			} else {
				if ($perfil == 1)
					header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main&estados=0&alerta=error');
				else 
					header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main&estados=0&alerta=error');
			}
		}
	}

	public function ModificarContrasena(){
		$id = $_POST['idModificar'];
		$contrasena = $_POST['contrasenaModificar'];
		
		$funcionario = new Funcionario();
		$funcionarioMetodos = new FuncionarioMetodos();

		if($id != null && $contrasena != null){
				if($funcionario = $funcionarioMetodos->Buscar($id)){
					$contrasenaModificarCifrada = password_hash($contrasena, PASSWORD_DEFAULT);
					$funcionario->setContrasena($contrasenaModificarCifrada);
					$funcionarioMetodos->ModificarContrasena($contrasenaModificarCifrada, $id);
					header('Location: ./?controlador=Index&accion=MiCuenta&alerta=success');
				}
				else
					header('Location: ./?controlador=Index&accion=MiCuenta&alerta=error');
		}
		else
			header('Location: ./?controlador=Index&accion=MiCuenta&alerta=error');
	}

	public function CambiarEstado($parametros)
	{
		$estado = $parametros[0];
		$perfil = $parametros[1];
		if (!isset($_REQUEST['idsArr'])) {
			if ($perfil == 1)
				header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main');
			else 
				header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main');
		} else {
			$arrayIds = $_REQUEST['idsArr'];
			$lengthArray = $_REQUEST['lengthArray'];
			$funcionarioMetodos = new FuncionarioMetodos();
			$volver = false;

			for ($i = 0; $i < $lengthArray; $i++) {
				$funcionario = new Funcionario();
				$funcionario = $funcionarioMetodos->Buscar($arrayIds[$i]);
				$funcionario->setEstado($estado);
				if ($funcionarioMetodos->Modificar($funcionario))
					$volver = true;
			}

			if ($estado == 0) {
				if ($volver) {
					if ($perfil == 1)
						header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main&alerta=success');
					else
						header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main&alerta=success');
				} else {
					if ($perfil == 1)
						header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main&alerta=error');
					else
						header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main&alerta=error');
				}
			} else {
				if ($volver) {
					if ($perfil == 1)
						header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main&estados=0&alerta=success');
					else
						header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main&estados=0&alerta=success');
				} else {
					if ($perfil == 1)
						header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main&estados=0&alerta=error');
					else
						header('Location: ./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main&estados=0&alerta=error');
				}
			}
		}
	}
}
