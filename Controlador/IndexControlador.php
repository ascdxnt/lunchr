<?php
session_start();
//Tiempo activo de la sesion en minutos
//session_cache_expire(460);
require_once './Controlador/funciones/CorreoControlador.php';
require_once './Controlador/funciones/ContrasenaControlador.php';

require_once "./Modelo/Conexion.php";
require_once "./Modelo/Metodos/EstudianteMetodos.php";
require_once "./Modelo/Entidades/Estudiante.php";
require_once "./Modelo/Metodos/ProfesorMetodos.php";
require_once "./Modelo/Entidades/Profesor.php";
require_once "./Modelo/Metodos/FuncionarioMetodos.php";
require_once "./Modelo/Entidades/Funcionario.php";
require_once './Modelo/Metodos/SeccionMetodos.php';
require_once './Modelo/Metodos/EspecialidadMetodos.php';
require_once './Modelo/Entidades/Seccion.php';
require_once './Modelo/Entidades/Especialidad.php';

//modelo
class IndexControlador
{
	public function Index()
	{
		require_once "./Vista/views/Login.php";
	}

	public function RestablecerContrasenaVista()
	{
		require_once "./Vista/views/RestablecerContrasena.php";
	}

	public function RestablecerContrasena()
	{
		$plantillaCorreo = new CorreoControlador();
		$generadorContrasenas = new ContrasenaControlador();
		$estudianteMetodos = new EstudianteMetodos();
		$profesorMetodos = new ProfesorMetodos();
		$funcionarioMetodos = new FuncionarioMetodos();
		$funcionario = new Funcionario();
		$estudiante = new Estudiante();
		$profesor = new Profesor();
		$correo = $_POST["correo"];
		$titulo = "Recuperación de Contraseña";
		$estado = true;

		//Generación de contraseña aleatoria
		$contrasena = $generadorContrasenas->ContrasenaAleatoria();
		$contrasenaEncriptada = password_hash($contrasena, PASSWORD_DEFAULT);

		$funcionario = $funcionarioMetodos->BuscarPorCorreo($correo);
		if ($funcionario != null && $funcionario->getEstado() == 1) {
			if ($funcionarioMetodos->ModificarContrasena($contrasenaEncriptada, $funcionario->getId()))
				$plantillaCorreo->CorreoIndividual($funcionario->getCorreo(), $titulo, $contrasena, $funcionario->getNombre(), $funcionario->getPrimerApellido());
		} else {
			$profesor = $profesorMetodos->BuscarPorCorreo($correo);
			if ($profesor != null && $profesor->getEstado() == 1) {
				if ($profesorMetodos->ModificarContrasena($contrasenaEncriptada, $profesor->getId()))
					$plantillaCorreo->CorreoIndividual($profesor->getCorreo(), $titulo, $contrasena, $profesor->getNombre(), $profesor->getPrimerApellido());
			} else {
				$estudiante = $estudianteMetodos->BuscarPorCorreo($correo);
				if ($estudiante != null && $estudiante->getEstado() == 1) {
					if ($estudianteMetodos->ModificarContrasena($contrasenaEncriptada, $estudiante->getId()))
						$plantillaCorreo->CorreoIndividual($estudiante->getCorreo(), $titulo, $contrasena, $estudiante->getNombre(), $estudiante->getPrimerApellido());
				} else
					$estado = false;
			}
		}

		if ($estado)
			header("Location: ./?alerta=success");
		else
			header("Location: ./?alerta=errorRestablecerContra");
	}

	public function Logout()
	{
		session_destroy();
		header("Location: ./");
	}

	public function MiCuenta()
	{
		require_once("./Vista/views/MiCuenta.php");
	}

