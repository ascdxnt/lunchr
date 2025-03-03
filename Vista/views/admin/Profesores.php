<?php
$head = file_get_contents('./Vista/views/components/Head.php');
$header = file_get_contents('./Vista/views/components/Header.php');
$sidebar = file_get_contents('./Vista/views/components/MenuAdmin.php');

if (isset($_REQUEST['estados'])) $estado = 0;
else $estado = 1;

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
				<?php if ($estado == 1) echo 'Profesores';
				else echo 'Profesores Eliminados' ?>
			</h1>
			<section class="table-system mt-3">
				<div class="d-flex justify-content-between gap-1">
					<input id="inputSearch" class="form-control" type="search" placeholder="Buscar" style="width: 100%; max-width: 15em">
					<div class="d-flex justify-content-between gap-1">
						<?php if ($estado == 1) { ?>
							<button onclick="CrearProfesores()" class="btn text-white py-2 table__green-button" title="Crear nuevo"><i class="fa-solid fa-plus"></i></button>
							<button onclick="CambiarEstado(0)" class="btn text-white py-2 table__red-button" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
							<button onclick="verInactivos()" class="btn text-white py-2 table__blue-button" title="Ver Eliminados"><i class="fa-solid fa-users-slash"></i></button>
						<?php } else { ?>
							<button onclick="CambiarEstado(1)" class="btn text-white py-2 table__red-button" title="Activar"><i class="fa-solid fa-heart"></i></i></button>
							<button onclick="verActivos()" class="btn text-white py-2 table__blue-button" title="Ver Activos"><i class="fa-solid fa-users"></i></button>
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

	<form id="formModificar" action="./?dir=admin&controlador=Profesor&accion=Index&id=modificar" method="POST" hidden>
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
	<div id="datos" hidden data-profesores='<?php echo $profesores ?>' data-estadoUsuarios='<?php echo $estado ?>'></div>

	<script>
		const datos = document.getElementById('datos');
		const tableBodyElement = document.getElementById("tableBodyElement");
		const ESTADO_USUARIOS = datos.dataset.estadousuarios;
		let arrayUsuarios = null;

		if (datos.dataset.profesores != "") {
			arrayUsuarios = JSON.parse(datos.dataset.profesores);
			buscarEnTabla("", ESTADO_USUARIOS);
		}

		function verActivos() {
			location.href = "./?dir=admin&controlador=Profesor&accion=Index&id=main";
		}

		function verInactivos() {
			location.href = "./?dir=admin&controlador=Profesor&accion=Index&id=main&estados=0";
		}

		function CrearProfesores() {
			location.href = "./?dir=admin&controlador=Profesor&accion=Index&id=crear";
		}

		const formModificar = document.getElementById('formModificar');

		function ModificarProfesores(...datosEntidad) {
			datosEntidad.forEach((element, index) => {
				formModificar.children[index].value = element;
			});
			formModificar.lastElementChild.click();
		}

		function LlenarInfoModal(...datosEntidad) {
			const modalBody = document.getElementById('modalBody');
			const modalTitle = document.getElementById('modalTitle');
			let estado;
			if (datosEntidad[6] === "1") estado = "Profesor Activo";
			else estado = "Profesor Eliminado";
			modalTitle.textContent = `${datosEntidad[0]} ${datosEntidad[1]} ${datosEntidad[2]}`;
			removeAllChildNodes(modalBody);
			modalBody.insertAdjacentHTML('beforeend', `
          <img class="text-center" src="${datosEntidad[7]}" alt="Foto de Perfil" width="150" height="150" style="display:block;margin: 0 auto;object-fit: cover; border-radius: 50%">
					<dl>
						<dt>Cédula</dt>
						<dd>${datosEntidad[3]}</dd>
						<dt>Correo</dt>
						<dd>${datosEntidad[4]}</dd>
						<dt>Comidas</dt>
						<dd>${datosEntidad[5]}</dd>
						<dt>Estado</dt>
						<dd class="mb-0">${estado}</dd>
					</dl>
			`);
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
				let direccionamiento = `./?dir=admin&controlador=Profesor&accion=CambiarEstado&id=${estado}`;
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
				let arrayTemp = Object.values(element).splice(1, 4);
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
							<td>${e.nombre}</td>
							<td>${e.apellido1} ${e.apellido2}</td>
							<td>${e.cedula}</td>
							<td class="text-center">
								<i class="fa-solid fa-pen-to-square me-1 fs-5" style="cursor: pointer" onclick="ModificarProfesores(${e.id}, '${e.nombre}', '${e.apellido1}', '${e.apellido2}', '${e.cedula}', '${e.correo}', '${e.comidas}', '${e.estado}')"></i>
								<i data-bs-toggle="modal" data-bs-target="#exampleModal" class="fa-solid fa-circle-info ms-1 fs-5" style="cursor: pointer" onclick="LlenarInfoModal('${e.nombre}', '${e.apellido1}', '${e.apellido2}', '${e.cedula}', '${e.correo}', '${e.comidas}', '${e.estado}', '${e.fotoPerfil}')"></i>	
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
			buscarEnTabla(inputSearch.value, ESTADO_USUARIOS);
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
			echo "<script>alertify.warning('Algunos profesores no se han creado');</script>";
		}
	}
	?>
</body>

</html>
