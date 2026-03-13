<?php
require_once './Model/Connection.php';
require_once './Model/Entities/Teacher.php';
require_once './Model/Methods/TeacherMethods.php';
require_once './Controller/helpers/EmailController.php';
require_once './Controller/helpers/PasswordController.php';

class TeacherController
{
	public function Index($view)
	{
		if ($view == "main") {
			$teacherMethods = new TeacherMethods();
			$allTeachers = $teacherMethods->FindAll();

			if ($allTeachers != null) {
				$teachers = array();
				for ($i = 0; $i < sizeof($allTeachers); $i++) {
					$teachers[$i] = array("id" => $allTeachers[$i]->getId());
					$teachers[$i] += array("nombre" => $allTeachers[$i]->getName());
					$teachers[$i] += array("apellido1" => $allTeachers[$i]->getFirstLastName());
					$teachers[$i] += array("apellido2" => $allTeachers[$i]->getSecondLastName());
					$teachers[$i] += array("cedula" => $allTeachers[$i]->getIdCard());
					$teachers[$i] += array("correo" => $allTeachers[$i]->getEmail());
					$teachers[$i] += array("comidas" => $allTeachers[$i]->getMeals());
					$teachers[$i] += array("estado" => $allTeachers[$i]->getStatus());
					$teachers[$i] += array("fotoPerfil" => $allTeachers[$i]->getProfilePhoto());
				}
			}

			if (isset($teachers))
				$teachers = json_encode($teachers);
			else
				$teachers = null;

			require_once "./View/views/admin/Teachers.php";
		} else if ($view == "crear")
			require_once "./View/views/admin/TeachersCreate.php";
		else if ($view == "modificar")
			require_once "./View/views/admin/TeachersEdit.php";
	}

	public function Create()
	{
		require_once './Controller/helpers/ProfilePhotoController.php';
		$profilePhotoController = new ProfilePhotoController();

		$teacherMethods = new TeacherMethods();
		$teacher = new Teacher();
		$emailTemplate = new EmailController();
		$passwordGenerator = new PasswordController();

		$firstName = $_POST['nombre'];
		$firstLastName = $_POST['primerApellido'];
		$secondLastName = $_POST['segundoApellido'];
		$idCard = $_POST['cedula'];
		$email = $_POST['correo'];

		$tmp_path = $_FILES["profile-image"]["tmp_name"];
		$name = $_FILES["profile-image"]["name"];
		$size = $_FILES["profile-image"]["size"];
		$type = $_FILES["profile-image"]["type"];

		$photoPath = $profilePhotoController->GenerateProfilePhoto($name, $tmp_path, $size, $type);

		if (!$photoPath) {
			header('Location: ./?dir=admin&controller=Teacher&action=Index&id=crear&alerta=error');
			return;
		}

		//Random password generation
		$password = $passwordGenerator->RandomPassword();
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

		$teacher->setName($firstName);
		$teacher->setFirstLastName($firstLastName);
		$teacher->setSecondLastName($secondLastName);
		$teacher->setIdCard($idCard);
		$teacher->setPassword($hashedPassword);
		$teacher->setProfilePhoto($photoPath);
		$teacher->setProfile(3);
		$teacher->setStatus(1);
		$teacher->setMeals(0);

		//Email validation
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
			$teacher->setEmail($email);

		if ($teacher->getName() != null && $teacher->getFirstLastName() != null && $teacher->getSecondLastName() != null && $teacher->getIdCard() != null && $teacher->getPassword() != null && $teacher->getEmail() != null && $teacher->getProfilePhoto() != null) {
			if ($teacherMethods->Create($teacher)) {
				$subject = "Cuenta de Comedor";
				$emailTemplate->IndividualEmail($teacher->getEmail(), $subject, $password, $teacher->getName(), $teacher->getFirstLastName());
				header('Location: ./?dir=admin&controller=Teacher&action=Index&id=main&alerta=success');
			} else {
				$profilePhotoController->DeleteProfilePhoto($teacher->getProfilePhoto());
				header('Location: ./?dir=admin&controller=Teacher&action=Index&id=main&alerta=error');
			}
		} else {
			$profilePhotoController->DeleteProfilePhoto($teacher->getProfilePhoto());
			header('Location: ./?dir=admin&controller=Teacher&action=Index&id=main&alerta=error');
		}
	}

	public function CreateFromJSON()
	{
		$emailTemplate = new EmailController();
		$passwordGenerator = new PasswordController();
		$teacherMethods = new TeacherMethods();
		$objectArray = json_decode(json_decode($_POST['PostJson']));
		$status = true;

		for ($i = 0; $i < count($objectArray); $i++) {
			$teacher = new Teacher();

			//Initialize most teacher attributes
			$count = 0;
			foreach ($objectArray[$i] as $key => $value) {
				if ($count == 0) $teacher->setName($value);
				else if ($count == 1) $teacher->setFirstLastName($value);
				else if ($count == 2) $teacher->setSecondLastName($value);
				else if ($count == 3) $teacher->setIdCard($value);
				else if ($count == 4) {
					if (filter_var($value, FILTER_VALIDATE_EMAIL))
						$teacher->setEmail($value);
				}
				$count++;
			}

			//Random password generation
			$password = $passwordGenerator->RandomPassword();
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

			$teacher->setMeals(0);
			$teacher->setPassword($hashedPassword);
			$teacher->setStatus(1);
			$teacher->setProfile(3);
			$teacher->setProfilePhoto('./View/assets/profile/default.jpg');

			if ($teacher->getName() != null && $teacher->getFirstLastName() != null && $teacher->getSecondLastName() != null && $teacher->getIdCard() != null && $teacher->getEmail() != null && $teacher->getProfilePhoto() != null) {
				if ($teacherMethods->Create($teacher)) {
					$status = true;

					$subject = "Cuenta de Comedor";
					$emailTemplate->IndividualEmail($teacher->getEmail(), $subject, $password, $teacher->getName(), $teacher->getFirstLastName());
				} else $status = false;
			} else $status = false;
		}

		if ($status)
			header("Location: ./?dir=admin&controller=Teacher&action=Index&id=main&alerta=success");
		else
			header("Location: ./?dir=admin&controller=Teacher&action=Index&id=main&alerta=warning");
	}

