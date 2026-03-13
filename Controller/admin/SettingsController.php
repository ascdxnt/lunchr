<?php
require_once './Model/Entities/NonSchoolDay.php';
require_once './Model/Methods/NonSchoolDayMethods.php';

class SettingsController
{
	public function Index()
	{
		$jsonPasswordConfig = file_get_contents('./Core/PasswordConfig.json');
		$configs = json_decode($jsonPasswordConfig);
		$nonSchoolDayMethods = new NonSchoolDayMethods();
		$nonSchoolDays = $nonSchoolDayMethods->FindAll();

		if ($nonSchoolDays != null) {
			$arrayNonSchoolDays = array();
			for ($i = 0; $i < sizeof($nonSchoolDays); $i++) {
				$arrayNonSchoolDays[$i] = array("id" => $nonSchoolDays[$i]->getId());
				$arrayNonSchoolDays[$i] += array("nombre" => $nonSchoolDays[$i]->getName());
				$arrayNonSchoolDays[$i] += array("fecha" => $nonSchoolDays[$i]->getDate());
				$arrayNonSchoolDays[$i] += array("estado" => $nonSchoolDays[$i]->getStatus());
			}
		}

		if (isset($arrayNonSchoolDays))
			$arrayNonSchoolDays = json_encode($arrayNonSchoolDays);
		else
			$arrayNonSchoolDays = null;

		require_once "./View/views/admin/Settings.php";
	}
}