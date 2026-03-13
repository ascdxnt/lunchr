<?php
$head = file_get_contents('./View/views/components/Head.php');
$header = file_get_contents('./View/views/components/Header.php');
$sidebar = file_get_contents('./View/views/components/BillingMenu.php');

if ($_SESSION["perfiles"] != 'cobros') {
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
        <section class="mt-4 mx-auto w-75 mobile-target">
            <h1 class="fs-3 d-flex justify-content-between">Profesores</h1>
            <section class="table-system mt-3">
                <div class="d-flex justify-content-between gap-1">
                    <input id="inputSearch" class="form-control" type="search" placeholder="Buscar" style="width: 100%; max-width: 15em">
                </div>
                <div class="table-responsive general-shadow mt-3" style="max-height: 70vh">
                    <table id="table" class="table overflow-auto rounded mb-0">
                        <thead class="sticky-top general-shadow" style="border-bottom: 2px solid #e5e7eb !important">
                            <tr id="tableHeadRow" class="align-middle" style="background-color: #e5e7eb; color: #4b5563">
                                <th>Nombre</th>
                                <th>Apellidos</th>
                                <th>Cedula</th>
                                <th class="text-center">Comidas</th>
                                <th class="text-center">Agregar</th>
                            </tr>
                        </thead>
                        <tbody id="tableBodyElement">
                        </tbody>
                    </table>
                </div>
            </section>
        </section>
    </main>

    <!--Teacher Meals Modal-->
    <div class="modal fade" id="ModalComidas" tabindex="-1" role="dialog" aria-labelledby="ModalComidasLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="manualForm" method="POST" action="./?dir=billing&controller=TeacherBilling&action=AddMeals" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalComidaLabel">Agregar Comidas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label>Agregue la cantidad de comidas</label>
                        <input id="idProfesorModal" name="idProfesor" type="text" hidden>
                        <input hidden id="inputFecha" type="text" name="fechaHoy">
                        <input id="inputHora" hidden type="text" name="hora">
                        <div class="input-group my-3 manual">
                            <input name="comidas" type="number" class="form-control" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                        <div id="advertencia" class="text-danger">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="addMealsButton" disabled type="submit" class="btn btn-comedor">Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="datos" hidden data-profesores='<?php echo $teachers ?>'></div>

    <script>
        const addMealsButton = document.getElementById('addMealsButton');
        const manualForm = document.getElementById('manualForm');
        const warningField = document.getElementById('advertencia');
        const teacherIdModal = document.getElementById('idProfesorModal');
        const inputTime = document.getElementById('inputHora');
        const data = document.getElementById('datos');
        const tableBodyElement = document.getElementById("tableBodyElement");
        let usersArray = null;

        if (data.dataset.profesores != "") {
            usersArray = Object.values(JSON.parse(data.dataset.profesores));
            searchInTable("");
        }

        let manualInputs = [...document.getElementsByClassName('manual')];

        tableBodyElement.addEventListener('click', (e) => {
            if (e.target.tagName === "BUTTON" || e.target.tagName === "I") {
                let row;
                if (e.target.tagName === "BUTTON") row = e.target.parentElement.parentElement;
                else if (e.target.tagName === "I") row = e.target.parentElement.parentElement.parentElement;

                let today = new Date();
                let time = today.getHours() + ':' + today.getMinutes();
                let date = `${today.getFullYear()}-${today.getMonth()+1}-${today.getDate()}`;

                inputTime.value = time;
                inputFecha.value = date;
                teacherIdModal.value = row.dataset.id;
            }
        })

        //validations
        manualForm.addEventListener('input', () => {
            let buttonState = false;
            manualInputs.forEach(e => {
                if (e.children[0].value == "") {
                    buttonState = true;
                }
            })
            addMealsButton.disabled = buttonState;
        })

        //Search matches in the table
        function searchInTable(text) {
            if (!usersArray)
                return null;

            usersArray.forEach((element) => {
                let found = false;
                let tempArray = Object.values(element).splice(1, 4);
                tempArray.forEach((field) => {
                    field = String(field);
                    if (field.includes(text)) found = true;
                });
                if (found) addRow(element);
            });
        }

        //Add rows to the table
        function addRow(e) {
            tableBodyElement.insertAdjacentHTML(
                "beforeend",
                `
                  <tr class="transicion align-middle" data-id="${e.id}">
                      <td>${e.nombre}</td>
                      <td>${e.apellido1} ${e.apellido2}</td>
                      <td>${e.cedula}</td>
                      <td class="text-center">${e.comidas}</td>
                      <td class="text-center">
                        <button type="button" class="btn btn-comedor" data-bs-toggle="modal" data-bs-target="#ModalComidas">
                            <i class="fa-solid fa-circle-plus"></i>
                        </button>
                      </td>	
                    </tr>
                `
            );
        }

        //Capture input data to be searched with the searchInTable function
        const inputSearch = document.getElementById("inputSearch");
        const tableHeadRow = document.getElementById("tableHeadRow");
        inputSearch.addEventListener("input", () => {
            removeAllChildNodes(tableBodyElement);
            searchInTable(inputSearch.value);
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
        }
    }
    ?>
</body>

</html>
