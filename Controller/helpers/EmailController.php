<?php

class EmailController
{
	function IndividualEmail($recipient, $subject, $password, $name = null, $lastName = null)
	{
		$messageBody = '
				<div style="color: #222; font-family: sans-serif; font-size: 1.2rem">
				<img style="width: 256px; display: block; margin: 1em auto;" src="https://covao.org/wp-content/uploads/2021/07/covao-logo-1.png" alt="Logo">
				<br><h1 style="margin: 0; text-align: center; font-size: 2rem !important; ">' . $subject . '</h1>
				<h2 style="text-align: center; font-size: 1.4rem !important; font-weight: 400">' . $name . ' ' . $lastName . '</h2><br>
				<span>Usuario:<span>
				<span text-decoration: none; font-weight: 400;">' . $recipient . '</span><br><br>
				<span style="">Contraseña: <span>
				<span>' . $password . '</span><br><br>
				<span>Iniciar sesión: </span><br>
				<span><a href="https://comedor.infocovao.xyz/">https://comedor.infocovao.xyz/</a></span>
				</div>
		';
		$headers = 'From: Comedor' . "\r\n" .
			'Reply-To: comedor' . "\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		return @mail($recipient, $subject, $messageBody, $headers);
	}
}