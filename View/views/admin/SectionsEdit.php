<?php
$head = file_get_contents('./View/views/components/Head.php');
$header = file_get_contents('./View/views/components/Header.php');
$sidebar = file_get_contents('./View/views/components/AdminMenu.php');
$id = $_POST['idSeccion'];
$description = $_POST['seccion'];
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

	<title>Modificar Sección</title>
	<?php echo $head; ?>
</head>

<body>
	<?php echo $header; ?>
	<main class="d-flex">
		<?php echo $sidebar; ?>
		<form id="manualForm" action="./?dir=admin&controller=Sections&action=Update" method="POST" class="w-50 mx-auto p-4 mt-4 rounded mobile-target general-shadow">
			<h1 class="fs-3">Modificar Sección</h1>
			<div class="mb-3 manual">
				<label for="nombre" class="form-label">Sección</label>
				<input value="<?php echo $description; ?>" type="text" class="form-control" id="seccion" name="seccionModificar">
				<p class="my-2" id="warning"></p>
				<input value="<?php echo $id; ?>" hidden type="text" class="form-control" id="idModificar" name="idModificar">
				<input value="<?php echo $status; ?>" hidden type="text" class="form-control" id="estadoModificar" name="estadoModificar">
			</div>
			<button id="buttonUpdate" disabled type="submit" class="btn btn-comedor">Guardar cambios</button>
			<a id="buttonBackManual" type="button" class="btn btn-secondary" href="./?dir=admin&controller=Sections&action=Index&id=main">Volver</a>
		</form>
	</main>

	<script>
		const inputSection = document.getElementById('seccion');
		const warning = document.getElementById('warning');
		const buttonUpdate = document.getElementById('buttonUpdate');

		//Ajax validation to check if the name exists
		inputSection.addEventListener('input', () => {
			if (inputSection.value != "") {
				fetch("./?dir=admin&controller=Sections&action=VerifyName", {
						method: 'POST',
						body: JSON.stringify({
							nombreSeccion: inputSection.value
						})
					})
					.then(response => response.json())
					.then(obj => {
						if (obj.message == "exito") {
							warning.classList.add('text-success');
							warning.classList.remove('text-danger');
							inputSection.classList.remove('is-invalid');
							inputSection.classList.add('is-valid');
							warning.textContent = "Nombre válido";
						} else if (obj.message == "error") {
							buttonUpdate.disabled = true;
							warning.classList.remove('text-success');
							warning.classList.add('text-danger');
							inputSection.classList.remove('is-valid');
							inputSection.classList.add('is-invalid');
							warning.textContent = "Este nombre está en uso";
						}
					});
			} else {
				inputSection.classList.remove('is-valid');
				inputSection.classList.remove('is-invalid');
				warning.textContent = "";
			}
		});

		//Validations
		let manualInputs = [...document.getElementsByClassName('manual')];
		manualForm.addEventListener('input', () => {
			let buttonState = false;
			manualInputs.forEach(e => {
				if (e.children[1].value == "") {
					buttonState = true;
				}
			})
			buttonUpdate.disabled = buttonState;
		})
	</script>
</body>

</html>
