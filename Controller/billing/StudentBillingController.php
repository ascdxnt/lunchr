<?php
require_once './Model/Connection.php';
require_once './Model/Entities/Student.php';
require_once './Model/Methods/StudentMethods.php';
require_once './Model/Methods/TransactionMethods.php';
require_once './Model/Entities/Transaction.php';

class StudentBillingController
{
	public function Index()
	{
		$studentMethods = new StudentMethods();
		$allStudents = $studentMethods->FindAll();

		if ($allStudents != null) {
			$students = array();
			for ($i = 0; $i < sizeof($allStudents); $i++) {
				if ($allStudents[$i]->getStatus() == 1) {
					$students[$i] = array("id" => $allStudents[$i]->getId());
					$students[$i] += array("nombre" => $allStudents[$i]->getName());
					$students[$i] += array("apellido1" => $allStudents[$i]->getFirstLastName());
					$students[$i] += array("apellido2" => $allStudents[$i]->getSecondLastName());
					$students[$i] += array("cedula" => $allStudents[$i]->getIdCard());
					$students[$i] += array("comidas" => $allStudents[$i]->getMeals());
					$students[$i] += array("becado" => $allStudents[$i]->getScholarship());
				}
			}
		}

		if (isset($students))
			$students = json_encode($students);
		else
			$students = null;

		// Variable alias for view compatibility (view expects $estudiantes)
		$estudiantes = $students;

		require_once "./View/views/billing/SearchStudent.php";
	}

	public function AddMeals()
	{
		$studentId = $_POST['idEstudiante'];
		$meals = $_POST['comidas'];
		$time = $_POST['hora'];
		$today = $_POST['fechaHoy'];
		$today = date("Y-m-d", strtotime($today));
		$transaction = new Transaction();
		$transactionMethods = new TransactionMethods();

		$student = new Student();
		$studentMethods = new StudentMethods();

		if ($meals <= 0)
			header('Location: ./?dir=billing&controller=StudentBilling&action=Index&alerta=error');
		else if ($student = $studentMethods->Find($studentId)) {
			$student->setMeals($student->getMeals() + $meals);
			if ($studentMethods->Update($student)) {
				if ($student->getScholarship() != 1) {
					$transaction->setTeacherId(0);
					$transaction->setStudentId($student->getId());
					$transaction->setDate($today);
					$transaction->setTime($time);
					$transaction->setStatus(1);
					$transaction->setMeals($meals);
					$transactionMethods->Create($transaction);
				}
				header('Location: ./?dir=billing&controller=StudentBilling&action=Index&alerta=success');
			} else
				header('Location: ./?dir=billing&controller=StudentBilling&action=Index&alerta=error');
		} else
			header('Location: ./?dir=billing&controller=StudentBilling&action=Index&alerta=error');
	}
}
