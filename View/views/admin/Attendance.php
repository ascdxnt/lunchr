<?php
$head = file_get_contents('./View/views/components/Head.php');

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

	<script defer type="text/javascript" src="https://unpkg.com/@zxing/library@latest"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<title>Comedor - Asistencia</title>
	<?php echo $head; ?>
</head>

<body style="overflow-x: hidden; background-color: #fff;">
	<header class="shadow-sm main-color-background" style="padding-left: 4%;">
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<a href="./?dir=admin&controller=AdminStatistics&action=Index" class="fs-4 text-dark">
			<i class="fa-solid fa-arrow-left text-light"></i>
		</a>
	</header>

	<main class="d-flex flex-wrap justify-content-around gap-1">
		<div style="margin-top: 2em; width: 35%; height: 518px;" class="rounded general-shadow position-relative mobile-target overflow-hidden">
			<div id="navbar-container">
				<nav aria-label="Page navigation example">
					<ul class="pagination justify-content-end">
						<li data-page="manual" class="page-item page-link text-center w-50 rounded-top" style="cursor: pointer; user-select: none">
							<i class="bi bi-ui-radios me-1"></i>
							Cédula
						</li>
						<li data-page="excel" class="page-item page-link text-center w-50 rounded-top" style="cursor: pointer; user-select: none">
							<i class="bi bi-qr-code me-1"></i>
							Lector QR
						</li>
					</ul>
				</nav>
			</div>
			<section class="asistencia-section py-2 px-4">
				<span id="fechaHoy" hidden><?php echo date("D-d-M-Y"); ?>
				</span>
				<h1 id="titulo" class="fs-4 text-bold text-center"><span id="titulo-fecha" style="color: #333"></span></h1>
			</section>
			<form id="manualForm" action="./?dir=admin&controller=Teacher&action=Create" method="POST" class="px-4 pb-4">
				<div class="text-center">
					<label class="form-label mt-3 fs-5" for="cedulaAsistencia">Ingresar cédula</label>
					<input autofocus class="form-control mb-4 w-50 mx-auto" type="text" id="cedulaAsistencia">
					<button id="markAttendanceButton" class="btn btn-comedor w-50" disabled>Marcar Asistencia</button>
				</div>
			</form>
			<section class="d-none px-4 pb-4" id="qrForm">
				<div class="position-relative mt-3">
					<div class="position-absolute w-100 d-flex justify-content-between">
						<div class="" id="sourceSelectPanel" style="display:none">
							<select id="sourceSelect" class="form-select" style="max-width:300px"></select>
						</div>
						<button class="btn btn-comedor" id="startButton">
							<i class="fa-solid fa-play me-1"></i>
							Iniciar
						</button>
					</div>
					<video id="video" height="320" class="w-100 mx-auto rounded"></video>
				</div>
				<pre><code id="result" class="d-none"></code></pre>
			</section>
		</div>
		<script>
			// Switch between tabs
			const navbarContainer = document.getElementById('navbar-container');
			const manualForm = document.getElementById('manualForm');
			const qrForm = document.getElementById('qrForm');

			navbarContainer.addEventListener('click', (e) => {
				if (e.target.dataset.page == "manual") {
					manualForm.classList.remove("d-none");
					qrForm.classList.add("d-none");
				} else {
					manualForm.classList.add("d-none");
					qrForm.classList.remove("d-none");
				}
			});

			// Get today's date
			let todayDate = document.getElementById('fechaHoy');
			let titleDate = document.getElementById('titulo-fecha');
			let newDate = '';

			let dateElements = todayDate.textContent.split("-");

			dateElements.forEach(e => {
				if (e.includes("Mon")) e = 'Lunes';
				else if (e.includes('Tue')) e = 'Martes';
				else if (e.includes('Wed')) e = 'Miércoles';
				else if (e.includes('Thu')) e = 'Jueves';
				else if (e.includes('Fri')) e = 'Viernes';
				else if (e.includes('Sat')) e = 'Sábado';
				else if (e.includes('Sun')) e = 'Domingo';

				// Months
				if (e.includes('Jan')) e = 'de Enero del';
				else if (e.includes('Feb')) e = 'de Febrero del';
				else if (e === 'Mar') e = 'de Marzo del';
				else if (e.includes('Apr')) e = 'de Abril del';
				else if (e.includes('May')) e = 'de Mayo del';
				else if (e.includes('Jun')) e = 'de Junio del';
				else if (e.includes('Jul')) e = 'de Julio del';
				else if (e.includes('Aug')) e = 'de Agosto del';
				else if (e.includes('Sep')) e = 'de Septiembre del';
				else if (e.includes('Oct')) e = 'de Octubre del';
				else if (e.includes('Nov')) e = 'de Noviembre del';
				else if (e.includes('Dec')) e = 'de Diciembre del';

				// Day
				if (e.includes("01")) e = "Primero";

				newDate += ` ${e}`;
			});

			titleDate.textContent = newDate;

			// ID card system
			const markAttendanceButton = document.getElementById('markAttendanceButton');
			markAttendanceButton.previousElementSibling.addEventListener('input', (e) => {
				if (e.target.value.length != 0) markAttendanceButton.disabled = false;
				else markAttendanceButton.disabled = true;
			});

			markAttendanceButton.addEventListener('click', e => {
				e.preventDefault();
				let idCard = markAttendanceButton.previousElementSibling.value;
				markAttendanceButton.previousElementSibling.value = "";
				evaluateIdCard(idCard);
			});

			// QR System
			function decodeOnce(codeReader, selectedDeviceId) {
				codeReader.decodeFromInputVideoDevice(selectedDeviceId, 'video').then((result) => {
					evaluateIdCard(result.text);
					codeReader.reset();
					setTimeout(() => {
						decodeOnce(codeReader, selectedDeviceId);
					}, 2000)
				}).catch((err) => {
					document.getElementById('result').textContent = err
				})
			}

			window.addEventListener('load', function() {
				let selectedDeviceId;
				const codeReader = new ZXing.BrowserQRCodeReader()

				codeReader.getVideoInputDevices()
					.then((videoInputDevices) => {
						const sourceSelect = document.getElementById('sourceSelect')
						selectedDeviceId = videoInputDevices[0].deviceId
						if (videoInputDevices.length >= 1) {
							videoInputDevices.forEach((element) => {
								const sourceOption = document.createElement('option')
								sourceOption.text = element.label
								sourceOption.value = element.deviceId
								sourceSelect.appendChild(sourceOption)
							})

							sourceSelect.onchange = () => {
								selectedDeviceId = sourceSelect.value;
							};

							const sourceSelectPanel = document.getElementById('sourceSelectPanel')
							sourceSelectPanel.style.display = 'block'
						}

						const startButton = document.getElementById('startButton');
						startButton.addEventListener('click', () => {
							video.classList.add('general-shadow');
							decodeOnce(codeReader, selectedDeviceId);
						})
					})
					.catch((err) => {
						console.error(err)
					});
			});

			// Alerts with Sweet Alert
			function Alert(iconType, message, name = false, profilePhoto = false) {
				let timerInterval;
				let content;

				if (name != false) {
					content = `<img style="border-radius: .5em; width: 150px; margin-right: .5em; border: 3px solid #dcdcdc" src="${profilePhoto}" alt="FotoPerfil"/><div style="margin: 1em auto; font-weight: bold">${name}</div>`;
				}

				Swal.fire({
					title: message,
					html: content,
					timer: 3200,
					showConfirmButton: false,
					icon: iconType,
					timerProgressBar: true,
					willClose: () => {
						clearInterval(timerInterval)
					}
				})
			}

			// Function that sends the ID card to PHP for evaluation
			const attendanceTable = document.getElementById('tablaAsistencia');

			function evaluateIdCard(idCard) {
				var today = new Date();
				var time = today.getHours() + ':' + today.getMinutes();

				let data = JSON.stringify({
					Cedula: idCard,
					Hora: time,
					Fecha: `${today.getFullYear()}-${today.getMonth()+1}-${today.getDate()}`
				});

				const possibleMessages = [{
						message: 'Usuario Inexistente.',
						tipoAlerta: 'error',
						sonidoAlerta: 'error.ogg',
						tieneFoto: false
					},
					{
						message: 'Hoy no es un día lectivo.',
						tipoAlerta: 'error',
						sonidoAlerta: 'error.ogg',
						tieneFoto: false
					},
					{
						message: 'Usted ya está presente.',
						tipoAlerta: 'warning',
						sonidoAlerta: 'error.ogg',
						tieneFoto: true
					},
					{
						message: 'Pase adelante.',
						tipoAlerta: 'success',
						sonidoAlerta: 'beep.wav',
						tieneFoto: true
					},
					{
						message: 'No tiene comidas.',
						tipoAlerta: 'error',
						sonidoAlerta: 'error.ogg',
						tieneFoto: true
					}
				]

				fetch("./?dir=admin&controller=Attendance&action=TakeAttendance", {
						method: 'POST',
						body: data
					})
					.then(response => response.json())
					.then(obj => {
						if (element = possibleMessages.find(e => e.message === obj.message)) {
							let music = new Audio(`./View/assets/audio/${element.sonidoAlerta}`);

							music.play();
							if (element.tieneFoto)
								Alert(element.tipoAlerta, element.message, `${obj.Nombre} ${obj.Apellido1} ${obj.Apellido2}`, `${obj.fotoPerfil}`);
							else
								Alert(element.tipoAlerta, element.message);
						}
					});
			}
		</script>
</body>

</html>