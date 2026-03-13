<?php

class TransactionMethods
{
	function FindAllByStudent($studentId)
	{
		$allTransactions = array();
		$connection = new Connection();

		$sql = "SELECT * FROM `TRANSACCION` WHERE `IDESTUDIANTE` = '$studentId' ORDER BY `FECHA` DESC";
		$result = $connection->Execute($sql);
		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$transaction = new Transaction();
				$transaction->setId($row["ID"]);
				$transaction->setStudentId($row["IDESTUDIANTE"]);
				$transaction->setTeacherId($row["IDPROFESOR"]);
				$transaction->setDate($row["FECHA"]);
				$transaction->setTime($row["HORA"]);
				$transaction->setMeals($row["COMIDAS"]);
				$transaction->setStatus($row["ESTADO"]);
				$allTransactions[] = $transaction;
			}
		} else {
			$allTransactions = null;
		}
		$connection->Close();
		return $allTransactions;
	}

	function FindAllByTeacher($teacherId)
	{
		$allTransactions = array();
		$connection = new Connection();

		$sql = "SELECT * FROM `TRANSACCION` WHERE `IDPROFESOR` = '$teacherId' ORDER BY `FECHA` DESC";
		$result = $connection->Execute($sql);
		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$transaction = new Transaction();
				$transaction->setId($row["ID"]);
				$transaction->setStudentId($row["IDESTUDIANTE"]);
				$transaction->setTeacherId($row["IDPROFESOR"]);
				$transaction->setDate($row["FECHA"]);
				$transaction->setTime($row["HORA"]);
				$transaction->setMeals($row["COMIDAS"]);
				$transaction->setStatus($row["ESTADO"]);
				$allTransactions[] = $transaction;
			}
		} else {
			$allTransactions = null;
		}
		$connection->Close();
		return $allTransactions;
	}

	function FindStudent($studentId)
	{
		$transaction = new Transaction();
		$allTransactions = array();
		$connection = new Connection();

		$sql = "SELECT ID, IDESTUDIANTE, FECHA, HORA, COMIDAS, ESTADO FROM `TRANSACCION` WHERE `IDESTUDIANTE` = '$studentId'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$transaction->setId($row["ID"]);
				$transaction->setStudentId($row["IDESTUDIANTE"]);
				$transaction->setDate($row["FECHA"]);
				$transaction->setTime($row["HORA"]);
				$transaction->setMeals($row["COMIDAS"]);
				$transaction->setStatus($row["ESTADO"]);
				$allTransactions[] = $transaction;
			}
		} else {
			$allTransactions = null;
		}
		$connection->Close();
		return $allTransactions;
	}

	function Find($id)
	{
		$transaction = new Transaction();
		$connection = new Connection();

		$sql = "SELECT * FROM `TRANSACCION` WHERE `ID` = '$id'";
		$result = $connection->Execute($sql);

		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$transaction->setId($row["ID"]);
				$transaction->setStudentId($row["IDESTUDIANTE"]);
				$transaction->setTeacherId($row["IDPROFESOR"]);
				$transaction->setDate($row["FECHA"]);
				$transaction->setTime($row["HORA"]);
				$transaction->setMeals($row["COMIDAS"]);
				$transaction->setStatus($row["ESTADO"]);
			}
		} else {
			$transaction = null;
		}
		$connection->Close();
		return $transaction;
	}

	function FindAll()
	{
		$allTransactions = array();
		$connection = new Connection();

		$sql = "SELECT * FROM `TRANSACCION`";
		$result = $connection->Execute($sql);
		if (mysqli_num_rows($result) > 0) {
			while ($row = $result->fetch_assoc()) {
				$transaction = new Transaction();
				$transaction->setId($row["ID"]);
				$transaction->setStudentId($row["IDESTUDIANTE"]);
				$transaction->setTeacherId($row["IDPROFESOR"]);
				$transaction->setDate($row["FECHA"]);
				$transaction->setTime($row["HORA"]);
				$transaction->setMeals($row["COMIDAS"]);
				$transaction->setStatus($row["ESTADO"]);
				$allTransactions[] = $transaction;
			}
		} else {
			$allTransactions = null;
		}
		$connection->Close();
		return $allTransactions;
	}

	function Create(Transaction $transaction)
	{
		$success = false;
		$connection = new Connection();

		$sql = "INSERT INTO `TRANSACCION`(`IDESTUDIANTE`, `IDPROFESOR`,`FECHA`,`HORA`,`COMIDAS`,`ESTADO`)
            VALUES('" . $transaction->getStudentId() . "',
                '" . $transaction->getTeacherId() . "',
                '" . $transaction->getDate() . "',
                '" . $transaction->getTime() . "',
                '" . $transaction->getMeals() . "',
                '" . $transaction->getStatus() . "')";
		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}

	function Update(Transaction $transaction)
	{
		$success = false;
		$connection = new Connection();

		$sql = "UPDATE TRANSACCION SET IDESTUDIANTE='" . $transaction->getStudentId() . "',
                                        IDPROFESOR='" . $transaction->getTeacherId() . "',
                                        FECHA='" . $transaction->getDate() . "',
                                        HORA='" . $transaction->getTime() . "',
                                        COMIDAS='" . $transaction->getMeals() . "',
                                        ESTADO='" . $transaction->getStatus() . "'
                                        Where `ID` =" . $transaction->getId();
		if ($connection->Execute($sql)) {
			$success = true;
		}
		$connection->Close();
		return $success;
	}
}
