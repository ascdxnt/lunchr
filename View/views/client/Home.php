<?php
$head = file_get_contents('./View/views/components/Head.php');
$header = file_get_contents('./View/views/components/Header.php');
$sidebar = file_get_contents('./View/views/components/ClientMenu.php');

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

	<div id="datos" hidden data-transacciones='<?php echo json_encode($transactions) ?>'></div>

	<script>
		//Table population
		const data = document.getElementById('datos');
		const transactionsArray = JSON.parse(data.dataset.transacciones);
		const tbodyAumentos = document.getElementById('tbodyAumentos');
		const tbodyRebajas = document.getElementById('tbodyRebajas');

		function populateTable() {
			transactionsArray.forEach(e => {
				let mealColor;
				let symbol;
				let isIncrease = false;

				if (e.comidas > 0) {
					symbol = "+";
					mealColor = "text-success";
					isIncrease = true;
				} else {
					symbol = "";
					mealColor = "text-danger";
				}

				if (isIncrease) {
					tbodyAumentos.insertAdjacentHTML('afterbegin', `
					<tr>
					<td class="${mealColor}" style="font-weight: 600">${symbol}${e.comidas}</td>
					<td>${e.fecha}</td>
					<td>${e.hora}</td>
					</tr>
					`);
				} else {
					tbodyRebajas.insertAdjacentHTML('afterbegin', `
					<tr>
					<td class="${mealColor}" style="font-weight: 600">${symbol}${e.comidas}</td>
					<td>${e.fecha}</td>
					<td>${e.hora}</td>
					</tr>
					`);
				}
			});
		}
		populateTable();

		//Date and time formatting
		for (row of tbodyAumentos.children) {
			let militaryTime = row.children[2];
			let formattedTime = format24HourTime(militaryTime.textContent);
			militaryTime.textContent = formattedTime;
		}

		function format24HourTime(militaryTime) {
			let splitTime = militaryTime.split(':');
			let hour = splitTime[0];
			let minute = splitTime[1];
			if (minute.length === 1) {
				minute = '0' + minute;
			}
			let suffix = "am";

			if (hour > 12) {
				hour -= 12;
				suffix = "pm";
			}

			hour = String(hour);
			return hour + ":" + minute + " " + suffix;
		}


		for (row of tbodyAumentos.children) {
			let unformattedDate = row.children[1];
			let formattedDate = formatTransactionDate(unformattedDate.textContent);
			unformattedDate.textContent = formattedDate;
		}

		function formatTransactionDate(unformattedDate) {
			let dateArray = unformattedDate.split('-');
			let dateObject = new Date(unformattedDate);
			let dayOfWeek = dateObject.getDay();
			let dayNumber = dateArray[2];
			let month = dateArray[1];
			let year = dateArray[0];

			if (dayOfWeek === 0) dayOfWeek = 'Lun';
			else if (dayOfWeek === 1) dayOfWeek = 'Mar';
			else if (dayOfWeek === 2) dayOfWeek = 'Mié';
			else if (dayOfWeek === 3) dayOfWeek = 'Jue';
			else if (dayOfWeek === 4) dayOfWeek = 'Vie';
			else if (dayOfWeek === 5) dayOfWeek = 'Sáb';
			else if (dayOfWeek === 6) dayOfWeek = 'Dom';

			if (month === "01") month = 'Ene';
			if (month === "02") month = 'Feb';
			if (month === "03") month = 'Mar';
			if (month === "04") month = 'Abr';
			if (month === "05") month = 'May';
			if (month === "06") month = 'Jun';
			if (month === "07") month = 'Jul';
			if (month === "08") month = 'Ago';
			if (month === "09") month = 'Sep';
			if (month === "10") month = 'Oct';
			if (month === "11") month = 'Nov';
			if (month === "12") month = 'Dic';
			return `${dayOfWeek} ${dayNumber} ${month} ${year}`;
		}
	</script>
</body>

</html>
