<?php
require_once './Modelo/Conexion.php';
require_once './Modelo/Entidades/Estudiante.php';
require_once './Modelo/Metodos/EstudianteMetodos.php';
require_once './Controlador/funciones/CorreoControlador.php';
require_once './Controlador/funciones/ContrasenaControlador.php';

class EstudianteControlador
{
	public function Index($vista)
	{
		require_once './Modelo/Metodos/EspecialidadMetodos.php';
		require_once './Modelo/Entidades/Especialidad.php';
		require_once './Modelo/Metodos/SeccionMetodos.php';
		require_once './Modelo/Entidades/Seccion.php';

		$especialidadMetodos = new EspecialidadMetodos();
		$todosEspecialidad = $especialidadMetodos->BuscarTodos();
		$seccionMetodos = new SeccionMetodos();
		$todosSeccion = $seccionMetodos->BuscarTodos();
		if ($vista == "main") {
			$estudianteMetodos = new EstudianteMetodos();
			$todosEstudiantes = $estudianteMetodos->BuscarTodos();

			if ($todosEstudiantes != null) {
				$estudiantes = array();
				for ($i = 0; $i < sizeof($todosEstudiantes); $i++) {
					$seccion = new Seccion();
					$seccion = $seccionMetodos->Buscar($todosEstudiantes[$i]->getIdSeccion());
					$especialidad = new Especialidad();
					$especialidad = $especialidadMetodos->Buscar($todosEstudiantes[$i]->getIdEspecialidad());

					$estudiantes[$i] = array("id" => $todosEstudiantes[$i]->getId());
					$estudiantes[$i] += array("nombre" => $todosEstudiantes[$i]->getNombre());
					$estudiantes[$i] += array("apellido1" => $todosEstudiantes[$i]->getPrimerApellido());
					$estudiantes[$i] += array("apellido2" => $todosEstudiantes[$i]->getSegundoApellido());
					$estudiantes[$i] += array("cedula" => $todosEstudiantes[$i]->getCedula());
					$estudiantes[$i] += array("comidas" => $todosEstudiantes[$i]->getComidas());
					$estudiantes[$i] += array("especialidad" => $especialidad->getDescripcion());
					$estudiantes[$i] += array("seccion" => $seccion->getDescripcion());
					$estudiantes[$i] += array("correo" => $todosEstudiantes[$i]->getCorreo());
					$estudiantes[$i] += array("becado" => $todosEstudiantes[$i]->getBecado());
					$estudiantes[$i] += array("estado" => $todosEstudiantes[$i]->getEstado());
					$estudiantes[$i] += array("fotoPerfil" => $todosEstudiantes[$i]->getFotoPerfil());
				}
			}

			if (isset($estudiantes))
				$estudiantes = json_encode($estudiantes);
			else
				$estudiantes = null;

			require_once "./Vista/views/admin/Estudiantes.php";
		} else if ($vista == "crear")
			require_once "./Vista/views/admin/EstudiantesCrear.php";
		else if ($vista == "modificar")
			require_once "./Vista/views/admin/EstudiantesModificar.php";
	}

