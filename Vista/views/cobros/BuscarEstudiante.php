<?php
$head = file_get_contents('./Vista/views/components/Head.php');
$header = file_get_contents('./Vista/views/components/Header.php');
$sidebar = file_get_contents('./Vista/views/components/MenuCobros.php');

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

    <title>Comedor - Estudiantes</title>
    <?php echo $head; ?>
</head>

<body>
    <?php echo $header; ?>
    <main class="d-flex">
        <?php echo $sidebar; ?>
        <section class="my-4 mx-auto w-75 mobile-target">
            <h2 class="fs-3 d-flex justify-content-between">Estudiantes</h2>
            <section class="table-system mt-3">
                <div class="d-flex justify-content-between gap-1 flex-wrap">
                    <input id="inputSearch" class="form-control" type="search" placeholder="Buscar" style="width: 100%; max-width: 15em">
                    <div id="filtro-beca-container" class="d-flex flex-wrap justify-content-center gap-3 align-items-center">
                        <label class="form-check-label" style="cursor: pointer; user-select: none">
                            Beca Subvencionada
                            <input class="form-check-input" type="radio" name="tipoBeca" value="subvencionada" checked>
                        </label>
                        <label class="form-check-label" style="cursor: pointer; user-select: none">
                            Beca Completa
                            <input class="form-check-input" type="radio" name="tipoBeca" value="completa">
                        </label>
                    </div>
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

    <!--Modal Comidas Estudiante-->
    <div class="modal fade" id="ModalComidas" tabindex="-1" role="dialog" aria-labelledby="ModalComidasLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="manualForm" class="modal-content" method="POST" action="./?dir=cobros&controlador=EstudianteCobros&accion=AgregarComidas">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalComidasLabel">Agregar comidas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label>Agregue la cantidad de comidas</label>
                    <input id="idEstudianteModal" name="idEstudiante" type="text" hidden>
                    <input hidden id="inputFecha" type="text" name="fechaHoy">
                    <input hidden id="inputHora" type="text" name="hora">
                    <div class="input-group my-3 manual">
                        <input name="comidas" type="number" class="form-control" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <div id="advertencia" class="text-danger">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="agregarComidasBoton" disabled type="submit" class="btn btn-comedor">Aceptar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="datos" hidden data-estudiantes='<?php echo $estudiantes ?>'></div>

    <script>
        const agregarComidasBoton = document.getElementById('agregarComidasBoton');
        const manualForm = document.getElementById('manualForm');
        const advertenciaCampo = document.getElementById('advertencia');
        const idEstudianteModal = document.getElementById('idEstudianteModal');
        const inputHora = document.getElementById('inputHora');
        const inputFecha = document.getElementById('inputFecha');
        const datos = document.getElementById('datos');
        const tableBodyElement = document.getElementById("tableBodyElement");
        const filtroBecaContainer = document.getElementById("filtro-beca-container");
        const tableHeadRow = document.getElementById("tableHeadRow");
        let arrayUsuarios = null;

        if (datos.dataset.estudiantes != "") {
            arrayUsuarios = JSON.parse(datos.dataset.estudiantes);
            buscarEnTabla("");
        }

        let manualInputs = [...document.getElementsByClassName('manual')];

        tableBodyElement.addEventListener('click', (e) => {
            if (e.target.tagName === "BUTTON" || e.target.tagName === "I") {
                let fila;
                if (e.target.tagName === "BUTTON") fila = e.target.parentElement.parentElement;
                else if (e.target.tagName === "I") fila = e.target.parentElement.parentElement.parentElement;

                let hoy = new Date();
                let hora = hoy.getHours() + ':' + hoy.getMinutes();
                let fecha = `${hoy.getFullYear()}-${hoy.getMonth()+1}-${hoy.getDate()}`;

                inputHora.value = hora;
                inputFecha.value = fecha;
                idEstudianteModal.value = fila.dataset.id;
            }
        })

        //validaciones
        manualForm.addEventListener('input', () => {
            let estadoBoton = false;
            manualInputs.forEach(e => {
                if (e.children[0].value == "") {
                    estadoBoton = true;
                }
            })
            agregarComidasBoton.disabled = estadoBoton;
        })

        //Buscar coincidencias en la tabla
        function buscarEnTabla(texto) {
            if (!arrayUsuarios)
                return null;

            removeAllChildNodes(tableBodyElement);
            let tipoBeca = document.querySelector("[name=tipoBeca]:checked");

            arrayUsuarios.forEach((element) => {
                let encontrado = false;
                let arrayTemp = Object.values(element).splice(1, 4);

                arrayTemp.forEach(campo => {
                    campo = String(campo);
                    if (campo.includes(texto)) encontrado = true;
                });

                if (encontrado && element.becado == 0 && tipoBeca.value === "subvencionada") {
                    agregarFila(element);
                }

                if (encontrado && element.becado == 1 && tipoBeca.value === "completa") {
                    agregarFilaBecadosCompletos(element);
                }
            });
        }

        //Agrega las filas a la tabla
        function agregarFila(e) {
            if (tableHeadRow.childElementCount < 5) {
                tableHeadRow.insertAdjacentHTML('beforeend', `
                    <th class="text-center">Comidas</th>
                    <th class="text-center">Agregar</th>
                `);
            }

            tableBodyElement.insertAdjacentHTML(
                "beforeend",
                `
                  <tr class="transicion align-middle" data-id="${e.id}">
                      <td>${e.nombre}</td>
                      <td>${e.apellido1} ${e.apellido2}</td>
                      <td>${e.cedula}</td>
                      <td class="text-center">${e.comidas}</td>
                      <td class="text-center">
                        <button title="Agregar Comidas" type="button" class="btn btn-comedor" data-bs-toggle="modal" data-bs-target="#ModalComidas"><i class="fa-solid fa-circle-plus"></i></button>
                      </td>	
                    </tr>
                `
            );
        }

        function agregarFilaBecadosCompletos(e) {
            if (tableHeadRow.childElementCount === 5) {
                tableHeadRow.lastElementChild.remove();
                tableHeadRow.lastElementChild.remove();
            }

            tableBodyElement.insertAdjacentHTML(
                "beforeend",
                `
                  <tr class="transicion align-middle" data-id="${e.id}">
                      <td>${e.nombre}</td>
                      <td>${e.apellido1} ${e.apellido2}</td>
                      <td>${e.cedula}</td>
                  </tr>
                `
            );

        }

        //Captura datos del input para que sean buscados con la función buscarEnTabla
        const inputSearch = document.getElementById("inputSearch");
        inputSearch.addEventListener("input", () => {
            buscarEnTabla(inputSearch.value);
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

        //Filtro por beca
        filtroBecaContainer.addEventListener('click', event => {
            if (!event.target.checked) return;

            buscarEnTabla("");
            return;
        });
    </script>

    <?php
    if (isset($_REQUEST['alerta'])) {
        $nombreAlerta = $_REQUEST['alerta'];
        if ($nombreAlerta == "success") {
            echo "<script>alertify.success('Proceso exitoso');</script>";
        } else if ($nombreAlerta == "error") {
            echo "<script>alertify.error('Hubo un error');</script>";
        }
    }
    ?>
</body>

</html>
