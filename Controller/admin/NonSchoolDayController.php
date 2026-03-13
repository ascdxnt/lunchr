<?php
require_once './Model/Connection.php';
require_once './Model/Entities/NonSchoolDay.php';
require_once './Model/Methods/NonSchoolDayMethods.php';

class NonSchoolDayController
{
	public function Create()
	{
		$nonSchoolDay = new NonSchoolDay();
		$nonSchoolDayMethods = new NonSchoolDayMethods();
		$date = $_POST['diaEspecifico'];
		$name = $_POST['nombreDiaEspecifico'];
		$dateToCreate = date('m-d', strtotime($date));

		if ($nonSchoolDayMethods->FindByDate($dateToCreate)) {
			header('Location: ./?dir=admin&controller=Settings&action=Index&id=main&alerta=error2');
		} else {
			$nonSchoolDay = new NonSchoolDay();
			$nonSchoolDay->setStatus(1);
			$nonSchoolDay->setDate($dateToCreate);
			$nonSchoolDay->setName($name);
			if ($nonSchoolDay->getDate() != null) {
				if ($nonSchoolDayMethods->Create($nonSchoolDay))
					header('Location: ./?dir=admin&controller=Settings&action=Index&id=main&alerta=success');
				else
					header('Location: ./?dir=admin&controller=Settings&action=Index&id=main&alerta=error');
			} else
				header('Location: ./?dir=admin&controller=Settings&action=Index&id=main&alerta=error');
		}
	}

	public function CreateRange()
	{
		require_once './Controller/helpers/DateController.php';

		$nonSchoolDay = new NonSchoolDay();
		$nonSchoolDayMethods = new NonSchoolDayMethods();
		$startDate = $_POST['inicioLapsoTiempo'];
		$endDate = $_POST['finLapsoTiempo'];
		$name = $_POST['nombreLapsoTiempo'];
		$startDate = strtotime($startDate);
		$endDate = strtotime($endDate);
		$dateController = new DateController();
		$status = false;

		for ($day = $startDate; $day <= $endDate; $day += 86400) {
			$startDate = date('Y-m-d', $day);
			$dateToCreate = date('m-d', $day);
			$nonSchoolDay = new NonSchoolDay();
			$nonSchoolDay->setStatus(1);
			$nonSchoolDay->setDate($dateToCreate);
			$nonSchoolDay->setName($name);

			if (!$nonSchoolDayMethods->FindByDate($dateToCreate)) {
				if ($nonSchoolDayMethods->Create($nonSchoolDay))
					$status = true;
				else
					header('Location: ./?dir=admin&controller=Settings&action=Index&id=main&alerta=error');
			} else
				header('Location: ./?dir=admin&controller=Settings&action=Index&id=main&alerta=error2');
		}

		if ($status)
			header('Location: ./?dir=admin&controller=Settings&action=Index&id=main&alerta=success');
		else {
			header('Location: ./?dir=admin&controller=Settings&action=Index&id=main&alerta=error');
		}
	}

	public function Update()
	{
		$nonSchoolDay = new NonSchoolDay();
		$nonSchoolDayMethods = new NonSchoolDayMethods();

		$id = $_POST['idFechaDiaEspecifico'];
		$date = $_POST['modificarDiaEspecifico'];
		$name = $_POST['modificarNombreDiaEspecifico'];
		$dateToUpdate = date('m-d', strtotime($date));

		$nonSchoolDay->setId($id);
		$nonSchoolDay->setDate($dateToUpdate);
		$nonSchoolDay->setName($name);
		$nonSchoolDay->setStatus(1);

		if ($nonSchoolDayMethods->FindByDate($dateToUpdate)) {
			header('Location: ./?dir=admin&controller=Settings&action=Index&id=main&alerta=error2');
		} else {
			if ($nonSchoolDay->getId() != null && $nonSchoolDay->getDate() != null && $nonSchoolDay->getName() != null) {
				if ($nonSchoolDayMethods->Update($nonSchoolDay)) {
					header('Location: ./?dir=admin&controller=Settings&action=Index&id=main&alerta=success');
				} else {
					header('Location: ./?dir=admin&controller=Settings&action=Index&id=main&alerta=error');
				}
			} else
				header('Location: ./?dir=admin&controller=Settings&action=Index&id=main&alerta=error');
		}
	}

	public function ChangeStatus()
	{
		$id = $_POST['idFecha'];
		$nonSchoolDay = new NonSchoolDay();
		$nonSchoolDayMethods = new NonSchoolDayMethods();

		$nonSchoolDay->setId($id);

		if ($nonSchoolDay->getId() != null) {
			if ($nonSchoolDayMethods->Delete($nonSchoolDay)) {
				header('Location: ./?dir=admin&controller=Settings&action=Index&id=main&alerta=success');
			} else {
				header('Location: ./?dir=admin&controller=Settings&action=Index&id=main&alerta=error');
			}
		}
	}
}