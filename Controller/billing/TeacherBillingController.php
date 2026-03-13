<?php
require_once './Model/Connection.php';
require_once './Model/Entities/Teacher.php';
require_once './Model/Methods/TeacherMethods.php';
require_once './Model/Methods/TransactionMethods.php';
require_once './Model/Entities/Transaction.php';

class TeacherBillingController
{
	public function Index()
	{
		$teacherMethods = new TeacherMethods();
		$allTeachers = $teacherMethods->FindAll();

		if ($allTeachers != null) {
			$teachers = array();
			for ($i = 0; $i < sizeof($allTeachers); $i++) {
				if ($allTeachers[$i]->getStatus() == 1) {
					$teachers[$i] = array("id" => $allTeachers[$i]->getId());
					$teachers[$i] += array("nombre" => $allTeachers[$i]->getName());
					$teachers[$i] += array("apellido1" => $allTeachers[$i]->getFirstLastName());
					$teachers[$i] += array("apellido2" => $allTeachers[$i]->getSecondLastName());
					$teachers[$i] += array("cedula" => $allTeachers[$i]->getIdCard());
					$teachers[$i] += array("comidas" => $allTeachers[$i]->getMeals());
				}
			}
		}

		if (isset($teachers))
			$teachers = json_encode($teachers);
		else
			$teachers = null;

		// Variable alias for view compatibility (view expects $profesores)
		$profesores = $teachers;

		require_once "./View/views/billing/SearchTeacher.php";
	}

	public function AddMeals()
	{
		$teacherId = $_POST['idProfesor'];
		$meals = $_POST['comidas'];
		$time = $_POST['hora'];
		$today = $_POST['fechaHoy'];
		$today = date("Y-m-d", strtotime($today));
		$transaction = new Transaction();
		$transactionMethods = new TransactionMethods();

		$teacher = new Teacher();
		$teacherMethods = new TeacherMethods();

		if ($meals <= 0)
			header('Location: ./?dir=billing&controller=TeacherBilling&action=Index&alerta=error');
		else if ($teacher = $teacherMethods->Find($teacherId)) {
			$teacher->setMeals($teacher->getMeals() + $meals);
			if ($teacherMethods->Update($teacher)) {
				$transaction->setStudentId(0);
				$transaction->setTeacherId($teacher->getId());
				$transaction->setDate($today);
				$transaction->setTime($time);
				$transaction->setStatus(1);
				$transaction->setMeals($meals);
				$transactionMethods->Create($transaction);
				header('Location: ./?dir=billing&controller=TeacherBilling&action=Index&alerta=success');
			} else
				header('Location: ./?dir=billing&controller=TeacherBilling&action=Index&alerta=error');
		} else
			header('Location: ./?dir=billing&controller=TeacherBilling&action=Index&alerta=error');
	}
}
