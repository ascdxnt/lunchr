<?php
$head = file_get_contents('./Vista/views/components/Head.php');
$header = file_get_contents('./Vista/views/components/Header.php');
$sidebar = file_get_contents('./Vista/views/components/MenuAdmin.php');

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
			<form id="manualForm" action="./?dir=admin&controlador=Especialidades&accion=Crear" method="POST" class="px-4 py-4">
				<h2 class="fs-3 mb-3">Nueva Especialidad</h2>
				<div class="manual mb-3">
					<label for="especialidad" class="form-label">Nombre de la Especialidad</label>
					<input type="text" class="form-control" id="especialidad" name="especialidad">
					<p class="my-2" id="advertencia"></p>
				</div>
				<button id="buttonCrearManual" class="btn btn-comedor" type="submit" disabled>Crear</button>
				<a id="buttonVolverManual" type="button" class="btn btn-secondary" href="./?dir=admin&controlador=Especialidades&accion=Index&id=main">Volver</a>
			</form>
		</div>
	</main>
	<div id="carga" class="d-none border justify-content-center align-items-center rounded shadow-lg p-4 position-fixed bg-light" style="width: 15rem; height: 4rem; z-index: 20; inset: 0; margin: 0 auto; top: 5rem">
		<span class="loader d-block me-4"></span>
		<div>Subiendo datos</div>
	</div>

	<script>
		const inputEspecialidad = document.getElementById('especialidad');
		const advertencia = document.getElementById('advertencia');
		const buttonCrearManual = document.getElementById('buttonCrearManual');

		//validación por ajax para verificar si el nombre existe
		inputEspecialidad.addEventListener('input', () => {
			if (inputEspecialidad.value != "") {
				fetch("./?dir=admin&controlador=Especialidades&accion=VerificarNombre", {
						method: 'POST',
						body: JSON.stringify({
							nombreEspecialidad: inputEspecialidad.value
						})
					})
					.then(response => response.json())
					.then(objeto => {
						if (objeto.message == "exito") {
							advertencia.classList.add('text-success');
							advertencia.classList.remove('text-danger');
							inputEspecialidad.classList.remove('is-invalid');
							inputEspecialidad.classList.add('is-valid');
							advertencia.textContent = "Nombre válido";
						} else if (objeto.message == "error") {
							buttonCrearManual.disabled = true;
							advertencia.classList.remove('text-success');
							advertencia.classList.add('text-danger');
							inputEspecialidad.classList.remove('is-valid');
							inputEspecialidad.classList.add('is-invalid');
							advertencia.textContent = "Este nombre está en uso";
						}
					});

			} else {
				inputEspecialidad.classList.remove('is-valid');
				inputEspecialidad.classList.remove('is-invalid');
				advertencia.textContent = "";
			}
		});

		//validaciones
		let manualInputs = [...document.getElementsByClassName('manual')];
		manualForm.addEventListener('input', () => {
			let estadoBoton = false;
			manualInputs.forEach(e => {
				if (e.children[1].value == "") {
					estadoBoton = true;
				}
			})
			buttonCrearManual.disabled = estadoBoton;
		})
	</script>
</body>

</html>
