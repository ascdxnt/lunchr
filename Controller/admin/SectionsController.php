<?php
require_once './Model/Connection.php';
require_once './Model/Entities/Section.php';
require_once './Model/Methods/SectionMethods.php';

class SectionsController
{
	public function Index($view)
	{
		if ($view == "main") {
			$sectionMethods = new SectionMethods();
			$allSections = $sectionMethods->FindAll();

			if ($allSections != null) {
				$sections = array();
				for ($i = 0; $i < sizeof($allSections); $i++) {
					$sections[$i] = array("id" => $allSections[$i]->getId());
					$sections[$i] += array("descripcion" => $allSections[$i]->getDescription());
					$sections[$i] += array("estado" => $allSections[$i]->getStatus());
				}
			}

			if (isset($sections))
				$sections = json_encode($sections);
			else
				$sections = null;

			require_once "./View/views/admin/Sections.php";
		} else if ($view == "crear")
			require_once "./View/views/admin/SectionsCreate.php";
		else if ($view == "modificar")
			require_once "./View/views/admin/SectionsEdit.php";
	}

	public function Create()
	{
		$section = new Section();
		$sectionMethods = new SectionMethods();
		$name = $_POST['seccion'];

		$section = $sectionMethods->FindByDescription($name);
		if ($section != null && $section->getStatus() == 1) {
			header('Location: ./?dir=admin&controller=Sections&action=Index&id=main&alerta=error');
		} else {
			$section = new Section();
			$section->setStatus(1);
			$section->setDescription($name);
			if ($section->getDescription() != null) {
				if ($sectionMethods->Create($section))
					header('Location: ./?dir=admin&controller=Sections&action=Index&id=main&alerta=success');
				else
					header('Location: ./?dir=admin&controller=Sections&action=Index&id=main&alerta=error');
			}
		}
	}

	public function VerifyName()
	{
		$data = json_decode(file_get_contents('php://input'), true);
		$sectionName = $data['nombreSeccion'];
		$section = new Section();
		$sectionMethods = new SectionMethods();

		if ($section = $sectionMethods->FindByDescription($sectionName)) {
			if ($section->getStatus() == 1)
				echo '{"message":"error"}';
			else
				echo '{"message":"exito"}';
		} else
			echo '{"message":"exito"}';
	}

	public function Update()
	{
		$section = new Section();
		$sectionMethods = new SectionMethods();

		$id = $_POST['idModificar'];
		$name = $_POST['seccionModificar'];
		$status = $_POST['estadoModificar'];

		$section->setId($id);
		$section->setDescription($name);
		$section->setStatus($status);

		if ($section->getId() != null && $section->getDescription() != null && $section->getStatus() != null) {
			if ($sectionMethods->Update($section)) {
				header('Location: ./?dir=admin&controller=Sections&action=Index&id=main&alerta=success');
			} else {
				header('Location: ./?dir=admin&controller=Sections&action=Index&id=main&alerta=error');
			}
		}
	}

	public function ChangeStatus($params)
	{
		$status = $params;
		if (!isset($_REQUEST['idsArr']))
			header('Location: ./?dir=admin&controller=Sections&action=Index&id=main&alerta=error');
		else {
			$arrayIds = $_REQUEST['idsArr'];
			$lengthArray = $_REQUEST['lengthArray'];
			$sectionMethods = new SectionMethods();
			$goBack = false;

			for ($i = 0; $i < $lengthArray; $i++) {
				$section = new Section();
				$section = $sectionMethods->Find($arrayIds[$i]);
				$section->setStatus($status);
				if ($sectionMethods->Update($section)) {
					$goBack = true;
				}
			}

			if ($status == 0) {
				if ($goBack)
					header('Location: ./?dir=admin&controller=Sections&action=Index&id=main&alerta=success');
				else
					header('Location: ./?dir=admin&controller=Sections&action=Index&id=main&alerta=error');
			} else {
				if ($goBack)
					header('Location: ./?dir=admin&controller=Sections&action=Index&id=main&alerta=success');
				else
					header('Location: ./?dir=admin&controller=Sections&action=Index&id=main&alerta=error');
			}
		}
	}
}