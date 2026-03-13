<?php
require_once "./Model/Connection.php";
require_once "./Model/Entities/Attendance.php";
require_once "./Model/Entities/Transaction.php";
require_once "./Model/Entities/Teacher.php";
require_once "./Model/Entities/Student.php";
require_once "./Model/Methods/AttendanceMethods.php";
require_once "./Model/Methods/TransactionMethods.php";
require_once "./Model/Methods/TeacherMethods.php";
require_once "./Model/Methods/StudentMethods.php";

class AttendanceController
{
	public function Index()
	{
		require_once "./View/views/admin/Attendance.php";
	}

	public function AttendanceRecord()
	{
		$studentMethods = new StudentMethods();
		$teacherMethods = new TeacherMethods();
		$allTeachers = $teacherMethods->FindAll();
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
					$students[$i] += array("becado" => $allStudents[$i]->getScholarship());
					$students[$i] += array("perfil" => "Estudiante");
				}
			}
		}

		if (isset($students))
			$students = json_encode($students);
		else
			$students = null;

		if ($allTeachers != null) {
			$teachers = array();
			for ($i = 0; $i < sizeof($allTeachers); $i++) {
				if ($allTeachers[$i]->getStatus() == 1) {
					$teachers[$i] = array("id" => $allTeachers[$i]->getId());
					$teachers[$i] += array("nombre" => $allTeachers[$i]->getName());
					$teachers[$i] += array("apellido1" => $allTeachers[$i]->getFirstLastName());
					$teachers[$i] += array("apellido2" => $allTeachers[$i]->getSecondLastName());
					$teachers[$i] += array("cedula" => $allTeachers[$i]->getIdCard());
					$teachers[$i] += array("perfil" => "Profesor");
				}
			}
		}

		if (isset($teachers))
			$teachers = json_encode($teachers);
		else
			$teachers = null;

		require_once "./View/views/admin/AttendanceRecord.php";
	}

	public function AttendanceDetails($id)
	{
		require_once './Controller/admin/AttendanceController.php';
		$studentMethods = new StudentMethods();
		$teacherMethods = new TeacherMethods();
		$sectionMethods = new SectionMethods();
		$specialtyMethods = new SpecialtyMethods();
		$section = new Section();
		$specialty = new Specialty();
		$attendanceController = new AttendanceController();
		$profile = $_REQUEST['perfil'];

		if ($studentMethods->Find($id) && $profile == "Estudiante") {
			$client = $studentMethods->Find($id);
			$section = $sectionMethods->Find($client->getSectionId());
			$specialty = $specialtyMethods->Find($client->getSpecialtyId());
			$attendanceRecords = $attendanceController->FilterAttendanceByPeriod(date('Y') . '-01-01', date('Y-m-d'), $id, null);
		} else if ($teacherMethods->Find($id) && $profile == "Profesor") {
			$client = $teacherMethods->Find($id);
			$attendanceRecords = $attendanceController->FilterAttendanceByPeriod(date('Y') . '-01-01', date('Y-m-d'), null, $id);
		}

		$attendanceRecords = json_encode($attendanceRecords);
		require_once "./View/views/admin/AttendanceDetails.php";
	}

	public function IsPresent($date, $clientAttendances)
	{
		$present = false;
		//This block checks if a student/teacher is present on X date.
		if ($clientAttendances != null) {
			foreach ($clientAttendances as $attendance) {
				if ($attendance->getDate() == $date)
					$present = true;
			}
		}

		if (!$present)
			return false;

		return true;
	}

	public function FilterAttendanceByPeriod($startDate, $endDate, $studentId, $teacherId)
	{
		require_once './Controller/admin/AttendanceController.php';
		require_once './Controller/helpers/DateController.php';
		$attendanceController = new AttendanceController();
		$attendanceMethods = new AttendanceMethods();
		$dateController = new DateController();

		if ($studentId != null || $studentId != 0)
			$clientAttendances = $attendanceMethods->FindStudentAttendances($studentId);
		else
			$clientAttendances = $attendanceMethods->FindTeacherAttendances($teacherId);

		$attendanceRecordArray = array();
		$startDate = strtotime($startDate);
		$endDate = strtotime($endDate);
		$counter = 0;

		for ($day = $startDate; $day <= $endDate; $day += 86400) {
			$date = date('Y-m-d', $day);
			if ($dateController->IsSchoolDay($date)) {
				if ($attendanceController->IsPresent($date, $clientAttendances)) {
					$attendanceRecordArray[$counter] = array('Fecha' => $date);
					$attendanceRecordArray[$counter] += array('Estado' => "Presente");
				} else {
					$attendanceRecordArray[$counter] = array('Fecha' => $date);
					$attendanceRecordArray[$counter] += array('Estado' => "Ausente");
				}
			}
			$counter++;
		}

		if ($attendanceRecordArray != null)
			return $attendanceRecordArray;
	}

	public function TakeAttendance()
	{
		require_once './Controller/admin/AttendanceController.php';
		require_once './Controller/helpers/DateController.php';
		$attendanceController = new AttendanceController();
		$data = json_decode(file_get_contents('php://input'), true);
		$dateController = new DateController();
		$idCard = $data['Cedula'];
		$time = $data['Hora'];
		$today = $data['Fecha'];
		$today = date("Y-m-d", strtotime($today));
		$student = new Student();
		$teacher = new Teacher();
		$studentMethods = new StudentMethods();
		$teacherMethods = new TeacherMethods();

		if (!$dateController->IsSchoolDay($today)) {
			echo '{"message":"Hoy no es un día lectivo."}';
			return;
		}

		if (!$teacherMethods->FindByIdCard($idCard) && !$studentMethods->FindByIdCard($idCard))
			echo '{"message":"Usuario Inexistente."}';

		if ($teacher = $teacherMethods->FindByIdCard($idCard)) {
			echo $attendanceController->TeacherAttendance($teacher, $today, $time);
			return;
		}

		if ($student = $studentMethods->FindByIdCard($idCard)) {
			echo $attendanceController->StudentAttendance($student, $today, $time);
			return;
		}
	}

	private function TeacherAttendance(Teacher $teacher, $today, $time)
	{
		$array = array('message' => "", 'Nombre' => $teacher->getName(), 'Apellido1' => $teacher->getFirstLastName(), 'Apellido2' => $teacher->getSecondLastName(), 'fotoPerfil' => $teacher->getProfilePhoto());

		$attendance = new Attendance();
		$transaction = new Transaction();
		$attendanceMethods = new AttendanceMethods();
		$transactionMethods = new TransactionMethods();
		$teacherMethods = new TeacherMethods();

		$teacherAttendances = $attendanceMethods->FindTeacherAttendances($teacher->getId());
		$status = true;

		//This small block checks if the teacher has already attended today
		if ($teacherAttendances != null) {
			foreach ($teacherAttendances as $teacherAttendance) {
				if ($teacherAttendance->getDate() == $today)
					$status = false;
			}
		}

		if (!$status) {
			$array['message'] = "Usted ya está presente.";
			return json_encode($array);
		}

		if (!$teacher->getMeals() > 0) {
			$array['message'] = "No tiene comidas.";
			return json_encode($array);
		}

		$teacher->setMeals($teacher->getMeals() - 1);
		$teacherMethods->Update($teacher);
		//attendances
		$attendance->setTeacherId($teacher->getId());
		$attendance->setStudentId(0);
		$attendance->setDate($today);
		$attendance->setStatus(1);
		$attendanceMethods->Create($attendance);
		//transactions
		$transaction->setTeacherId($teacher->getId());
		$transaction->setStudentId(0);
		$transaction->setDate($today);
		$transaction->setTime($time);
		$transaction->setStatus(1);
		$transaction->setMeals(-1);
		$transactionMethods->Create($transaction);

		$array['message'] = "Pase adelante.";
		return json_encode($array);
	}

	private function StudentAttendance(Student $student, $today, $time)
	{
		$array = array('message' => "", 'Nombre' => $student->getName(), 'Apellido1' => $student->getFirstLastName(), 'Apellido2' => $student->getSecondLastName(), 'fotoPerfil' => $student->getProfilePhoto());

		$attendance = new Attendance();
		$transaction = new Transaction();
		$attendanceMethods = new AttendanceMethods();
		$transactionMethods = new TransactionMethods();
		$studentMethods = new StudentMethods();

		$studentAttendances = $attendanceMethods->FindStudentAttendances($student->getId());
		$status = true;
		if ($studentAttendances != null) {
			foreach ($studentAttendances as $studentAttendance) {
				if ($studentAttendance->getDate() == $today)
					$status = false;
			}
		}

		if (!$status) {
			$array['message'] = "Usted ya está presente.";
			return json_encode($array);
		}

		if (!$student->getMeals() > 0 && !$student->getScholarship() == 1) {
			$array['message'] = "No tiene comidas.";
			return json_encode($array);
		}

		if ($student->getScholarship() != 1)
			$student->setMeals($student->getMeals() - 1);

		$studentMethods->Update($student);
		$attendance->setTeacherId(0);
		$attendance->setStudentId($student->getId());
		$attendance->setDate($today);
		$attendance->setStatus(1);
		$attendanceMethods->Create($attendance);

		if ($student->getScholarship() != 1) {
			$transaction->setTeacherId(0);
			$transaction->setStudentId($student->getId());
			$transaction->setDate($today);
			$transaction->setTime($time);
			$transaction->setStatus(1);
			$transaction->setMeals(-1);
			$transactionMethods->Create($transaction);
		}

		$array['message'] = "Pase adelante.";
		return json_encode($array);
	}
}