	public function Crear()
	{
		require_once './Controlador/funciones/FotoPerfilControlador.php';
		$FotoPerfilControlador = new FotoPerfilControlador();

		$plantillaCorreo = new CorreoControlador();
		$generadorContrasenas = new ContrasenaControlador();
		$estudianteMetodos = new EstudianteMetodos();
		$estudiante = new Estudiante();

		$tmp_path = $_FILES["profile-image"]["tmp_name"];
		$name = $_FILES["profile-image"]["name"];
		$size = $_FILES["profile-image"]["size"];
		$type = $_FILES["profile-image"]["type"];

		$fotoRuta = $FotoPerfilControlador->GenerarFotoPerfil($name, $tmp_path, $size, $type);

		if (!$fotoRuta) {
			header('Location: ./?dir=admin&controlador=Estudiante&accion=Index&id=crear&alerta=error');
			return;
		}

		//Declaración de atributos
		$nombre = $_POST['nombre'];
		$primerApellido = $_POST['primerApellido'];
		$segundoApellido = $_POST['segundoApellido'];
		$cedula = $_POST['cedula'];
		$idEspecialidad = $_POST['idEspecialidad'];
		$idSeccion = $_POST['idSeccion'];
		$correo = $_POST['correo'];
		$becado = $_POST['becado'];

		//Generación de contraseña aleatoria
		$contrasena = $generadorContrasenas->ContrasenaAleatoria();
		$contrasenaEncriptada = password_hash($contrasena, PASSWORD_DEFAULT);

		$estudiante->setNombre($nombre);
		$estudiante->setPrimerApellido($primerApellido);
		$estudiante->setSegundoApellido($segundoApellido);
		$estudiante->setCedula($cedula);
		$estudiante->setComidas(0);
		$estudiante->setIdEspecialidad($idEspecialidad);
		$estudiante->setIdSeccion($idSeccion);
		if (filter_var($correo, FILTER_VALIDATE_EMAIL))
			$estudiante->setCorreo($correo);
		$estudiante->setContrasena($contrasenaEncriptada);
		$estudiante->setEstado(1);
		$estudiante->setBecado($becado); //1 = activo
		$estudiante->setPerfil(3);
		$estudiante->setFotoPerfil($fotoRuta);

		if ($estudiante->getNombre() != null && $estudiante->getPrimerApellido() != null &&  $estudiante->getSegundoApellido() != null && $estudiante->getCedula() != null && $estudiante->getContrasena() != null && $estudiante->getCorreo() != null && $estudiante->getIdEspecialidad() != null && $estudiante->getIdSeccion() != null && $estudiante->getBecado() != null && $estudiante->getFotoPerfil() != null) {
			if ($estudianteMetodos->Crear($estudiante)) {
				$titulo = "Cuenta de Comedor";
				$plantillaCorreo->CorreoIndividual($estudiante->getCorreo(), $titulo, $contrasena, $estudiante->getNombre(), $estudiante->getPrimerApellido());
				header('Location: ./?dir=admin&controlador=Estudiante&accion=Index&id=main&alerta=success');
			} else {
				$FotoPerfilControlador->EliminarFotoPerfil($estudiante->getFotoPerfil());
				header('Location: ./?dir=admin&controlador=Estudiante&accion=Index&id=main&alerta=error');
			}
		} else {
			$FotoPerfilControlador->EliminarFotoPerfil($estudiante->getFotoPerfil());
			header('Location: ./?dir=admin&controlador=Estudiante&accion=Index&id=main&alerta=error');
		}
	}

