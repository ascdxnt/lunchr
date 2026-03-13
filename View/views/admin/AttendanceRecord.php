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

	<!-- Filter Modal -->
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
					<button onclick="ApplyFilter()" type="button" data-bs-dismiss="modal" class="btn btn-comedor">Aplicar filtro</button>
				</div>
			</div>
		</div>
	</div>


	<div id="datos" hidden data-profesores='<?php echo $teachers ?>' data-estudiantes='<?php echo $students ?>'></div>

	<script>
		const datos = document.getElementById('datos');
		const tableBodyElement = document.getElementById("tableBodyElement");
		let teachersArray = null;
		let studentsArray = null;
		let mainArray = null;

		if (datos.dataset.estudiantes != "") {
			studentsArray = JSON.parse(datos.dataset.estudiantes);
			mainArray = studentsArray;
			searchInTable("", mainArray);
		}

		if (datos.dataset.profesores != "")
			teachersArray = JSON.parse(datos.dataset.profesores);

		// Change view
		function viewMoreDetails(id, profile) {
			location.href = `./?dir=admin&controller=Attendance&action=AttendanceDetails&id=${id}&perfil=${profile}`;
		}

		// Filter
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

		// Apply Filter
		function ApplyFilter() {
			if (selectPerfil.value === 'estudiante') {
				if (!studentsArray)
					return null;

				removeAllChildNodes(tableBodyElement);
				mainArray = studentsArray;
				if (selectBeca.value === 'completa') {
					studentsArray.forEach(e => {
						if (e.becado != "0")
							addRow(e);
					});
				} else if (selectBeca.value === 'subvencionada') {
					studentsArray.forEach(e => {
						if (e.becado != "1")
							addRow(e);
					});
				} else
					studentsArray.forEach(e => addRow(e));
			} else {
				if (!teachersArray)
					return null;

				removeAllChildNodes(tableBodyElement);
				mainArray = teachersArray;
				teachersArray.forEach(e => addRow(e));
			}
			loadTitle();
			tableNoRecords();
		}


		//~~~~~ Table ~~~~~

		// Search matches in table
		function searchInTable(text, array) {
			if (!array)
				return null;

			array.forEach((element) => {
				let found = false;
				let tempArray = Object.values(element).splice(1, 4);
				tempArray.forEach((field) => {
					field = String(field);
					if (field.includes(text)) found = true;
				});
				if (found) addRow(element);
			});
		}

		// Add rows to table
		function addRow(e) {
			if (e.perfil === "Estudiante") {
				tableBodyElement.insertAdjacentHTML(
					"beforeend",
					`
						<tr class="transicion align-middle" data-id="${e.id}">
								<td>${e.nombre}</td>
								<td>${e.apellido1} ${e.apellido2}</td>
								<td>${e.cedula}</td>
								<td class="text-center"><button class="btn btn-comedor" onclick="viewMoreDetails(${e.id}, 'Estudiante')">Ver asistencia</button></td>
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
								<td class="text-center"><button class="btn btn-comedor" onclick="viewMoreDetails(${e.id}, 'Profesor')">Ver asistencia</button></td>
							</tr>
						`
				);
			}
		}
		// Capture input data to search with searchInTable function
		const inputSearch = document.getElementById("inputSearch");
		const tableHeadRow = document.getElementById("tableHeadRow");
		inputSearch.addEventListener("input", () => {
			removeAllChildNodes(tableBodyElement);
			searchInTable(inputSearch.value, mainArray);
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

		const titulo = document.getElementById('titulo');
		const currentDate = new Date();

		function loadTitle() {
			if (!mainArray) {
				titulo.textContent = `Registro Asistencia ${currentDate.getFullYear()}`;
				return null;
			}

			let profile = mainArray[0].perfil;
			if (profile === "Estudiante")
				titulo.textContent = `Registro Asistencia Estudiantes ${currentDate.getFullYear()}`;
			else if (profile === "Profesor")
				titulo.textContent = `Registro Asistencia Profesores ${currentDate.getFullYear()}`;
		}
		loadTitle();
	</script>

</body>

</html>