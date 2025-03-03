<?php
$head = file_get_contents('./Vista/views/components/Head.php');
$header = file_get_contents('./Vista/views/components/Header.php');
$sidebar = file_get_contents('./Vista/views/components/MenuCliente.php');

$idUsuario = $_SESSION['usuario']['Id'];
$perfilUsuario = $_SESSION['usuario']['Perfil'];

if ($_SESSION["perfiles"] != 'cliente') {
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
		<div id="datosBar" hidden data-idusuario='<?php echo $idUsuario ?>' data-perfilusuario='<?php echo $perfilUsuario ?>'></div>

	</div>

	<script>
		//IrAInicio
		const datosBar = document.getElementById('datosBar');
		const perfilUsuario = datosBar.dataset.perfilusuario;
		const idUsuario = datosBar.dataset.idusuario;

		function IrAInicio() {
			location.href = `./?dir=cliente&controlador=ClienteInicio&accion=Index&id=${idUsuario}&perfil=${perfilUsuario}`;
		}

		//Grafica
		const Asistencia = document.getElementById('Asistencia');

		function generarGrafica(asistencias, ausencias) {
			removeAllChildNodes(Asistencia);
			Asistencia.insertAdjacentHTML('beforeend', `<canvas height="250"></canvas>`);

			var xValues = ["Asistencias", "Ausencias"];
			let yValues = [asistencias, ausencias];
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
		let registroAsistencias = document.getElementById('registroAsistencias');
		let arrayRegistroAsistencias = Object.values(JSON.parse(registroAsistencias.textContent));
		const AplicarFiltroBoton = document.getElementById('AplicarFiltroBoton');
		//funciones de inicio
		let objectDate = new Date();
		let fechaDeHoy = objectDate.toISOString().split('T')[0];
		filtroAnual();

		function mostrarAsistencia(fechaInicio, fechaFin) {
			removeAllChildNodes(tableBody);
			let asistencias = 0;
			let ausencias = 0;
			arrayRegistroAsistencias.forEach(e => {
				if (e.Fecha >= fechaInicio && e.Fecha <= fechaFin) {
					if (e.Estado === "Presente") {
						asistencias++;
						tableBody.insertAdjacentHTML('afterbegin', `
							<tr class="">
						  	<td class="px-3"><div class="badge rounded-pill bg-success">Presente</div></td>
					   		<td class="px-3">${formatoFecha(e.Fecha)}</td>
							</tr>
					  `);
					} else {
						ausencias++;
						tableBody.insertAdjacentHTML('afterbegin', `
							<tr class="">
									<td class="px-3"><div class="badge rounded-pill bg-danger">Ausente</div></td>
									<td class="px-3">${formatoFecha(e.Fecha)}</td>
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
			generarGrafica(asistencias, ausencias);
		}

		//filtro
		const inputsRadioContainer = document.getElementById('inputsRadioContainer');
		const inputsTiempo = document.getElementById('inputsTiempo');
		let datosAlServidor = null;

		function identifyInputRadio(e) {
			if (e.target.type === 'radio') removeAllChildNodes(inputsTiempo);
			if (e.target.value === "anual") {
				AplicarFiltroBoton.setAttribute('onclick', 'filtroAnual()')
			} else if (e.target.value === "diaEspecifico") {
				AplicarFiltroBoton.setAttribute('onclick', 'filtrarPorDiaEspecifico()')
				inputsTiempo.insertAdjacentHTML('afterbegin', `
						<div class="p-3 d-flex align-items-center flex-wrap">
						  <label class="d-block me-2">Seleccione el día</label>
						  <input type="date" class="form-control" style="width: max-content" max="<?php echo date('Y-m-d') ?>" id="diaEspecifico" name="diaEspecifico">
						</div>
				`);
			} else if (e.target.value === "lapso") {
				AplicarFiltroBoton.setAttribute('onclick', 'filtrarPorLapso()')
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


		//Funciones para filtrar fechas
		function filtroAnual() {
			AsistenciaTitulo.textContent = `Mis Estadísticas ${objectDate.getFullYear()}`;
			mostrarAsistencia(objectDate.getFullYear() + '-01-01', fechaDeHoy);
		}

		function filtrarPorDiaEspecifico() {
			const diaEspecifico = document.getElementById('diaEspecifico');

			if (diaEspecifico.value != "") {
				AsistenciaTitulo.textContent = `Mis Estadísticas ${formatoFecha(diaEspecifico.value)}`;
				mostrarAsistencia(diaEspecifico.value, diaEspecifico.value);
			}
		}

		function filtrarPorLapso() {
			const diaInicio = document.getElementById('diaInicio');
			const diaFin = document.getElementById('diaFin');

			if (diaInicio.value != "" && diaFin.value != "") {
				AsistenciaTitulo.textContent = `Mis Estadísticas ${formatoFecha(diaInicio.value)} - ${formatoFecha(diaFin.value)}`;
				mostrarAsistencia(diaInicio.value, diaFin.value)
			}
		}
	</script>
</body>

</html>
