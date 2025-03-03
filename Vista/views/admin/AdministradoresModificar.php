<?php
$head = file_get_contents('./Vista/views/components/Head.php');
$header = file_get_contents('./Vista/views/components/Header.php');
$sidebar = file_get_contents('./Vista/views/components/MenuAdmin.php');
$id = $_POST['idU'];
$nombre = $_POST['nombre'];
$primerAp = $_POST['primerap'];
$segundoAp = $_POST['segundoap'];
$correo = $_POST['correo'];
$estado = $_POST['estado'];

if ($_SESSION["perfiles"] != 'admin') {
	header('Location: ./?alerta=error');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Modificar Administrador</title>
	<?php echo $head; ?>
</head>

<body>
	<?php echo $header; ?>
	<main class="d-flex">
		<?php echo $sidebar; ?>
		<form id="manualForm" action="./?dir=admin&controlador=Funcionario&accion=Modificar&id=1" method="POST" class="w-50 mx-auto p-4 my-4 rounded mobile-target general-shadow">
			<h1 class="fs-3">Modificar Administrador</h1>
			<div class="mb-3 manual">
				<label for="nombre" class="form-label">Nombre</label>
				<input value="<?php echo $nombre; ?>" type="text" class="form-control" id="nombreModificar" name="nombreModificar">
				<input value="<?php echo $id; ?>" hidden type="text" class="form-control" id="idModificar" name="idModificar">
				<input value="<?php echo $estado; ?>" hidden type="text" class="form-control" id="estadoModificar" name="estadoModificar">
			</div>
			<div class="mb-3 manual">
				<label for="primerApellido" class="form-label">Primer Apellido</label>
				<input value="<?php echo $primerAp; ?>" type="text" class="form-control" id="primerApellidoModificar" name="primerApellidoModificar">
			</div>
			<div class="mb-3 manual">
				<label for="segundoApellido" class="form-label">Segundo Apellido</label>
				<input value="<?php echo $segundoAp; ?>" type="text" class="form-control" id="segundoApellidoModificar" name="segundoApellidoModificar">
			</div>
			<div class="mb-3 manual">
				<label for="correo" class="form-label">Correo</label>
				<input value="<?php echo $correo; ?>" type="email" class="form-control" id="correoModificar" name="correoModificar">
			</div>
			<div class="mb-3">
				<label for="contrasena" class="form-label">Contraseña</label>
				<input value="" type="password" class="form-control" id="contrasenaModificar" name="contrasenaModificar" placeholder="Este campo es opcional">
			</div>
			<button id="buttonModificar" disabled type="submit" class="btn btn-comedor">Guardar cambios</button>
			<a id="buttonVolverManual" type="button" class="btn btn-secondary" href="./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main">Volver</a>
		</form>
	</main>

	<script>
		//validaciones
		let manualInputs = [...document.getElementsByClassName('manual')];
		const buttonModificar = document.getElementById('buttonModificar');
		manualForm.addEventListener('input', () => {
			let estadoBoton = false;
			manualInputs.forEach(e => {
				if (e.children[1].value == "") {
					estadoBoton = true;
				}
			})
			buttonModificar.disabled = estadoBoton;
		})
	</script>
</body>

</html>
