<?php
require_once './Model/Connection.php';
require_once './Model/Entities/Staff.php';
require_once './Model/Methods/StaffMethods.php';
require_once './Controller/helpers/EmailController.php';
require_once './Controller/helpers/PasswordController.php';

class StaffController
{
	public function AdminViews($view)
	{
		$profile = 1;
		if ($view == "main") {
			$staffMethods = new StaffMethods();
			$allAdmins = $staffMethods->FindAllByProfile($profile);

			if ($allAdmins != null) {
				$administrators = array();
				for ($i = 0; $i < sizeof($allAdmins); $i++) {
					$administrators[$i] = array("id" => $allAdmins[$i]->getId());
					$administrators[$i] += array("nombre" => $allAdmins[$i]->getName());
					$administrators[$i] += array("apellido1" => $allAdmins[$i]->getFirstLastName());
					$administrators[$i] += array("apellido2" => $allAdmins[$i]->getSecondLastName());
					$administrators[$i] += array("correo" => $allAdmins[$i]->getEmail());
					$administrators[$i] += array("estado" => $allAdmins[$i]->getStatus());
				}
			}

			if (isset($administrators))
				$administrators = json_encode($administrators);
			else
				$administrators = null;

			require_once "./View/views/admin/Administrators.php";
		} else if ($view == "crear")
			require_once "./View/views/admin/AdministratorsCreate.php";
		else if ($view == "modificar")
			require_once "./View/views/admin/AdministratorsEdit.php";
	}

	public function BillingViews($view)
	{
		$profile = 2;
		if ($view == "main") {
			$staffMethods = new StaffMethods();
			$allBillers = $staffMethods->FindAllByProfile($profile);

			$billers = array();
			if ($allBillers != null) {
				for ($i = 0; $i < sizeof($allBillers); $i++) {
					$billers[$i] = array("id" => $allBillers[$i]->getId());
					$billers[$i] += array("nombre" => $allBillers[$i]->getName());
					$billers[$i] += array("apellido1" => $allBillers[$i]->getFirstLastName());
					$billers[$i] += array("apellido2" => $allBillers[$i]->getSecondLastName());
					$billers[$i] += array("correo" => $allBillers[$i]->getEmail());
					$billers[$i] += array("estado" => $allBillers[$i]->getStatus());
				}
			}

			if (isset($billers))
				$billers = json_encode($billers);
			else
				$billers = null;

			require_once "./View/views/admin/Billing.php";
		} else if ($view == "crear")
			require_once "./View/views/admin/BillingCreate.php";
		else if ($view == "modificar")
			require_once "./View/views/admin/BillingEdit.php";
	}

	public function Create($profile)
	{
		$staffMethods = new StaffMethods();
		$staff = new Staff();
		$emailTemplate = new EmailController();
		$passwordGenerator = new PasswordController();

		$firstName = $_POST['nombre'];
		$firstLastName = $_POST['primerApellido'];
		$secondLastName = $_POST['segundoApellido'];
		$email = $_POST['correo'];

		//Random password generation
		$password = $passwordGenerator->RandomPassword();
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

		$staff->setName($firstName);
		$staff->setFirstLastName($firstLastName);
		$staff->setSecondLastName($secondLastName);
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
			$staff->setEmail($email);
		$staff->setPassword($hashedPassword);
		$staff->setStatus(1);
		$staff->setProfile($profile);

		if ($staff->getName() != null && $staff->getFirstLastName() != null && $staff->getSecondLastName() != null && $staff->getEmail() != null) {
			if ($staffMethods->Create($staff)) {
				$subject = "Cuenta de Comedor";
				$emailTemplate->IndividualEmail($staff->getEmail(), $subject, $password, $staff->getName(), $staff->getFirstLastName());
				if ($profile == 1)
					header('Location: ./?dir=admin&controller=Staff&action=AdminViews&id=main&alerta=success');
				else
					header('Location: ./?dir=admin&controller=Staff&action=BillingViews&id=main&alerta=success');
			} else {
				if ($profile == 1)
					header('Location: ./?dir=admin&controller=Staff&action=AdminViews&id=main&alerta=error');
				else
					header('Location: ./?dir=admin&controller=Staff&action=BillingViews&id=main&alerta=error');
			}
		}
	}

