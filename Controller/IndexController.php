<?php
session_start();
//Active session time in minutes
//session_cache_expire(460);
require_once './Controller/helpers/EmailController.php';
require_once './Controller/helpers/PasswordController.php';

require_once "./Model/Connection.php";
require_once "./Model/Methods/StudentMethods.php";
require_once "./Model/Entities/Student.php";
require_once "./Model/Methods/TeacherMethods.php";
require_once "./Model/Entities/Teacher.php";
require_once "./Model/Methods/StaffMethods.php";
require_once "./Model/Entities/Staff.php";
require_once './Model/Methods/SectionMethods.php';
require_once './Model/Methods/SpecialtyMethods.php';
require_once './Model/Entities/Section.php';
require_once './Model/Entities/Specialty.php';

class IndexController
{
	public function Index()
	{
		require_once "./View/views/Login.php";
	}

	public function ResetPasswordView()
	{
		require_once "./View/views/ResetPassword.php";
	}

	public function ResetPassword()
	{
		$emailTemplate = new EmailController();
		$passwordGenerator = new PasswordController();
		$studentMethods = new StudentMethods();
		$teacherMethods = new TeacherMethods();
		$staffMethods = new StaffMethods();
		$staff = new Staff();
		$student = new Student();
		$teacher = new Teacher();
		$email = $_POST["correo"];
		$subject = "Recuperación de Contraseña";
		$status = true;

		//Random password generation
		$password = $passwordGenerator->RandomPassword();
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

		$staff = $staffMethods->FindByEmail($email);
		if ($staff != null && $staff->getStatus() == 1) {
			if ($staffMethods->UpdatePassword($hashedPassword, $staff->getId()))
				$emailTemplate->IndividualEmail($staff->getEmail(), $subject, $password, $staff->getName(), $staff->getFirstLastName());
		} else {
			$teacher = $teacherMethods->FindByEmail($email);
			if ($teacher != null && $teacher->getStatus() == 1) {
				if ($teacherMethods->UpdatePassword($hashedPassword, $teacher->getId()))
					$emailTemplate->IndividualEmail($teacher->getEmail(), $subject, $password, $teacher->getName(), $teacher->getFirstLastName());
			} else {
				$student = $studentMethods->FindByEmail($email);
				if ($student != null && $student->getStatus() == 1) {
					if ($studentMethods->UpdatePassword($hashedPassword, $student->getId()))
						$emailTemplate->IndividualEmail($student->getEmail(), $subject, $password, $student->getName(), $student->getFirstLastName());
				} else
					$status = false;
			}
		}

		if ($status)
			header("Location: ./?alerta=success");
		else
			header("Location: ./?alerta=errorRestablecerContra");
	}

	public function Logout()
	{
		session_destroy();
		header("Location: ./");
	}

	public function MyAccount()
	{
		require_once("./View/views/MyAccount.php");
	}

	public function Login()
	{
		$staffMethods = new StaffMethods();
		$studentMethods = new StudentMethods();
		$teacherMethods = new TeacherMethods();
		$_SESSION['perfiles'] = null;

		if (isset($_POST['correo']) && isset($_POST['contrasena'])) {
			$email = $_POST['correo'];
			$password = $_POST['contrasena'];

			$allStaff = $staffMethods->FindAll();
			if (isset($allStaff)) {
				foreach ($allStaff as $f) {
					if ($f->getEmail() == $email && password_verify($password, $f->getPassword()) && $f->getStatus() == 1) {
						$_SESSION['usuario'] = array(
							'Nombre' => $f->getName(),
							'Id' => $f->getId(),
							'PrimerApellido' => $f->getFirstLastName(),
							'SegundoApellido' => $f->getSecondLastName(),
							'Correo' => $f->getEmail()
						);
						if ($f->getProfile() == 1) {
							$_SESSION['usuario'] += array('Perfil' => 'Administrador');
							$_SESSION['perfiles']  = 'admin';
							header('Location: ?dir=admin&controller=AdminStatistics&action=Index');
						} else if ($f->getProfile() == 2) {
							$_SESSION['usuario'] += array('Perfil' => 'Cobrador');
							$_SESSION['perfiles']  = 'billing';
							header('Location: ./?dir=billing&controller=StudentBilling&action=Index');
						}
					}
				}
			}

			$allTeachers = $teacherMethods->FindAll();
			if (isset($allTeachers)) {
				foreach ($allTeachers as $p) {
					if ($p->getEmail() == $email && password_verify($password, $p->getPassword()) && $p->getStatus() == 1) {
						$_SESSION['usuario'] = array(
							'Nombre' => $p->getName(),
							'Id' => $p->getId(),
							'PrimerApellido' => $p->getFirstLastName(),
							'SegundoApellido' => $p->getSecondLastName(),
							'Correo' => $p->getEmail(),
							'Foto' => $p->getProfilePhoto()
						);
						$_SESSION['usuario'] += array('Perfil' => 'Profesor', 'Comidas' => $p->getMeals(), 'Cedula' => $p->getIdCard());
						$_SESSION['perfiles']  = 'client';
						$teacherId = $_SESSION['usuario']['Id'];
						header('Location: ./?dir=client&controller=ClientHome&action=Index&id=' . $teacherId . '&perfil=Profesor');
					}
				}
			}

			$allStudents = $studentMethods->FindAll();
			if (isset($allStudents)) {
				foreach ($allStudents as $e) {
					if ($e->getEmail() == $email && password_verify($password, $e->getPassword()) && $e->getStatus() == 1) {
						$section = new Section();
						$specialty = new Specialty();
						$specialtyMethods = new SpecialtyMethods();
						$sectionMethods = new SectionMethods();

						$section = $sectionMethods->Find($e->getSectionId());
						$specialty = $specialtyMethods->Find($e->getSpecialtyId());
						$_SESSION['usuario'] = array(
							'Nombre' => $e->getName(),
							'Id' => $e->getId(),
							'PrimerApellido' => $e->getFirstLastName(),
							'SegundoApellido' => $e->getSecondLastName(),
							'Correo' => $e->getEmail()
						);
						$_SESSION['usuario'] += array(
							'Perfil' => 'Estudiante',
							'Becado' => $e->getScholarship(),
							'Comidas' => $e->getMeals(),
							'Cedula' => $e->getIdCard(),
							'Foto' => $e->getProfilePhoto(),
							'Especialidad' => $specialty->getDescription(),
							'Seccion' => $section->getDescription()
						);
						$_SESSION['perfiles']  = 'client';
						$studentId = $_SESSION['usuario']['Id'];
						header('Location: ./?dir=client&controller=ClientHome&action=Index&id=' . $studentId . '&perfil=Estudiante');
					}
				}
			}
			if ($_SESSION['perfiles'] == null)
				header('Location: ./?alerta=error');
		} else
			header('Location: ./?alerta=error');
	}
}