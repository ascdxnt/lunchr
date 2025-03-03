<?php
$head = file_get_contents('./Vista/views/components/Head.php');
$header = file_get_contents('./Vista/views/components/Header.php');
$sidebar = file_get_contents('./Vista/views/components/MenuCliente.php');

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

	<title>Comedor - Inicio</title>
	<?php echo $head; ?>
</head>

<body>
	<?php echo $header; ?>
	<main class="d-flex">
		<?php echo $sidebar; ?>
		<div class="container mx-sm-auto pb-0 pt-4">
			<div class="row general-shadow p-2 rounded">
				<div class="col">
					<div class="d-flex flex-wrap justify-content-between">
						<div>
							<h1 class="fs-4 me-3 d-inline text-sm-end">
								<?php echo $_SESSION['usuario']['Nombre'] . " " . $_SESSION['usuario']['PrimerApellido'] . " " . $_SESSION['usuario']['SegundoApellido'] ?>
							</h1>
						</div>
						<?php
						if ($_SESSION['usuario']['Perfil'] == "Profesor" || $_SESSION['usuario']['Becado'] != 1) {
						?>
							<div class="mt-2 mt-sm-0 rounded-pill text-white px-2 py-1 bg-success opacity-75">
								<p class="text-center d-inline fs-6 fw-bold"><?php echo $_SESSION['usuario']['Comidas']; ?> Comidas</p>
							</div>
						<?php
						}
						?>
					</div>
				</div>
			</div>
			<div class="row  mt-3">
				<div class="col-12 col-md-6 general-shadow">
					<div class="overflow-auto table-responsive" style="height: 60vh">
						<h3 class="text-center py-3">Aumentos</h2>
							<table class="table table-striped general-shadow overflow-hidden bg-white rounded align-middle">
								<thead class="text-center table-secondary">
									<tr>
										<th scope="col" class="pt-2">Comidas</th>
										<th scope="col" class="pt-2">Fecha</th>
										<th scope="col" class="pt-2">Hora</th>
									</tr>
								</thead>
								<tbody class="text-center border" id="tbodyAumentos">
									<?php
									if (isset($_SESSION['usuario']['Becado'])) {
										if ($_SESSION['usuario']['Becado'] == 1)
											echo '<tr><td colspan="3" class="text-center">No hay aumentos</td></tr>';
									}
									?>
								</tbody>
							</table>
					</div>
				</div>
				<div class="col-12 col-md-6 general-shadow">
					<div class="overflow-auto table-responsive" style="height: 60vh">
						<h3 class="text-center py-3">Rebajas</h2>
							<table class="table general-shadow overflow-hidden table-striped bg-white rounded align-middle">
								<thead class="text-center table-secondary">
									<tr>
										<th scope="col" class="pt-2">Comidas</th>
										<th scope="col" class="pt-2">Fecha</th>
										<th scope="col" class="pt-2">Hora</th>
									</tr>
								</thead>
								<tbody class="text-center border" id="tbodyRebajas">
									<?php
									if (isset($_SESSION['usuario']['Becado'])) {
										if ($_SESSION['usuario']['Becado'] == 1)
											echo '<tr><td colspan="3" class="text-center">No hay rebajas</td></tr>';
									}
									?>
								</tbody>
							</table>
					</div>
				</div>
			</div>
		</div>
	</main>

	<!--
		<div class="row w-100 mx-auto pt-5">
		<div class="col">
		<div class="shadow-lg p-3 bg-body rounded">
		<div class="d-flex flex-wrap justify-content-between">
		<div>
		<h1 class="fs-4 me-3 d-inline text-sm-end">Historial de transacciones</h1>
		</div>
		<?php
		if ($_SESSION['usuario']['Perfil'] == "Profesor" || $_SESSION['usuario']['Becado'] != 1) {
		?>
		<div class="mt-2 mt-sm-0 rounded-pill text-white px-2 py-1 bg-success opacity-75">
		<p class="fs-6 d-inline">Comidas: </p>
		<p class="text-center d-inline fs-6 fw-bold"><?php echo $_SESSION['usuario']['Comidas']; ?></p>
		</div>
		<?php
		}
		?>
		</div>
		<p class="text-secondary fs-5 py-2"><?php echo $_SESSION['usuario']['Nombre'] . ' ' . $_SESSION['usuario']['PrimerApellido']; ?></p>
		</div>
		</div>
		</div>
		-->

	<div id="datos" hidden data-transacciones='<?php echo json_encode($transacciones) ?>'></div>

	<script>
		//Relleno de tabla
		const datos = document.getElementById('datos');
		const arrayTransacciones = JSON.parse(datos.dataset.transacciones);
		const tbodyAumentos = document.getElementById('tbodyAumentos');
		const tbodyRebajas = document.getElementById('tbodyRebajas');

		function rellenarTabla() {
			arrayTransacciones.forEach(e => {
				let colorComida;
				let simbolo;
				let tabla = false;

				if (e.comidas > 0) {
					simbolo = "+";
					colorComida = "text-success";
					tabla = true;
				} else {
					simbolo = "";
					colorComida = "text-danger";
				}

				if (tabla) {
					tbodyAumentos.insertAdjacentHTML('afterbegin', `
					<tr>
					<td class="${colorComida}" style="font-weight: 600">${simbolo}${e.comidas}</td>
					<td>${e.fecha}</td>
					<td>${e.hora}</td>
					</tr>
					`);
				} else {
					tbodyRebajas.insertAdjacentHTML('afterbegin', `
					<tr>
					<td class="${colorComida}" style="font-weight: 600">${simbolo}${e.comidas}</td>
					<td>${e.fecha}</td>
					<td>${e.hora}</td>
					</tr>
					`);
				}
			});
		}
		rellenarTabla();

		//Formato de Fechas y horas
		for (fila of tbodyAumentos.children) {
			let horaMilitar = fila.children[2];
			let horaFormateada = formatoHora24Horas(horaMilitar.textContent);
			horaMilitar.textContent = horaFormateada;
		}

		function formatoHora24Horas(horaMilitar) {
			let dividirHora = horaMilitar.split(':');
			let hora = dividirHora[0];
			let minuto = dividirHora[1];
			if (minuto.length === 1) {
				minuto = '0' + minuto;
			}
			let sufijo = "am";

			if (hora > 12) {
				hora -= 12;
				sufijo = "pm";
			}

			hora = String(hora);
			return hora + ":" + minuto + " " + sufijo;
		}


		for (fila of tbodyAumentos.children) {
			let fechaSinFormato = fila.children[1];
			let fechaFormateada = formatoFechaTransaccion(fechaSinFormato.textContent);
			fechaSinFormato.textContent = fechaFormateada;
		}

		function formatoFechaTransaccion(fechaSinFormato) {
			let arrayFecha = fechaSinFormato.split('-');
			let objetoDate = new Date(fechaSinFormato);
			let diaDeLaSemana = objetoDate.getDay();
			let numeroDiaDeLaSemana = arrayFecha[2];
			let mes = arrayFecha[1];
			let ano = arrayFecha[0];

			if (diaDeLaSemana === 0) diaDeLaSemana = 'Lun';
			else if (diaDeLaSemana === 1) diaDeLaSemana = 'Mar';
			else if (diaDeLaSemana === 2) diaDeLaSemana = 'Mié';
			else if (diaDeLaSemana === 3) diaDeLaSemana = 'Jue';
			else if (diaDeLaSemana === 4) diaDeLaSemana = 'Vie';
			else if (diaDeLaSemana === 5) diaDeLaSemana = 'Sáb';
			else if (diaDeLaSemana === 6) diaDeLaSemana = 'Dom';

			if (mes === "01") mes = 'Ene';
			if (mes === "02") mes = 'Feb';
			if (mes === "03") mes = 'Mar';
			if (mes === "04") mes = 'Abr';
			if (mes === "05") mes = 'May';
			if (mes === "06") mes = 'Jun';
			if (mes === "07") mes = 'Jul';
			if (mes === "08") mes = 'Ago';
			if (mes === "09") mes = 'Sep';
			if (mes === "10") mes = 'Oct';
			if (mes === "11") mes = 'Nov';
			if (mes === "12") mes = 'Dic';
			return `${diaDeLaSemana} ${numeroDiaDeLaSemana} ${mes} ${ano}`;
		}
	</script>
</body>

</html>