	public function CreateFromJSON($profile)
	{
		$staffMethods = new StaffMethods();
		$emailTemplate = new EmailController();
		$passwordGenerator = new PasswordController();
		$objectArray = json_decode(json_decode($_POST['PostJson']));
		$status = true;

		for ($i = 0; $i < count($objectArray); $i++) {
			$staff = new Staff();

			//Initialize most staff attributes
			$count = 0;
			foreach ($objectArray[$i] as $key => $value) {
				if ($count == 0) $staff->setName($value);
				else if ($count == 1) $staff->setFirstLastName($value);
				else if ($count == 2) $staff->setSecondLastName($value);
				else if ($count == 3) {
					if (filter_var($value, FILTER_VALIDATE_EMAIL))
						$staff->setEmail($value);
				} else if ($count > 3) break;
				$count++;
			}

			//Random password generation
			$password = $passwordGenerator->RandomPassword();
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

			$staff->setPassword($hashedPassword);
			$staff->setStatus(1);
			$staff->setProfile($profile);

			if ($staff->getName() != null && $staff->getFirstLastName() != null && $staff->getSecondLastName() != null && $staff->getPassword() != null) {
				if ($staffMethods->Create($staff)) {
					$subject = "Cuenta de Comedor";
					$emailTemplate->IndividualEmail($staff->getEmail(), $subject, $password, $staff->getName(), $staff->getFirstLastName());
				} else $status = false;
			} else $status = false;
		}

		if ($profile == 1) {
			if ($status)
				header("Location: ./?dir=admin&controller=Staff&action=AdminViews&id=main&alerta=success");
			else
				header("Location: ./?dir=admin&controller=Staff&action=AdminViews&id=main&alerta=warning");
		} else {
			if ($status)
				header("Location: ./?dir=admin&controller=Staff&action=BillingViews&id=main&alerta=success");
			else
				header("Location: ./?dir=admin&controller=Staff&action=BillingViews&id=main&alerta=warning");
		}
	}

	public function Update($profile)
	{
		$staff = new Staff();
		$staffMethods = new StaffMethods();

		$id = $_POST['idModificar'];
		$firstName = $_POST['nombreModificar'];
		$firstLastName = $_POST['primerApellidoModificar'];
		$secondLastName = $_POST['segundoApellidoModificar'];
		$email = $_POST['correoModificar'];
		$password = $_POST['contrasenaModificar'];
		$status = $_POST['estadoModificar'];

		if ($password != null)
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

		$staff->setId($id);
		$staff->setName($firstName);
		$staff->setFirstLastName($firstLastName);
		$staff->setSecondLastName($secondLastName);
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
			$staff->setEmail($email);
		if ($password != null) {
			$staff->setPassword($hashedPassword);
			$staffMethods->UpdatePassword($hashedPassword, $id);
		}

		$staff->setStatus($status);
		$staff->setProfile($profile);

		if ($staff->getName() != null && $staff->getFirstLastName() != null && $staff->getSecondLastName() != null && $staff->getEmail() != null) {
			if ($staffMethods->Update($staff)) {
				if ($status == 1) {
					if ($profile == 1)
						header('Location: ./?dir=admin&controller=Staff&action=AdminViews&id=main&alerta=success');
					else
						header('Location: ./?dir=admin&controller=Staff&action=BillingViews&id=main&alerta=success');
				} else {
					if ($profile == 1)
						header('Location: ./?dir=admin&controller=Staff&action=AdminViews&id=main&estados=0&alerta=success');
					else
						header('Location: ./?dir=admin&controller=Staff&action=BillingViews&id=main&estados=0&alerta=success');
				}
			} else {
				if ($status == 1) {
					if ($profile == 1)
						header('Location: ./?dir=admin&controller=Staff&action=AdminViews&id=main&alerta=error');
					else
						header('Location: ./?dir=admin&controller=Staff&action=BillingViews&id=main&alerta=error');
				} else {
					if ($profile == 1)
						header('Location: ./?dir=admin&controller=Staff&action=AdminViews&id=main&estados=0&alerta=error');
					else
						header('Location: ./?dir=admin&controller=Staff&action=BillingViews&id=main&estados=0&alerta=error');
				}
			}
		} else {
			if ($status == 1) {
				if ($profile == 1)
					header('Location: ./?dir=admin&controller=Staff&action=AdminViews&id=main&alerta=error');
				else
					header('Location: ./?dir=admin&controller=Staff&action=BillingViews&id=main&alerta=error');
			} else {
				if ($profile == 1)
					header('Location: ./?dir=admin&controller=Staff&action=AdminViews&id=main&estados=0&alerta=error');
				else
					header('Location: ./?dir=admin&controller=Staff&action=BillingViews&id=main&estados=0&alerta=error');
			}
		}
	}

