<?php
require_once './Modelo/Conexion.php';
require_once './Modelo/Entidades/Profesor.php';
require_once './Modelo/Metodos/ProfesorMetodos.php';
require_once './Controlador/funciones/CorreoControlador.php';
require_once './Controlador/funciones/ContrasenaControlador.php';

class ProfesorControlador
{
	public function Index($vista)
	{
		if ($vista == "main") {
			$profesorMetodos = new ProfesorMetodos();
			$todosProfesor = $profesorMetodos->BuscarTodos();

			if ($todosProfesor != null) {
				$profesores = array();
				for ($i = 0; $i < sizeof($todosProfesor); $i++) {
					$profesores[$i] = array("id" => $todosProfesor[$i]->getId());
					$profesores[$i] += array("nombre" => $todosProfesor[$i]->getNombre());
					$profesores[$i] += array("apellido1" => $todosProfesor[$i]->getPrimerApellido());
					$profesores[$i] += array("apellido2" => $todosProfesor[$i]->getSegundoApellido());
					$profesores[$i] += array("cedula" => $todosProfesor[$i]->getCedula());
					$profesores[$i] += array("correo" => $todosProfesor[$i]->getCorreo());
					$profesores[$i] += array("comidas" => $todosProfesor[$i]->getComidas());
					$profesores[$i] += array("estado" => $todosProfesor[$i]->getEstado());
					$profesores[$i] += array("fotoPerfil" => $todosProfesor[$i]->getFotoPerfil());
				}
			}

			if (isset($profesores))
				$profesores = json_encode($profesores);
			else
				$profesores = null;

			require_once "./Vista/views/admin/Profesores.php";
		} else if ($vista == "crear")
			require_once "./Vista/views/admin/ProfesoresCrear.php";
		else if ($vista == "modificar")
			require_once "./Vista/views/admin/ProfesoresModificar.php";
	}
	public function Crear()
	{
		require_once './Controlador/funciones/FotoPerfilControlador.php';
		$FotoPerfilControlador = new FotoPerfilControlador();

		$profesorMetodos = new ProfesorMetodos();
		$profesor = new Profesor();
		$plantillaCorreo = new CorreoControlador();
		$generadorContrasenas = new ContrasenaControlador();

		$nombre = $_POST['nombre'];
		$primerApellido = $_POST['primerApellido'];
		$segundoApellido = $_POST['segundoApellido'];
		$cedula = $_POST['cedula'];
		$correo = $_POST['correo'];

		$tmp_path = $_FILES["profile-image"]["tmp_name"];
		$name = $_FILES["profile-image"]["name"];
		$size = $_FILES["profile-image"]["size"];
		$type = $_FILES["profile-image"]["type"];

		$fotoRuta = $FotoPerfilControlador->GenerarFotoPerfil($name, $tmp_path, $size, $type);

		if (!$fotoRuta) {
			header('Location: ./?dir=admin&controlador=Estudiante&accion=Index&id=crear&alerta=error');
			return;
		}

		//Generación de contraseña aleatoria
		$contrasena = $generadorContrasenas->ContrasenaAleatoria();
		$contrasenaEncriptada = password_hash($contrasena, PASSWORD_DEFAULT);

		$profesor->setNombre($nombre);
		$profesor->setPrimerApellido($primerApellido);
		$profesor->setSegundoApellido($segundoApellido);
		$profesor->setCedula($cedula);
		$profesor->setContrasena($contrasenaEncriptada);
		$profesor->setFotoPerfil($fotoRuta);
		$profesor->setPerfil(3);
		$profesor->setEstado(1);
		$profesor->setComidas(0);

		//Validacion de correo
		if (filter_var($correo, FILTER_VALIDATE_EMAIL))
			$profesor->setCorreo($correo);

		if ($profesor->getNombre() != null && $profesor->getPrimerApellido() != null &&  $profesor->getSegundoApellido() != null && $profesor->getCedula() != null && $profesor->getContrasena() != null && $profesor->getCorreo() != null && $profesor->getFotoPerfil() != null) {
			if ($profesorMetodos->Crear($profesor)) {
				$titulo = "Cuenta de Comedor";
				$plantillaCorreo->CorreoIndividual($profesor->getCorreo(), $titulo, $contrasena, $profesor->getNombre(), $profesor->getPrimerApellido());
				header('Location: ./?dir=admin&controlador=Profesor&accion=Index&id=main&alerta=success');
			} else {
				$FotoPerfilControlador->EliminarFotoPerfil($profesor->getFotoPerfil());
				header('Location: ./?dir=admin&controlador=Profesor&accion=Index&id=main&alerta=error');
			}
		} else {
			$FotoPerfilControlador->EliminarFotoPerfil($profesor->getFotoPerfil());
			header('Location: ./?dir=admin&controlador=Profesor&accion=Index&id=main&alerta=error');
		}
	}

