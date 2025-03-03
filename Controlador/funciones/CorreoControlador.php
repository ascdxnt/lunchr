<?php
class CorreoControlador
{
	function CorreoIndividual($destinatario, $titulo, $contrasena, $nombre = null, $apellido = null)
	{
		$cuerpoMensaje = '
				<div style="color: #222; font-family: sans-serif; font-size: 1.2rem">
				<img style="width: 256px; display: block; margin: 1em auto;" src="https://covao.org/wp-content/uploads/2021/07/covao-logo-1.png" alt="Logo">
				<br><h1 style="margin: 0; text-align: center; font-size: 2rem !important; ">' . $titulo . '</h1>
				<h2 style="text-align: center; font-size: 1.4rem !important; font-weight: 400">' . $nombre . ' ' . $apellido . '</h2><br>
				<span>Usuario:<span>
				<span text-decoration: none; font-weight: 400;">' . $destinatario . '</span><br><br>
				<span style="">Contraseña: <span>
				<span>' . $contrasena . '</span><br><br>
				<span>Iniciar sesión: </span><br>
				<span><a href="https://comedor.infocovao.xyz/">https://comedor.infocovao.xyz/</a></span>
				</div>
		';
		$cabeceras = 'From: Comedor' . "\r\n" .
			'Reply-To: comedor' . "\r\n";
		$cabeceras .= 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		if(mail($destinatario, $titulo, $cuerpoMensaje, $cabeceras))
				return true;
		else 
				return false;	
	}
}
