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
	<title>Crear Estudiante</title>
	<?php echo $head; ?>

	<!-- links para exportar a excel -->
	<script src="https://unpkg.com/xlsx@0.16.9/dist/xlsx.full.min.js"></script>
	<script src="https://unpkg.com/file-saverjs@latest/FileSaver.min.js"></script>
	<script src="https://unpkg.com/tableexport@latest/dist/js/tableexport.min.js"></script>

</head>

<body>
	<?php echo $header; ?>

	<main class="d-flex">
		<?php echo $sidebar; ?>
		<div class="w-50 mx-auto rounded general-shadow mt-4 mobile-target overflow-hidden">
			<div id="navbar-container">
				<nav aria-label="Page navigation example">
					<ul class="pagination justify-content-end">
						<li data-page="manual" class="page-item page-link text-center w-50 rounded-top" style="cursor: pointer; user-select: none">
							<i class="bi bi-ui-radios"></i>
							Manual
						</li>
						<li data-page="excel" class="page-item page-link text-center w-50 rounded-top" style="cursor: pointer; user-select: none">
							<i class="bi bi-file-earmark-spreadsheet-fill"></i>
							Excel
						</li>
					</ul>
				</nav>
			</div>
			<form id="manualForm" action="./?dir=admin&controlador=Estudiante&accion=Crear" method="post" class="px-4 pb-4" enctype="multipart/form-data">
				<h2 class="fs-3">Nuevo Estudiante</h2>
				<div class="manual mb-3">
					<label for="nombre" class="form-label">Nombre</label>
					<input type="text" class="form-control" id="nombre" name="nombre">
				</div>
				<div class="manual mb-3">
					<label for="primerApellido" class="form-label">Primer Apellido</label>
					<input type="text" class="form-control" id="primerApellido" name="primerApellido">
				</div>
				<div class="manual mb-3">
					<label for="segundoApellido" class="form-label">Segundo Apellido</label>
					<input type="text" class="form-control" id="segundoApellido" name="segundoApellido">
				</div>
				<div class="manual mb-3">
					<label for="cedula" class="form-label">Cédula</label>
					<input type="text" class="form-control" id="cedula" name="cedula">
				</div>
				<div class="manual mb-3">
					<label for="idEspecialidad" class="form-label">Seleccionar Especialidad</label>
					<select class="form-control" name="idEspecialidad" id="idEspecialidad">
						<?php
						if ($todosEspecialidad != null) {
							foreach ($todosEspecialidad as $especialidad) {
								if ($especialidad->getEstado() == 1) {
						?>
									<option value="<?php echo $especialidad->getId(); ?>"><?php echo $especialidad->getDescripcion(); ?></option>
						<?php
								}
							}
						}
						?>
					</select>
				</div>
				<div class="manual mb-3">
					<label for="idSeccion" class="form-label">Seleccionar Seccion</label>
					<select class="form-control" name="idSeccion" id="idSeccion">
						<?php
						if ($todosSeccion != null) {
							foreach ($todosSeccion as $seccion) {
								if ($seccion->getEstado() == 1) {
						?>
									<option value="<?php echo $seccion->getId(); ?>"> <?php echo $seccion->getDescripcion(); ?></option>
						<?php
								}
							}
						}
						?>
					</select>
				</div>
				<div class="manual mb-3">
					<label for="correo" class="form-label">Correo</label>
					<input type="email" class="form-control" id="correo" name="correo">
				</div>
				<div class="mb-3">
					<label for="inputFotoPerfil" class="form-label">Foto de Perfil (Opcional)</label>
					<input id="inputFotoPerfil" type="file" accept="image/jpg, image/png, image/jpeg" name="profile-image" class="form-control" />
					<div></div>
				</div>
				<div class="mb-3">
					<label for="" class="form-label">Beca completa: </label>
					<label class="ms-2 form-check-label" for="noBecado">No</label>
					<input class="form-check-input" checked style="cursor: pointer" value="0" type="radio" name="becado" id="noBecado">
					<label class="ms-2 form-check-label" for="siBecado">Sí</label>
					<input class="form-check-input" style="cursor: pointer" value="1" type="radio" name="becado" id="siBecado">
				</div>
				<button id="buttonCrearManual" class="btn btn-comedor" type="submit" disabled>Crear</button>
				<a id="buttonVolverManual" type="button" class="btn btn-secondary" href="./?dir=admin&controlador=Estudiante&accion=Index&id=main">Volver</a>
			</form>
			<form class="d-none px-4 pb-4" id="excelForm">
				<div class="d-flex flex-wrap justify-content-between">
					<h2 class="fs-3 mb-0">Ingresar Estudiantes</h2>
					<button type="button" id="btnPlantilla" class="btn btn-comedor">Descargar Plantilla</button>
				</div>
				<div class="mb-4 mt-3 position-relative">
					<label for="formFileLg" class="form-label d-block">Arrastra o selecciona un archivo de excel con los estudiantes</label>
					<input id="upload" name="files[]" type="file" accept=".xlsx" class="inputfile inputfile-5" id="formFileLg" placeholder="asdf">
					<label for="upload">
						<figure class="border">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
								<path d="M144 480C64.47 480 0 415.5 0 336C0 273.2 40.17 219.8 96.2 200.1C96.07 197.4 96 194.7 96 192C96 103.6 167.6 32 256 32C315.3 32 367 64.25 394.7 112.2C409.9 101.1 428.3 96 448 96C501 96 544 138.1 544 192C544 204.2 541.7 215.8 537.6 226.6C596 238.4 640 290.1 640 352C640 422.7 582.7 480 512 480H144zM223 263C213.7 272.4 213.7 287.6 223 296.1C232.4 306.3 247.6 306.3 256.1 296.1L296 257.9V392C296 405.3 306.7 416 320 416C333.3 416 344 405.3 344 392V257.9L383 296.1C392.4 306.3 407.6 306.3 416.1 296.1C426.3 287.6 426.3 272.4 416.1 263L336.1 183C327.6 173.7 312.4 173.7 303 183L223 263z" />
							</svg>
						</figure>
						<span id="textoArchivo">Seleccionar archivo</span>
					</label>
				</div>
				<button id="enviarExcel" type="submit" class="btn btn-comedor" disabled>Enviar</button>
				<a id="buttonVolverManual" type="button" class="btn btn-secondary" href="./?dir=admin&controlador=Estudiante&accion=Index&id=main">Volver</a>
			</form>
		</div>
	</main>

	<div id="carga" class="d-none border justify-content-center align-items-center rounded shadow-lg p-4 position-fixed bg-light" style="width: 15rem; height: 4rem; z-index: 20; inset: 0; margin: 0 auto; top: 5rem">
		<span class="loader d-block me-4"></span>
		<div>Subiendo datos</div>
	</div>

	<table hidden id="tabla">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Primer Apellido</th>
				<th>Segundo Apellido</th>
				<th>Cédula</th>
				<th>Correo</th>
				<th>Especialidad</th>
				<th>Sección</th>
				<th>TipoDeBeca</th>
			</tr>
		</thead>
		<tbody id="tbodyPlantilla">
			<?php
			if ($todosEspecialidad != null) {
				foreach ($todosEspecialidad as $especialidad) {
					if ($especialidad->getEstado() == 1) {
			?>
						<tr>
							<td>Fernando</td>
							<td>Camacho</td>
							<td>Solano</td>
							<td>numCedula</td>
							<td>correo@gmail.com</td>
							<td><?php echo $especialidad->getDescripcion(); ?></td>
							<td>10-A</td>
							<td>
								<?php
								if (rand(1, 100) < 50) echo "Subvencionada";
								else echo "Completa";
								?>
							</td>
						</tr>
			<?php
					}
				}
			}
			?>
		</tbody>
	</table>

	<form id="formEnviarExcel" action="./?dir=admin&controlador=Estudiante&accion=CrearPorJSON" method="post" hidden>
		<input type="text" name="PostJson">
		<button></button>
	</form>

	<script>
		//Cambiar entre pestañas manual <-> excel
		const navbarContainer = document.getElementById('navbar-container');
		const manualForm = document.getElementById('manualForm');
		const excelForm = document.getElementById('excelForm');

		navbarContainer.addEventListener('click', (e) => {
			if (e.target.dataset.page == "manual") {
				manualForm.classList.remove("d-none");
				excelForm.classList.add("d-none");
			} else {
				manualForm.classList.add("d-none");
				excelForm.classList.remove("d-none");
			}
		});

		//Actualizar texto bajo el icono de nube
		const textoArchivo = document.getElementById('textoArchivo');
		upload.addEventListener('change', (e) => {
			textoArchivo.textContent = e.target.files[0].name;
		});

		//Retorno de JSON con los datos del Excel
		let jsonToSubmit = false;
		var ExcelToJSON = function() {

			this.parseExcel = function(file) {
				var reader = new FileReader();

				reader.onload = function(e) {
					var data = e.target.result;
					var workbook = XLSX.read(data, {
						type: 'binary'
					});
					workbook.SheetNames.forEach(function(sheetName) {
						var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
						var json_object = JSON.stringify(XL_row_object);
						jsonToSubmit = JSON.stringify(json_object);
					})
				};

				reader.onerror = function(ex) {
					console.log(ex);
				};

				reader.readAsBinaryString(file);
			};
		};

		const enviarExcelButton = document.getElementById('enviarExcel');

		function handleFileSelect(evt) {
			if (evt.explicitOriginalTarget.value.slice(-4) == "xlsx") {
				enviarExcelButton.disabled = false;
				var files = evt.target.files;
				var xl2json = new ExcelToJSON();
				xl2json.parseExcel(files[0]);
			}
		}

		enviarExcelButton.addEventListener('click', (e) => {
			const cargaTarget = document.getElementById('carga');
			const formEnviarExcel = document.getElementById('formEnviarExcel');
			cargaTarget.classList.remove('d-none');
			cargaTarget.classList.add('d-flex');

			e.preventDefault();

			formEnviarExcel.firstElementChild.value = jsonToSubmit;
			formEnviarExcel.lastElementChild.click();
		});

		document.getElementById('upload').addEventListener('change', handleFileSelect, false);


		//validaciones
		let manualInputs = [...document.getElementsByClassName('manual')];
		const buttonCrearManual = document.getElementById('buttonCrearManual');
		let imagenValida = true;

		const validarCampos = () => {
			let estadoBoton = false;

			manualInputs.forEach(e => {
				if (e.children[1].value == "") {
					estadoBoton = true;
				}
			})

			buttonCrearManual.disabled = estadoBoton;
			if (!imagenValida) {
				buttonCrearManual.disabled = true;
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

		//Descargar Plantilla
		const btnPlantilla = document.getElementById('btnPlantilla');
		const tabla = document.getElementById('tabla');
		const tbodyPlantilla = document.getElementById('tbodyPlantilla');

		if (tbodyPlantilla.childElementCount === 0) {
			btnPlantilla.disabled = true;
			manualForm.innerHTML = "<p class='text-center'>Deben existir especialidades y secciones para poder crear un estudiante.</p>";
		}

		btnPlantilla.addEventListener("click", function() {
			let tableExport = new TableExport(tabla, {
				exportButtons: false,
				filename: "PlantillaEstudiantes",
				sheetname: "PlantillaEstudiantes",
			});
			let datos = tableExport.getExportData();
			let preferenciasDocumento = datos.tabla.xlsx;
			tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);
		});
	</script>
</body>
<?php
if (isset($_REQUEST['alerta'])) {
	$nombreAlerta = $_REQUEST['alerta'];
	if ($nombreAlerta == "error") {
		echo "<script>alertify.error('Imagen no válida.');</script>";
	}
}
?>

</html>