	public function CrearPorJSON()
	{
		$plantillaCorreo = new CorreoControlador();
		$generadorContrasenas = new ContrasenaControlador();
		$profesorMetodos = new ProfesorMetodos();
		$objectArray = json_decode(json_decode($_POST['PostJson']));
		$estado = true;

		for ($i = 0; $i < count($objectArray); $i++) {
			$profesor = new Profesor();

			//Inicialización de la mayoría de atributos del profesor
			$count = 0;
			foreach ($objectArray[$i] as $clave => $valor) {
				if ($count == 0) $profesor->setNombre($valor);
				else if ($count == 1) $profesor->setPrimerApellido($valor);
				else if ($count == 2) $profesor->setSegundoApellido($valor);
				else if ($count == 3) $profesor->setCedula($valor);
				else if ($count == 4) {
					if (filter_var($valor, FILTER_VALIDATE_EMAIL))
						$profesor->setCorreo($valor);
				}
				$count++;
			}

			//Generación de contraseña aleatoria
			$contrasena = $generadorContrasenas->ContrasenaAleatoria();
			$contrasenaEncriptada = password_hash($contrasena, PASSWORD_DEFAULT);

			$profesor->setComidas(0);
			$profesor->setContrasena($contrasenaEncriptada);
			$profesor->setEstado(1);
			$profesor->setPerfil(3);
			$profesor->setFotoPerfil('./Vista/assets/profile/default.jpg');

			if ($profesor->getNombre() != null && $profesor->getPrimerApellido() != null &&  $profesor->getSegundoApellido() != null && $profesor->getCedula() != null && $profesor->getCorreo() != null && $profesor->getFotoPerfil() != null) {
				if ($profesorMetodos->Crear($profesor)) {
					$estado = true;

					$titulo = "Cuenta de Comedor";
					$plantillaCorreo->CorreoIndividual($profesor->getCorreo(), $titulo, $contrasena, $profesor->getNombre(), $profesor->getPrimerApellido());
				} else $estado = false;
			} else $estado = false;
		}

		if ($estado)
			header("Location: ./?dir=admin&controlador=Profesor&accion=Index&id=main&alerta=success");
		else
			header("Location: ./?dir=admin&controlador=Profesor&accion=Index&id=main&alerta=warning");
	}

