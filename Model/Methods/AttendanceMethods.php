<?php

class AttendanceMethods
{
	function Find($id)
	{
		$attendance = new Attendance();
		$connection = new Connection();

		$sql = "SELECT * FROM `ASISTENCIA` WHERE `ID` = '$id'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$attendance->setId($row["ID"]);
				$attendance->setStudentId($row["IDESTUDIANTE"]);
				$attendance->setTeacherId($row["IDPROFESOR"]);
				$attendance->setDate($row["FECHA"]);
				$attendance->setStatus($row["ESTADO"]);
			}
		} else {
			$attendance = null;
		}
		$connection->Close();
		return $attendance;
	}

	function FindStudentAttendances($studentId)
	{
		$allAttendances = array();
		$connection = new Connection();

		$sql = "SELECT * FROM `ASISTENCIA` WHERE `IDESTUDIANTE` = " . $studentId;
		$result = $connection->Execute($sql);
		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$attendance = new Attendance();
				$attendance->setId($row["ID"]);
				$attendance->setStudentId($row["IDESTUDIANTE"]);
				$attendance->setTeacherId($row["IDPROFESOR"]);
				$attendance->setDate($row["FECHA"]);
				$attendance->setStatus($row["ESTADO"]);
				$allAttendances[] = $attendance;
			}
		} else {
			$allAttendances = null;
		}
		$connection->Close();
		return $allAttendances;
	}

	function CountAttendances($studentId, $teacherId, $startDate, $endDate)
	{
		$connection = new Connection();

		if ($studentId != null)
			$sql = "SELECT COUNT(*) FROM `ASISTENCIA` WHERE IDESTUDIANTE = " . $studentId . " AND ESTADO = 1 AND FECHA >= '" . $startDate . "' AND FECHA <= '" . $endDate . "'";
		else
			$sql = "SELECT COUNT(*) FROM `ASISTENCIA` WHERE IDPROFESOR = " . $teacherId . " AND ESTADO = 1 AND FECHA >= '" . $startDate . "' AND FECHA <= '" . $endDate . "'";

		$result = $connection->Execute($sql);
		$row = $result->fetch_assoc();
		$result = $row["COUNT(*)"];

		if ($result != null)
			return $result;
		else
			return false;
	}

	function FindTeacherAttendances($teacherId)
	{
		$allAttendances = array();
		$connection = new Connection();

		$sql = "SELECT * FROM `ASISTENCIA` WHERE `IDPROFESOR` = " . $teacherId;
		$result = $connection->Execute($sql);
		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$attendance = new Attendance();
				$attendance->setId($row["ID"]);
				$attendance->setStudentId($row["IDESTUDIANTE"]);
				$attendance->setTeacherId($row["IDPROFESOR"]);
				$attendance->setDate($row["FECHA"]);
				$attendance->setStatus($row["ESTADO"]);
				$allAttendances[] = $attendance;
			}
		} else {
			$allAttendances = null;
		}
		$connection->Close();
		return $allAttendances;
	}

	function FindAll()
	{
		$allAttendances = array();
		$connection = new Connection();

		$sql = "SELECT * FROM `ASISTENCIA`";
		$result = $connection->Execute($sql);
		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$attendance = new Attendance();
				$attendance->setId($row["ID"]);
				$attendance->setStudentId($row["IDESTUDIANTE"]);
				$attendance->setTeacherId($row["IDPROFESOR"]);
				$attendance->setDate($row["FECHA"]);
				$attendance->setStatus($row["ESTADO"]);
				$allAttendances[] = $attendance;
			}
		} else {
			$allAttendances = null;
		}
		$connection->Close();
		return $allAttendances;
	}

	public function Create(Attendance $attendance)
	{
		$success = false;
		$connection = new Connection();

		$sql = "INSERT INTO `ASISTENCIA` (`IDESTUDIANTE`, `IDPROFESOR`, `FECHA`, `ESTADO`)
                    VALUES(" . $attendance->getStudentId() . ",
                           " . $attendance->getTeacherId() . ",
                           '" . $attendance->getDate() . "',
                            " . $attendance->getStatus() . ")";

		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}

	function Update(Attendance $attendance)
	{
		$success = false;
		$connection = new Connection();

		$sql = "UPDATE ASISTENCIA SET  IDESTUDIANTE='" . $attendance->getStudentId() . "',
                                        IDPROFESOR='" . $attendance->getTeacherId() . "',
                                        FECHA='" . $attendance->getDate() . "',
                                        ESTADO='" . $attendance->getStatus() . "'
                                        Where `ID` =" . $attendance->getId();
		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}
}
