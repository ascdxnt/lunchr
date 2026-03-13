<?php
$head = file_get_contents('./View/views/components/Head.php');
$header = file_get_contents('./View/views/components/Header.php');
$sidebar = file_get_contents('./View/views/components/AdminMenu.php');

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

	<title>Crear Especialidad</title>
	<?php echo $head; ?>
</head>

<body>
	<?php echo $header; ?>

	<main class="d-flex">
		<?php echo $sidebar; ?>
		<div class="w-50 mx-auto rounded general-shadow mt-4 mobile-target overflow-hidden">
			<form id="manualForm" action="./?dir=admin&controller=Specialties&action=Create" method="POST" class="px-4 py-4">
				<h2 class="fs-3 mb-3">Nueva Especialidad</h2>
				<div class="manual mb-3">
					<label for="especialidad" class="form-label">Nombre de la Especialidad</label>
					<input type="text" class="form-control" id="especialidad" name="especialidad">
					<p class="my-2" id="warning"></p>
				</div>
				<button id="buttonCreateManual" class="btn btn-comedor" type="submit" disabled>Crear</button>
				<a id="buttonBackManual" type="button" class="btn btn-secondary" href="./?dir=admin&controller=Specialties&action=Index&id=main">Volver</a>
			</form>
		</div>
	</main>
	<div id="loading" class="d-none border justify-content-center align-items-center rounded shadow-lg p-4 position-fixed bg-light" style="width: 15rem; height: 4rem; z-index: 20; inset: 0; margin: 0 auto; top: 5rem">
		<span class="loader d-block me-4"></span>
		<div>Subiendo datos</div>
	</div>

	<script>
		const inputSpecialty = document.getElementById('especialidad');
		const warning = document.getElementById('warning');
		const buttonCreateManual = document.getElementById('buttonCreateManual');

		//Ajax validation to check if the name exists
		inputSpecialty.addEventListener('input', () => {
			if (inputSpecialty.value != "") {
				fetch("./?dir=admin&controller=Specialties&action=VerifyName", {
						method: 'POST',
						body: JSON.stringify({
							nombreEspecialidad: inputSpecialty.value
						})
					})
					.then(response => response.json())
					.then(obj => {
						if (obj.message == "exito") {
							warning.classList.add('text-success');
							warning.classList.remove('text-danger');
							inputSpecialty.classList.remove('is-invalid');
							inputSpecialty.classList.add('is-valid');
							warning.textContent = "Nombre válido";
						} else if (obj.message == "error") {
							buttonCreateManual.disabled = true;
							warning.classList.remove('text-success');
							warning.classList.add('text-danger');
							inputSpecialty.classList.remove('is-valid');
							inputSpecialty.classList.add('is-invalid');
							warning.textContent = "Este nombre está en uso";
						}
					});

			} else {
				inputSpecialty.classList.remove('is-valid');
				inputSpecialty.classList.remove('is-invalid');
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
			buttonCreateManual.disabled = buttonState;
		})
	</script>
</body>

</html>
