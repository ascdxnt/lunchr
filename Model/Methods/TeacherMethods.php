<?php

class TeacherMethods
{
	function Find($id)
	{
		$teacher = new Teacher();
		$connection = new Connection();

		$sql = "SELECT * FROM `PROFESOR` WHERE `ID` = '$id'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$teacher->setId($row["ID"]);
				$teacher->setName($row["NOMBRE"]);
				$teacher->setFirstLastName($row["PRIMERAPELLIDO"]);
				$teacher->setSecondLastName($row["SEGUNDOAPELLIDO"]);
				$teacher->setIdCard($row["CEDULA"]);
				$teacher->setMeals($row["COMIDAS"]);
				$teacher->setEmail($row["CORREO"]);
				$teacher->setPassword($row["CONTRASENA"]);
				$teacher->setStatus($row["ESTADO"]);
				$teacher->setProfilePhoto($row["FOTOPERFIL"]);
			}
		} else {
			$teacher = null;
		}
		$connection->Close();
		return $teacher;
	}

	function FindByEmail($email)
	{
		$teacher = new Teacher();
		$connection = new Connection();

		$sql = "SELECT * FROM `PROFESOR` WHERE `CORREO` = '$email'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$teacher->setId($row["ID"]);
				$teacher->setName($row["NOMBRE"]);
				$teacher->setFirstLastName($row["PRIMERAPELLIDO"]);
				$teacher->setSecondLastName($row["SEGUNDOAPELLIDO"]);
				$teacher->setIdCard($row["CEDULA"]);
				$teacher->setMeals($row["COMIDAS"]);
				$teacher->setEmail($row["CORREO"]);
				$teacher->setPassword($row["CONTRASENA"]);
				$teacher->setStatus($row["ESTADO"]);
				$teacher->setProfilePhoto($row["FOTOPERFIL"]);
			}
		} else {
			$teacher = null;
		}
		$connection->Close();
		return $teacher;
	}

	function FindByIdCard($idCard)
	{
		$teacher = new Teacher();
		$connection = new Connection();

		$sql = "SELECT * FROM `PROFESOR` WHERE `CEDULA` = '$idCard'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$teacher->setId($row["ID"]);
				$teacher->setName($row["NOMBRE"]);
				$teacher->setFirstLastName($row["PRIMERAPELLIDO"]);
				$teacher->setSecondLastName($row["SEGUNDOAPELLIDO"]);
				$teacher->setIdCard($row["CEDULA"]);
				$teacher->setMeals($row["COMIDAS"]);
				$teacher->setEmail($row["CORREO"]);
				$teacher->setPassword($row["CONTRASENA"]);
				$teacher->setStatus($row["ESTADO"]);
				$teacher->setProfilePhoto($row["FOTOPERFIL"]);
			}
		} else {
			$teacher = null;
		}
		$connection->Close();
		return $teacher;
	}

	function FindAll()
	{
		$connection = new Connection();

		$sql = "SELECT * FROM `PROFESOR`";
		$result = $connection->Execute($sql);
		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$teacher = new Teacher();
				$teacher->setId($row["ID"]);
				$teacher->setName($row["NOMBRE"]);
				$teacher->setFirstLastName($row["PRIMERAPELLIDO"]);
				$teacher->setSecondLastName($row["SEGUNDOAPELLIDO"]);
				$teacher->setIdCard($row["CEDULA"]);
				$teacher->setMeals($row["COMIDAS"]);
				$teacher->setEmail($row["CORREO"]);
				$teacher->setPassword($row["CONTRASENA"]);
				$teacher->setStatus($row["ESTADO"]);
				$teacher->setProfilePhoto($row["FOTOPERFIL"]);
				$allTeachers[] = $teacher;
			}
		} else {
			$allTeachers = null;
		}
		$connection->Close();
		return $allTeachers;
	}

	function Create(Teacher $teacher)
	{
		$success = false;
		$connection = new Connection();

		$sql = "INSERT INTO `PROFESOR`(`NOMBRE`,`PRIMERAPELLIDO`,`SEGUNDOAPELLIDO`, `CEDULA`,`COMIDAS`, `CORREO`, `CONTRASENA`, `ESTADO`,`PERFIL`, `FOTOPERFIL`)
                    VALUES('" . $teacher->getName() . "',
                                '" . $teacher->getFirstLastName() . "',
                                '" . $teacher->getSecondLastName() . "',
                                '" . $teacher->getIdCard() . "',
                                '" . $teacher->getMeals() . "',
                                '" . $teacher->getEmail() . "',
                                '" . $teacher->getPassword() . "',
                                '" . $teacher->getStatus() . "',
                                '" . $teacher->getProfile() . "',
                                '" . $teacher->getProfilePhoto() . "')";

		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}

	public function Update(Teacher $teacher)
	{
		$success = false;
		$connection = new Connection();

		$sql = "UPDATE PROFESOR SET NOMBRE='" . $teacher->getName() . "',PRIMERAPELLIDO='" . $teacher->getFirstLastName() . "',SEGUNDOAPELLIDO='" . $teacher->getSecondLastName() . "',CEDULA='" . $teacher->getIdCard() . "',COMIDAS='" . $teacher->getMeals() . "',CORREO='" . $teacher->getEmail() . "',FOTOPERFIL='" . $teacher->getProfilePhoto() . "',CONTRASENA='" . $teacher->getPassword() . "',ESTADO='" . $teacher->getStatus() . "'Where `ID` =" . $teacher->getId();

		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}

	function UpdatePassword($password, $teacherId)
	{
		$success = false;
		$connection = new Connection();

		$sql = "UPDATE PROFESOR SET CONTRASENA='" . $password . "' Where `ID` =" . $teacherId;
		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}
}
