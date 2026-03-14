<?php

class StaffMethods
{
	function Find($id)
	{
		$staff = new Staff();
		$connection = new Connection();

		$sql = "SELECT * FROM `FUNCIONARIO` WHERE `ID` = '$id'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$staff->setId($row["ID"]);
				$staff->setProfile($row["PERFIL"]);
				$staff->setName($row["NOMBRE"]);
				$staff->setFirstLastName($row["PRIMERAPELLIDO"]);
				$staff->setSecondLastName($row["SEGUNDOAPELLIDO"]);
				$staff->setEmail($row["CORREO"]);
				$staff->setPassword($row["CONTRASENA"]);
				$staff->setStatus($row["ESTADO"]);
			}
		} else {
			$staff = null;
		}
		$connection->Close();
		return $staff;
	}

	function FindByEmail($email)
	{
		$staff = new Staff();
		$connection = new Connection();

		$sql = "SELECT * FROM `FUNCIONARIO` WHERE `CORREO` = '$email'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$staff->setId($row["ID"]);
				$staff->setName($row["NOMBRE"]);
				$staff->setFirstLastName($row["PRIMERAPELLIDO"]);
				$staff->setSecondLastName($row["SEGUNDOAPELLIDO"]);
				$staff->setProfile($row["PERFIL"]);
				$staff->setEmail($row["CORREO"]);
				$staff->setPassword($row["CONTRASENA"]);
				$staff->setStatus($row["ESTADO"]);
			}
		} else {
			$staff = null;
		}
		$connection->Close();
		return $staff;
	}

	function FindAll()
	{
		$allStaff = array();
		$connection = new Connection();

		$sql = "SELECT * FROM `FUNCIONARIO`";
		$result = $connection->Execute($sql);
		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$staff = new Staff();
				$staff->setId($row["ID"]);
				$staff->setProfile($row["PERFIL"]);
				$staff->setName($row["NOMBRE"]);
				$staff->setFirstLastName($row["PRIMERAPELLIDO"]);
				$staff->setSecondLastName($row["SEGUNDOAPELLIDO"]);
				$staff->setEmail($row["CORREO"]);
				$staff->setPassword($row["CONTRASENA"]);
				$staff->setStatus($row["ESTADO"]);
				$allStaff[] = $staff;
			}
		} else {
			$allStaff = null;
		}
		$connection->Close();
		return $allStaff;
	}

	function FindAllByProfile($profile)
	{
		$allStaff = array();
		$connection = new Connection();

		$sql = "SELECT * FROM `FUNCIONARIO` WHERE `PERFIL` = '$profile'";
		$result = $connection->Execute($sql);
		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$staff = new Staff();
				$staff->setId($row["ID"]);
				$staff->setProfile($row["PERFIL"]);
				$staff->setName($row["NOMBRE"]);
				$staff->setFirstLastName($row["PRIMERAPELLIDO"]);
				$staff->setSecondLastName($row["SEGUNDOAPELLIDO"]);
				$staff->setEmail($row["CORREO"]);
				$staff->setPassword($row["CONTRASENA"]);
				$staff->setStatus($row["ESTADO"]);
				$allStaff[] = $staff;
			}
		} else {
			$allStaff = null;
		}
		$connection->Close();
		return $allStaff;
	}

	function Create(Staff $staff)
	{
		$success = false;
		$connection = new Connection();

		$sql = "INSERT INTO `FUNCIONARIO`(`PERFIL`,`NOMBRE`,`PRIMERAPELLIDO`,`SEGUNDOAPELLIDO`, `CORREO`, `CONTRASENA`, `ESTADO`)
                    VALUES('" . $staff->getProfile() . "',
                           '" . $staff->getName() . "',
                            '" . $staff->getFirstLastName() . "',
                            '" . $staff->getSecondLastName() . "',
                            '" . $staff->getEmail() . "',
                            '" . $staff->getPassword() . "',
                            '" . $staff->getStatus() . "')";
		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}

	function Update(Staff $staff)
	{
		$success = false;
		$connection = new Connection();

		$sql = "UPDATE FUNCIONARIO SET  PERFIL='" . $staff->getProfile() . "',
                                        NOMBRE='" . $staff->getName() . "',
                                        PRIMERAPELLIDO='" . $staff->getFirstLastName() . "',
                                        SEGUNDOAPELLIDO='" . $staff->getSecondLastName() . "',
                                        CORREO='" . $staff->getEmail() . "',
                                        ESTADO='" . $staff->getStatus() . "'
                                        Where `ID` =" . $staff->getId();
		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}

	function UpdatePassword($password, $staffId)
	{
		$connection = new Connection();
		$mysqli = $connection->Raw();
		$stmt = $mysqli->prepare("UPDATE FUNCIONARIO SET CONTRASENA=? WHERE ID=?");
		$stmt->bind_param('si', $password, $staffId);
		$success = $stmt->execute();
		$stmt->close();
		$mysqli->close();
		return $success;
	}
}