	public function CrearPorJSON()
	{
		require_once './Modelo/Metodos/SeccionMetodos.php';
		require_once './Modelo/Entidades/Seccion.php';
		require_once './Modelo/Entidades/Especialidad.php';
		require_once './Modelo/Metodos/EspecialidadMetodos.php';
		$plantillaCorreo = new CorreoControlador();
		$generadorContrasenas = new ContrasenaControlador();
		$estudianteMetodos = new EstudianteMetodos();
		$seccionMetodos = new SeccionMetodos();
		$especialidadMetodos = new EspecialidadMetodos();
		$seccion = new Seccion();
		$especialidad = new Especialidad();

		$objectArray = json_decode(json_decode($_POST['PostJson']));
		$estado = true;

		//Itera sobre cada entidad o registro
		for ($i = 0; $i < count($objectArray); $i++) {
			$estudiante = new Estudiante();
			$cancelar = false;

			//Rellena los atributos de la entidad
			$count = 0;
			foreach ($objectArray[$i] as $clave => $valor) {
				if ($count == 0) $estudiante->setNombre($valor);
				else if ($count == 1) $estudiante->setPrimerApellido($valor);
				else if ($count == 2) $estudiante->setSegundoApellido($valor);
				else if ($count == 3) $estudiante->setCedula($valor);
				else if ($count == 4) {
					if (filter_var($valor, FILTER_VALIDATE_EMAIL)) {
						$estudiante->setCorreo($valor);
					} else $cancelar = true;
				} else if ($count == 5) {
					if (!$especialidad = $especialidadMetodos->BuscarPorDescripcion($valor)) {
						$cancelar = true;
					} else $estudiante->setIdEspecialidad($especialidad->getId());
				} else if ($count == 6) {
					if (!$seccion = $seccionMetodos->BuscarPorDescripcion($valor)) {
						$cancelar = true;
					} else $estudiante->setIdSeccion($seccion->getId());
				} else if ($count == 7) {
					if ($valor == "Subvencionada") $estudiante->setBecado(0);
					else if ($valor == "Completa") $estudiante->setBecado(1);
					else $cancelar = true;
				};
				$count++;
			}

			if ($cancelar) continue;

			//Generación de contraseña aleatoria
			$contrasena = $generadorContrasenas->ContrasenaAleatoria();
			$contrasenaEncriptada = password_hash($contrasena, PASSWORD_DEFAULT);

			$estudiante->setComidas(0);
			$estudiante->setContrasena($contrasenaEncriptada);
			$estudiante->setEstado(1);
			$estudiante->setPerfil(3);
			$estudiante->setFotoPerfil('./Vista/assets/profile/default.jpg');

			if ($estudiante->getNombre() != null && $estudiante->getPrimerApellido() != null &&  $estudiante->getSegundoApellido() != null && $estudiante->getCedula() != null && $estudiante->getIdSeccion() != null && $estudiante->getIdEspecialidad() != null && $estudiante->getCorreo() != null && $estudiante->getFotoPerfil() != null) {
				if (!$seccionMetodos->Buscar($estudiante->getIdSeccion()) || !$especialidadMetodos->Buscar($estudiante->getIdEspecialidad()))
					$estado = false;
				else if ($estudianteMetodos->Crear($estudiante)) {
					$titulo = "Cuenta de Comedor";
					$plantillaCorreo->CorreoIndividual($estudiante->getCorreo(), $titulo, $contrasena, $estudiante->getNombre(), $estudiante->getPrimerApellido());
				} else
					$estado = false;
			} else
				$estado = false;
		}

		if ($estado)
			header("Location: ./?dir=admin&controlador=Estudiante&accion=Index&id=main&alerta=success");
		else
			header("Location: ./?dir=admin&controlador=Estudiante&accion=Index&id=main&alerta=warning");
	}

	public function Modificar()
	{
		require_once './Controlador/funciones/FotoPerfilControlador.php';
		$FotoPerfilControlador = new FotoPerfilControlador();

		$estudiante = new Estudiante();
		$estudianteMetodos = new EstudianteMetodos();

		$id = $_POST['idModificar'];
		$nombre = $_POST['nombreModificar'];
		$primerApellido = $_POST['primerApellidoModificar'];
		$segundoApellido = $_POST['segundoApellidoModificar'];
		$cedula = $_POST['cedulaModificar'];
		$idEspecialidad = $_POST['especialidadModificar'];
		$idSeccion = $_POST['seccionModificar'];
		$correo = $_POST['correoModificar'];
		$contrasena = $_POST['contrasenaModificar'];
		$estado = $_POST['estadoModificar'];
		$becado = $_POST['becadoModificar'];

		$tmp_path = $_FILES["profile-image"]["tmp_name"];
		$name = $_FILES["profile-image"]["name"];
		$size = $_FILES["profile-image"]["size"];
		$type = $_FILES["profile-image"]["type"];

		if ($estudiante = $estudianteMetodos->Buscar($id)) {
			$fotoRuta = $estudiante->getFotoPerfil();
			if ($name != null) {
				$fotoRuta = $FotoPerfilControlador->GenerarFotoPerfil($name, $tmp_path, $size, $type);
				$FotoPerfilControlador->EliminarFotoPerfil($estudiante->getFotoPerfil());
			}

			$estudiante->setFotoPerfil($fotoRuta);
			$estudiante->setNombre($nombre);
			$estudiante->setPrimerApellido($primerApellido);
			$estudiante->setSegundoApellido($segundoApellido);
			$estudiante->setCedula($cedula);
			$estudiante->setIdEspecialidad($idEspecialidad);
			$estudiante->setIdSeccion($idSeccion);
			if (filter_var($correo, FILTER_VALIDATE_EMAIL))
				$estudiante->setCorreo($correo);
			if ($contrasena != null) {
				$contrasenaModificarCifrada = password_hash($contrasena, PASSWORD_DEFAULT);
				$estudiante->setContrasena($contrasenaModificarCifrada);
				$estudianteMetodos->ModificarContrasena($contrasenaModificarCifrada, $id);
			}
			$estudiante->setEstado($estado);
			$estudiante->setBecado($becado);

			if ($estudiante->getNombre() != null && $estudiante->getPrimerApellido() != null &&  $estudiante->getSegundoApellido() != null && $estudiante->getCedula() != null && $estudiante->getCorreo() != null && $estudiante->getIdEspecialidad() != null && $estudiante->getIdSeccion() != null && $estudiante->getBecado() != null && $estudiante->getFotoPerfil() != null) {
				if ($estudianteMetodos->Modificar($estudiante))
					header('Location: ./?dir=admin&controlador=Estudiante&accion=Index&id=main&alerta=success');
				else {
					$FotoPerfilControlador->EliminarFotoPerfil($estudiante->getFotoPerfil());
					header('Location: ./?dir=admin&controlador=Estudiante&accion=Index&id=main&alerta=error');
				}
			} else {
				$FotoPerfilControlador->EliminarFotoPerfil($estudiante->getFotoPerfil());
				header('Location: ./?dir=admin&controlador=Estudiante&accion=Index&id=main&alerta=error');
			}
		} else header('Location: ./?dir=admin&controlador=Estudiante&accion=Index&id=main&alerta=error');
	}

