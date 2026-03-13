<?php
$head = file_get_contents('./View/views/components/Head.php');
$header = file_get_contents('./View/views/components/Header.php');
$sidebar = file_get_contents('./View/views/components/AdminMenu.php');

if (isset($_REQUEST['estados'])) $status = 0;
else $status = 1;

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

	<title>Comedor - Profesores</title>
	<?php echo $head; ?>
</head>

<body>
	<?php echo $header; ?>
	<main class="d-flex">
		<?php echo $sidebar; ?>
		<section class="mt-4 w-75 mx-auto mobile-target">
			<h1 class="fs-3">
				<?php if ($status == 1) echo 'Profesores';
				else echo 'Profesores Eliminados' ?>
			</h1>
			<section class="table-system mt-3">
				<div class="d-flex justify-content-between gap-1">
					<input id="inputSearch" class="form-control" type="search" placeholder="Buscar" style="width: 100%; max-width: 15em">
					<div class="d-flex justify-content-between gap-1">
						<?php if ($status == 1) { ?>
							<button onclick="CreateTeachers()" class="btn text-white py-2 table__green-button" title="Crear nuevo"><i class="fa-solid fa-plus"></i></button>
							<button onclick="ChangeStatus(0)" class="btn text-white py-2 table__red-button" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
							<button onclick="viewInactive()" class="btn text-white py-2 table__blue-button" title="Ver Eliminados"><i class="fa-solid fa-users-slash"></i></button>
						<?php } else { ?>
							<button onclick="ChangeStatus(1)" class="btn text-white py-2 table__red-button" title="Activar"><i class="fa-solid fa-heart"></i></i></button>
							<button onclick="viewActive()" class="btn text-white py-2 table__blue-button" title="Ver Activos"><i class="fa-solid fa-users"></i></button>
						<?php } ?>
					</div>
				</div>
				<div class="table-responsive general-shadow mt-3" style="max-height: 70vh">
					<table id="table" class="table overflow-auto rounded mb-0">
						<thead class="sticky-top general-shadow" style="border-bottom: 2px solid #e5e7eb !important">
							<tr id="tableHeadRow" class="align-middle" style="background-color: #e5e7eb; color: #4b5563">
								<th id="main-checkbox" class="text-center border-bottom-0" style="width: 2em;"><input class="form-check-input" style="cursor: pointer" type="checkbox" name="" id=""></th>
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

	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 id="modalTitle" class="modal-title" id="exampleModalLabel"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div id="modalBody" class="modal-body pb-0">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	<form id="formEdit" action="./?dir=admin&controller=Teacher&action=Index&id=modificar" method="POST" hidden>
		<input type="text" name="idU">
		<input type="text" name="nombre">
		<input type="text" name="primerap">
		<input type="text" name="segundoap">
		<input type="text" name="cedula">
		<input type="text" name="correo">
		<input type="text" name="comidas">
		<input type="text" name="estado">
		<button></button>
	</form>
	<div id="data" hidden data-profesores='<?php echo $teachers ?>' data-estadoUsuarios='<?php echo $status ?>'></div>

	<script>
		const data = document.getElementById('data');
		const tableBodyElement = document.getElementById("tableBodyElement");
		const USER_STATUS = data.dataset.estadousuarios;
		let usersArray = null;

		if (data.dataset.profesores != "") {
			usersArray = JSON.parse(data.dataset.profesores);
			searchInTable("", USER_STATUS);
		}

		function viewActive() {
			location.href = "./?dir=admin&controller=Teacher&action=Index&id=main";
		}

		function viewInactive() {
			location.href = "./?dir=admin&controller=Teacher&action=Index&id=main&estados=0";
		}

		function CreateTeachers() {
			location.href = "./?dir=admin&controller=Teacher&action=Index&id=crear";
		}

		const formEdit = document.getElementById('formEdit');

		function EditTeachers(...entityData) {
			entityData.forEach((element, index) => {
				formEdit.children[index].value = element;
			});
			formEdit.lastElementChild.click();
		}

		function FillInfoModal(...entityData) {
			const modalBody = document.getElementById('modalBody');
			const modalTitle = document.getElementById('modalTitle');
			let status;
			if (entityData[6] === "1") status = "Profesor Activo";
			else status = "Profesor Eliminado";
			modalTitle.textContent = `${entityData[0]} ${entityData[1]} ${entityData[2]}`;
			removeAllChildNodes(modalBody);
			modalBody.insertAdjacentHTML('beforeend', `
          <img class="text-center" src="${entityData[7]}" alt="Foto de Perfil" width="150" height="150" style="display:block;margin: 0 auto;object-fit: cover; border-radius: 50%">
					<dl>
						<dt>Cédula</dt>
						<dd>${entityData[3]}</dd>
						<dt>Correo</dt>
						<dd>${entityData[4]}</dd>
						<dt>Comidas</dt>
						<dd>${entityData[5]}</dd>
						<dt>Estado</dt>
						<dd class="mb-0">${status}</dd>
					</dl>
			`);
		}

		function ChangeStatus(status) {
			let urlIds = "";
			let lengthArray = 0;
			let validRoute = false;
			for (let i = 0; i < tableBodyElement.children.length; i++) {
				if (tableBodyElement.children[i].firstElementChild.firstElementChild.checked) {
					urlIds += `&idsArr[]=${tableBodyElement.children[i].dataset.id}`;
					validRoute = true;
					lengthArray++;
				}
			}
			if (validRoute) {
				let redirect = `./?dir=admin&controller=Teacher&action=ChangeStatus&id=${status}`;
				redirect += urlIds;
				redirect += `&lengthArray=${lengthArray}`;
				location.href = redirect;
			}
		}


		//~~~~~ Table ~~~~~

		// Changes color of a selected row
		tableBodyElement.addEventListener("click", (event) => {
			if (event.target.type === "checkbox" && event.target.checked)
				event.target.parentElement.parentElement.classList.add("selectedRow");
			else
				event.target.parentElement.parentElement.classList.remove("selectedRow");
		});

		// Checkbox to select all rows
		const mainCheckbox = document.getElementById("main-checkbox");
		mainCheckbox.addEventListener("click", () => {
			for (row of tableBodyElement.children) {
				if (row.firstElementChild.firstElementChild.checked)
					row.firstElementChild.firstElementChild.checked = false;
				else
					row.firstElementChild.firstElementChild.checked = true;
			}
			rowChangeColor();
		});

		// Function to set color on rows
		function rowChangeColor() {
			for (row of tableBodyElement.children) {
				if (row.firstElementChild.firstElementChild.checked)
					row.classList.add("selectedRow");
				else row.classList.remove("selectedRow");
			}
		}
		rowChangeColor();

		// Search matches in the table
		function searchInTable(text, userStatus) {
			if (!usersArray)
				return null;

			usersArray.forEach((element) => {
				let found = false;
				let tempArray = Object.values(element).splice(1, 4);
				tempArray.forEach((field) => {
					field = String(field);
					if (field.includes(text)) found = true;
				});
				if (found && element.estado === userStatus) addRow(element);
			});
		}

		// Adds rows to the table
		function addRow(e) {
			tableBodyElement.insertAdjacentHTML(
				"beforeend",
				`
					<tr class="transicion align-middle" data-id="${e.id}">
							<td class="text-center"><input class="form-check-input" style="cursor: pointer" type="checkbox"></td>
							<td>${e.nombre}</td>
							<td>${e.apellido1} ${e.apellido2}</td>
							<td>${e.cedula}</td>
							<td class="text-center">
								<i class="fa-solid fa-pen-to-square me-1 fs-5" style="cursor: pointer" onclick="EditTeachers(${e.id}, '${e.nombre}', '${e.apellido1}', '${e.apellido2}', '${e.cedula}', '${e.correo}', '${e.comidas}', '${e.estado}')"></i>
								<i data-bs-toggle="modal" data-bs-target="#exampleModal" class="fa-solid fa-circle-info ms-1 fs-5" style="cursor: pointer" onclick="FillInfoModal('${e.nombre}', '${e.apellido1}', '${e.apellido2}', '${e.cedula}', '${e.correo}', '${e.comidas}', '${e.estado}', '${e.fotoPerfil}')"></i>	
							</td>	
						</tr>
					`
			);
		}

		// Captures input data to search with the searchInTable function
		const inputSearch = document.getElementById("inputSearch");
		const tableHeadRow = document.getElementById("tableHeadRow");
		inputSearch.addEventListener("input", () => {
			removeAllChildNodes(tableBodyElement);
			searchInTable(inputSearch.value, USER_STATUS);
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
		if ($alertName == "success") {
			echo "<script>alertify.success('Proceso exitoso');</script>";
		} else if ($alertName == "error") {
			echo "<script>alertify.error('Hubo un error');</script>";
		} else if ($alertName == "warning") {
			echo "<script>alertify.warning('Algunos profesores no se han creado');</script>";
		}
	}
	?>
</body>

</html>