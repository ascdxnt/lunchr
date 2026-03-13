<?php
require_once './Model/Connection.php';
require_once './Model/Entities/Student.php';
require_once './Model/Methods/StudentMethods.php';
require_once './Controller/helpers/EmailController.php';
require_once './Controller/helpers/PasswordController.php';

class StudentController
{
	public function Index($view)
	{
		require_once './Model/Methods/SpecialtyMethods.php';
		require_once './Model/Entities/Specialty.php';
		require_once './Model/Methods/SectionMethods.php';
		require_once './Model/Entities/Section.php';

		$specialtyMethods = new SpecialtyMethods();
		$allSpecialties = $specialtyMethods->FindAll();
		$sectionMethods = new SectionMethods();
		$allSections = $sectionMethods->FindAll();
		if ($view == "main") {
			$studentMethods = new StudentMethods();
			$allStudents = $studentMethods->FindAll();

			if ($allStudents != null) {
				$students = array();
				for ($i = 0; $i < sizeof($allStudents); $i++) {
					$section = new Section();
					$section = $sectionMethods->Find($allStudents[$i]->getSectionId());
					$specialty = new Specialty();
					$specialty = $specialtyMethods->Find($allStudents[$i]->getSpecialtyId());

					$students[$i] = array("id" => $allStudents[$i]->getId());
					$students[$i] += array("nombre" => $allStudents[$i]->getName());
					$students[$i] += array("apellido1" => $allStudents[$i]->getFirstLastName());
					$students[$i] += array("apellido2" => $allStudents[$i]->getSecondLastName());
					$students[$i] += array("cedula" => $allStudents[$i]->getIdCard());
					$students[$i] += array("comidas" => $allStudents[$i]->getMeals());
					$students[$i] += array("especialidad" => $specialty->getDescription());
					$students[$i] += array("seccion" => $section->getDescription());
					$students[$i] += array("correo" => $allStudents[$i]->getEmail());
					$students[$i] += array("becado" => $allStudents[$i]->getScholarship());
					$students[$i] += array("estado" => $allStudents[$i]->getStatus());
					$students[$i] += array("fotoPerfil" => $allStudents[$i]->getProfilePhoto());
				}
			}

			if (isset($students))
				$students = json_encode($students);
			else
				$students = null;

			require_once "./View/views/admin/Students.php";
		} else if ($view == "crear")
			require_once "./View/views/admin/StudentsCreate.php";
		else if ($view == "modificar")
			require_once "./View/views/admin/StudentsEdit.php";
	}

	public function Create()
	{
		require_once './Controller/helpers/ProfilePhotoController.php';
		$profilePhotoController = new ProfilePhotoController();

		$emailTemplate = new EmailController();
		$passwordGenerator = new PasswordController();
		$studentMethods = new StudentMethods();
		$student = new Student();

		$tmp_path = $_FILES["profile-image"]["tmp_name"];
		$name = $_FILES["profile-image"]["name"];
		$size = $_FILES["profile-image"]["size"];
		$type = $_FILES["profile-image"]["type"];

		$photoPath = $profilePhotoController->GenerateProfilePhoto($name, $tmp_path, $size, $type);

		if (!$photoPath) {
			header('Location: ./?dir=admin&controller=Student&action=Index&id=crear&alerta=error');
			return;
		}

		//Attribute declaration
		$firstName = $_POST['nombre'];
		$firstLastName = $_POST['primerApellido'];
		$secondLastName = $_POST['segundoApellido'];
		$idCard = $_POST['cedula'];
		$specialtyId = $_POST['idEspecialidad'];
		$sectionId = $_POST['idSeccion'];
		$email = $_POST['correo'];
		$scholarship = $_POST['becado'];

		//Random password generation
		$password = $passwordGenerator->RandomPassword();
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

		$student->setName($firstName);
		$student->setFirstLastName($firstLastName);
		$student->setSecondLastName($secondLastName);
		$student->setIdCard($idCard);
		$student->setMeals(0);
		$student->setSpecialtyId($specialtyId);
		$student->setSectionId($sectionId);
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
			$student->setEmail($email);
		$student->setPassword($hashedPassword);
		$student->setStatus(1);
		$student->setScholarship($scholarship); //1 = active
		$student->setProfile(3);
		$student->setProfilePhoto($photoPath);

		if ($student->getName() != null && $student->getFirstLastName() != null && $student->getSecondLastName() != null && $student->getIdCard() != null && $student->getPassword() != null && $student->getEmail() != null && $student->getSpecialtyId() != null && $student->getSectionId() != null && $student->getScholarship() != null && $student->getProfilePhoto() != null) {
			if ($studentMethods->Create($student)) {
				$subject = "Cuenta de Comedor";
				$emailTemplate->IndividualEmail($student->getEmail(), $subject, $password, $student->getName(), $student->getFirstLastName());
				header('Location: ./?dir=admin&controller=Student&action=Index&id=main&alerta=success');
			} else {
				$profilePhotoController->DeleteProfilePhoto($student->getProfilePhoto());
				header('Location: ./?dir=admin&controller=Student&action=Index&id=main&alerta=error');
			}
		} else {
			$profilePhotoController->DeleteProfilePhoto($student->getProfilePhoto());
			header('Location: ./?dir=admin&controller=Student&action=Index&id=main&alerta=error');
		}
	}

