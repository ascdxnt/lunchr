<?php
require_once './Model/Entities/NonSchoolDay.php';
require_once './Model/Methods/NonSchoolDayMethods.php';
require_once './Model/Connection.php';

class DateController
{
	public function IsSchoolDay($date)
	{
		$nonSchoolDayMethods = new NonSchoolDayMethods();
		$nonSchoolDays = $nonSchoolDayMethods->FindAll();
		$nonSchoolDay = false;
		$dayOfWeek = date('D', strtotime($date));

		if ($dayOfWeek == 'Sat' || $dayOfWeek == 'Sun')
			$nonSchoolDay = true;
		else if ($nonSchoolDays != null) {
			$date = substr($date, 5);
			foreach ($nonSchoolDays as $day) {
				if ($day->getStatus() == 1) {
					if ($day->getDate() == $date)
						$nonSchoolDay = true;
				}
			}
		} else
			echo $nonSchoolDay = false;

		if (!$nonSchoolDay)
			return true;
		else
			return false;
	}
}