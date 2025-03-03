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

	<title>Crear Sección</title>
	<?php echo $head; ?>
</head>

<body>
	<?php echo $header; ?>

	<main class="d-flex">
		<?php echo $sidebar; ?>
		<div class="mx-auto rounded general-shadow mt-4 w-50 mobile-target overflow-hidden">
			<form id="manualForm" action="./?dir=admin&controlador=Secciones&accion=Crear" method="POST" class="px-4 py-4">
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
					<input id="inputLetraSeccion" name="letraSeccion" type="text" class="form-control" style="width: max-content" value="A">
				</div>
				<div class="manual mb-3 input-group-sm">
					<label for="seccion" class="mb-1">Nombre resultante</label> <input readonly type="text" class="form-control" id="seccion" name="seccion" style="width: max-content">
					<p class="my-2" id="advertencia"></p>
				</div>
				<button id="buttonCrearManual" class="btn btn-comedor" type="submit" disabled>Crear</button>
				<a id="buttonVolverManual" type="button" class="btn btn-secondary" href="./?dir=admin&controlador=Secciones&accion=Index&id=main">Volver</a>
			</form>
		</div>
	</main>
	<div id="carga" class="d-none border justify-content-center align-items-center rounded shadow-lg p-4 position-fixed bg-light" style="width: 15rem; height: 4rem; z-index: 20; inset: 0; margin: 0 auto; top: 5rem">
		<span class="loader d-block me-4"></span>
		<div>Subiendo datos</div>
	</div>

	<script>
		const inputSeccion = document.getElementById('seccion');
		const advertencia = document.getElementById('advertencia');
		const buttonCrearManual = document.getElementById('buttonCrearManual');

		//validación por ajax para verificar si el nombre existe
		inputSeccion.addEventListener('input', validarInputSeccion);

		function validarInputSeccion() {
			if (inputSeccion.value != "") {
				fetch("./?dir=admin&controlador=Secciones&accion=VerificarNombre", {
						method: 'POST',
						body: JSON.stringify({
							nombreSeccion: inputSeccion.value
						})
					})
					.then(response => response.json())
					.then(objeto => {
						if (objeto.message == "exito") {
							advertencia.classList.add('text-success');
							advertencia.classList.remove('text-danger');
							inputSeccion.classList.remove('is-invalid');
							inputSeccion.classList.add('is-valid');
							advertencia.textContent = "Nombre válido";
						} else if (objeto.message == "error") {
							buttonCrearManual.disabled = true;
							advertencia.classList.remove('text-success');
							advertencia.classList.add('text-danger');
							inputSeccion.classList.remove('is-valid');
							inputSeccion.classList.add('is-invalid');
							advertencia.textContent = "Este nombre está en uso";
						}
					});
			} else {
				inputSeccion.classList.remove('is-valid');
				inputSeccion.classList.remove('is-invalid');
				advertencia.textContent = "";
			}
		}

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

		//llenar el input de solo lectura
		const inputLetraSeccion = document.getElementById('inputLetraSeccion');
		const radiosContenedor = document.getElementById('radiosContenedor');

		inputLetraSeccion.addEventListener('input', validarCampoLetra);
		inputLetraSeccion.addEventListener('input', llenarInputSeccion);
		radiosContenedor.addEventListener('click', llenarInputSeccion);

		function llenarInputSeccion() {
			let nivelSeccion = document.querySelector("[name=nivel]:checked");
			if (inputLetraSeccion.value == "" || nivelSeccion.value == "") return;

			inputSeccion.value = `${nivelSeccion.value}-${inputLetraSeccion.value}`;
			validarInputSeccion();
		}
		llenarInputSeccion();

		function validarCampoLetra() {
			let letra = (inputLetraSeccion.value).slice(-1);
			inputLetraSeccion.value = letra;
		}
	</script>
</body>

</html>
