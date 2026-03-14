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
            <h1 class="fs-3">
                <?php echo $client->getName() . " " . $client->getFirstLastName() . " " . $client->getSecondLastName() ?>
            </h1>
            <h2 id="AttendanceTitle" class="fs-4 mt-2 text-secondary">Registro de Asistencia</h2>
            <section class="d-flex flex-wrap gap-3 mt-4">
                <div class="py-3 px-4 rounded overflow-auto float-start general-shadow mobile-target mx-auto mobile-target2" style="min-width: 30%;">
                    <h3 class="fs-5 fw-bold mb-4">Desgloce</h3>
                    <p>Cédula:
                        <?php echo $client->getIdCard() ?>
                    </p>
                    <?php
                    if ($profile == "Estudiante") {
                    ?>
                        <p>Especialidad:
                            <?php echo $specialty->getDescription() ?>
                        </p>
                        <p>Seccion:
                            <?php echo $section->getDescription() ?>
                        </p>
                        <?php
                        if ($client->getScholarship() == 0) {
                        ?>
                            <p>Tipo de beca: Subvencionada</p>
                        <?php
                        } else {
                        ?>
                            <p>Tipo de beca: Completa</p>
                        <?php
                        }
                        ?>
                    <?php
                    }
                    ?>
                    <p>Correo:
                        <?php echo $client->getEmail() ?>
                    </p>
                    <p>Total Asistencias: <span id="totalAttendancesField" class="text-success"></span></p>
                    <p>Total Ausencias: <span id="totalAbsencesField" class="text-danger"></span></p>
                    <a class="btn btn-secondary w-100" href="./?dir=admin&controller=Attendance&action=AttendanceRecord">Volver</a>
                </div>

                <div class="py-3 px-4 rounded float-start general-shadow mobile-target mobile-target2 mx-auto" style="min-width: 60%; height: 60vh;">
                    <h3 class="mb-3 d-flex justify-content-between fs-5">
                        <div class="fw-bold me-2">Asistencia</div>
                        <button class="btn btn-comedor" data-bs-toggle="modal" data-bs-target="#exampleModal">Filtrar</button>
                    </h3>
                    <div class="overflow-auto table-responsive" style="height: 90%;">
                        <table class="table general-shadow text-center">
                            <thead class="sticky-top table-light general-shadow">
                                <tr>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" class="align-middle border">

                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </section>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Filtrar asistencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="">
                    <div id="inputsRadioContainer" class="p-3 target-background">
                        <label class="d-block form-check-label" style="cursor: pointer">
                            <input checked class="form-check-input me-2" value="anual" type="radio" name="tiempo" id="inputAnual">
                            Durante todo el año
                        </label>
                        <label class="d-block form-check-label" style="cursor: pointer">
                            <input class="form-check-input me-2" value="diaEspecifico" type="radio" name="tiempo" id="inputAnual">
                            Día específico
                        </label>
                        <label class="d-block form-check-label" style="cursor: pointer">
                            <input class="form-check-input me-2" value="lapso" type="radio" name="tiempo" id="inputAnual">
                            Lapso de tiempo
                        </label>
                    </div>
                    <section id="inputsTiempo"></section>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="ApplyFilterButton" type="button" data-bs-dismiss="modal" class="btn btn-comedor">Aplicar Filtro</button>
                </div>
            </div>
        </div>
    </div>
    <div id="attendanceRecords" hidden>
        <?php echo $attendanceRecords ?>
    </div>
    <script>
        const tableBody = document.getElementById('tableBody');
        const totalAbsencesField = document.getElementById('totalAbsencesField');
        const totalAttendancesField = document.getElementById('totalAttendancesField');
        const AttendanceTitle = document.getElementById('AttendanceTitle');
        let attendanceRecords = document.getElementById('attendanceRecords');
        let attendanceRecordsArray = Object.values(JSON.parse(attendanceRecords.textContent));
        const ApplyFilterButton = document.getElementById('ApplyFilterButton');
        // Initialization functions
        let dateObject = new Date();
        let today = dateObject.toISOString().split('T')[0];
        annualFilter();

        function showAttendance(startDate, endDate) {
            removeAllChildNodes(tableBody);
            let attendances = 0;
            let absences = 0;
            attendanceRecordsArray.forEach(e => {
                if (e.Fecha >= startDate && e.Fecha <= endDate) {
                    if (e.Estado === "Presente") {
                        attendances++;
                        tableBody.insertAdjacentHTML('afterbegin', `
							<tr class="">
						  	<td class="px-3"><div class="badge rounded-pill bg-success">Presente</div></td>
					   		<td class="px-3">${formatDate(e.Fecha)}</td>
							</tr>
					  `);
                    } else {
                        absences++;
                        tableBody.insertAdjacentHTML('afterbegin', `
							<tr class="">
									<td class="px-3"><div class="badge rounded-pill bg-danger">Ausente</div></td>
									<td class="px-3">${formatDate(e.Fecha)}</td>
								</tr>
						 `);
                    }
                }
            });
            if (tableBody.childElementCount == 0) {
                tableBody.insertAdjacentHTML('afterbegin', `
					<tr>
						<td colspan="2" class="align-middle py-2 text-center">No hay registros</td>
					</tr>
				`);
            }
            totalAttendancesField.textContent = attendances;
            totalAbsencesField.textContent = absences;
        }

        // Filter
        const inputsRadioContainer = document.getElementById('inputsRadioContainer');
        const inputsTiempo = document.getElementById('inputsTiempo');
        let dataToServer = null;

        function identifyInputRadio(e) {
            if (e.target.type === 'radio') removeAllChildNodes(inputsTiempo);
            if (e.target.value === "anual") {
                ApplyFilterButton.setAttribute('onclick', 'annualFilter()')
            } else if (e.target.value === "diaEspecifico") {
                ApplyFilterButton.setAttribute('onclick', 'filterBySpecificDay()')
                inputsTiempo.insertAdjacentHTML('afterbegin', `
						<div class="p-3 d-flex align-items-center flex-wrap">
						  <label class="d-block me-2">Seleccione el día</label>
						  <input type="date" class="form-control" style="width: max-content" max="<?php echo date('Y-m-d') ?>" id="diaEspecifico" name="diaEspecifico">
						</div>
				`);
            } else if (e.target.value === "lapso") {
                ApplyFilterButton.setAttribute('onclick', 'filterByPeriod()')
                inputsTiempo.insertAdjacentHTML('afterbegin', `
						<div class="p-3 d-flex align-items-center flex-wrap">
								<div class="text-center w-50">
									<label class="d-block me-2">Día inicio</label>
									<input type="date" class="form-control mx-auto" style="width: max-content" max="<?php echo date('Y-m-d') ?>" name="diaInicio" id="diaInicio">
								</div>
								<div class="w-50 text-center">
									<label class="d-block me-2">Día fin</label>
									<input type="date" class="form-control mx-auto" style="width: max-content" max="<?php echo date('Y-m-d') ?>" name="diaFin" id="diaFin">
								</div>
						</div>
				`);
            }
        }
        inputsRadioContainer.addEventListener('click', (e) => {
            identifyInputRadio(e)
        });


        // Functions to filter dates
        function annualFilter() {
            AttendanceTitle.textContent = `Registro de Asitencia ${dateObject.getFullYear()}`;
            showAttendance(dateObject.getFullYear() + '-01-01', today);
        }

        function filterBySpecificDay() {
            const specificDay = document.getElementById('diaEspecifico');

            if (specificDay.value != "") {
                AttendanceTitle.textContent = `Registro de Asitencia ${formatDate(specificDay.value)}`;
                showAttendance(specificDay.value, specificDay.value);
            }
        }

        function filterByPeriod() {
            const startDay = document.getElementById('diaInicio');
            const endDay = document.getElementById('diaFin');

            if (startDay.value != "" && endDay.value != "") {
                AttendanceTitle.textContent = `Registro de Asitencia ${formatDate(startDay.value)} - ${formatDate(endDay.value)}`;
                showAttendance(startDay.value, endDay.value)
            }
        }
    </script>
</body>

</html>