	public function ModificarContrasena()
	{
		$id = $_POST['idModificar'];
		$contrasena = $_POST['contrasenaModificar'];

		$estudiante = new Estudiante();
		$estudianteMetodos = new EstudianteMetodos();

		if ($id != null && $contrasena != null) {
			if ($estudiante = $estudianteMetodos->Buscar($id)) {
				$contrasenaModificarCifrada = password_hash($contrasena, PASSWORD_DEFAULT);
				$estudiante->setContrasena($contrasenaModificarCifrada);
				$estudianteMetodos->ModificarContrasena($contrasenaModificarCifrada, $id);
				header('Location: ./?controlador=Index&accion=MiCuenta&alerta=success');
			} else
				header('Location: ./?controlador=Index&accion=MiCuenta&alerta=error');
		} else
			header('Location: ./?controlador=Index&accion=MiCuenta&alerta=error');
	}

	public function CambiarEstado($estado)
	{
		$nuevoEstado = $estado;
		if (!isset($_REQUEST['idsArr']))
			header('Location: ./?dir=admin&controlador=Estudiante&accion=Index&id=main');
		else {
			$arrayIds = $_REQUEST['idsArr'];
			$lengthArray = $_REQUEST['lengthArray'];
			$estudianteMetodos = new EstudianteMetodos();
			$volver = false;

			//Recorre los estudiantes que han sido seleccionados para cambiarles el estado
			for ($i = 0; $i < $lengthArray; $i++) {
				$estudiante = new Estudiante();
				$estudiante = $estudianteMetodos->Buscar($arrayIds[$i]);
				$estudiante->setEstado($nuevoEstado);
				if ($estudianteMetodos->Modificar($estudiante)) {
					$volver = true;
				}
			}

			if ($nuevoEstado == 0) {
				if ($volver)
					header('Location: ./?dir=admin&controlador=Estudiante&accion=Index&id=main&alerta=success');
				else
					header('Location: ./?dir=admin&controlador=Estudiante&accion=Index&id=main&alerta=error');
			} else {
				if ($volver)
					header('Location: ./?dir=admin&controlador=Estudiante&accion=Index&id=main&estados=0&alerta=success');
				else
					header('Location: ./?dir=admin&controlador=Estudiante&accion=Index&id=main&estados=0&alerta=error');
			}
		}
	}
}