	public function Login()
	{
		$funcionarioMetodos = new FuncionarioMetodos();
		$estudianteMetodos = new EstudianteMetodos();
		$profesorMetodos = new ProfesorMetodos();
		$_SESSION['perfiles'] = null;

		if (isset($_POST['correo']) && isset($_POST['contrasena'])) {
			$correo = $_POST['correo'];
			$contrasena = $_POST['contrasena'];

			$todosFuncionarios = $funcionarioMetodos->BuscarTodos();
			if (isset($todosFuncionarios)) {
				foreach ($todosFuncionarios as $f) {
					if ($f->getCorreo() == $correo && password_verify($contrasena, $f->getContrasena()) && $f->getEstado() == 1) {
						$_SESSION['usuario'] = array(
							'Nombre' => $f->getNombre(),
							'Id' => $f->getId(),
							'PrimerApellido' => $f->getPrimerApellido(),
							'SegundoApellido' => $f->getSegundoApellido(),
							'Correo' => $f->getCorreo()
						);
						if ($f->getPerfil() == 1) {
							$_SESSION['usuario'] += array('Perfil' => 'Administrador');
							$_SESSION['perfiles']  = 'admin';
							header('Location: ?dir=admin&controlador=EstadisticasAdmin&accion=Index');
							//admin = 1 && cobros = 2
						} else if ($f->getPerfil() == 2) {
							$_SESSION['usuario'] += array('Perfil' => 'Cobrador');
							$_SESSION['perfiles']  = 'cobros';
							header('Location: ./?dir=cobros&controlador=EstudianteCobros&accion=Index');
						}
					}
				}
			}

			$todosProfesores = $profesorMetodos->BuscarTodos();
			if (isset($todosProfesores)) {
				foreach ($todosProfesores as $p) {
					if ($p->getCorreo() == $correo && password_verify($contrasena, $p->getContrasena()) && $p->getEstado() == 1) {
						$_SESSION['usuario'] = array(
							'Nombre' => $p->getNombre(),
							'Id' => $p->getId(),
							'PrimerApellido' => $p->getPrimerApellido(),
							'SegundoApellido' => $p->getSegundoApellido(),
							'Correo' => $p->getCorreo(),
							'Foto' => $p->getFotoPerfil()
						);
						$_SESSION['usuario'] += array('Perfil' => 'Profesor', 'Comidas' => $p->getComidas(), 'Cedula' => $p->getCedula());
						$_SESSION['perfiles']  = 'cliente';
						$idProfesor = $_SESSION['usuario']['Id'];
						header('Location: ./?dir=cliente&controlador=ClienteInicio&accion=Index&id=' . $idProfesor . '&perfil=Profesor');
					}
				}
			}

			$todosEstudiantes = $estudianteMetodos->BuscarTodos();
			if (isset($todosEstudiantes)) {
				foreach ($todosEstudiantes as $e) {
					if ($e->getCorreo() == $correo && password_verify($contrasena, $e->getContrasena()) && $e->getEstado() == 1) {
						$seccion = new Seccion();
						$especialidad = new Especialidad();
						$especialidadMetodos = new EspecialidadMetodos();
						$seccionMetodos = new SeccionMetodos();

						$seccion = $seccionMetodos->Buscar($e->getIdSeccion());
						$especialidad = $especialidadMetodos->Buscar($e->getIdEspecialidad());
						$_SESSION['usuario'] = array(
							'Nombre' => $e->getNombre(),
							'Id' => $e->getId(),
							'PrimerApellido' => $e->getPrimerApellido(),
							'SegundoApellido' => $e->getSegundoApellido(),
							'Correo' => $e->getCorreo()
						);
						$_SESSION['usuario'] += array(
							'Perfil' => 'Estudiante',
							'Becado' => $e->getBecado(),
							'Comidas' => $e->getComidas(),
							'Cedula' => $e->getCedula(),
							'Foto' => $e->getFotoPerfil(),
							'Especialidad' => $especialidad->getDescripcion(),
							'Seccion' => $seccion->getDescripcion()
						);
						$_SESSION['perfiles']  = 'cliente';
						$idEstudiante = $_SESSION['usuario']['Id'];
						header('Location: ./?dir=cliente&controlador=ClienteInicio&accion=Index&id=' . $idEstudiante . '&perfil=Estudiante');
					}
				}
			}
			if ($_SESSION['perfiles'] == null)
				header('Location: ./?alerta=error');
		} else
			header('Location: ./?alerta=error');
	}
}