	public function Update()
	{
		require_once './Controller/helpers/ProfilePhotoController.php';
		$profilePhotoController = new ProfilePhotoController();

		$teacher = new Teacher();
		$teacherMethods = new TeacherMethods();

		$id = $_POST['idModificar'];
		$firstName = $_POST['nombreModificar'];
		$firstLastName = $_POST['primerApellidoModificar'];
		$secondLastName = $_POST['segundoApellidoModificar'];
		$idCard = $_POST['cedulaModificar'];
		$email = $_POST['correoModificar'];
		$password = $_POST['contrasenaModificar'];
		$status = $_POST['estadoModificar'];

		$tmp_path = $_FILES["profile-image"]["tmp_name"];
		$name = $_FILES["profile-image"]["name"];
		$size = $_FILES["profile-image"]["size"];
		$type = $_FILES["profile-image"]["type"];

		if ($teacher = $teacherMethods->Find($id)) {
			$photoPath = $teacher->getProfilePhoto();
			if ($name != null) {
				$photoPath = $profilePhotoController->GenerateProfilePhoto($name, $tmp_path, $size, $type);
				$profilePhotoController->DeleteProfilePhoto($teacher->getProfilePhoto());
			}

			$teacher->setProfilePhoto($photoPath);
			$teacher->setId($id);
			$teacher->setName($firstName);
			$teacher->setFirstLastName($firstLastName);
			$teacher->setSecondLastName($secondLastName);
			$teacher->setIdCard($idCard);

			if (filter_var($email, FILTER_VALIDATE_EMAIL))
				$teacher->setEmail($email);

			if ($password != null) {
				$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
				$teacher->setPassword($hashedPassword);
				$teacherMethods->UpdatePassword($hashedPassword, $id);
			}

			$teacher->setStatus($status);

			if ($teacher->getName() != null && $teacher->getFirstLastName() != null && $teacher->getSecondLastName() != null && $teacher->getIdCard() != null && $teacher->getMeals() != null && $teacher->getEmail() != null && $teacher->getProfilePhoto() != null) {
				if ($teacherMethods->Update($teacher)) {
					header('Location: ./?dir=admin&controller=Teacher&action=Index&id=main&alerta=success');
				} else {
					$profilePhotoController->DeleteProfilePhoto($teacher->getProfilePhoto());
					header('Location: ./?dir=admin&controller=Teacher&action=Index&id=main&alerta=error');
				}
			} else {
				$profilePhotoController->DeleteProfilePhoto($teacher->getProfilePhoto());
				header('Location: ./?dir=admin&controller=Teacher&action=Index&id=main&alerta=error');
			}
		} else header('Location: ./?dir=admin&controller=Teacher&action=Index&id=main&alerta=error');
	}

	public function UpdatePassword()
	{
		$id = $_POST['idModificar'];
		$password = $_POST['contrasenaModificar'];

		$teacher = new Teacher();
		$teacherMethods = new TeacherMethods();

		if ($id != null && $password != null) {
			if ($teacher = $teacherMethods->Find($id)) {
				$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
				$teacher->setPassword($hashedPassword);
				$teacherMethods->UpdatePassword($hashedPassword, $id);
				header('Location: ./?controller=Index&action=MyAccount&alerta=success');
			} else
				header('Location: ./?controller=Index&action=MyAccount&alerta=error');
		} else
			header('Location: ./?controller=Index&action=MyAccount&alerta=error');
	}

	public function ChangeStatus($status)
	{
		if (!isset($_REQUEST['idsArr'])) {
			header('Location: ./?dir=admin&controller=Teacher&action=Index&id=main');
		} else {
			$arrayIds = $_REQUEST['idsArr'];
			$lengthArray = $_REQUEST['lengthArray'];
			$teacherMethods = new TeacherMethods();
			$goBack = false;

			for ($i = 0; $i < $lengthArray; $i++) {
				$teacher = new Teacher();
				$teacher = $teacherMethods->Find($arrayIds[$i]);
				$teacher->setStatus($status);
				if ($teacherMethods->Update($teacher)) {
					$goBack = true;
				}
			}

			if ($status == 0) {
				if ($goBack) {
					header('Location: ./?dir=admin&controller=Teacher&action=Index&id=main&alerta=success');
				} else {
					header('Location: ./?dir=admin&controller=Teacher&action=Index&id=main&alerta=error');
				}
			} else {
				if ($goBack) {
					header('Location: ./?dir=admin&controller=Teacher&action=Index&id=main&estados=0&alerta=success');
				} else {
					header('Location: ./?dir=admin&controller=Teacher&action=Index&id=main&estados=0&alerta=error');
				}
			}
		}
	}
}