	public function Modificar()
	{
		require_once './Controlador/funciones/FotoPerfilControlador.php';
		$FotoPerfilControlador = new FotoPerfilControlador();

		$profesor = new Profesor();
		$profesorMetodos = new ProfesorMetodos();

		$id = $_POST['idModificar'];
		$nombre = $_POST['nombreModificar'];
		$primerApellido = $_POST['primerApellidoModificar'];
		$segundoApellido = $_POST['segundoApellidoModificar'];
		$cedula = $_POST['cedulaModificar'];
		$correo = $_POST['correoModificar'];
		$contrasena = $_POST['contrasenaModificar'];
		$estado = $_POST['estadoModificar'];

		$tmp_path = $_FILES["profile-image"]["tmp_name"];
		$name = $_FILES["profile-image"]["name"];
		$size = $_FILES["profile-image"]["size"];
		$type = $_FILES["profile-image"]["type"];

		if ($profesor = $profesorMetodos->Buscar($id)) {
			$fotoRuta = $profesor->getFotoPerfil();
			if ($name != null) {
				$fotoRuta = $FotoPerfilControlador->GenerarFotoPerfil($name, $tmp_path, $size, $type);
				$FotoPerfilControlador->EliminarFotoPerfil($profesor->getFotoPerfil());
			}

			$profesor->setFotoPerfil($fotoRuta);
			$profesor->setId($id);
			$profesor->setNombre($nombre);
			$profesor->setPrimerApellido($primerApellido);
			$profesor->setSegundoApellido($segundoApellido);
			$profesor->setCedula($cedula);

			if (filter_var($correo, FILTER_VALIDATE_EMAIL))
				$profesor->setCorreo($correo);

			if ($contrasena != null) {
				$contrasenaModificarCifrada = password_hash($contrasena, PASSWORD_DEFAULT);
				$profesor->setContrasena($contrasenaModificarCifrada);
				$profesorMetodos->ModificarContrasena($contrasenaModificarCifrada, $id);
			}

			$profesor->setEstado($estado);

			if ($profesor->getNombre() != null && $profesor->getPrimerApellido() != null && $profesor->getSegundoApellido() != null && $profesor->getCedula() != null && $profesor->getComidas() != null && $profesor->getCorreo() != null && $profesor->getFotoPerfil() != null) {
				if ($profesorMetodos->Modificar($profesor)) {
					header('Location: ./?dir=admin&controlador=Profesor&accion=Index&id=main&alerta=success');
				} else {
					$FotoPerfilControlador->EliminarFotoPerfil($profesor->getFotoPerfil());
					header('Location: ./?dir=admin&controlador=Profesor&accion=Index&id=main&alerta=error');
				}
			} else {
				$FotoPerfilControlador->EliminarFotoPerfil($profesor->getFotoPerfil());
				header('Location: ./?dir=admin&controlador=Profesor&accion=Index&id=main&alerta=error');
			}
		} else header('Location: ./?dir=admin&controlador=Profesor&accion=Index&id=main&alerta=error');
	}

	public function ModificarContrasena()
	{
		$id = $_POST['idModificar'];
		$contrasena = $_POST['contrasenaModificar'];

		$profesor = new Profesor();
		$profesorMetodos = new ProfesorMetodos();

		if ($id != null && $contrasena != null) {
			if ($profesor = $profesorMetodos->Buscar($id)) {
				$contrasenaModificarCifrada = password_hash($contrasena, PASSWORD_DEFAULT);
				$profesor->setContrasena($contrasenaModificarCifrada);
				$profesorMetodos->ModificarContrasena($contrasenaModificarCifrada, $id);
				header('Location: ./?controlador=Index&accion=MiCuenta&alerta=success');
			} else
				header('Location: ./?controlador=Index&accion=MiCuenta&alerta=error');
		} else
			header('Location: ./?controlador=Index&accion=MiCuenta&alerta=error');
	}

	public function CambiarEstado($estado)
	{
		if (!isset($_REQUEST['idsArr'])) {
			header('Location: ./?dir=admin&controlador=Profesor&accion=Index&id=main');
		} else {
			$arrayIds = $_REQUEST['idsArr'];
			$lengthArray = $_REQUEST['lengthArray'];
			$profesorMetodos = new ProfesorMetodos();
			$volver = false;

			for ($i = 0; $i < $lengthArray; $i++) {
				$profesor = new Profesor();
				$profesor = $profesorMetodos->Buscar($arrayIds[$i]);
				$profesor->setEstado($estado);
				if ($profesorMetodos->Modificar($profesor)) {
					$volver = true;
				}
			}

			if ($estado == 0) {
				if ($volver) {
					header('Location: ./?dir=admin&controlador=Profesor&accion=Index&id=main&alerta=success');
				} else {
					header('Location: ./?dir=admin&controlador=Profesor&accion=Index&id=main&alerta=error');
				}
			} else {
				if ($volver) {
					header('Location: ./?dir=admin&controlador=Profesor&accion=Index&id=main&estados=0&alerta=success');
				} else {
					header('Location: ./?dir=admin&controlador=Profesor&accion=Index&id=main&estados=0&alerta=error');
				}
			}
		}
	}
}
