<?php

class SpecialtyMethods
{
	function Find($id)
	{
		$specialty = new Specialty();
		$connection = new Connection();

		$sql = "SELECT * FROM `ESPECIALIDAD` WHERE `ID` = '$id'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$specialty->setId($row["ID"]);
				$specialty->setDescription($row["DESCRIPCION"]);
				$specialty->setStatus($row["ESTADO"]);
			}
		} else {
			$specialty = null;
		}
		$connection->Close();
		return $specialty;
	}

	function FindByDescription($description)
	{
		$specialty = new Specialty();
		$connection = new Connection();

		$sql = "SELECT * FROM `ESPECIALIDAD` WHERE `DESCRIPCION` = '$description'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$specialty->setId($row["ID"]);
				$specialty->setDescription($row["DESCRIPCION"]);
				$specialty->setStatus($row["ESTADO"]);
			}
		} else {
			$specialty = null;
		}
		$connection->Close();
		return $specialty;
	}

	function FindAll()
	{
		$allSpecialties = array();
		$connection = new Connection();

		$sql = "SELECT * FROM `ESPECIALIDAD`";
		$result = $connection->Execute($sql);
		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$specialty = new Specialty();
				$specialty->setId($row["ID"]);
				$specialty->setDescription($row["DESCRIPCION"]);
				$specialty->setStatus($row["ESTADO"]);
				$allSpecialties[] = $specialty;
			}
		} else {
			$allSpecialties = null;
		}
		$connection->Close();
		return $allSpecialties;
	}

	function Create(Specialty $specialty)
	{
		$success = false;
		$connection = new Connection();

		$sql = "INSERT INTO `ESPECIALIDAD`(`DESCRIPCION`,`ESTADO`)
                    VALUES('" . $specialty->getDescription() . "',
                            '" . $specialty->getStatus() . "')";
		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}

	function Update(Specialty $specialty)
	{
		$success = false;
		$connection = new Connection();

		$sql = "UPDATE ESPECIALIDAD SET  DESCRIPCION='" . $specialty->getDescription() . "',
                                        ESTADO='" . $specialty->getStatus() . "'
                                        Where `ID` =" . $specialty->getId();
		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}
}
