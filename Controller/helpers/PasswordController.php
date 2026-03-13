<?php

class PasswordController
{
	function RandomPassword()
	{
		$jsonPasswordConfig = file_get_contents('./Core/PasswordConfig.json');
		$configs = json_decode($jsonPasswordConfig);

		$passwordLength = $configs->passwordLength;
		$characters = $configs->passwordCharacters;

		$pass = array();
		$alphaLength = strlen($characters);
		for ($j = 0; $j < $passwordLength; $j++) {
			$num = rand(0, $alphaLength - 1);
			$pass[] = $characters[$num];
		}
		$password = implode($pass);

		return $password;
	}

	function PasswordSettings()
	{
		$filePath = "./Core/PasswordConfig.json";
		if ($_POST['passwordLength'] != null && $_POST['passwordCharacters'] != null) {
			$passwordLength = $_POST['passwordLength'];
			$passwordCharacters = $_POST['passwordCharacters'];
			if (file_exists($filePath) && is_readable($filePath) && is_writable($filePath)) {
				$file = fopen($filePath, "w");

				fwrite($file, "{\"passwordLength\": \"" . $passwordLength . "\",\"passwordCharacters\": \"" . $passwordCharacters . "\"}");

				fclose($file);

				header('Location: ./?dir=admin&controller=Settings&action=Index&alerta=success');
			} else
				header('Location: ./?dir=admin&controller=Settings&action=Index&alerta=error');
		} else
			header('Location: ./?dir=admin&controller=Settings&action=Index&alerta=error');
	}
}