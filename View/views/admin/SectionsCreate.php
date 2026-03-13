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

	<title>Crear Sección</title>
	<?php echo $head; ?>
</head>

<body>
	<?php echo $header; ?>

	<main class="d-flex">
		<?php echo $sidebar; ?>
		<div class="mx-auto rounded general-shadow mt-4 w-50 mobile-target overflow-hidden">
			<form id="manualForm" action="./?dir=admin&controller=Sections&action=Create" method="POST" class="px-4 py-4">
				<h2 class="fs-3 mb-4">Nueva Sección</h2>
				<div class="mb-3">
					<label class="form-label">Nivel</label>
					<div id="radiosContenedor" class="d-flex gap-3 flex-wrap">
						<label class="form-check-label" style="cursor: pointer; user-select: none">
							Décimo
							<input checked class="form-check-input" type="radio" name="nivel" value="10" style="cursor: pointer">
						</label>
						<label class="form-check-label" style="cursor: pointer; user-select: none">
							Undémico
							<input class="form-check-input" type="radio" name="nivel" value="11" style="cursor: pointer">
						</label>
						<label class="form-check-label" style="cursor: pointer; user-select: none">
							Duodécimo
							<input class="form-check-input" type="radio" name="nivel" value="12" style="cursor: pointer">
						</label>
					</div>
				</div>
				<div class="manual mb-3 input-group-sm gap-3">
					<label for="seccion" class="">Letra</label>
					<input id="inputLetterSection" name="letraSeccion" type="text" class="form-control" style="width: max-content" value="A">
				</div>
				<div class="manual mb-3 input-group-sm">
					<label for="seccion" class="mb-1">Nombre resultante</label> <input readonly type="text" class="form-control" id="seccion" name="seccion" style="width: max-content">
					<p class="my-2" id="warning"></p>
				</div>
				<button id="buttonCreateManual" class="btn btn-comedor" type="submit" disabled>Crear</button>
				<a id="buttonBackManual" type="button" class="btn btn-secondary" href="./?dir=admin&controller=Sections&action=Index&id=main">Volver</a>
			</form>
		</div>
	</main>
	<div id="loading" class="d-none border justify-content-center align-items-center rounded shadow-lg p-4 position-fixed bg-light" style="width: 15rem; height: 4rem; z-index: 20; inset: 0; margin: 0 auto; top: 5rem">
		<span class="loader d-block me-4"></span>
		<div>Subiendo datos</div>
	</div>

	<script>
		const inputSection = document.getElementById('seccion');
		const warning = document.getElementById('warning');
		const buttonCreateManual = document.getElementById('buttonCreateManual');

		//Ajax validation to check if the name exists
		inputSection.addEventListener('input', validateInputSection);

		function validateInputSection() {
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
							buttonCreateManual.disabled = true;
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
		}

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

		//Fill the read-only input
		const inputLetterSection = document.getElementById('inputLetterSection');
		const radiosContenedor = document.getElementById('radiosContenedor');

		inputLetterSection.addEventListener('input', validateLetterField);
		inputLetterSection.addEventListener('input', fillInputSection);
		radiosContenedor.addEventListener('click', fillInputSection);

		function fillInputSection() {
			let sectionLevel = document.querySelector("[name=nivel]:checked");
			if (inputLetterSection.value == "" || sectionLevel.value == "") return;

			inputSection.value = `${sectionLevel.value}-${inputLetterSection.value}`;
			validateInputSection();
		}
		fillInputSection();

		function validateLetterField() {
			let letter = (inputLetterSection.value).slice(-1);
			inputLetterSection.value = letter;
		}
	</script>
</body>

</html>
