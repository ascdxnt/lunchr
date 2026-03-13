<?php

class AdminStatisticsController
{
	public function Index()
	{
		require_once './Controller/admin/AttendanceController.php';
		require_once './Model/Methods/StudentMethods.php';
		require_once './Model/Methods/TeacherMethods.php';
		require_once './Model/Methods/AttendanceMethods.php';

		if (isset($_POST['fechaInicio']))
			$startDate = $_POST['fechaInicio'];
		else
			$startDate = date('Y-m-') . "01";
		if (isset($_POST['fechaFin']))
			$endDate = $_POST['fechaFin'];
		else
			$endDate = date('Y-m-d');
		if (isset($_POST['perfil']))
			$profile = $_POST['perfil'];
		else
			$profile = "estudiante";
		if (isset($_POST['beca']))
			$scholarship = $_POST['beca'];
		else
			$scholarship = "cualquiera";

		$filterData = array("FechaInicio" => $startDate, "FechaFin" => $endDate, "Perfil" => $profile, "Beca" => $scholarship);
		$studentMethods = new StudentMethods();
		$teacherMethods = new TeacherMethods();
		$attendanceMethods = new AttendanceMethods();
		$attendanceController = new AttendanceController();
		$allStudents = $studentMethods->FindAll();
		$allTeachers = $teacherMethods->FindAll();
		$attendanceRecords = array();
		$mostAbsentClients = array();
		$attendanceCount = 0;
		$sections = $this->querySections();
		$specialties = $this->querySpecialties();

		if ($allStudents != null && $profile == "estudiante") {
			foreach ($allStudents as $student) {
				if ($student->getStatus() == 1 && ($student->getScholarship() == $scholarship || $scholarship == "cualquiera")) {
					//Here we count the student's absences and store them in an array
					$attendanceCount = $attendanceMethods->CountAttendances($student->getId(), null, $startDate, $endDate);
					array_push($mostAbsentClients, ['Nombre' => $student->getName(), 'Apellido1' => $student->getFirstLastName(), 'Apellido2' => $student->getSecondLastName(), "Cedula" => $student->getIdCard(), "Asistencias" => $attendanceCount, "IdSeccion" => $student->getSectionId(), "IdEspecialidad" => $student->getSpecialtyId()]);
					//Here we get absences and attendances and store them in an array
					array_push($attendanceRecords, json_encode($attendanceController->FilterAttendanceByPeriod($startDate, $endDate, $student->getId(), null)));
				}
			}
		}

		if ($allTeachers != null && $profile == "profesor") {
			foreach ($allTeachers as $teacher) {
				if ($teacher->getStatus() == 1) {
					$attendanceCount = $attendanceMethods->CountAttendances(null, $teacher->getId(), $startDate, $endDate);
					array_push($mostAbsentClients, ['Nombre' => $teacher->getName(), 'Apellido1' => $teacher->getFirstLastName(), 'Apellido2' => $teacher->getSecondLastName(), "Cedula" => $teacher->getIdCard(), "Asistencias" => $attendanceCount]);
					array_push($attendanceRecords, json_encode($attendanceController->FilterAttendanceByPeriod($startDate, $endDate, null, $teacher->getId())));
				}
			}
		}

		require_once "./View/views/admin/Statistics.php";
	}

	private function querySections()
	{
		require_once './Model/Methods/SectionMethods.php';
		require_once './Model/Entities/Section.php';

		$sectionMethods = new SectionMethods();
		$allSections = $sectionMethods->FindAll();

		if ($allSections == null) return null;

		$sections = array();
		for ($i = 0; $i < sizeof($allSections); $i++) {
			if ($allSections[$i]->getStatus() == 0) continue;

			$sections[$i] = array("id" => $allSections[$i]->getId());
			$sections[$i] += array("descripcion" => $allSections[$i]->getDescription());
		}

		return $sections;
	}

	private function querySpecialties()
	{
		require_once './Model/Methods/SpecialtyMethods.php';
		require_once './Model/Entities/Specialty.php';

		$specialtyMethods = new SpecialtyMethods();
		$allSpecialties = $specialtyMethods->FindAll();

		if ($allSpecialties == null) return null;

		$specialties = array();
		for ($i = 0; $i < sizeof($allSpecialties); $i++) {
			if ($allSpecialties[$i]->getStatus() == 0) continue;

			$specialties[$i] = array("id" => $allSpecialties[$i]->getId());
			$specialties[$i] += array("descripcion" => $allSpecialties[$i]->getDescription());
		}

		return $specialties;
	}
}