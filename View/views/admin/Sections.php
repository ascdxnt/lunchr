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

	<title>Comedor - Secciones</title>
	<?php echo $head; ?>
</head>

<body>
	<?php echo $header; ?>
	<main class="d-flex">
		<?php echo $sidebar; ?>
		<section class="mt-4 w-75 mx-auto mobile-target">
			<h1 class="fs-3">
				Secciones
			</h1>
			<section class="table-system mt-3">
				<div class="d-flex justify-content-between gap-1">
					<input id="inputSearch" class="form-control" type="search" placeholder="Buscar" style="width: 100%; max-width: 15em">
					<div class="d-flex justify-content-between gap-1">
						<button onclick="CreateSections()" class="btn text-white py-2 table__green-button" title="Crear nuevo"><i class="fa-solid fa-plus"></i></button>
						<button onclick="ChangeStatus(0)" class="btn text-white py-2 table__red-button" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
					</div>
				</div>
				<div class="table-responsive general-shadow mt-3" style="max-height: 70vh">
					<table id="table" class="table overflow-auto rounded mb-0">
						<thead class="sticky-top general-shadow" style="border-bottom: 2px solid #e5e7eb !important">
							<tr id="tableHeadRow" class="align-middle" style="background-color: #e5e7eb; color: #4b5563">
								<th id="main-checkbox" class="text-center border-bottom-0" style="width: 2em;"><input class="form-check-input" style="cursor: pointer" type="checkbox" name="" id=""></th>
								<th>Nombre</th>
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

	<div id="data" hidden data-secciones='<?php echo $sections ?>'></div>

	<form id="formUpdate" action="./?dir=admin&controller=Sections&action=Index&id=modificar" method="post" hidden>
		<input type="text" name="idSeccion">
		<input type="text" name="seccion">
		<input type="text" name="estado">
		<button></button>
	</form>

	<script>
		const data = document.getElementById('data');
		const tableBodyElement = document.getElementById("tableBodyElement");
		const ELEMENT_STATUS = "1";
		let usersArray = null;

		if (data.dataset.secciones != "") {
			usersArray = JSON.parse(data.dataset.secciones);
			searchInTable("", ELEMENT_STATUS);
		}

		function CreateSections() {
			location.href = "./?dir=admin&controller=Sections&action=Index&id=crear";
		}

		const formUpdate = document.getElementById('formUpdate');

		function UpdateSections(...entityData) {
			entityData.forEach((element, index) => {
				formUpdate.children[index].value = element;
			});
			formUpdate.lastElementChild.click();
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
				let redirect = `./?dir=admin&controller=Sections&action=ChangeStatus&id=${status}`;
				redirect += urlIds;
				redirect += `&lengthArray=${lengthArray}`;
				location.href = redirect;
			}
		}

		//~~~~~ Table ~~~~~

		//Changes color of a selected row
		tableBodyElement.addEventListener("click", (event) => {
			if (event.target.type === "checkbox" && event.target.checked)
				event.target.parentElement.parentElement.classList.add("selectedRow");
			else
				event.target.parentElement.parentElement.classList.remove("selectedRow");
		});

		//Checkbox to select all rows
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

		//Function to set row colors
		function rowChangeColor() {
			for (row of tableBodyElement.children) {
				if (row.firstElementChild.firstElementChild.checked)
					row.classList.add("selectedRow");
				else row.classList.remove("selectedRow");
			}
		}
		rowChangeColor();

		//Search matches in the table
		function searchInTable(text, userStatus) {
			if (!usersArray)
				return null;

			usersArray.forEach((element) => {
				let found = false;
				let tempArray = Object.values(element).splice(0, 2);
				tempArray.forEach((field) => {
					field = String(field);
					if (field.includes(text)) found = true;
				});
				if (found && element.estado === userStatus) addRow(element);
			});
		}

		//Adds rows to the table
		function addRow(e) {
			tableBodyElement.insertAdjacentHTML(
				"beforeend",
				`
					<tr class="transicion align-middle" data-id="${e.id}">
							<td class="text-center"><input class="form-check-input" style="cursor: pointer" type="checkbox"></td>
							<td>${e.descripcion}</td>
							<td class="text-center">
								<i class="fa-solid fa-pen-to-square me-1 fs-5" style="cursor: pointer" onclick="UpdateSections(${e.id}, '${e.descripcion}', '${e.estado}')"></i>
							</td>	
						</tr>
					`
			);
		}

		//Captures input data to be searched with the searchInTable function
		const inputSearch = document.getElementById("inputSearch");
		const tableHeadRow = document.getElementById("tableHeadRow");
		inputSearch.addEventListener("input", () => {
			removeAllChildNodes(tableBodyElement);
			searchInTable(inputSearch.value, ELEMENT_STATUS);
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
			echo "<script>alertify.warning('Algunas secciones no se han creado');</script>";
		}
	}
	?>
</body>

</html>
