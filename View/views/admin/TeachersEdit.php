<?php
$head = file_get_contents('./View/views/components/Head.php');
$header = file_get_contents('./View/views/components/Header.php');
$sidebar = file_get_contents('./View/views/components/AdminMenu.php');
$id = $_POST['idU'];
$name = $_POST['nombre'];
$firstLastName = $_POST['primerap'];
$secondLastName = $_POST['segundoap'];
$idCard = $_POST['cedula'];
$email = $_POST['correo'];
$status = $_POST['estado'];

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

	<title>Modificar Profesor</title>
	<?php echo $head; ?>
</head>

<body>
	<?php echo $header; ?>
	<main class="d-flex">
		<?php echo $sidebar; ?>
		<form id="manualForm" action="./?dir=admin&controller=Teacher&action=Update" method="POST" class="w-50 mx-auto p-4 my-4 rounded mobile-target general-shadow" enctype="multipart/form-data">
			<h1 class="fs-3">Modificar Profesor</h1>
			<div class="mb-3 manual">
				<label for="nombre" class="form-label">Nombre</label>
				<input value="<?php echo $name; ?>" type="text" class="form-control" id="nombreModificar" name="nombreModificar">
				<input value="<?php echo $id; ?>" hidden type="text" class="form-control" id="idModificar" name="idModificar">
				<input value="<?php echo $status; ?>" hidden type="text" class="form-control" id="estadoModificar" name="estadoModificar">
			</div>
			<div class="mb-3 manual">
				<label for="primerApellido" class="form-label">Primer Apellido</label>
				<input value="<?php echo $firstLastName; ?>" type="text" class="form-control" id="primerApellidoModificar" name="primerApellidoModificar">
			</div>
			<div class="mb-3 manual">
				<label for="segundoApellido" class="form-label">Segundo Apellido</label>
				<input value="<?php echo $secondLastName; ?>" type="text" class="form-control" id="segundoApellidoModificar" name="segundoApellidoModificar">
			</div>
			<div class="mb-3 manual">
				<label for="cedula" class="form-label">Cédula</label>
				<input value="<?php echo $idCard; ?>" type="text" class="form-control" id="cedulaModificar" name="cedulaModificar">
			</div>
			<div class="mb-3 manual">
				<label for="correo" class="form-label">Correo</label>
				<input value="<?php echo $email; ?>" type="email" class="form-control" id="correoModificar" name="correoModificar">
			</div>
			<div class="mb-3">
				<label for="inputProfilePhoto" class="form-label">Cambiar Foto de Perfil (Opcional)</label>
				<input id="inputProfilePhoto" type="file" accept="image/jpg, image/png, image/jpeg" name="profile-image" class="form-control" />
				<div></div>
			</div>
			<div class="mb-3">
				<label for="contrasena" class="form-label">Contraseña</label>
				<input value="" type="password" class="form-control" id="contrasenaModificar" name="contrasenaModificar" placeholder="Este campo es opcional">
			</div>
			<!--<a href="./?dir=admin&controller=Teacher&action=Index&id=main" class="btn btn-secondary">Volver</a>-->
			<button id="buttonEdit" disabled type="submit" class="btn btn-comedor">Guardar cambios</button>
			<a id="buttonBackManual" type="button" class="btn btn-secondary" href="./?dir=admin&controller=Teacher&action=Index&id=main">Volver</a>
		</form>
	</main>

	<script>
		// Validations
		let manualInputs = [...document.getElementsByClassName('manual')];
		const buttonEdit = document.getElementById('buttonEdit');
		let imageValid = true;

		const validateFields = () => {
			let buttonState = false;

			manualInputs.forEach(e => {
				if (e.children[1].value == "") {
					buttonState = true;
				}
			})

			buttonEdit.disabled = buttonState;
			if (!imageValid) {
				buttonEdit.disabled = true;
			}
		}
		manualForm.addEventListener('input', validateFields);

		// Validate photo
		const ValidFormats = [
			"image/jpeg",
			"image/jpg",
			"image/png"
		];

		const inputProfilePhoto = document.getElementById('inputProfilePhoto');
		inputProfilePhoto.addEventListener('change', (e) => {
			const MB = 1000000;
			let size = e.target.files[0].size;
			let type = e.target.files[0].type;
			let messageContainer = inputProfilePhoto.nextElementSibling;

			removeAllChildNodes(messageContainer);
			if (size >= 2 * MB) {
				messageContainer.insertAdjacentHTML('beforeend', `
					<p class="text-danger">La imagen es demasiado pesada.</p>
				`);
				imageValid = false;
				validateFields();
				return;
			}

			if (!ValidFormats.find(element => element === type)) {
				messageContainer.insertAdjacentHTML('beforeend', `
					<p class="text-danger">Formato de imagen inválido.</p>
				`);
				imageValid = false;
				validateFields();
				return;
			}

			messageContainer.insertAdjacentHTML('beforeend', `
				<p class="text-success">Formato válido.</p>
			`);
			imageValid = true;
			validateFields();
		});
	</script>
</body>

</html>