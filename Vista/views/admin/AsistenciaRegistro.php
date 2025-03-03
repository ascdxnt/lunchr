<?php
$head = file_get_contents('./Vista/views/components/Head.php');
$header = file_get_contents('./Vista/views/components/Header.php');
$sidebar = file_get_contents('./Vista/views/components/MenuAdmin.php');

if ($_SESSION["perfiles"] != 'admin') {
	header('Location: ./?alerta=error');
}

if (isset($_REQUEST['estados'])) $estado = 0;
else $estado = 1;

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Comedor - Asistencia</title>
	<?php echo $head; ?>
</head>

<body>
	<?php echo $header; ?>
	<main class="d-flex">
		<?php echo $sidebar; ?>
		<section class="mt-4 w-75 mx-auto mobile-target">
			<h1 id="titulo" class="fs-3"></h1>
			<section class="table-system mt-3">
				<div class="d-flex justify-content-between gap-1">
					<input id="inputSearch" class="form-control" type="search" placeholder="Buscar" style="width: 100%; max-width: 15em">
					<div class="d-flex justify-content-between gap-1">
						<button data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn text-white py-2 btn-comedor">Filtrar</button>
					</div>
				</div>
				<div class="table-responsive general-shadow mt-3" style="max-height: 70vh">
					<table id="table" class="table overflow-auto rounded mb-0">
						<thead class="sticky-top general-shadow" style="border-bottom: 2px solid #e5e7eb !important">
							<tr id="tableHeadRow" class="align-middle" style="background-color: #e5e7eb; color: #4b5563">
								<th>Nombre</th>
								<th>Apellidos</th>
								<th>Cedula</th>
								<th class="text-center">Acciones</th>
							</tr>
						</thead>
						<tbody id="tableBodyElement">
						</tbody>
					</table>
				</div>
			</section>
		</section>
	</main>

	<!-- Modal Filtros -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Filtrar Usuarios</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body px-0 pb-0">
					<div id="perfilSelectContainer" class="mb-3 px-3">
						<label>Perfil</label>
						<select class="form-select" name="perfil" id="selectPerfil">
							<option value="estudiante">Estudiante</option>
							<option value="profesor">Profesor</option>
						</select>
					</div>

					<div class="mb-3 px-3">
						<label for="" class="mb-1">Beca estudiantil</label>
						<select id="selectBeca" class="form-select" name="beca">
							<option value="cualquiera">Ambas</option>
							<option value="completa">Beca Completa</option>
							<option value="subvencionada">Beca Subvencionada</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
					<button onclick="AplicarFiltro()" type="button" data-bs-dismiss="modal" class="btn btn-comedor">Aplicar filtro</button>
				</div>
			</div>
		</div>
	</div>


	<div id="datos" hidden data-profesores='<?php echo $profesores ?>' data-estudiantes='<?php echo $estudiantes ?>'></div>

	<script>
		const datos = document.getElementById('datos');
		const tableBodyElement = document.getElementById("tableBodyElement");
		let arrayProfesores = null;
		let arrayEstudiantes = null;
		let mainArray = null;

		if (datos.dataset.estudiantes != "") {
			arrayEstudiantes = JSON.parse(datos.dataset.estudiantes);
			mainArray = arrayEstudiantes;
			buscarEnTabla("", mainArray);
		}

		if (datos.dataset.profesores != "")
			arrayProfesores = JSON.parse(datos.dataset.profesores);

		//cambiar de vista
		function verMasDetalles(id, perfil) {
			location.href = `./?dir=admin&controlador=Asistencia&accion=DetallesAsistencia&id=${id}&perfil=${perfil}`;
		}

		//filtro
		const selectPerfil = document.getElementById('selectPerfil');
		const selectBeca = document.getElementById('selectBeca');
		selectPerfil.addEventListener('click', (e) => {
			if (e.target.tagName === "OPTION") {
				if (selectPerfil.value === 'profesor') {
					selectBeca.parentElement.classList.add('d-none');
				} else {
					selectBeca.parentElement.classList.remove('d-none');
				}
			}
		});

		//Aplicar Filtro
		function AplicarFiltro() {
			if (selectPerfil.value === 'estudiante') {
				if (!arrayEstudiantes)
					return null;

				removeAllChildNodes(tableBodyElement);
				mainArray = arrayEstudiantes;
				if (selectBeca.value === 'completa') {
					arrayEstudiantes.forEach(e => {
						if (e.becado != "0")
							agregarFila(e);
					});
				} else if (selectBeca.value === 'subvencionada') {
					arrayEstudiantes.forEach(e => {
						if (e.becado != "1")
							agregarFila(e);
					});
				} else
					arrayEstudiantes.forEach(e => agregarFila(e));
			} else {
				if (!arrayProfesores)
					return null;

				removeAllChildNodes(tableBodyElement);
				mainArray = arrayProfesores;
				arrayProfesores.forEach(e => agregarFila(e));
			}
			cargarTitulo();
			tablaSinRegistros();
		}


		//~~~~~ Tabla ~~~~~

		//Buscar coincidencias en la tabla
		function buscarEnTabla(texto, array) {
			if (!array)
				return null;

			array.forEach((element) => {
				let encontrado = false;
				let arrayTemp = Object.values(element).splice(1, 4);
				arrayTemp.forEach((campo) => {
					campo = String(campo);
					if (campo.includes(texto)) encontrado = true;
				});
				if (encontrado) agregarFila(element);
			});
		}

		//Agrega las filas a la tabla
		function agregarFila(e) {
			if (e.perfil === "Estudiante") {
				tableBodyElement.insertAdjacentHTML(
					"beforeend",
					`
						<tr class="transicion align-middle" data-id="${e.id}">
								<td>${e.nombre}</td>
								<td>${e.apellido1} ${e.apellido2}</td>
								<td>${e.cedula}</td>
								<td class="text-center"><button class="btn btn-comedor" onclick="verMasDetalles(${e.id}, 'Estudiante')">Ver asistencia</button></td>
							</tr>
						`
				);
			} else if (e.perfil === "Profesor") {
				e.becado = "Null";
				tableBodyElement.insertAdjacentHTML(
					"beforeend",
					`
						<tr class="transicion align-middle" data-id="${e.id}">
								<td>${e.nombre}</td>
								<td>${e.apellido1} ${e.apellido2}</td>
								<td>${e.cedula}</td>
								<td class="text-center"><button class="btn btn-comedor" onclick="verMasDetalles(${e.id}, 'Profesor')">Ver asistencia</button></td>
							</tr>
						`
				);
			}
		}
		//Captura datos del input para que sean buscados con la función buscarEnTabla
		const inputSearch = document.getElementById("inputSearch");
		const tableHeadRow = document.getElementById("tableHeadRow");
		inputSearch.addEventListener("input", () => {
			removeAllChildNodes(tableBodyElement);
			buscarEnTabla(inputSearch.value, mainArray);
			tablaSinRegistros();
		});

		function tablaSinRegistros() {
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
		tablaSinRegistros();

		const titulo = document.getElementById('titulo');
		const date = new Date();

		function cargarTitulo() {
			if (!mainArray) {
				titulo.textContent = `Registro Asistencia ${date.getFullYear()}`;
				return null;
			}

			let perfil = mainArray[0].perfil;
			if (perfil === "Estudiante")
				titulo.textContent = `Registro Asistencia Estudiantes ${date.getFullYear()}`;
			else if (perfil === "Profesor")
				titulo.textContent = `Registro Asistencia Profesores ${date.getFullYear()}`;
		}
		cargarTitulo();
	</script>

</body>

</html>
