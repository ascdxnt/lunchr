<?php

class Connection
{
	private $mysqli;

	private function loadEnv()
	{
		$envFile = __DIR__ . '/../.env';
		if (!file_exists($envFile)) return;

		$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach ($lines as $line) {
			if (strpos(trim($line), '#') === 0) continue;
			if (strpos($line, '=') === false) continue;

			list($key, $value) = explode('=', $line, 2);
			$key = trim($key);
			$value = trim($value);

			if (!getenv($key)) {
				putenv("$key=$value");
			}
		}
	}

	function Execute($query)
	{
		$this->loadEnv();

		$host = getenv('DB_HOST') ?: 'localhost';
		$user = getenv('DB_USER') ?: 'root';
		$pass = getenv('DB_PASS') ?: '';
		$db = getenv('DB_NAME') ?: 'COMEDOR';

		if (!$this->mysqli = new mysqli($host, $user, $pass, $db)) {
			die('Connection error (' . mysqli_connect_errno() . ') '
				. mysqli_connect_error());
		}
		$this->mysqli->autocommit(TRUE);
		$result = $this->mysqli->query($query);
		return $result;
	}

	function Close()
	{
		$this->mysqli->close();
	}
}
