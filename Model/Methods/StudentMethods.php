<?php

class StudentMethods
{
	function Find($id)
	{
		$student = new Student();
		$connection = new Connection();

		$sql = "SELECT * FROM `ESTUDIANTE` WHERE `ID` = '$id'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$student->setId($row["ID"]);
				$student->setName($row["NOMBRE"]);
				$student->setFirstLastName($row["PRIMERAPELLIDO"]);
				$student->setSecondLastName($row["SEGUNDOAPELLIDO"]);
				$student->setIdCard($row["CEDULA"]);
				$student->setMeals($row["COMIDAS"]);
				$student->setSpecialtyId($row["IDESPECIALIDAD"]);
				$student->setSectionId($row["IDSECCION"]);
				$student->setEmail($row["CORREO"]);
				$student->setPassword($row["CONTRASENA"]);
				$student->setStatus($row["ESTADO"]);
				$student->setScholarship($row["BECADO"]);
				$student->setProfile($row["PERFIL"]);
				$student->setProfilePhoto($row["FOTOPERFIL"]);
			}
		} else {
			$student = null;
		}
		$connection->Close();
		return $student;
	}

	function FindByIdCard($idCard)
	{
		$student = new Student();
		$connection = new Connection();

		$sql = "SELECT * FROM `ESTUDIANTE` WHERE `CEDULA` = '$idCard'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$student->setId($row["ID"]);
				$student->setName($row["NOMBRE"]);
				$student->setFirstLastName($row["PRIMERAPELLIDO"]);
				$student->setSecondLastName($row["SEGUNDOAPELLIDO"]);
				$student->setIdCard($row["CEDULA"]);
				$student->setMeals($row["COMIDAS"]);
				$student->setSpecialtyId($row["IDESPECIALIDAD"]);
				$student->setSectionId($row["IDSECCION"]);
				$student->setProfile($row["PERFIL"]);
				$student->setEmail($row["CORREO"]);
				$student->setPassword($row["CONTRASENA"]);
				$student->setScholarship($row["BECADO"]);
				$student->setStatus($row["ESTADO"]);
				$student->setProfilePhoto($row["FOTOPERFIL"]);
			}
		} else {
			$student = null;
		}
		$connection->Close();
		return $student;
	}

	function FindByEmail($email)
	{
		$student = new Student();
		$connection = new Connection();

		$sql = "SELECT * FROM `ESTUDIANTE` WHERE `CORREO` = '$email'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$student->setId($row["ID"]);
				$student->setName($row["NOMBRE"]);
				$student->setFirstLastName($row["PRIMERAPELLIDO"]);
				$student->setSecondLastName($row["SEGUNDOAPELLIDO"]);
				$student->setIdCard($row["CEDULA"]);
				$student->setMeals($row["COMIDAS"]);
				$student->setSpecialtyId($row["IDESPECIALIDAD"]);
				$student->setSectionId($row["IDSECCION"]);
				$student->setEmail($row["CORREO"]);
				$student->setPassword($row["CONTRASENA"]);
				$student->setStatus($row["ESTADO"]);
				$student->setScholarship($row["BECADO"]);
				$student->setProfile($row["PERFIL"]);
				$student->setProfilePhoto($row["FOTOPERFIL"]);
			}
		} else {
			$student = null;
		}
		$connection->Close();
		return $student;
	}

	function FindAll()
	{
		$allStudents = array();
		$connection = new Connection();

		$sql = "SELECT * FROM `ESTUDIANTE`";
		$result = $connection->Execute($sql);
		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$student = new Student();
				$student->setId($row["ID"]);
				$student->setName($row["NOMBRE"]);
				$student->setFirstLastName($row["PRIMERAPELLIDO"]);
				$student->setSecondLastName($row["SEGUNDOAPELLIDO"]);
				$student->setIdCard($row["CEDULA"]);
				$student->setSpecialtyId($row["IDESPECIALIDAD"]);
				$student->setSectionId($row["IDSECCION"]);
				$student->setEmail($row["CORREO"]);
				$student->setPassword($row["CONTRASENA"]);
				$student->setMeals($row["COMIDAS"]);
				$student->setStatus($row["ESTADO"]);
				$student->setScholarship($row["BECADO"]);
				$student->setProfile($row["PERFIL"]);
				$student->setProfilePhoto($row["FOTOPERFIL"]);
				$allStudents[] = $student;
			}
		} else {
			$allStudents = null;
		}
		$connection->Close();
		return $allStudents;
	}

	function Create(Student $student)
	{
		$success = false;
		$connection = new Connection();

		$sql = "INSERT INTO `ESTUDIANTE`(`NOMBRE`,`PRIMERAPELLIDO`,`SEGUNDOAPELLIDO`, `CEDULA`,`COMIDAS`,`IDESPECIALIDAD`,`IDSECCION`, `CORREO`, `CONTRASENA`, `ESTADO`,`BECADO`, `FOTOPERFIL`, `PERFIL`)
                    VALUES('" . $student->getName() . "',
                                '" . $student->getFirstLastName() . "',
                                '" . $student->getSecondLastName() . "',
                                '" . $student->getIdCard() . "',
                                '" . $student->getMeals() . "',
                                '" . $student->getSpecialtyId() . "',
                                '" . $student->getSectionId() . "',
                                '" . $student->getEmail() . "',
                                '" . $student->getPassword() . "',
                                '" . $student->getStatus() . "',
                                '" . $student->getScholarship() . "',
                                '" . $student->getProfilePhoto() . "',
                                '" . $student->getProfile() . "')";

		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}

	function Update(Student $student)
	{
		$success = false;
		$connection = new Connection();

		$sql = "UPDATE ESTUDIANTE SET NOMBRE='" . $student->getName() . "',PRIMERAPELLIDO='" . $student->getFirstLastName() . "',
        SEGUNDOAPELLIDO='" . $student->getSecondLastName() . "',CEDULA='" . $student->getIdCard() . "',
        CORREO='" . $student->getEmail() . "',COMIDAS='" . $student->getMeals() . "',CONTRASENA='" . $student->getPassword() . "',ESTADO='" . $student->getStatus() . "',
        IDESPECIALIDAD='" . $student->getSpecialtyId() . "',IDSECCION='" . $student->getSectionId() . "',FOTOPERFIL='" . $student->getProfilePhoto() . "',BECADO='" . $student->getScholarship() . "' Where `ID` =" . $student->getId();

		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}

	function UpdatePassword($password, $studentId)
	{
		$success = false;
		$connection = new Connection();

		$sql = "UPDATE ESTUDIANTE SET CONTRASENA='" . $password . "'
                                        Where `ID` =" . $studentId;
		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}
}