	public function UpdatePassword()
	{
		$id = $_POST['idModificar'];
		$password = $_POST['contrasenaModificar'];

		$staff = new Staff();
		$staffMethods = new StaffMethods();

		if ($id != null && $password != null) {
			if ($staff = $staffMethods->Find($id)) {
				$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
				$staff->setPassword($hashedPassword);
				$staffMethods->UpdatePassword($hashedPassword, $id);
				header('Location: ./?controller=Index&action=MyAccount&alerta=success');
			} else
				header('Location: ./?controller=Index&action=MyAccount&alerta=error');
		} else
			header('Location: ./?controller=Index&action=MyAccount&alerta=error');
	}

	public function ChangeStatus($params)
	{
		$status = $params[0];
		$profile = $params[1];
		if (!isset($_REQUEST['idsArr'])) {
			if ($profile == 1)
				header('Location: ./?dir=admin&controller=Staff&action=AdminViews&id=main');
			else
				header('Location: ./?dir=admin&controller=Staff&action=BillingViews&id=main');
		} else {
			$arrayIds = $_REQUEST['idsArr'];
			$lengthArray = $_REQUEST['lengthArray'];
			$staffMethods = new StaffMethods();
			$goBack = false;

			for ($i = 0; $i < $lengthArray; $i++) {
				$staff = new Staff();
				$staff = $staffMethods->Find($arrayIds[$i]);
				$staff->setStatus($status);
				if ($staffMethods->Update($staff))
					$goBack = true;
			}

			if ($status == 0) {
				if ($goBack) {
					if ($profile == 1)
						header('Location: ./?dir=admin&controller=Staff&action=AdminViews&id=main&alerta=success');
					else
						header('Location: ./?dir=admin&controller=Staff&action=BillingViews&id=main&alerta=success');
				} else {
					if ($profile == 1)
						header('Location: ./?dir=admin&controller=Staff&action=AdminViews&id=main&alerta=error');
					else
						header('Location: ./?dir=admin&controller=Staff&action=BillingViews&id=main&alerta=error');
				}
			} else {
				if ($goBack) {
					if ($profile == 1)
						header('Location: ./?dir=admin&controller=Staff&action=AdminViews&id=main&estados=0&alerta=success');
					else
						header('Location: ./?dir=admin&controller=Staff&action=BillingViews&id=main&estados=0&alerta=success');
				} else {
					if ($profile == 1)
						header('Location: ./?dir=admin&controller=Staff&action=AdminViews&id=main&estados=0&alerta=error');
					else
						header('Location: ./?dir=admin&controller=Staff&action=BillingViews&id=main&estados=0&alerta=error');
				}
			}
		}
	}
}