<?php
$head = file_get_contents('./Vista/views/components/Head.php');
$header = file_get_contents('./Vista/views/components/Header.php');
$sidebar = file_get_contents('./Vista/views/components/MenuAdmin.php');
$id = $_POST['idU'];
$nombre = $_POST['nombre'];
$primerAp = $_POST['primerap'];
$segundoAp = $_POST['segundoap'];
$cedula = $_POST['cedula'];
$especialidadN = $_POST['especialidad'];
$seccionN = $_POST['seccion'];
$correo = $_POST['correo'];
$estado = $_POST['estado'];
$becado = $_POST['becado'];

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

	<title>Modificar Estudiante</title>
	<?php echo $head; ?>
</head>

<body>
	<?php echo $header; ?>
	<main class="d-flex">
		<?php echo $sidebar; ?>
		<form id="manualForm" action="./?dir=admin&controlador=Estudiante&accion=Modificar" method="POST" class="w-50 mx-auto p-4 my-4 rounded mobile-target general-shadow" enctype="multipart/form-data">
			<h1 class="fs-3">Modificar Estudiante</h1>
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
				<label for="correo" class="form-label">Cedula</label>
				<input value="<?php echo $cedula; ?>" type="text" class="form-control" id="cedulaModificar" name="cedulaModificar">
			</div>
			<div class="mb-3 manual">
				<label for="correo" class="form-label">Especialidad</label>
				<input value="<?php echo $especialidadN; ?>" type="text" class="form-control" id="especialidadModificar" name="especialidadModificar" hidden>
				<select class="form-control" name="especialidadModificar" id="idEspecialidad">
					<?php
					if ($todosEspecialidad != null) {
						foreach ($todosEspecialidad as $especialidad) {
							if ($especialidad->getEstado() == 1) {
								if ($especialidad->getId() == $especialidadN) {
					?>
									<option value="<?php echo $especialidad->getId(); ?>" selected> <?php echo $especialidad->getDescripcion(); ?></option>
								<?php
								} else {
								?>
									<option value="<?php echo $especialidad->getId(); ?>"> <?php echo $especialidad->getDescripcion(); ?></option>
					<?php
								}
							}
						}
					}
					?>
				</select>
			</div>
			<div class="manual mb-3">
				<label for="idSeccion" class="form-label">Seleccionar Seccion</label>
				<select class="form-control" name="seccionModificar" id="seccionModificar">
					<?php
					if ($todosSeccion != null) {
						foreach ($todosSeccion as $seccion) {
							if ($seccion->getEstado() == 1) {
								if ($seccion->getId() == $seccionN) {
					?>
									<option value="<?php echo $seccion->getId(); ?>" selected> <?php echo $seccion->getDescripcion(); ?></option>
								<?php
								} else {
								?>
									<option value="<?php echo $seccion->getId(); ?>"> <?php echo $seccion->getDescripcion(); ?></option>
					<?php
								}
							}
						}
					}
					?>
				</select>
			</div>
			<div class="mb-3 manual">
				<label for="correo" class="form-label">Correo</label>
				<input value="<?php echo $correo; ?>" type="email" class="form-control" id="correoModificar" name="correoModificar">
			</div>
			<div class="mb-3">
				<label for="contrasena" class="form-label">Contraseña</label>
				<input value="" type="password" class="form-control" id="contrasenaModificar" name="contrasenaModificar" placeholder="Este campo es opcional">
			</div>
			<div class="mb-3">
				<label for="inputFotoPerfil" class="form-label">Cambiar Foto de Perfil (Opcional)</label>
				<input id="inputFotoPerfil" type="file" accept="image/jpg, image/png, image/jpeg" name="profile-image" class="form-control" />
				<div></div>
			</div>
			<div class="mb-3">
				<label for="" class="form-label">Beca completa: </label>
				<label class="ms-2 form-check-label" for="noBecado">No</label>
				<input class="form-check-input" <?php if ($becado == 0) echo 'checked' ?> style="cursor: pointer" value="0" type="radio" name="becadoModificar" id="noBecado">
				<label class="ms-2 form-check-label" for="siBecado">Sí</label>
				<input class="form-check-input" <?php if ($becado == 1) echo 'checked' ?> style="cursor: pointer" value="1" type="radio" name="becadoModificar" id="siBecado">
			</div>
			<button id="buttonModificar" disabled type="submit" class="btn btn-comedor">Guardar cambios</button>
			<a id="buttonVolverManual" type="button" class="btn btn-secondary" href="./?dir=admin&controlador=Estudiante&accion=Index&id=main">Volver</a>
		</form>
	</main>

	<script>
		//validaciones
		let manualInputs = [...document.getElementsByClassName('manual')];
		const buttonModificar = document.getElementById('buttonModificar');
		let imagenValida = true;

		const validarCampos = () => {
			let estadoBoton = false;

			manualInputs.forEach(e => {
				if (e.children[1].value == "") {
					estadoBoton = true;
				}
			})

			buttonModificar.disabled = estadoBoton;
			if (!imagenValida) {
				buttonModificar.disabled = true;
			}
		}
		manualForm.addEventListener('input', validarCampos);

		//Validar foto
		const FormatosValidos = [
			"image/jpeg",
			"image/jpg",
			"image/png"
		];

		const inputFotoPerfil = document.getElementById('inputFotoPerfil');
		inputFotoPerfil.addEventListener('change', (e) => {
			const MB = 1000000;
			let size = e.target.files[0].size;
			let type = e.target.files[0].type;
			let mensajeContenedor = inputFotoPerfil.nextElementSibling;

			removeAllChildNodes(mensajeContenedor);
			if (size >= 2 * MB) {
				mensajeContenedor.insertAdjacentHTML('beforeend', `
					<p class="text-danger">La imagen es demasiado pesada.</p>
				`);
				imagenValida = false;
				validarCampos();
				return;
			}

			if (!FormatosValidos.find(element => element === type)) {
				mensajeContenedor.insertAdjacentHTML('beforeend', `
					<p class="text-danger">Formato de imagen inválido.</p>
				`);
				imagenValida = false;
				validarCampos();
				return;
			}

			mensajeContenedor.insertAdjacentHTML('beforeend', `
				<p class="text-success">Formato válido.</p>
			`);
			imagenValida = true;
			validarCampos();
		});
	</script>
</body>

</html>
