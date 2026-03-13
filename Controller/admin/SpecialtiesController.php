<?php
require_once './Model/Connection.php';
require_once './Model/Entities/Specialty.php';
require_once './Model/Methods/SpecialtyMethods.php';

class SpecialtiesController
{
	public function Index($view)
	{
		if ($view == "main") {
			$specialtyMethods = new SpecialtyMethods();
			$allSpecialties = $specialtyMethods->FindAll();

			if ($allSpecialties != null) {
				$specialties = array();
				for ($i = 0; $i < sizeof($allSpecialties); $i++) {
					$specialties[$i] = array("id" => $allSpecialties[$i]->getId());
					$specialties[$i] += array("descripcion" => $allSpecialties[$i]->getDescription());
					$specialties[$i] += array("estado" => $allSpecialties[$i]->getStatus());
				}
			}

			if (isset($specialties))
				$specialties = json_encode($specialties);
			else
				$specialties = null;

			require_once "./View/views/admin/Specialties.php";
		} else if ($view == "crear")
			require_once "./View/views/admin/SpecialtiesCreate.php";
		else if ($view == "modificar")
			require_once "./View/views/admin/SpecialtiesEdit.php";
	}

	public function Create()
	{
		$specialty = new Specialty();
		$specialtyMethods = new SpecialtyMethods();
		$name = $_POST['especialidad'];

		$specialty = $specialtyMethods->FindByDescription($name);
		if ($specialty != null && $specialty->getStatus() == 1)
			header('Location: ./?dir=admin&controller=Specialties&action=Index&id=main&alerta=error');
		else {
			$specialty = new Specialty();
			$specialty->setDescription($name);
			$specialty->setStatus(1);
			if ($specialty->getDescription() != null) {
				if ($specialtyMethods->Create($specialty)) {
					header('Location: ./?dir=admin&controller=Specialties&action=Index&id=main&alerta=success');
				} else {
					header('Location: ./?dir=admin&controller=Specialties&action=Index&id=main&alerta=error');
				}
			}
		}
	}

	public function Update()
	{
		$specialty = new Specialty();
		$specialtyMethods = new SpecialtyMethods();

		$id = $_POST['idModificar'];
		$name = $_POST['especialidadModificar'];
		$status = $_POST['estadoModificar'];

		$specialty->setId($id);
		$specialty->setDescription($name);
		$specialty->setStatus($status);

		if ($specialty->getId() != null && $specialty->getDescription() != null && $specialty->getStatus() != null) {
			if ($specialtyMethods->Update($specialty)) {
				header('Location: ./?dir=admin&controller=Specialties&action=Index&id=main&alerta=success');
			} else {
				header('Location: ./?dir=admin&controller=Specialties&action=Index&id=main&alerta=error');
			}
		}
	}

	public function VerifyName()
	{
		$data = json_decode(file_get_contents('php://input'), true);
		$specialtyName = $data['nombreEspecialidad'];
		$specialty = new Specialty();
		$specialtyMethods = new SpecialtyMethods();

		if ($specialty = $specialtyMethods->FindByDescription($specialtyName)) {
			if ($specialty->getStatus() == 1)
				echo '{"message":"error"}';
			else
				echo '{"message":"exito"}';
		} else
			echo '{"message":"exito"}';
	}

	public function ChangeStatus($params)
	{
		$status = $params[0];
		$profile = $params[1];
		if (!isset($_REQUEST['idsArr'])) {
			header('Location: ./?dir=admin&controller=Specialties&action=Index&id=main');
		} else {
			$arrayIds = $_REQUEST['idsArr'];
			$lengthArray = $_REQUEST['lengthArray'];
			$specialtyMethods = new SpecialtyMethods();
			$goBack = false;

			for ($i = 0; $i < $lengthArray; $i++) {
				$specialty = new Specialty();
				$specialty = $specialtyMethods->Find($arrayIds[$i]);
				$specialty->setStatus($status);
				if ($specialtyMethods->Update($specialty)) {
					$goBack = true;
				}
			}

			if ($status == 0) {
				if ($goBack) {
					header('Location: ./?dir=admin&controller=Specialties&action=Index&id=main&alerta=success');
				} else {
					header('Location: ./?dir=admin&controller=Specialties&action=Index&id=main&alerta=error');
				}
			} else {
				if ($goBack) {
					header('Location: ./?dir=admin&controller=Specialties&action=Index&id=main&alerta=success');
				} else {
					header('Location: ./?dir=admin&controller=Specialties&action=Index&id=main&alerta=error');
				}
			}
		}
	}
}