<?php
require_once './Model/Connection.php';
require_once './Model/Entities/Transaction.php';
require_once './Model/Methods/TransactionMethods.php';
require_once './Model/Entities/Student.php';
require_once './Model/Methods/StudentMethods.php';
require_once './Model/Entities/Teacher.php';
require_once './Model/Methods/TeacherMethods.php';

class ClientHomeController
{
	public function Index($userId)
	{
		$transactionMethods = new TransactionMethods();
		$profile = $_REQUEST['perfil'];

		if ($profile == "Profesor") {
			$teacherTransactions = $transactionMethods->FindAllByTeacher($userId);
			if ($teacherTransactions != null) {
				$transactions = array();

				for ($i = 0; $i < sizeof($teacherTransactions); $i++) {
					if (!$teacherTransactions[$i]->getStatus() == 1) continue;

					$transactions[$i] = array("comidas" => $teacherTransactions[$i]->getMeals());
					$transactions[$i] += array("fecha" => $teacherTransactions[$i]->getDate());
					$transactions[$i] += array("hora" => $teacherTransactions[$i]->getTime());
				}
			}
		} else if ($profile == "Estudiante") {
			$studentTransactions = $transactionMethods->FindAllByStudent($userId);
			if ($studentTransactions != null) {
				$transactions = array();
				for ($i = 0; $i < sizeof($studentTransactions); $i++) {
					if (!$studentTransactions[$i]->getStatus() == 1) continue;

					$transactions[$i] = array("comidas" => $studentTransactions[$i]->getMeals());
					$transactions[$i] += array("fecha" => $studentTransactions[$i]->getDate());
					$transactions[$i] += array("hora" => $studentTransactions[$i]->getTime());
				}
			}
		}

		require_once "./View/views/client/Home.php";
	}
}
