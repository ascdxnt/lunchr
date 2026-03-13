<?php
$head = file_get_contents('./View/views/components/Head.php');
$header = file_get_contents('./View/views/components/Header.php');
$sidebar = file_get_contents('./View/views/components/AdminMenu.php');

if ($_SESSION["perfiles"] != 'admin') {
	header('Location: ./?alerta=error');
}

if (isset($_REQUEST['estados'])) $status = 0;
else $status = 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Comedor - Ajustes</title>
	<?php echo $head; ?>
</head>

<body>
	<?php echo $header; ?>
	<main class="d-flex">
		<?php echo $sidebar; ?>


		<section class="mt-4 container-md px-3 d-flex flex-column  align-items-sm-center align-items-md-stretch">
			<div class="container">
				<div class="row">
					<div class="accordion" id="accordionExample">
						<div class="accordion-item">
							<h2 class="accordion-header" id="headingOne">
								<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									Días no lectivos
								</button>
							</h2>
							<div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									<div class="col-sm-10 col-md-12 col-lg-12 col-xl-12 rounded">

										<section class="table-system mt-3">
											<div class="d-flex justify-content-between gap-1">
												<input id="inputSearch" class="form-control" type="search" placeholder="Buscar" style="width: 100%; max-width: 15em">
												<div class="d-flex justify-content-between gap-1">
													<button type="button" class="btn ms-auto btn-comedor" data-bs-toggle="modal" data-bs-target="#agregarModal">Agregar</button>
												</div>
											</div>
											<div class="table-responsive general-shadow mt-3" style="max-height: 70vh">
												<table id="table" class="table overflow-auto rounded mb-0">
													<thead class="sticky-top general-shadow" style="border-bottom: 2px solid #e5e7eb !important">
														<tr id="tableHeadRow" class="align-middle text-center" style="background-color: #e5e7eb; color: #4b5563">
															<th>Fecha</th>
															<th>Nombre</th>
															<th class="text-center">Acciones</th>
														</tr>
													</thead>
													<tbody id="tableBodyElement">
													</tbody>
												</table>
											</div>
										</section>
									</div>
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h2 class="accordion-header" id="headingTwo">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
									Sistema de contraseña
								</button>
							</h2>
							<div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									<div class="w-sm-50">
										<div class="col-sm-12 col-md-8 col-lg-6 col-xl-6 rounded">
											<form id="manualForm" action="./?dir=helpers&controller=Password&action=PasswordSettings" method="post">
												<div class="manual">
													<label class="form-label  fs-5" for="">Longitud de
														contraseñas</label>
													<input type="" autofocus class="form-control" id="inputLongitud" autofocus value="<?php echo $configs->passwordLength ?>" name="passwordLength" placeholder="longitud contra">
												</div>
												<div class="manual">
													<label class="form-label my-2  fs-5" for="">Caracteres de
														contraseñas</label>
													<input type="text" autofocus class="form-control" id="inputCaracteres" value="<?php echo $configs->passwordCharacters ?>" name="passwordCharacters" placeholder="caracteres contra">
												</div>
												<div class="mt-2">
													<button type="submit" id="configurarContraBoton" class="btn btn-comedor mt-2" disabled>Guardar cambios
													</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- Modal list -->

		<!-- Add Modal -->
		<div class="modal fade" id="agregarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="agregarModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="agregarModalLabel">Agregar día no lectivo</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">

						<div class="accordion accordion-flush" id="accordionFlushExample">
							<div class="accordion-item">
								<h2 class="accordion-header" id="flush-headingOne">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
										Agregrar un día
									</button>
								</h2>
								<div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
									<div class="accordion-body">
										<form method="POST" id="specificDayForm" action="./?dir=admin&controller=NonSchoolDay&action=Create" class="border p-2">
											<div class="diaEspecifico mb-3 diaEspecifico">
												<label for="fechaInput" class="form-label">Fecha</label>
												<input type="date" class="form-control" id="fechaInput" name="diaEspecifico" aria-describedby="dateHelp">
											</div>
											<div class="diaEspecifico mb-3 diaEspecifico">
												<label for="nombreInput" class="form-label">Nombre del día</label>
												<input type="text" class="form-control" id="nombreInput" name="nombreDiaEspecifico">
											</div>
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
												Cancelar
											</button>
											<button type="submit" id="guardarDiaEspecificoBoton" class="btn btn-comedor" disabled>Guardar
											</button>
										</form>
									</div>
								</div>
							</div>
							<div class="accordion-item">
								<h2 class="accordion-header" id="flush-headingTwo">
									<button class="accordion-button  collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
										Agregar por lapso
									</button>
								</h2>
								<div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
									<div class="accordion-body">
										<form method="POST" id="periodForm" action="./?dir=admin&controller=NonSchoolDay&action=CreateRange" class="border p-2">
											<div class="lapso mb-3">
												<label for="fechaInicioInput" class="form-label">Fecha inicio</label>
												<input type="date" class="form-control" id="fechaInicioInput" aria-describedby="dateHelp" name="inicioLapsoTiempo">
											</div>
											<div class="lapso mb-3">
												<label for="fechaFinalInput" class="form-label">Fecha fin</label>
												<input type="date" class="form-control" id="fechaFinalInput" aria-describedby="dateHelp" name="finLapsoTiempo">
											</div>
											<div class="lapso mb-3">
												<label for="nombreInput" class="form-label">Nombre del lapso</label>
												<input type="text" class="form-control" id="nombreInput" name="nombreLapsoTiempo">
											</div>
											<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
												Cancelar
											</button>
											<button type="submit" class="btn btn-comedor" id="guardarLapsoBoton" disabled>
												Guardar
											</button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Update Modal -->
		<div class="modal fade" id="modificarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modificarModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<form method="POST" action="./?dir=admin&controller=NonSchoolDay&action=Update" class="border p-2">
						<div class="modal-header">
							<h5 class="modal-title" id="modificarModalLabel">Modificar día no lectivo</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="mb-3">
								<label for="modifcarFechaInput" class="form-label">Nueva Fecha</label>
								<input type="date" class="form-control" id="modifcarFechaInput" name="modificarDiaEspecifico" aria-describedby="dateHelp">
							</div>
							<input type="text" class="form-control" id="idFechaDiaEspecifico" name="idFechaDiaEspecifico" aria-describedby="dateHelp" hidden>
							<div class="mb-3">
								<label for="modificarNombreInput" class="form-label">Nombre del día</label>
								<input type="text" class="form-control" id="modificarNombreInput" name="modificarNombreDiaEspecifico">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
								<button id="buttonModificar" type="submit" class="btn btn-comedor">Guardar</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- Delete Modal -->
		<div class="modal fade" id="eliminarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<form method="POST" action="./?dir=admin&controller=NonSchoolDay&action=ChangeStatus" class="border p-2">
						<div class="modal-header">
							<h5 class="modal-title" id="modificarModalLabel">Eliminar día no lectivo</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<input type="text" class="form-control" id="idFecha" name="idFecha" aria-describedby="dateHelp" hidden>
							<span>¿Estás seguro de que quieres eliminar este día?</span>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
								<button id="buttonEliminar" type="submit" class="btn btn-danger">Eliminar</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</main>

	<div id="datos" hidden data-noLectivos='<?php echo $arrayNonSchoolDays ?>'></div>

	<script>
		const datos = document.getElementById('datos');
		const tableBodyElement = document.getElementById("tableBodyElement");
		const ENTITY_STATUS = 1;
		let nonSchoolDaysArray = null;

		if (datos.dataset.nolectivos != "") {
			nonSchoolDaysArray = JSON.parse(datos.dataset.nolectivos);

			nonSchoolDaysArray.sort(function(a, b) {
				if (a.fecha < b.fecha) {
					return 1;
				}
				if (a.fecha > b.fecha) {
					return -1;
				}
				// a must be equal to b
				return 0;
			});
			searchInTable("", ENTITY_STATUS);
		}

		// Validations
		let manualInputs = [...document.getElementsByClassName('manual')];
		const configurarContraBoton = document.getElementById('configurarContraBoton');
		const manualForm = document.getElementById('manualForm');
		const dateObject = new Date();
		manualForm.addEventListener('input', () => {
			let buttonStatus = false;
			manualInputs.forEach(e => {
				if (e.children[1].value == "") {
					buttonStatus = true;
				}
			})
			configurarContraBoton.disabled = buttonStatus;
		});

		let specificDay = [...document.getElementsByClassName('diaEspecifico')];
		const guardarDiaEspecificoBoton = document.getElementById('guardarDiaEspecificoBoton');
		const specificDayForm = document.getElementById('specificDayForm');
		specificDayForm.addEventListener('input', () => {
			let buttonStatus = false;
			specificDay.forEach(e => {
				if (e.children[1].value == "") {
					buttonStatus = true;
				}
			})
			guardarDiaEspecificoBoton.disabled = buttonStatus;
		});

		let period = [...document.getElementsByClassName('lapso')];
		const savePeriodButton = document.getElementById('guardarLapsoBoton');
		const periodForm = document.getElementById('periodForm');
		periodForm.addEventListener('input', () => {
			let buttonStatus = false;
			period.forEach(e => {
				if (e.children[1].value == "") {
					buttonStatus = true;
				}
			})
			guardarLapsoBoton.disabled = buttonStatus;
		});

		// Update modal
		const modificarNombreInput = document.getElementById('modificarNombreInput');
		const modifcarFechaInput = document.getElementById('modifcarFechaInput');
		const idFechaDiaEspecifico = document.getElementById('idFechaDiaEspecifico');
		tableBodyElement.addEventListener('click', (e) => {
			let row;
			if (e.target.classList.contains('fa-solid'))
				row = e.target.parentElement.parentElement.parentElement;
			else
				row = e.target.parentElement.parentElement;

			modificarNombreInput.value = row.dataset.nombre;
			idFechaDiaEspecifico.value = row.dataset.id;
			modifcarFechaInput.value = dateObject.getFullYear() + "-" + row.dataset.fecha;
		});

		// Open Delete

		function OpenDeleteModal(tempId) {
			let myModal = document.getElementById('eliminarModal')

			myModal.addEventListener('shown.bs.modal', function() {
				modificarDiaInput.focus()
			})
		}

		// Delete Modal
		const dateId = document.getElementById('idFecha');
		tableBodyElement.addEventListener('click', (e) => {
			let row;
			if (e.target.classList.contains('fa-solid'))
				row = e.target.parentElement.parentElement.parentElement;
			else
				row = e.target.parentElement.parentElement;

			dateId.value = row.dataset.id;
		});

		// Date format
		function formatNonSchoolDay(day) {
			day = day.split('-').reverse();

			day[0] = Number(day[0]);

			if (day[1] === '01')
				day[1] = 'de Enero';
			else if (day[1] === '02')
				day[1] = 'de Febrero';
			else if (day[1] === '03')
				day[1] = 'de Marzo';
			else if (day[1] === '04')
				day[1] = 'de Abril';
			else if (day[1] === '05')
				day[1] = 'de Mayo';
			else if (day[1] === '06')
				day[1] = 'de Junio';
			else if (day[1] === '07')
				day[1] = 'de Julio';
			else if (day[1] === '08')
				day[1] = 'de Agosto';
			else if (day[1] === '09')
				day[1] = 'de Septiembre';
			else if (day[1] === '10')
				day[1] = 'de Octubre';
			else if (day[1] === '11')
				day[1] = 'de Noviembre';
			else if (day[1] === '12')
				day[1] = 'de Diciembre';

			return day.join(' ');
		}

		function ChangeStatus(status) {
			let urlIds = "";
			let arrayLength = 0;
			let validRoute = false;
			for (let i = 0; i < tableBodyElement.children.length; i++) {
				if (tableBodyElement.children[i].firstElementChild.firstElementChild.firstElementChild.checked) {
					urlIds += `&idsArr[]=${tableBodyElement.children[i].dataset.id}`;
					validRoute = true;
					arrayLength++;
				}
			}
			if (validRoute) {
				let redirect = `./?dir=admin&controller=NonSchoolDay&action=ChangeStatus&id=${status}`;
				redirect += urlIds;
				redirect += `&lengthArray=${arrayLength}`;
				location.href = redirect;
			}
		}

		// Table

		// Search matches in table
		function searchInTable(text, userStatus) {
			if (!nonSchoolDaysArray)
				return null;

			nonSchoolDaysArray.forEach((element) => {
				let found = false;
				let tempArray = Object.values(element).splice(1, 2);
				tempArray.forEach((field, index) => {
					field = String(field);
					if (field.includes(text) && index == 0) found = true;
					else if (formatNonSchoolDay(field).includes(text)) found = true;
				});
				if (found && element.estado == userStatus) addRow(element);
			});
		}

		// Add rows to the table
		function addRow(e) {
			tableBodyElement.insertAdjacentHTML(
				"beforeend",
				`
					<tr class="transicion align-middle text-center" data-id="${e.id}" data-fecha="${e.fecha}" data-nombre="${e.nombre}">
							<td>${formatNonSchoolDay(e.fecha)}</td>
							<td>${e.nombre}</td>
							<td>
								<button type="button" class="btn btn-modificar btn-comedor" data-bs-toggle="modal" data-bs-target="#modificarModal">
									<i class="fa-solid fa-pen-to-square"></i>
								</button>
								<button class="btn btn-danger" onclick="OpenDeleteModal('${e.id}','${e.fecha}','${e.nombre}')" data-bs-toggle="modal" data-bs-target="#eliminarModal">
									<i class="fa-solid fa-trash-can"></i>
								</button>
							</td>
						</tr>
					`
			);
		}

		// Capture input data to search with searchInTable function
		const inputSearch = document.getElementById("inputSearch");
		const tableHeadRow = document.getElementById("tableHeadRow");
		inputSearch.addEventListener("input", () => {
			removeAllChildNodes(tableBodyElement);
			searchInTable(inputSearch.value, ENTITY_STATUS);
			tableNoRecords();
		});

		function tableNoRecords() {
			if (tableBodyElement.childElementCount === 0) {
				let colspanNumber = tableHeadRow.childElementCount;
				tableBodyElement.insertAdjacentHTML(
					"beforeend",
					`
							<tr>
								<td colspan="${colspanNumber}" class="text-center">No hay registros.</td>
							</tr>
						`
				);
			}
		}
		tableNoRecords();
	</script>

	<?php
	if (isset($_REQUEST['alerta'])) {
		$alertName = $_REQUEST['alerta'];
		if ($alertName == "success")
			echo "<script>alertify.success('Proceso exitoso');</script>";
		else if ($alertName == "error")
			echo "<script>alertify.error('Hubo un error');</script>";
		else if ($alertName == "error2")
			echo "<script>alertify.error('Día ya existente');</script>";
	}
	?>

</body>

</html>