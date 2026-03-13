<?php

class NonSchoolDayMethods
{
	function Find($id)
	{
		$nonSchoolDay = new NonSchoolDay();
		$connection = new Connection();

		$sql = "SELECT * FROM `DIANOLECTIVO` WHERE `ID` = '$id'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$nonSchoolDay->setId($row["ID"]);
				$nonSchoolDay->setName($row["NOMBRE"]);
				$nonSchoolDay->setDate($row["FECHA"]);
				$nonSchoolDay->setStatus($row["ESTADO"]);
			}
		} else {
			$nonSchoolDay = null;
		}
		$connection->Close();
		return $nonSchoolDay;
	}

	function FindByDate($date)
	{
		$nonSchoolDay = new NonSchoolDay();
		$connection = new Connection();

		$sql = "SELECT * FROM `DIANOLECTIVO` WHERE `FECHA` = '$date'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$nonSchoolDay->setId($row["ID"]);
				$nonSchoolDay->setName($row["NOMBRE"]);
				$nonSchoolDay->setDate($row["FECHA"]);
				$nonSchoolDay->setStatus($row["ESTADO"]);
			}
		} else {
			$nonSchoolDay = null;
		}
		$connection->Close();
		return $nonSchoolDay;
	}

	function FindAll()
	{
		$nonSchoolDays = array();
		$connection = new Connection();

		$sql = "SELECT * FROM `DIANOLECTIVO`";
		$result = $connection->Execute($sql);
		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$nonSchoolDay = new NonSchoolDay();
				$nonSchoolDay->setId($row["ID"]);
				$nonSchoolDay->setName($row["NOMBRE"]);
				$nonSchoolDay->setDate($row["FECHA"]);
				$nonSchoolDay->setStatus($row["ESTADO"]);
				$nonSchoolDays[] = $nonSchoolDay;
			}
		} else {
			$nonSchoolDays = null;
		}
		$connection->Close();
		return $nonSchoolDays;
	}

	function Create(NonSchoolDay $nonSchoolDay)
	{
		$success = false;
		$connection = new Connection();

		$sql = "INSERT INTO `DIANOLECTIVO`(`FECHA`,`NOMBRE`,`ESTADO`)
                    VALUES('" . $nonSchoolDay->getDate() . "',
                           '" . $nonSchoolDay->getName() . "',
                           '" . $nonSchoolDay->getStatus() . "')";
		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}

	function Update(NonSchoolDay $nonSchoolDay)
	{
		$success = false;
		$connection = new Connection();

		$sql = "UPDATE DIANOLECTIVO SET NOMBRE='" . $nonSchoolDay->getName() . "',
                                        ESTADO='" . $nonSchoolDay->getStatus() . "',
                                        FECHA='" . $nonSchoolDay->getDate() . "'
                                        Where `ID` =" . $nonSchoolDay->getId();
		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}

	function Delete(NonSchoolDay $nonSchoolDay)
	{
		$success = false;
		$connection = new Connection();

		$sql = "UPDATE DIANOLECTIVO SET ESTADO= 0
                                        WHERE `ID` =" . $nonSchoolDay->getId();
		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}
}
