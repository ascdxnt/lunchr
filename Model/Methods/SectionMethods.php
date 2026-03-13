<?php

class SectionMethods
{
	function Find($id)
	{
		$section = new Section();
		$connection = new Connection();

		$sql = "SELECT * FROM `SECCION` WHERE `ID` = '$id'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$section->setId($row["ID"]);
				$section->setDescription($row["DESCRIPCION"]);
				$section->setStatus($row["ESTADO"]);
			}
		} else {
			$section = null;
		}
		$connection->Close();
		return $section;
	}

	function FindByDescription($description)
	{
		$section = new Section();
		$connection = new Connection();

		$sql = "SELECT * FROM `SECCION` WHERE `DESCRIPCION` = '$description'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$section->setId($row["ID"]);
				$section->setDescription($row["DESCRIPCION"]);
				$section->setStatus($row["ESTADO"]);
			}
		} else {
			$section = null;
		}
		$connection->Close();
		return $section;
	}

	function FindAll()
	{
		$allSections = array();
		$connection = new Connection();

		$sql = "SELECT * FROM `SECCION`";
		$result = $connection->Execute($sql);
		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$section = new Section();
				$section->setId($row["ID"]);
				$section->setDescription($row["DESCRIPCION"]);
				$section->setStatus($row["ESTADO"]);
				$allSections[] = $section;
			}
		} else {
			$allSections = null;
		}
		$connection->Close();
		return $allSections;
	}

	function Create(Section $section)
	{
		$success = false;
		$connection = new Connection();

		$sql = "INSERT INTO `SECCION`(`DESCRIPCION`,`ESTADO`)
                    VALUES('" . $section->getDescription() . "',
                            '" . $section->getStatus() . "')";
		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}

	function Update(Section $section)
	{
		$success = false;
		$connection = new Connection();

		$sql = "UPDATE SECCION SET  DESCRIPCION='" . $section->getDescription() . "',
                                        ESTADO='" . $section->getStatus() . "'
                                        Where `ID` =" . $section->getId();
		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}
}
