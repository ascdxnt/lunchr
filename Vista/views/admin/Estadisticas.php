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
						<!--Arreglar el tamaño de la tabla-->
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

	<!-- Modal Filtro -->
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

	<!-- Modal Info Seccion -->
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

	<form id="formularioFiltro" action="./?dir=admin&controlador=EstadisticasAdmin&accion=Index" method="post" hidden>
		<input type="text" name="fechaInicio">
		<input type="text" name="fechaFin">
		<input type="text" name="beca">
		<input type="text" name="perfil">
		<button></button>
	</form>

	<div id="registroAsistencias" hidden>
		<?php
		foreach ($registroAsistencias as $registro) {
			echo "<div>$registro</div>";
		}
		?>
	</div>

	<div id="datosFiltro" hidden>
		<?php echo json_encode($datosFiltro) ?>
	</div>

	<div id="clientesMasAusentes" hidden>
		<?php
		echo json_encode($clientesMasAusentes);
		?>
	</div>

	<div id="datosGraficas" hidden data-secciones='<?php echo json_encode($secciones) ?>' data-especialidades='<?php echo json_encode($especialidades) ?>'></div>

	<script>
		const jsonClientesMasAusentes = document.getElementById('clientesMasAusentes');
		const clientesMasAusentes = JSON.parse(jsonClientesMasAusentes.textContent);
		const tbodyMasAusentes = document.getElementById('tbodyMasAusentes');
		const registroAsistencias = document.getElementById('registroAsistencias');
		const fecha = new Date();
		const tituloLapso = document.getElementById('tituloLapso');
		const inputsRadioContainer = document.getElementById('inputsRadioContainer');
		const formularioFiltro = document.getElementById('formularioFiltro');
		const AplicarFiltroBoton = document.getElementById('AplicarFiltroBoton');
		const inputAnual = document.getElementById('inputAnual');
		const inputsTiempo = document.getElementById('inputsTiempo');
		const selectPerfil = document.getElementById('selectPerfil');
		const selectBeca = document.getElementById('selectBeca');
		const contenedorSelectBeca = document.getElementById('contenedorSelectBeca');
		let datosAlServidor = null;
		let datosFiltro = document.getElementById('datosFiltro');
		let validador = true;
		let estado = false;
		let ausencias = 0;
		let asistencias = 0;
		let cantidadDiasLectivos = 0;

		//Titulo filtro
		let buttonToSecondPage = document.querySelector('[data-page="second"]');

		function cargarTitulo() {
			datosFiltro = JSON.parse(datosFiltro.textContent);
			const tituloTablaMasAusentes = document.getElementById('tituloTablaMasAusentes');

			for (optionElement of selectPerfil.children) {
				if (optionElement.value !== datosFiltro.Perfil) continue;

				optionElement.selected = true;
			}

			for (optionElement of selectBeca.children) {
				if (optionElement.value !== datosFiltro.Beca) continue;

				optionElement.selected = true;
			}

			if (datosFiltro.Perfil === "profesor") {
				tituloTablaMasAusentes.textContent = "Profesores más ausentes"
				contenedorSelectBeca.classList.add('d-none');
				buttonToSecondPage.disabled = true;
			}

			let mesActual = ("0" + (Number(fecha.getMonth()) + 1)).slice(-2);
			let diaActual = fecha.getDate();
			let fechaHoy = fecha.getFullYear() + "-" + mesActual + "-" + diaActual;
			if (datosFiltro.FechaInicio === fecha.getFullYear() + "-01-01" && datosFiltro.FechaFin === fechaHoy)
				tituloLapso.textContent = fecha.getFullYear();
			else if (datosFiltro.FechaInicio === datosFiltro.FechaFin)
				tituloLapso.textContent = formatoFecha(datosFiltro.FechaInicio);
			else
				tituloLapso.textContent = `${formatoFecha(datosFiltro.FechaInicio)} - ${formatoFecha(datosFiltro.FechaFin)}`;
		}
		cargarTitulo();


		//filtro
		function identifyInputRadio(e) {
			AplicarFiltroBoton.disabled = false;
			if (e.target.type === 'radio') removeAllChildNodes(inputsTiempo);
			if (e.target.value === "anual") {
				datosAlServidor = "datosAnuales";
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

		//Filtro
		AplicarFiltroBoton.addEventListener('click', () => {
			const inputDiaEspecifico = document.getElementById('inputDiaEspecifico');
			const inputDiaFin = document.getElementById('inputDiaFin');
			const inputDiaInicio = document.getElementById('inputDiaInicio');
			if (inputDiaEspecifico) {
				formularioFiltro.children[0].value = inputDiaEspecifico.value;
				formularioFiltro.children[1].value = inputDiaEspecifico.value;
				formularioFiltro.children[2].value = selectBeca.value;
				formularioFiltro.children[3].value = selectPerfil.value;
				if (inputDiaEspecifico.value != "")
					estado = true;
			} else if (inputDiaFin && inputDiaInicio) {
				formularioFiltro.children[0].value = inputDiaInicio.value;
				formularioFiltro.children[1].value = inputDiaFin.value;
				formularioFiltro.children[2].value = selectBeca.value;
				formularioFiltro.children[3].value = selectPerfil.value;
				if (inputDiaInicio.value != "" && inputDiaFin.value != "" && inputDiaInicio.value <= inputDiaFin.value)
					estado = true;
			} else if (inputAnual.checked) {
				let anoActual = fecha.getFullYear();
				let mesActual = ("0" + (Number(fecha.getMonth()) + 1)).slice(-2);
				formularioFiltro.children[0].value = anoActual + "-01-01";
				formularioFiltro.children[1].value = anoActual + "-" + mesActual + "-" + fecha.getDate();
				formularioFiltro.children[2].value = selectBeca.value;
				formularioFiltro.children[3].value = selectPerfil.value;
				estado = true;
			}

			if (estado) {
				formularioFiltro.lastElementChild.click();
				AplicarFiltroBoton.insertAdjacentHTML('afterend', `<button class="btn btn-primary" type="button" disabled>
							<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
							Cargando...
						</button>`);
				AplicarFiltroBoton.remove();
			}
		});


		//Contar días no lectivos
		function contarDiasNoLectivos() {
			for (registro of registroAsistencias.children) {
				if (JSON.parse(registro.textContent)) {
					let registroCliente = Object.values(JSON.parse(registro.textContent));
					registroCliente.forEach(e => {
						if (e.Estado != "Presente")
							cantidadDiasLectivos++;
					});
				} else
					validador = false;
				break;
			}
		}
		contarDiasNoLectivos();

		//Rellenar tabla de más ausentes
		function tablaMasAusentes() {
			clientesMasAusentes.sort(function(a, b) {
				if (a.Asistencias > b.Asistencias) {
					return 1;
				}
				if (a.Asistencias < b.Asistencias) {
					return -1;
				}
				// a must be equal to b
				return 0;
			});

			if (cantidadDiasLectivos === 0) cantidadDiasLectivos = 1;
			for (clienteAusente of clientesMasAusentes) {
				tbodyMasAusentes.insertAdjacentHTML('beforeend', `
						<tr class="align-middle">
								<td class="text-center">${tbodyMasAusentes.childElementCount + 1}</td>
								<td>${clienteAusente.Nombre} ${clienteAusente.Apellido1} ${clienteAusente.Apellido2}</td>
								<td>${clienteAusente.Cedula}</td>
								<td class="text-center fw-bold text-danger">${cantidadDiasLectivos - clienteAusente.Asistencias}</td>
						</tr>
				`);
			}
		}
		if (validador) {
			tablaMasAusentes();
		}

		//Función para contar ausencias y asistencias
		function contarAsistenciaGeneral() {
			let mesActual = ("0" + (Number(fecha.getMonth()) + 1)).slice(-2);
			let diaActual = fecha.getDate();
			let fechaHoy = fecha.getFullYear() + "-" + mesActual + "-" + diaActual;

			for (registro of registroAsistencias.children) {
				let registroCliente = Object.values(JSON.parse(registro.textContent));
				for (elemento of registroCliente) {
					if (elemento.Fecha > fechaHoy) continue;
					elemento.Estado === "Ausente" ? ausencias++ : asistencias++;
				}
			}
		}

		if (validador) {
			contarAsistenciaGeneral();
		}

		//función que genera la gráfica
		function createChart(asistencias, ausencias, canvas, legendPosition, typeChart) {
			var xValues = ["Asistencias", "Ausencias"];
			var yValues = [asistencias, ausencias];
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
		if (validador) {
			removeAllChildNodes(Asistencia);
			Asistencia.insertAdjacentHTML('beforeend', `<canvas height="250"></canvas>`);
			createChart(asistencias, ausencias, Asistencia.children[0], 'top', "doughnut");
		} else {
			const bloqueGraficaAsistencia = document.getElementById('bloqueGraficaAsistencia');
			const bloqueMasAusentes = document.getElementById('bloqueMasAusentes');
			bloqueGraficaAsistencia.parentElement.classList.add('d-none');
			bloqueMasAusentes.parentElement.classList.add('mx-auto');
			buttonToSecondPage.disabled = true;
			tbodyMasAusentes.insertAdjacentHTML('beforeend', `<tr><td colspan="4" class="text-center">No hay registros.</td></tr>`)
		}

		//Cambio entre pestañas
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

		//Sección gráficos
		const datosGraficas = document.getElementById('datosGraficas');
		const duoDecimo = document.getElementById('duoDecimo');
		const unDecimo = document.getElementById('unDecimo');
		const decimo = document.getElementById('decimo');

		if (datosGraficas.dataset.secciones != "") generarSecciones();

		function generarSecciones() {
			let arraySecciones = Object.values(JSON.parse(datosGraficas.dataset.secciones));

			//Ordenando las secciones por orden alfabético
			arraySecciones.sort(function(a, b) {
				if (a.descripcion > b.descripcion) {
					return 1;
				}
				if (a.descripcion < b.descripcion) {
					return -1;
				}

				return 0;
			});

			let niveles = {
				"12": duoDecimo,
				"11": unDecimo,
				"10": decimo
			};

			for (seccion of arraySecciones) {
				let nombreSeccionTarjeta = seccion.descripcion;
				let nivelSeccion = seccion.descripcion.split('-')[0];
				let idSeccionTarjeta = seccion.id;

				if (!niveles[nivelSeccion]) continue;

				let mainElementNivel = niveles[nivelSeccion];
				mainElementNivel.insertAdjacentHTML('beforeend', `
					<section class="target col-sm-3 rounded overflow-hidden general-shadow px-0" style="min-width: 15em; border: 1px solid #305ed5ff;">
						<header class="main-background text-light p-2 d-flex justify-content-between align-items-center">
							<h4 class="fw-bold mb-0">${nombreSeccionTarjeta}</h4>
								<button data-idseccion="${idSeccionTarjeta}" data-seccion="${nombreSeccionTarjeta}" class="btn btn-sm btn-light align-middle" data-bs-toggle="modal" data-bs-target="#modalInfoSeccion">
								<i class="d-block fa-solid fa-circle-info" style="font-size: 1.1rem; color: #333;"></i>
							</button>
						</header>
						<main class="py-1 px-2">
							
						</main>
					</section>
				`);

				//línea que agrega un elemento canvas a la tarjeta
				mainElementNivel.lastElementChild.lastElementChild.appendChild(retornarGrafico(idSeccionTarjeta));
			}
		}

		function retornarGrafico(idSeccionParam) {
			const canvas = document.createElement('canvas');
			let asistenciasSeccion = 0;
			let ausenciasSeccion = 0;

			for (estudiante of clientesMasAusentes) {
				if (estudiante.IdSeccion !== idSeccionParam) continue;

				let ausenciasEstudiante = cantidadDiasLectivos - (estudiante.Asistencias ?? 0);
				asistenciasSeccion += (Number(estudiante.Asistencias) ?? 0);
				ausenciasSeccion += ausenciasEstudiante;
			}

			createChart(asistenciasSeccion, ausenciasSeccion, canvas, 'left', 'pie');

			return canvas;
		}

		//Ver información de la sección
		const contenedorEstadisticas = document.getElementById('contenedorEstadisticas');
		const modalInfoSeccionLabel = document.getElementById('modalInfoSeccionLabel');
		const modalInfoSeccion = document.getElementById('modalInfoSeccion');
		let nombreSeccion;
		let idSeccion;
		let especialidadesSeccion = [];
		let estudiantesSeccion = [];
		let especialidades = Object.values(JSON.parse(datosGraficas.dataset.especialidades));

		contenedorEstadisticas.addEventListener('click', (event) => {
			if (event.target.classList.contains('fa-solid')) {
				nombreSeccion = event.target.parentElement.dataset.seccion;
				idSeccion = event.target.parentElement.dataset.idseccion;
			} else if (event.target.dataset.seccion) {
				nombreSeccion = event.target.dataset.seccion;
				idSeccion = event.target.dataset.idseccion;
			} else return;

			modalInfoSeccion.click();
			filtrarTablaSecciones();
			modalInfoSeccionLabel.textContent = `Sección ${nombreSeccion}`;
			mostrarEspecialidadesSeccion(especialidadesSeccion);
		});

		modalInfoSeccion.addEventListener('click', (e) => {
			especialidadesSeccion = [];
			estudiantesSeccion = [];

			for (estudiante of clientesMasAusentes) {
				if (estudiante.IdSeccion !== idSeccion) continue;
				especialidadesSeccion.push(estudiante.IdEspecialidad);
				estudiantesSeccion.push(estudiante);
			}

			estudiantesSeccion.sort(function(a, b) {
				if (a.Asistencias > b.Asistencias) {
					return 1;
				}
				if (a.Asistencias < b.Asistencias) {
					return -1;
				}

				return 0;
			});

			especialidadesSeccion = Array.from(new Set(especialidadesSeccion));
		});

		const filtroSeccionContenedor = document.getElementById('filtroSeccionContenedor');

		function filtrarTablaSecciones() {
			removeAllChildNodes(filtroSeccionContenedor);
			for (idEspecialidadSeccion of especialidadesSeccion) {
				especialidades.find(e => {
					if (e.id === idEspecialidadSeccion) {
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

			let inputsEspecialidades = [...document.querySelectorAll("[name=especialidad]:checked")];
			let idsEspecialidades = [];

			for (inputEspecialidad of inputsEspecialidades) {
				idsEspecialidades.push(inputEspecialidad.value)
			}

			mostrarEspecialidadesSeccion(idsEspecialidades);
		});

		const tbodyModalSeccion = document.getElementById('tbodyModalSeccion');

		function mostrarEspecialidadesSeccion(idsEspecialidades) {
			removeAllChildNodes(tbodyModalSeccion);

			for (estudianteSeccion of estudiantesSeccion) {
				if (!idsEspecialidades.includes(estudianteSeccion.IdEspecialidad)) continue;

				tbodyModalSeccion.insertAdjacentHTML('beforeend', `
						<tr class="align-middle">
								<td class="text-center">${tbodyModalSeccion.childElementCount + 1}</td>
								<td>${estudianteSeccion.Nombre} ${estudianteSeccion.Apellido1} ${estudianteSeccion.Apellido2}</td>
								<td>${estudianteSeccion.Cedula}</td>
								<td class="text-center fw-bold text-danger">${cantidadDiasLectivos - estudianteSeccion.Asistencias}</td>
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
