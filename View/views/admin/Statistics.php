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

	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
	<title>Comedor - Estadísticas</title>
	<?php echo $head; ?>
</head>

<body>
	<?php echo $header; ?>
	<main class="d-flex">
		<?php echo $sidebar; ?>
		<section class="w-75 mobile-target container-fluid mt-4">
			<h1 class="fs-3">Estadísticas Generales</h1>
			<div class="d-flex justify-content-between flex-wrap gap-3">
				<h2 class="fs-4 text-secondary" id="tituloLapso"></h2>
				<button class="btn btn-comedor" data-bs-toggle="modal" data-bs-target="#modalFiltro">Filtrar</button>
			</div>
			<section id="contenedorEstadisticas" class="justify-content-center my-3 rounded general-shadow overflow-hidden">
				<header id="tabs" class="d-flex" style="cursor: pointer">
					<button class="w-50 py-1 text-center target-background border" data-page="first">General</button>
					<button class="w-50 py-1 text-center target-background border" data-page="second">Gráficas</button>
				</header>
				<div id="firstPage" class="row mx-0 p-3">
					<div class="col-sm-5">
						<div id="bloqueGraficaAsistencia" class="rounded px-0 general-shadow py-3 mx-auto">
							<h3 class="fw-bold fs-5 text-center">Asistencia General</h3>
							<div id="Asistencia"></div>
						</div>
					</div>
					<div class="col-sm-7">
						<!--Fix table size-->
						<div id="bloqueMasAusentes" class="row rounded overflow-hidden general-shadow py-3" style="max-height: auto">
							<h3 id="tituloTablaMasAusentes" class="fw-bold fs-5 text-center">Estudiantes más ausentes</h3>
							<div class="table-responsive overflow-auto h-100 pb-3">
								<table class="table overflow-auto">
									<thead class="sticky-top general-shadow">
										<tr class="table-light">
											<th class="text-center">#</th>
											<th>Nombre</th>
											<th>Cédula</th>
											<th class="text-center">Ausencias</th>
										</tr>
									</thead>
									<tbody id="tbodyMasAusentes">
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div id="secondPage" class="row mx-0 p-3" hidden>
					<section class="general-shadow rounded px-0 overflow-hidden mb-4">
						<header class="target-background px-2 py-1 border">
							<h3 class="fs-4 mb-0">Duodécimo</h3>
						</header>
						<main id="duoDecimo" class="row gap-5 justify-content-between px-4 py-3"></main>
					</section>
					<section class="general-shadow rounded px-0 overflow-hidden mb-4">
						<header class="target-background px-2 py-1 border">
							<h3 class="fs-4 mb-0">Undécimo</h3>
						</header>
						<main id="unDecimo" class="row gap-5 justify-content-between px-4 py-3"></main>
					</section>
					<section class="general-shadow rounded px-0 overflow-hidden">
						<header class="target-background px-2 py-1 border">
							<h3 class="fs-4 mb-0">Décimo</h3>
						</header>
						<main id="decimo" class="row gap-5 justify-content-between px-4 py-3"></main>
					</section>
				</div>
			</section>
		</section>
	</main>

	<!-- Filter Modal -->
	<div class="modal fade" id="modalFiltro" tabindex="-1" aria-labelledby="modalFiltroLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="fw-bold fs-5 text-center pt-2">Filtrar Estadísticas</h3>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body px-0">
					<div class="mb-3 px-3">
						<label for="Perfil">Perfil</label>
						<select class="form-select" name="perfil" id="selectPerfil">
							<option value="estudiante">Estudiante</option>
							<option value="profesor">Profesor</option>
						</select>
					</div>
					<div class="mb-3 px-3" id="contenedorSelectBeca">
						<label for="Beca" class="mb-1">Beca estudiantil</label>
						<select class="form-select" name="beca" id="selectBeca">
							<option value="cualquiera">Ambas</option>
							<option value="1">Beca Completa</option>
							<option value="0">Beca Subvencionada</option>
						</select>
					</div>
					<div id="inputsRadioContainer" class="p-3 target-background">
						<h4 class="fs-5">Filtrar por tiempo</h4>
						<label class="d-block form-check-label" style="cursor: pointer">
							<input class="form-check-input me-2" value="anual" type="radio" name="tiempo" id="inputAnual">
							Durante todo el año
						</label>
						<label class="d-block form-check-label" style="cursor: pointer">
							<input class="form-check-input me-2" value="diaEspecifico" type="radio" name="tiempo" id="inputFecha">
							Día específico
						</label>
						<label class="d-block form-check-label" style="cursor: pointer">
							<input class="form-check-input me-2" value="lapso" type="radio" name="tiempo" id="inputLapso">
							Lapso de tiempo
						</label>
					</div>
					<section id="inputsTiempo" class=""></section>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-comedor" disabled id="AplicarFiltroBoton">Aplicar filtro</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Section Info Modal -->
	<div class="modal fade" id="modalInfoSeccion" tabindex="-1" aria-labelledby="modalInfoSeccionLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalInfoSeccionLabel">Sección 12-A</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-0">
					<div class="d-flex flex-wrap justify-content-between align-items-center my-2">
						<h4 class="fs-5 px-3 mb-0">Más ausentes</h4>
						<header id="filtroSeccionContenedor" class="d-flex gap-3 flex-wrap px-3 py-2"></header>
					</div>
					<div class="table-responsive overflow-auto h-100 px-3">
						<table class="table overflow-auto">
							<thead class="sticky-top general-shadow">
								<tr class="table-light">
									<th class="text-center">#</th>
									<th>Nombre</th>
									<th>Cédula</th>
									<th class="text-center">Ausencias</th>
								</tr>
							</thead>
							<tbody id="tbodyModalSeccion">
							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<form id="filterForm" action="./?dir=admin&controller=AdminStatistics&action=Index" method="post" hidden>
		<input type="text" name="fechaInicio">
		<input type="text" name="fechaFin">
		<input type="text" name="beca">
		<input type="text" name="perfil">
		<button></button>
	</form>

	<div id="attendanceRecords" hidden>
		<?php
		foreach ($attendanceRecords as $record) {
			echo "<div>$record</div>";
		}
		?>
	</div>

	<div id="filterData" hidden>
		<?php echo json_encode($filterData) ?>
	</div>

	<div id="mostAbsentClients" hidden>
		<?php
		echo json_encode($mostAbsentClients);
		?>
	</div>

	<div id="chartData" hidden data-secciones='<?php echo json_encode($sections) ?>' data-especialidades='<?php echo json_encode($specialties) ?>'></div>

	<script>
		const jsonMostAbsentClients = document.getElementById('mostAbsentClients');
		const mostAbsentClients = JSON.parse(jsonMostAbsentClients.textContent);
		const tbodyMasAusentes = document.getElementById('tbodyMasAusentes');
		const attendanceRecords = document.getElementById('attendanceRecords');
		const date = new Date();
		const tituloLapso = document.getElementById('tituloLapso');
		const inputsRadioContainer = document.getElementById('inputsRadioContainer');
		const filterForm = document.getElementById('filterForm');
		const AplicarFiltroBoton = document.getElementById('AplicarFiltroBoton');
		const inputAnual = document.getElementById('inputAnual');
		const inputsTiempo = document.getElementById('inputsTiempo');
		const selectPerfil = document.getElementById('selectPerfil');
		const selectBeca = document.getElementById('selectBeca');
		const contenedorSelectBeca = document.getElementById('contenedorSelectBeca');
		let dataToServer = null;
		let filterData = document.getElementById('filterData');
		let validator = true;
		let status = false;
		let absences = 0;
		let attendances = 0;
		let schoolDayCount = 0;

		// Filter title
		let buttonToSecondPage = document.querySelector('[data-page="second"]');

		function loadTitle() {
			filterData = JSON.parse(filterData.textContent);
			const tituloTablaMasAusentes = document.getElementById('tituloTablaMasAusentes');

			for (optionElement of selectPerfil.children) {
				if (optionElement.value !== filterData.Perfil) continue;

				optionElement.selected = true;
			}

			for (optionElement of selectBeca.children) {
				if (optionElement.value !== filterData.Beca) continue;

				optionElement.selected = true;
			}

			if (filterData.Perfil === "profesor") {
				tituloTablaMasAusentes.textContent = "Profesores más ausentes"
				contenedorSelectBeca.classList.add('d-none');
				buttonToSecondPage.disabled = true;
			}

			let currentMonth = ("0" + (Number(date.getMonth()) + 1)).slice(-2);
			let currentDay = date.getDate();
			let today = date.getFullYear() + "-" + currentMonth + "-" + currentDay;
			if (filterData.FechaInicio === date.getFullYear() + "-01-01" && filterData.FechaFin === today)
				tituloLapso.textContent = date.getFullYear();
			else if (filterData.FechaInicio === filterData.FechaFin)
				tituloLapso.textContent = formatDate(filterData.FechaInicio);
			else
				tituloLapso.textContent = `${formatDate(filterData.FechaInicio)} - ${formatDate(filterData.FechaFin)}`;
		}
		loadTitle();


		// Filter
		function identifyInputRadio(e) {
			AplicarFiltroBoton.disabled = false;
			if (e.target.type === 'radio') removeAllChildNodes(inputsTiempo);
			if (e.target.value === "anual") {
				dataToServer = "datosAnuales";
			} else if (e.target.value === "diaEspecifico") {
				inputsTiempo.insertAdjacentHTML('afterbegin', `
						<div class="p-3 pb-0 d-flex align-items-center flex-wrap">
						  <label class="d-block me-2">Seleccione el día</label>
						  <input type="date" max="<?php echo date('Y-m-d') ?>" id="inputDiaEspecifico" class="form-control" style="width: max-content" name="diaEspecifico">
						</div>
				`);
			} else if (e.target.value === "lapso") {
				inputsTiempo.insertAdjacentHTML('afterbegin', `
						<div class="pt-3 d-flex align-items-center flex-wrap">
								<div class="text-center w-50">
									<label class="d-block me-2">Día inicio</label>
									<input type="date" id="inputDiaInicio" class="form-control mx-auto" style="width: max-content" max="<?php echo date('Y-m-d') ?>" name="diaEspecifico">
								</div>
								<div class="w-50 text-center">
									<label class="d-block me-2">Día fin</label>
									<input type="date" id="inputDiaFin" class="form-control mx-auto" style="width: max-content" max="<?php echo date('Y-m-d') ?>" name="diaEspecifico">
								</div>
						</div>
				`);
			}
		}
		inputsRadioContainer.addEventListener('click', (e) => {
			identifyInputRadio(e)
		});

		selectPerfil.addEventListener('click', (e) => {
			if (e.target.tagName === "OPTION") {
				if (selectPerfil.value === 'profesor')
					contenedorSelectBeca.classList.add('d-none');
				else
					contenedorSelectBeca.classList.remove('d-none');
			}
		});

		// Filter
		AplicarFiltroBoton.addEventListener('click', () => {
			const inputDiaEspecifico = document.getElementById('inputDiaEspecifico');
			const inputDiaFin = document.getElementById('inputDiaFin');
			const inputDiaInicio = document.getElementById('inputDiaInicio');
			if (inputDiaEspecifico) {
				filterForm.children[0].value = inputDiaEspecifico.value;
				filterForm.children[1].value = inputDiaEspecifico.value;
				filterForm.children[2].value = selectBeca.value;
				filterForm.children[3].value = selectPerfil.value;
				if (inputDiaEspecifico.value != "")
					status = true;
			} else if (inputDiaFin && inputDiaInicio) {
				filterForm.children[0].value = inputDiaInicio.value;
				filterForm.children[1].value = inputDiaFin.value;
				filterForm.children[2].value = selectBeca.value;
				filterForm.children[3].value = selectPerfil.value;
				if (inputDiaInicio.value != "" && inputDiaFin.value != "" && inputDiaInicio.value <= inputDiaFin.value)
					status = true;
			} else if (inputAnual.checked) {
				let currentYear = date.getFullYear();
				let currentMonth = ("0" + (Number(date.getMonth()) + 1)).slice(-2);
				filterForm.children[0].value = currentYear + "-01-01";
				filterForm.children[1].value = currentYear + "-" + currentMonth + "-" + date.getDate();
				filterForm.children[2].value = selectBeca.value;
				filterForm.children[3].value = selectPerfil.value;
				status = true;
			}

			if (status) {
				filterForm.lastElementChild.click();
				AplicarFiltroBoton.insertAdjacentHTML('afterend', `<button class="btn btn-primary" type="button" disabled>
							<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
							Cargando...
						</button>`);
				AplicarFiltroBoton.remove();
			}
		});


		// Count non-school days
		function countNonSchoolDays() {
			for (record of attendanceRecords.children) {
				if (JSON.parse(record.textContent)) {
					let clientRecord = Object.values(JSON.parse(record.textContent));
					clientRecord.forEach(e => {
						if (e.Estado != "Presente")
							schoolDayCount++;
					});
				} else
					validator = false;
				break;
			}
		}
		countNonSchoolDays();

		// Fill most absent table
		function mostAbsentTable() {
			mostAbsentClients.sort(function(a, b) {
				if (a.Asistencias > b.Asistencias) {
					return 1;
				}
				if (a.Asistencias < b.Asistencias) {
					return -1;
				}
				// a must be equal to b
				return 0;
			});

			if (schoolDayCount === 0) schoolDayCount = 1;
			for (absentClient of mostAbsentClients) {
				tbodyMasAusentes.insertAdjacentHTML('beforeend', `
						<tr class="align-middle">
								<td class="text-center">${tbodyMasAusentes.childElementCount + 1}</td>
								<td>${absentClient.Nombre} ${absentClient.Apellido1} ${absentClient.Apellido2}</td>
								<td>${absentClient.Cedula}</td>
								<td class="text-center fw-bold text-danger">${schoolDayCount - absentClient.Asistencias}</td>
						</tr>
				`);
			}
		}
		if (validator) {
			mostAbsentTable();
		}

		// Function to count absences and attendances
		function countGeneralAttendance() {
			let currentMonth = ("0" + (Number(date.getMonth()) + 1)).slice(-2);
			let currentDay = date.getDate();
			let today = date.getFullYear() + "-" + currentMonth + "-" + currentDay;

			for (record of attendanceRecords.children) {
				let clientRecord = Object.values(JSON.parse(record.textContent));
				for (element of clientRecord) {
					if (element.Fecha > today) continue;
					element.Estado === "Ausente" ? absences++ : attendances++;
				}
			}
		}

		if (validator) {
			countGeneralAttendance();
		}

		// Function that generates the chart
		function createChart(attendances, absences, canvas, legendPosition, typeChart) {
			var xValues = ["Asistencias", "Ausencias"];
			var yValues = [attendances, absences];
			var barColors = [
				"hsla(223, 77%, 51%, 0.75)",
				"rgba(213,48,67,0.75)"
			];
			var borderColors = [
				"#305ed5ff",
				"#d53043"
			];

			new Chart(canvas, {
				type: typeChart,
				data: {
					labels: xValues,
					datasets: [{
						backgroundColor: barColors,
						data: yValues,
						borderColor: borderColors,
						borderWidth: 2
					}]
				},
				options: {
					responsive: true,
					legend: {
						position: legendPosition
					},
					title: {
						display: false
					}
				}
			});
		}
		if (validator) {
			removeAllChildNodes(Asistencia);
			Asistencia.insertAdjacentHTML('beforeend', `<canvas height="250"></canvas>`);
			createChart(attendances, absences, Asistencia.children[0], 'top', "doughnut");
		} else {
			const bloqueGraficaAsistencia = document.getElementById('bloqueGraficaAsistencia');
			const bloqueMasAusentes = document.getElementById('bloqueMasAusentes');
			bloqueGraficaAsistencia.parentElement.classList.add('d-none');
			bloqueMasAusentes.parentElement.classList.add('mx-auto');
			buttonToSecondPage.disabled = true;
			tbodyMasAusentes.insertAdjacentHTML('beforeend', `<tr><td colspan="4" class="text-center">No hay registros.</td></tr>`)
		}

		// Tab switching
		const tabs = document.getElementById('tabs');
		const firstPage = document.getElementById('firstPage');
		const secondPage = document.getElementById('secondPage');

		tabs.addEventListener('click', (event) => {
			if (event.target.dataset.page === "first") {
				firstPage.hidden = false;
				secondPage.hidden = true;
			}

			if (event.target.dataset.page === "second") {
				firstPage.hidden = true;
				secondPage.hidden = false;
			}
		});

		// Charts section
		const chartData = document.getElementById('chartData');
		const duoDecimo = document.getElementById('duoDecimo');
		const unDecimo = document.getElementById('unDecimo');
		const decimo = document.getElementById('decimo');

		if (chartData.dataset.secciones != "") generateSections();

		function generateSections() {
			let sectionsArray = Object.values(JSON.parse(chartData.dataset.secciones));

			// Sorting sections alphabetically
			sectionsArray.sort(function(a, b) {
				if (a.descripcion > b.descripcion) {
					return 1;
				}
				if (a.descripcion < b.descripcion) {
					return -1;
				}

				return 0;
			});

			let levels = {
				"12": duoDecimo,
				"11": unDecimo,
				"10": decimo
			};

			for (section of sectionsArray) {
				let sectionCardName = section.descripcion;
				let sectionLevel = section.descripcion.split('-')[0];
				let sectionCardId = section.id;

				if (!levels[sectionLevel]) continue;

				let levelMainElement = levels[sectionLevel];
				levelMainElement.insertAdjacentHTML('beforeend', `
					<section class="target col-sm-3 rounded overflow-hidden general-shadow px-0" style="min-width: 15em; border: 1px solid #305ed5ff;">
						<header class="main-background text-light p-2 d-flex justify-content-between align-items-center">
							<h4 class="fw-bold mb-0">${sectionCardName}</h4>
								<button data-idseccion="${sectionCardId}" data-seccion="${sectionCardName}" class="btn btn-sm btn-light align-middle" data-bs-toggle="modal" data-bs-target="#modalInfoSeccion">
								<i class="d-block fa-solid fa-circle-info" style="font-size: 1.1rem; color: #333;"></i>
							</button>
						</header>
						<main class="py-1 px-2">
							
						</main>
					</section>
				`);

				// Line that adds a canvas element to the card
				levelMainElement.lastElementChild.lastElementChild.appendChild(returnChart(sectionCardId));
			}
		}

		function returnChart(sectionIdParam) {
			const canvas = document.createElement('canvas');
			let sectionAttendances = 0;
			let sectionAbsences = 0;

			for (student of mostAbsentClients) {
				if (student.IdSeccion !== sectionIdParam) continue;

				let studentAbsences = schoolDayCount - (student.Asistencias ?? 0);
				sectionAttendances += (Number(student.Asistencias) ?? 0);
				sectionAbsences += studentAbsences;
			}

			createChart(sectionAttendances, sectionAbsences, canvas, 'left', 'pie');

			return canvas;
		}

		// View section information
		const contenedorEstadisticas = document.getElementById('contenedorEstadisticas');
		const modalInfoSeccionLabel = document.getElementById('modalInfoSeccionLabel');
		const modalInfoSeccion = document.getElementById('modalInfoSeccion');
		let sectionName;
		let sectionId;
		let sectionSpecialties = [];
		let sectionStudents = [];
		let specialties = Object.values(JSON.parse(chartData.dataset.especialidades));

		contenedorEstadisticas.addEventListener('click', (event) => {
			if (event.target.classList.contains('fa-solid')) {
				sectionName = event.target.parentElement.dataset.seccion;
				sectionId = event.target.parentElement.dataset.idseccion;
			} else if (event.target.dataset.seccion) {
				sectionName = event.target.dataset.seccion;
				sectionId = event.target.dataset.idseccion;
			} else return;

			modalInfoSeccion.click();
			filterSectionsTable();
			modalInfoSeccionLabel.textContent = `Sección ${sectionName}`;
			showSectionSpecialties(sectionSpecialties);
		});

		modalInfoSeccion.addEventListener('click', (e) => {
			sectionSpecialties = [];
			sectionStudents = [];

			for (student of mostAbsentClients) {
				if (student.IdSeccion !== sectionId) continue;
				sectionSpecialties.push(student.IdEspecialidad);
				sectionStudents.push(student);
			}

			sectionStudents.sort(function(a, b) {
				if (a.Asistencias > b.Asistencias) {
					return 1;
				}
				if (a.Asistencias < b.Asistencias) {
					return -1;
				}

				return 0;
			});

			sectionSpecialties = Array.from(new Set(sectionSpecialties));
		});

		const filtroSeccionContenedor = document.getElementById('filtroSeccionContenedor');

		function filterSectionsTable() {
			removeAllChildNodes(filtroSeccionContenedor);
			for (specialtyIdInSection of sectionSpecialties) {
				specialties.find(e => {
					if (e.id === specialtyIdInSection) {
						filtroSeccionContenedor.insertAdjacentHTML('beforeend', `
							<label class="form-check-label" style="cursor: pointer; user-select: none">
								${e.descripcion}
								<input checked class="form-check-input" type="checkbox" name="especialidad" value="${e.id}" style="cursor: pointer">
							</label>
						`);
					}
				});
			}
		}

		filtroSeccionContenedor.addEventListener('click', (e) => {
			if (e.target.nodeName !== "INPUT") return;

			let specialtyInputs = [...document.querySelectorAll("[name=especialidad]:checked")];
			let specialtyIds = [];

			for (specialtyInput of specialtyInputs) {
				specialtyIds.push(specialtyInput.value)
			}

			showSectionSpecialties(specialtyIds);
		});

		const tbodyModalSeccion = document.getElementById('tbodyModalSeccion');

		function showSectionSpecialties(specialtyIds) {
			removeAllChildNodes(tbodyModalSeccion);

			for (sectionStudent of sectionStudents) {
				if (!specialtyIds.includes(sectionStudent.IdEspecialidad)) continue;

				tbodyModalSeccion.insertAdjacentHTML('beforeend', `
						<tr class="align-middle">
								<td class="text-center">${tbodyModalSeccion.childElementCount + 1}</td>
								<td>${sectionStudent.Nombre} ${sectionStudent.Apellido1} ${sectionStudent.Apellido2}</td>
								<td>${sectionStudent.Cedula}</td>
								<td class="text-center fw-bold text-danger">${schoolDayCount - sectionStudent.Asistencias}</td>
						</tr>
				`);
			}

			if (tbodyModalSeccion.childElementCount === 0) {
				tbodyModalSeccion.insertAdjacentHTML('beforeend', `
						<tr class="align-middle">
								<td colspan="4" class="text-center">No hay registros.</td>
						</tr>
				`);
			}
		}
	</script>
</body>

</html>