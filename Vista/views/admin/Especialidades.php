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

	<title>Comedor - Especialidades</title>
	<?php echo $head; ?>
</head>

<body>
	<?php echo $header; ?>
	<main class="d-flex">
		<?php echo $sidebar; ?>
		<section class="mt-4 w-75 mx-auto mobile-target">
			<h1 class="fs-3">
				Especialidades
			</h1>
			<section class="table-system mt-3">
				<div class="d-flex justify-content-between gap-1">
					<input id="inputSearch" class="form-control" type="search" placeholder="Buscar" style="width: 100%; max-width: 15em">
					<div class="d-flex justify-content-between gap-1">
						<button onclick="CrearEspecialidades()" class="btn text-white py-2 table__green-button" title="Crear nuevo"><i class="fa-solid fa-plus"></i></button>
						<button onclick="CambiarEstado(0)" class="btn text-white py-2 table__red-button" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
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

	<div id="datos" hidden data-especialidades='<?php echo $especialidades ?>'></div>

	<form id="formModificar" action="./?dir=admin&controlador=Especialidades&accion=Index&id=modificar" method="post" hidden>
		<input type="text" name="idEspecialidad">
		<input type="text" name="especialidad">
		<input type="text" name="estado">
		<button></button>
	</form>

	<script>
		const datos = document.getElementById('datos');
		const tableBodyElement = document.getElementById("tableBodyElement");
		const ESTADO_ELEMENTOS = "1";
		let arrayUsuarios = JSON.parse(datos.dataset.especialidades);
		buscarEnTabla("", ESTADO_ELEMENTOS);

		function CrearEspecialidades() {
			location.href = "./?dir=admin&controlador=Especialidades&accion=Index&id=crear";
		}

		const formModificar = document.getElementById('formModificar');

		function ModificarEspecialidades(...datosEntidad) {
			datosEntidad.forEach((element, index) => {
				formModificar.children[index].value = element;
			});
			formModificar.lastElementChild.click();
		}

		function CambiarEstado(estado) {
			let urlIds = "";
			let lengthArray = 0;
			let rutaValida = false;
			for (let i = 0; i < tableBodyElement.children.length; i++) {
				if (tableBodyElement.children[i].firstElementChild.firstElementChild.checked) {
					urlIds += `&idsArr[]=${tableBodyElement.children[i].dataset.id}`;
					rutaValida = true;
					lengthArray++;
				}
			}
			if (rutaValida) {
				let direccionamiento = `./?dir=admin&controlador=Especialidades&accion=CambiarEstado&id=${estado}`;
				direccionamiento += urlIds;
				direccionamiento += `&lengthArray=${lengthArray}`;
				location.href = direccionamiento;
			}
		}

		//~~~~~ Tabla ~~~~~

		//Cambia color a una fila seleccionada
		tableBodyElement.addEventListener("click", (event) => {
			if (event.target.type === "checkbox" && event.target.checked)
				event.target.parentElement.parentElement.classList.add("selectedRow");
			else
				event.target.parentElement.parentElement.classList.remove("selectedRow");
		});

		//Checkbox para seleccionar todas la filas
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

		//Función para colocar el color a las filas
		function rowChangeColor() {
			for (row of tableBodyElement.children) {
				if (row.firstElementChild.firstElementChild.checked)
					row.classList.add("selectedRow");
				else row.classList.remove("selectedRow");
			}
		}
		rowChangeColor();

		//Buscar coincidencias en la tabla
		function buscarEnTabla(texto, estadoUsuarios) {
			if (!arrayUsuarios)
				return null;

			arrayUsuarios.forEach((element) => {
				let encontrado = false;
				let arrayTemp = Object.values(element).splice(0, 2);
				arrayTemp.forEach((campo) => {
					campo = String(campo);
					if (campo.includes(texto)) encontrado = true;
				});
				if (encontrado && element.estado === estadoUsuarios) agregarFila(element);
			});
		}

		//Agrega las filas a la tabla
		function agregarFila(e) {
			tableBodyElement.insertAdjacentHTML(
				"beforeend",
				`
					<tr class="transicion align-middle" data-id="${e.id}">
							<td class="text-center"><input class="form-check-input" style="cursor: pointer" type="checkbox"></td>
							<td>${e.descripcion}</td>
							<td class="text-center">
								<i class="fa-solid fa-pen-to-square me-1 fs-5" style="cursor: pointer" onclick="ModificarEspecialidades(${e.id}, '${e.descripcion}', '${e.estado}')"></i>
							</td>	
						</tr>
					`
			);
		}

		//Captura datos del input para que sean buscados con la función buscarEnTabla
		const inputSearch = document.getElementById("inputSearch");
		const tableHeadRow = document.getElementById("tableHeadRow");
		inputSearch.addEventListener("input", () => {
			removeAllChildNodes(tableBodyElement);
			buscarEnTabla(inputSearch.value, ESTADO_ELEMENTOS);
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
	</script>
	<?php
	if (isset($_REQUEST['alerta'])) {
		$nombreAlerta = $_REQUEST['alerta'];
		if ($nombreAlerta == "success") {
			echo "<script>alertify.success('Proceso exitoso');</script>";
		} else if ($nombreAlerta == "error") {
			echo "<script>alertify.error('Hubo un error');</script>";
		} else if ($nombreAlerta == "warning") {
			echo "<script>alertify.warning('Algunas especialidades no se han creado');</script>";
		}
	}
	?>
</body>

</html>
