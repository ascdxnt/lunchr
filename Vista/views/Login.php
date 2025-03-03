<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php
	$head = file_get_contents('./Vista/views/components/Head.php');
	echo $head;
	?>
	<title>Comedor - Iniciar Sesión</title>
</head>

<body class="d-flex min-vh-100">
	<img class="login-image" style="width: 42%; object-fit: cover" src="./Vista/assets/images/comedor.jpeg" alt="Comedor">
	<section class="mx-auto login-section d-flex" style="width: 52%">
		<div class="login-form-target target px-3 overflow-hidden rounded-3 py-4 mx-auto my-auto border" style="width: 426px;">
			<img height="100" class="d-block mx-auto" src="./Vista/assets/images/covao-logo.png" alt="Logo COVAO">
			<div id="forms-container" class="form-translate d-flex" style="width: 200%">
				<form id="formLogin" class="w-50" style="transition: opacity 200ms;" action="./?controlador=Index&accion=Login" method="post">
					<h1 class="fs-4 my-4 text-center fw-bold my-0">Iniciar Sesión</h1>
					<div class="mb-3">
						<label for="formGroupExampleInput" class="form-label">Correo</label>
						<input id="correo" type="email" name="correo" class="form-control" id="formGroupExampleInput" placeholder="">
					</div>
					<div class="mb-3">
						<label for="formGroupExampleInput2" class="form-label">Contraseña</label>
						<input id="contrasena" type="password" name="contrasena" class="form-control" id="formGroupExampleInput2" placeholder="">
					</div>
					<button class="w-100 btn btn-comedor mt-3">Aceptar</button>
					<a onclick="moverContenido()" style="user-select: none; cursor: pointer;" class="d-block mt-3 text-center text-decoration-none">¿Olvidaste tu contraseña?</a>
				</form>
				<form id="formRestablecer" class="w-50 opacity-0 d-flex flex-column" action="./?controlador=Index&accion=RestablecerContrasena" method="post" style="float: left; transition: opacity 200ms;">
					<h1 class="fs-4 my-4 text-center fw-bold my-0">¿Olvidaste tu contraseña?</h1>
					<div class="form-group">
						<div class="mb-3">
							<label for="formGroupExampleInput" class="form-label">Correo</label>
							<input id="correo2" type="email" name="correo" class="form-control" id="formGroupExampleInput" placeholder="">
						</div>
					</div>
					<button class="w-100 btn btn-comedor mt-3">Enviar</button>
					<p class="card-text text-center mt-3 text-secondary">Pronto se enviará un correo electrónico al correo ingresado con su nueva contraseña</p>
					<a onclick="moverContenido()" class="d-block mt-auto text-center text-decoration-none" style="user-select: none; cursor: pointer;">Iniciar Sesión</a>
				</form>
			</div>
		</div>
	</section>

	<script>
		// @ts-check
		let numero = 4;
		numero = "caca";
		const formLogin = document.getElementById('formLogin');
		const formRestablecer = document.getElementById('formRestablecer');
		const formsContainer = document.getElementById('forms-container');

		function moverContenido() {
			formLogin.classList.toggle('opacity-0');
			formRestablecer.classList.toggle('opacity-0');
			formsContainer.classList.toggle('form-translate-active');
		}
	</script>

	<?php
	if (isset($_REQUEST['alerta'])) {
		$nombreAlerta = $_REQUEST['alerta'];
		if ($nombreAlerta == 'error') {
	?>
			<script>
				document.getElementById('correo').classList.add('is-invalid');
				document.getElementById('contrasena').classList.add('is-invalid');
				document.getElementById('contrasena').insertAdjacentHTML('afterend', `
				<div class="invalid-feedback">
						Correo o contraseña incorrectos.
				</div>
	 `);
			</script>
	<?php
		} else if ($nombreAlerta == "success") {
			echo "<script>alertify.success('Proceso exitoso');</script>";
		} else if ($nombreAlerta == "errorRestablecerContra")
			echo "<script>alertify.error('Hubo un error');</script>";
	}
	?>
</body>

</html>
