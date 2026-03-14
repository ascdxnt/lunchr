<?php
$head = file_get_contents('./View/views/components/Head.php');
$header = file_get_contents('./View/views/components/Header.php');
$sidebar = file_get_contents('./View/views/components/ClientMenu.php');

$userId = $_SESSION['usuario']['Id'];
$userProfile = $_SESSION['usuario']['Perfil'];

if ($_SESSION["perfiles"] != 'client') {
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
		<section class="mt-4 mx-auto container-md px-3 d-flex flex-column align-items-sm-center align-items-md-stretch">
			<h1 id="AsistenciaTitulo" class="fs-3">Mis Estadísticas</h1>
			<section class="mt-4 d-flex flex-wrap gap-3 justify-content-beetwen">
				<div class="rounded general-shadow py-3 center position-relative mx-auto" style="width: 25rem;">
					<h3 class="mb-3 d-flex justify-content-between fs-5 fw-bold px-3">Gráfica</h3>
					<div id="Asistencia">
					</div>
				</div>
				<div class="py-3 px-4 rounded float-start general-shadow mobile-target2 mx-auto" style="min-width: 60%; height: 70vh;">
					<h3 class="mb-3 d-flex justify-content-between fs-5">
						<div class="fw-bold me-2">Asistencia</div>
						<button class="btn btn-comedor" data-bs-toggle="modal" data-bs-target="#exampleModal">Filtrar</button>
					</h3>
					<div class="overflow-auto table-responsive" style="height: 90%;">
						<table class="table border text-center">
							<thead class="sticky-top  shadow-sm" style="background-color: #f7f7f7;">
								<tr>
									<th>Estado</th>
									<th>Fecha</th>
								</tr>
							</thead>
							<tbody id="tableBody" class="align-middle border">

							</tbody>
						</table>
					</div>
				</div>
			</section>
		</section>
	</main>

	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Filtrar asistencia</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="">
					<div id="inputsRadioContainer" class="p-3 target-background">
						<label class="d-block form-check-label" style="cursor: pointer">
							<input checked class="form-check-input me-2" value="anual" type="radio" name="tiempo" id="inputAnual">
							Durante todo el año
						</label>
						<label class="d-block form-check-label" style="cursor: pointer">
							<input class="form-check-input me-2" value="diaEspecifico" type="radio" name="tiempo" id="inputAnual">
							Día específico
						</label>
						<label class="d-block form-check-label" style="cursor: pointer">
							<input class="form-check-input me-2" value="lapso" type="radio" name="tiempo" id="inputAnual">
							Lapso de tiempo
						</label>
					</div>
					<section id="inputsTiempo"></section>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
					<button id="AplicarFiltroBoton" type="button" data-bs-dismiss="modal" class="btn btn-comedor">Aplicar Filtro</button>
				</div>
			</div>
		</div>
	</div>
	<div>
		<div id="registroAsistencias" hidden>
			<?php echo $registroAsistencias ?>
		</div>
		<div id="datosBar" hidden data-idusuario='<?php echo $userId ?>' data-perfilusuario='<?php echo $userProfile ?>'></div>

	</div>

	<script>
		//GoToHome
		const datosBar = document.getElementById('datosBar');
		const userProfile = datosBar.dataset.perfilusuario;
		const userId = datosBar.dataset.idusuario;

		function GoToHome() {
			location.href = `./?dir=client&controller=ClientHome&action=Index&id=${userId}&perfil=${userProfile}`;
		}

		//Chart
		const Asistencia = document.getElementById('Asistencia');

		function generateChart(attendances, absences) {
			removeAllChildNodes(Asistencia);
			Asistencia.insertAdjacentHTML('beforeend', `<canvas height="250"></canvas>`);

			var xValues = ["Asistencias", "Ausencias"];
			let yValues = [attendances, absences];
			var barColors = [
				"hsla(223, 77%, 51%, 0.75)",
				"rgba(213,48,67,0.75)"
			];
			var borderColors = [
				"#305ed5ff",
				"#d53043"
			];

			new Chart(Asistencia.children[0], {
				type: "pie",
				data: {
					labels: xValues,
					datasets: [{
						label: 'Asistencias',
						backgroundColor: barColors,
						data: yValues,
						borderColor: borderColors,
						borderWidth: 2
					}]
				},
				options: {
					responsive: true
				}
			});
		}
		const tableBody = document.getElementById('tableBody');
		const AsistenciaTitulo = document.getElementById('AsistenciaTitulo');
		let attendanceRecord = document.getElementById('registroAsistencias');
		let attendanceRecordArray = Object.values(JSON.parse(attendanceRecord.textContent));
		const ApplyFilterButton = document.getElementById('AplicarFiltroBoton');
		//startup functions
		let objectDate = new Date();
		let todayDate = objectDate.toISOString().split('T')[0];
		yearlyFilter();

		function showAttendance(startDate, endDate) {
			removeAllChildNodes(tableBody);
			let attendances = 0;
			let absences = 0;
			attendanceRecordArray.forEach(e => {
				if (e.Fecha >= startDate && e.Fecha <= endDate) {
					if (e.Estado === "Presente") {
						attendances++;
						tableBody.insertAdjacentHTML('afterbegin', `
							<tr class="">
						  	<td class="px-3"><div class="badge rounded-pill bg-success">Presente</div></td>
					   		<td class="px-3">${formatDate(e.Fecha)}</td>
							</tr>
					  `);
					} else {
						absences++;
						tableBody.insertAdjacentHTML('afterbegin', `
							<tr class="">
									<td class="px-3"><div class="badge rounded-pill bg-danger">Ausente</div></td>
									<td class="px-3">${formatDate(e.Fecha)}</td>
								</tr>
						 `);
					}
				}
			});
			if (tableBody.childElementCount == 0) {
				tableBody.insertAdjacentHTML('afterbegin', `
					<tr>
						<td colspan="2" class="align-middle py-2 text-center">No hay registros</td>
					</tr>
				`);
			}
			generateChart(attendances, absences);
		}

		//filter
		const inputsRadioContainer = document.getElementById('inputsRadioContainer');
		const inputsTiempo = document.getElementById('inputsTiempo');
		let dataToServer = null;

		function identifyInputRadio(e) {
			if (e.target.type === 'radio') removeAllChildNodes(inputsTiempo);
			if (e.target.value === "anual") {
				ApplyFilterButton.setAttribute('onclick', 'yearlyFilter()')
			} else if (e.target.value === "diaEspecifico") {
				ApplyFilterButton.setAttribute('onclick', 'filterBySpecificDay()')
				inputsTiempo.insertAdjacentHTML('afterbegin', `
						<div class="p-3 d-flex align-items-center flex-wrap">
						  <label class="d-block me-2">Seleccione el día</label>
						  <input type="date" class="form-control" style="width: max-content" max="<?php echo date('Y-m-d') ?>" id="diaEspecifico" name="diaEspecifico">
						</div>
				`);
			} else if (e.target.value === "lapso") {
				ApplyFilterButton.setAttribute('onclick', 'filterByRange()')
				inputsTiempo.insertAdjacentHTML('afterbegin', `
						<div class="p-3 d-flex align-items-center flex-wrap">
								<div class="text-center w-50">
									<label class="d-block me-2">Día inicio</label>
									<input type="date" class="form-control mx-auto" style="width: max-content" max="<?php echo date('Y-m-d') ?>" name="diaInicio" id="diaInicio">
								</div>
								<div class="w-50 text-center">
									<label class="d-block me-2">Día fin</label>
									<input type="date" class="form-control mx-auto" style="width: max-content" max="<?php echo date('Y-m-d') ?>" name="diaFin" id="diaFin">
								</div>
						</div>
				`);
			}
		}
		inputsRadioContainer.addEventListener('click', (e) => {
			identifyInputRadio(e)
		});


		//Date filter functions
		function yearlyFilter() {
			AsistenciaTitulo.textContent = `Mis Estadísticas ${objectDate.getFullYear()}`;
			showAttendance(objectDate.getFullYear() + '-01-01', todayDate);
		}

		function filterBySpecificDay() {
			const specificDay = document.getElementById('diaEspecifico');

			if (specificDay.value != "") {
				AsistenciaTitulo.textContent = `Mis Estadísticas ${formatDate(specificDay.value)}`;
				showAttendance(specificDay.value, specificDay.value);
			}
		}

		function filterByRange() {
			const startDay = document.getElementById('diaInicio');
			const endDay = document.getElementById('diaFin');

			if (startDay.value != "" && endDay.value != "") {
				AsistenciaTitulo.textContent = `Mis Estadísticas ${formatDate(startDay.value)} - ${formatDate(endDay.value)}`;
				showAttendance(startDay.value, endDay.value)
			}
		}
	</script>
</body>

</html>