	public function CreateFromJSON()
	{
		require_once './Model/Methods/SectionMethods.php';
		require_once './Model/Entities/Section.php';
		require_once './Model/Entities/Specialty.php';
		require_once './Model/Methods/SpecialtyMethods.php';
		$emailTemplate = new EmailController();
		$passwordGenerator = new PasswordController();
		$studentMethods = new StudentMethods();
		$sectionMethods = new SectionMethods();
		$specialtyMethods = new SpecialtyMethods();
		$section = new Section();
		$specialty = new Specialty();

		$objectArray = json_decode(json_decode($_POST['PostJson']));
		$status = true;

		//Iterate over each entity or record
		for ($i = 0; $i < count($objectArray); $i++) {
			$student = new Student();
			$cancel = false;

			//Fill in entity attributes
			$count = 0;
			foreach ($objectArray[$i] as $key => $value) {
				if ($count == 0) $student->setName($value);
				else if ($count == 1) $student->setFirstLastName($value);
				else if ($count == 2) $student->setSecondLastName($value);
				else if ($count == 3) $student->setIdCard($value);
				else if ($count == 4) {
					if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
						$student->setEmail($value);
					} else $cancel = true;
				} else if ($count == 5) {
					if (!$specialty = $specialtyMethods->FindByDescription($value)) {
						$cancel = true;
					} else $student->setSpecialtyId($specialty->getId());
				} else if ($count == 6) {
					if (!$section = $sectionMethods->FindByDescription($value)) {
						$cancel = true;
					} else $student->setSectionId($section->getId());
				} else if ($count == 7) {
					if ($value == "Subvencionada") $student->setScholarship(0);
					else if ($value == "Completa") $student->setScholarship(1);
					else $cancel = true;
				};
				$count++;
			}

			if ($cancel) continue;

			//Random password generation
			$password = $passwordGenerator->RandomPassword();
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

			$student->setMeals(0);
			$student->setPassword($hashedPassword);
			$student->setStatus(1);
			$student->setProfile(3);
			$student->setProfilePhoto('./View/assets/profile/default.jpg');

			if ($student->getName() != null && $student->getFirstLastName() != null && $student->getSecondLastName() != null && $student->getIdCard() != null && $student->getSectionId() != null && $student->getSpecialtyId() != null && $student->getEmail() != null && $student->getProfilePhoto() != null) {
				if (!$sectionMethods->Find($student->getSectionId()) || !$specialtyMethods->Find($student->getSpecialtyId()))
					$status = false;
				else if ($studentMethods->Create($student)) {
					$subject = "Cuenta de Comedor";
					$emailTemplate->IndividualEmail($student->getEmail(), $subject, $password, $student->getName(), $student->getFirstLastName());
				} else
					$status = false;
			} else
				$status = false;
		}

