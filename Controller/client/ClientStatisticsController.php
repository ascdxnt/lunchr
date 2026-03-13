<?php

class ClientStatisticsController
{
	public function Index()
	{
		require_once "./Controller/admin/AttendanceController.php";
		$attendanceController = new AttendanceController();
		$attendanceRecords;

		if ($_SESSION['usuario']['Perfil'] == "Estudiante")
			$attendanceRecords = $attendanceController->FilterAttendanceByPeriod(date('Y') . '-01-01', date('Y-m-d'), $_SESSION['usuario']['Id'], null);
		else
			$attendanceRecords = $attendanceController->FilterAttendanceByPeriod(date('Y') . '-01-01', date('Y-m-d'), null, $_SESSION['usuario']['Id']);

		// Variable alias for view compatibility (view expects $registroAsistencias)
		$registroAsistencias = json_encode($attendanceRecords);

		require_once './View/views/client/Statistics.php';
	}
}
