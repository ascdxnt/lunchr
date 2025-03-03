<?php

class ClienteEstadisticasControlador
{
	public function Index()
	{
		require_once "./Controlador/admin/AsistenciaControlador.php";
		$asistenciaControlador = new AsistenciaControlador();
		$registroAsistencias;

		if($_SESSION['usuario']['Perfil'] == "Estudiante")
			$registroAsistencias = $asistenciaControlador->FiltrarAsistenciaPorPeriodo(date('Y').'-01-01', date('Y-m-d'), $_SESSION['usuario']['Id'], null);
		else
			$registroAsistencias = $asistenciaControlador->FiltrarAsistenciaPorPeriodo(date('Y').'-01-01', date('Y-m-d'), null, $_SESSION['usuario']['Id']);

		$registroAsistencias = json_encode($registroAsistencias);
		require_once './Vista/views/cliente/Estadisticas.php';
	}
}

