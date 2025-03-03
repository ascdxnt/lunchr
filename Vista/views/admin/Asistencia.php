<?php
$head = file_get_contents('./Vista/views/components/Head.php');

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

		<a href="./?dir=admin&controlador=EstadisticasAdmin&accion=Index" class="fs-4 text-dark">
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
			<form id="manualForm" action="./?dir=admin&controlador=Profesor&accion=Crear" method="POST" class="px-4 pb-4">
				<div class="text-center">
					<label class="form-label mt-3 fs-5" for="cedulaAsistencia">Ingresar cédula</label>
					<input autofocus class="form-control mb-4 w-50 mx-auto" type="text" id="cedulaAsistencia">
					<button id="marcarAsistenciaBoton" class="btn btn-comedor w-50" disabled>Marcar Asistencia</button>
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
			//Cambiar Entre Pestañas
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

			//Obtener el día de hoy
			let fechaHoy = document.getElementById('fechaHoy');
			let tituloFecha = document.getElementById('titulo-fecha');
			let nuevaFecha = '';

			let elementosFecha = fechaHoy.textContent.split("-");

			elementosFecha.forEach(e => {
				if (e.includes("Mon")) e = 'Lunes';
				else if (e.includes('Tue')) e = 'Martes';
				else if (e.includes('Wed')) e = 'Miércoles';
				else if (e.includes('Thu')) e = 'Jueves';
				else if (e.includes('Fri')) e = 'Viernes';
				else if (e.includes('Sat')) e = 'Sábado';
				else if (e.includes('Sun')) e = 'Domingo';

				//Meses
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

				//Dia
				if (e.includes("01")) e = "Primero";

				nuevaFecha += ` ${e}`;
			});

			tituloFecha.textContent = nuevaFecha;

			//Sistema por cédula
			const marcarAsistenciaBoton = document.getElementById('marcarAsistenciaBoton');
			marcarAsistenciaBoton.previousElementSibling.addEventListener('input', (e) => {
				if (e.target.value.length != 0) marcarAsistenciaBoton.disabled = false;
				else marcarAsistenciaBoton.disabled = true;
			});

			marcarAsistenciaBoton.addEventListener('click', e => {
				e.preventDefault();
				let cedula = marcarAsistenciaBoton.previousElementSibling.value;
				marcarAsistenciaBoton.previousElementSibling.value = "";
				evaluarCedula(cedula);
			});

			//Sistema QR
			function decodeOnce(codeReader, selectedDeviceId) {
				codeReader.decodeFromInputVideoDevice(selectedDeviceId, 'video').then((result) => {
					evaluarCedula(result.text);
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

			//Alertas con Sweet Alert
			function Alerta(tipoIcono, mensaje, nombre = false, fotoPerfil = false) {
				let timerInterval;
				let contenido;

				if (nombre != false) {
					contenido = `<img style="border-radius: .5em; width: 150px; margin-right: .5em; border: 3px solid #dcdcdc" src="${fotoPerfil}" alt="FotoPerfil"/><div style="margin: 1em auto; font-weight: bold">${nombre}</div>`;
				}

				Swal.fire({
					title: mensaje,
					html: contenido,
					timer: 3200,
					showConfirmButton: false,
					icon: tipoIcono,
					timerProgressBar: true,
					willClose: () => {
						clearInterval(timerInterval)
					}
				})
			}

			//función que envía la cedula a php para ser evaluada
			const tablaAsistencia = document.getElementById('tablaAsistencia');

			function evaluarCedula(cedula) {
				var hoy = new Date();
				var hora = hoy.getHours() + ':' + hoy.getMinutes();

				let datos = JSON.stringify({
					Cedula: cedula,
					Hora: hora,
					Fecha: `${hoy.getFullYear()}-${hoy.getMonth()+1}-${hoy.getDate()}`
				});

				const posiblesMensajes = [{
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

				fetch("./?dir=admin&controlador=Asistencia&accion=PasarAsistencia", {
						method: 'POST',
						body: datos
					})
					.then(response => response.json())
					.then(objeto => {
						if (elemento = posiblesMensajes.find(e => e.message === objeto.message)) {
							let music = new Audio(`./Vista/assets/audio/${elemento.sonidoAlerta}`);

							music.play();
							if (elemento.tieneFoto)
								Alerta(elemento.tipoAlerta, elemento.message, `${objeto.Nombre} ${objeto.Apellido1} ${objeto.Apellido2}`, `${objeto.fotoPerfil}`);
							else
								Alerta(elemento.tipoAlerta, elemento.message);
						}
					});
			}
		</script>
</body>

</html>