		if ($status)
			header("Location: ./?dir=admin&controller=Student&action=Index&id=main&alerta=success");
		else
			header("Location: ./?dir=admin&controller=Student&action=Index&id=main&alerta=warning");
	}

	public function Update()
	{
		require_once './Controller/helpers/ProfilePhotoController.php';
		$profilePhotoController = new ProfilePhotoController();

		$student = new Student();
		$studentMethods = new StudentMethods();

		$id = $_POST['idModificar'];
		$firstName = $_POST['nombreModificar'];
		$firstLastName = $_POST['primerApellidoModificar'];
		$secondLastName = $_POST['segundoApellidoModificar'];
		$idCard = $_POST['cedulaModificar'];
		$specialtyId = $_POST['especialidadModificar'];
		$sectionId = $_POST['seccionModificar'];
		$email = $_POST['correoModificar'];
		$password = $_POST['contrasenaModificar'];
		$status = $_POST['estadoModificar'];
		$scholarship = $_POST['becadoModificar'];

		$tmp_path = $_FILES["profile-image"]["tmp_name"];
		$name = $_FILES["profile-image"]["name"];
		$size = $_FILES["profile-image"]["size"];
		$type = $_FILES["profile-image"]["type"];

		if ($student = $studentMethods->Find($id)) {
			$photoPath = $student->getProfilePhoto();
			if ($name != null) {
				$photoPath = $profilePhotoController->GenerateProfilePhoto($name, $tmp_path, $size, $type);
				$profilePhotoController->DeleteProfilePhoto($student->getProfilePhoto());
			}

			$student->setProfilePhoto($photoPath);
			$student->setName($firstName);
			$student->setFirstLastName($firstLastName);
			$student->setSecondLastName($secondLastName);
			$student->setIdCard($idCard);
			$student->setSpecialtyId($specialtyId);
			$student->setSectionId($sectionId);
			if (filter_var($email, FILTER_VALIDATE_EMAIL))
				$student->setEmail($email);
			if ($password != null) {
				$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
				$student->setPassword($hashedPassword);
				$studentMethods->UpdatePassword($hashedPassword, $id);
			}
			$student->setStatus($status);
			$student->setScholarship($scholarship);

			if ($student->getName() != null && $student->getFirstLastName() != null && $student->getSecondLastName() != null && $student->getIdCard() != null && $student->getEmail() != null && $student->getSpecialtyId() != null && $student->getSectionId() != null && $student->getScholarship() != null && $student->getProfilePhoto() != null) {
				if ($studentMethods->Update($student))
					header('Location: ./?dir=admin&controller=Student&action=Index&id=main&alerta=success');
				else {
					$profilePhotoController->DeleteProfilePhoto($student->getProfilePhoto());
					header('Location: ./?dir=admin&controller=Student&action=Index&id=main&alerta=error');
				}
			} else {
				$profilePhotoController->DeleteProfilePhoto($student->getProfilePhoto());
				header('Location: ./?dir=admin&controller=Student&action=Index&id=main&alerta=error');
			}
		} else header('Location: ./?dir=admin&controller=Student&action=Index&id=main&alerta=error');
	}

	public function UpdatePassword()
	{
		$id = $_POST['idModificar'];
		$password = $_POST['contrasenaModificar'];

		$student = new Student();
		$studentMethods = new StudentMethods();

		if ($id != null && $password != null) {
			if ($student = $studentMethods->Find($id)) {
				$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
				$student->setPassword($hashedPassword);
				$studentMethods->UpdatePassword($hashedPassword, $id);
				header('Location: ./?controller=Index&action=MyAccount&alerta=success');
			} else
				header('Location: ./?controller=Index&action=MyAccount&alerta=error');
		} else
			header('Location: ./?controller=Index&action=MyAccount&alerta=error');
	}

	public function ChangeStatus($status)
	{
		$newStatus = $status;
		if (!isset($_REQUEST['idsArr']))
			header('Location: ./?dir=admin&controller=Student&action=Index&id=main');
		else {
			$arrayIds = $_REQUEST['idsArr'];
			$lengthArray = $_REQUEST['lengthArray'];
			$studentMethods = new StudentMethods();
			$goBack = false;

			//Iterate over selected students to change their status
			for ($i = 0; $i < $lengthArray; $i++) {
				$student = new Student();
				$student = $studentMethods->Find($arrayIds[$i]);
				$student->setStatus($newStatus);
				if ($studentMethods->Update($student)) {
					$goBack = true;
				}
			}

			if ($newStatus == 0) {
				if ($goBack)
					header('Location: ./?dir=admin&controller=Student&action=Index&id=main&alerta=success');
				else
					header('Location: ./?dir=admin&controller=Student&action=Index&id=main&alerta=error');
			} else {
				if ($goBack)
					header('Location: ./?dir=admin&controller=Student&action=Index&id=main&estados=0&alerta=success');
				else
					header('Location: ./?dir=admin&controller=Student&action=Index&id=main&estados=0&alerta=error');
			}
		}
	}
}