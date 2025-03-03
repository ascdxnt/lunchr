<?php
class ContrasenaControlador
{
	function ContrasenaAleatoria()
	{
		$jsonConfiguracionContra = file_get_contents('./Core/ContrasenaConfiguracion.json');
		$configs = json_decode($jsonConfiguracionContra);

		$longitudContrasena = $configs->longitudContrasena;
		$caracteres = $configs->caracteresContrasena;

		$pass = array();
		$alphaLength = strlen($caracteres);
		for ($j = 0; $j < $longitudContrasena; $j++) {
			$num = rand(0, $alphaLength - 1);
			$pass[] = $caracteres[$num];
		}
		$contrasena = implode($pass);

		return $contrasena;
	}

	function ConfiguracionContrasena()
	{
		$rutaArchivo = "./Core/ContrasenaConfiguracion.json";
		if ($_POST['longitudContrasena'] != null && $_POST['caracteresContrasena'] != null) {
			$logitudContrasena = $_POST['longitudContrasena'];
			$caracteresContrasena = $_POST['caracteresContrasena'];
			if (file_exists($rutaArchivo) && is_readable($rutaArchivo) && is_writable($rutaArchivo)) {
				$archivo = fopen($rutaArchivo, "w");

				fwrite($archivo, "{\"longitudContrasena\": \"" . $logitudContrasena . "\",\"caracteresContrasena\": \"" . $caracteresContrasena . "\"}");

				fclose($archivo);

				header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&alerta=success');
			} else
				header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&alerta=error');
		} else
			header('Location: ./?dir=admin&controlador=Ajustes&accion=Index&alerta=error');
	}
}
