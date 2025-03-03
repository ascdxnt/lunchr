<?php
$head = file_get_contents('./Vista/views/components/Head.php');
$header = file_get_contents('./Vista/views/components/Header.php');
$sidebar = file_get_contents('./Vista/views/components/MenuAdmin.php');
$id = $_POST['idSeccion'];
$descripcion = $_POST['seccion'];
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

	<title>Modificar Sección</title>
	<?php echo $head; ?>
</head>

<body>
	<?php echo $header; ?>
	<main class="d-flex">
		<?php echo $sidebar; ?>
		<form id="manualForm" action="./?dir=admin&controlador=Secciones&accion=Modificar" method="POST" class="w-50 mx-auto p-4 mt-4 rounded mobile-target general-shadow">
			<h1 class="fs-3">Modificar Sección</h1>
			<div class="mb-3 manual">
				<label for="nombre" class="form-label">Sección</label>
				<input value="<?php echo $descripcion; ?>" type="text" class="form-control" id="seccion" name="seccionModificar">
				<p class="my-2" id="advertencia"></p>
				<input value="<?php echo $id; ?>" hidden type="text" class="form-control" id="idModificar" name="idModificar">
				<input value="<?php echo $estado; ?>" hidden type="text" class="form-control" id="estadoModificar" name="estadoModificar">
			</div>
			<button id="buttonModificar" disabled type="submit" class="btn btn-comedor">Guardar cambios</button>
			<a id="buttonVolverManual" type="button" class="btn btn-secondary" href="./?dir=admin&controlador=Secciones&accion=Index&id=main">Volver</a>
		</form>
	</main>

	<script>
		const inputSeccion = document.getElementById('seccion');
		const advertencia = document.getElementById('advertencia');
		const buttonModificar = document.getElementById('buttonModificar');

		//validación por ajax para verificar si el nombre existe
		inputSeccion.addEventListener('input', () => {
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
							buttonModificar.disabled = true;
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
			buttonModificar.disabled = estadoBoton;
		})
	</script>
</body>

</html>
