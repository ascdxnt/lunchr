<?php
$head = file_get_contents('./Vista/views/components/Head.php');
$header = file_get_contents('./Vista/views/components/Header.php');
$cobrosSidebar = file_get_contents('./Vista/views/components/MenuCobros.php');
$adminSidebar = file_get_contents('./Vista/views/components/MenuAdmin.php');
$clienteSidebar = file_get_contents('./Vista/views/components/MenuCliente.php');
$idUsuario = $_SESSION['usuario']['Id'];
$perfilUsuario = $_SESSION['usuario']['Perfil'];

if ($_SESSION["perfiles"] != 'admin' && $_SESSION["perfiles"] != 'cobros' && $_SESSION["perfiles"] != 'cliente') {
    header('Location: ./?alerta=error');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Comedor - MiCuenta</title>
    <?php echo $head; ?>
</head>

<body>
    <?php echo $header; ?>
    <main class="d-flex">
        <?php
        if ($_SESSION['usuario']['Perfil'] == "Cobrador")
            echo $cobrosSidebar;
        else if ($_SESSION['usuario']['Perfil'] == "Administrador")
            echo $adminSidebar;
        else
            echo $clienteSidebar;
        ?>
        <div class="container d-flex mt-4">
            <div class="row mx-auto">
                <div class="col-12">
                    <div class="container-fluid shadow-lg py-3 px-3 px-sm-5 mb-5 rounded">
                        <div class="row flex-wrap justify-content-evenly">
                            <?php
                            if ($_SESSION['usuario']['Perfil'] == "Estudiante" || $_SESSION['usuario']['Perfil'] == "Profesor") {
                            ?>
                                <div class="col-12 col-lg-4 pb-4 pb-md-0 d-flex flex-column justify-content-center">
                                    <img src="<?php echo $_SESSION['usuario']['Foto'] ?>" class="fotoMiPerfil" alt="Foto Perfil" />
                                </div>
                            <?php
                            }
                            ?>
                            <div class="col-12 col-lg-4">
                                <h2 class="fs-4 py-2 py-md-4 py-lg-2 fw-bold gap-3 text-center text-lg-start">Mi cuenta</h2>
                                <p><span class="fw-bolder">Nombre:</span>
                                    <?php echo $_SESSION['usuario']['Nombre'] ?>
                                </p>
                                <p><span class="fw-bolder">Apellidos:</span>
                                    <?php echo $_SESSION['usuario']['PrimerApellido'] ?>
                                    <?php echo $_SESSION['usuario']['SegundoApellido'] ?>
                                </p>
                                <p><span class="fw-bolder">Correo:</span>
                                    <?php echo $_SESSION['usuario']['Correo'] ?>
                                </p>
                                <p><span class="fw-bolder">Perfil:</span>
                                    <?php echo $_SESSION['usuario']['Perfil'] ?>
                                </p>
                                <?php
                                if ($_SESSION['usuario']['Perfil'] == "Estudiante" || $_SESSION['usuario']['Perfil'] == "Profesor") {
                                ?>
                                    <p id="campoComidas"><span class="fw-bolder">Comidas:</span>
                                        <?php
                                        if ($_SESSION['usuario']['Perfil'] == "Estudiante") {
                                            if ($_SESSION['usuario']['Becado'] == 1)
                                                echo '<i class="fa-solid fa-infinity"></i>';
                                            else
                                                echo $_SESSION['usuario']['Comidas'];
                                        } else {
                                            echo $_SESSION['usuario']['Comidas'];
                                        }
                                        ?>
                                    </p>
                                    <p><span class="fw-bolder">Cédula:</span> <span id="cedula"><?php echo $_SESSION['usuario']['Cedula']; ?></span></p>
                                <?php
                                }
                                if ($_SESSION['usuario']['Perfil'] == "Estudiante") {
                                ?>
                                    <p><span class="fw-bolder">Especialidad:</span>
                                        <?php echo $_SESSION['usuario']['Especialidad']; ?>
                                    </p>
                                    <p><span class="fw-bolder">Sección:</span>
                                        <?php echo $_SESSION['usuario']['Seccion']; ?>
                                    </p>
                                    <p><span id="campoTipoBeca" class="fw-bolder">Beca estudiantil:</span>
                                        <?php
                                        if ($_SESSION['usuario']['Becado'] == 1) echo "Completa";
                                        else if ($_SESSION['usuario']['Becado'] == 0) echo "Subvencionada";
                                        ?>
                                    </p>
                                <?php
                                }
                                ?>
                                <p><span class="fw-bolder"><button type="button" class="btn btn-comedor btn-sm" style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="me-2 fa-solid fa-pen"></i>Modificar Contraseña</button></p>
                            </div>
                            <div class="col-12 col-lg-4">
                                <?php
                                if ($_SESSION['usuario']['Perfil'] == 'Estudiante' || $_SESSION['usuario']['Perfil'] == 'Profesor') {
                                ?>
                                    <h4 class="text-center fw-bold py-2 fs-4">Código QR</h4>
                                    <!-- <div id="qr" style="margin-top: -2em;"></div> -->
                                    <img alt="Código QR" class="d-block mx-auto pb-4 img-fluid" id="codigoQR">
                                    <button class="btn btn-comedor w-100" id="btnDescargarQR">
                                        <i class="fa-solid fa-download text-white me-2"></i>
                                        <span>Descargar QR</span>
                                    </button>
                                <?php
                                } else
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Cambiar Contraseña -->
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <?php
        if ($_SESSION['usuario']['Perfil'] == 'Estudiante') {
            ?>
                <form id="manualForm" action="./?dir=admin&controlador=Estudiante&accion=ModificarContrasena" method="post" class="modal-content">
                <?php
            } else if ($_SESSION['usuario']['Perfil'] == 'Profesor') {
                ?>
                    <form id="manualForm" action="./?dir=admin&controlador=Profesor&accion=ModificarContrasena" method="post" class="modal-content">
                    <?php
                } else if ($_SESSION['usuario']['Perfil'] == 'Administrador') {
                    ?>
                        <form id="manualForm" action="./?dir=admin&controlador=Funcionario&accion=ModificarContrasena" method="post" class="modal-content">
                        <?php
                    }
                    if ($_SESSION['usuario']['Perfil'] == 'Cobrador') {
                        ?>
                            <form id="manualForm" action="./?dir=admin&controlador=Funcionario&accion=ModificarContrasena" method="post" class="modal-content">
                            <?php
                        }
                            ?>
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Cambiar contraseña</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body py-0">
                                <input type="text" hidden name="idModificar" value="<?php echo $_SESSION['usuario']['Id'] ?>">
                                <div class="my-3 manual">
                                    <label class="form-label">Nueva contraseña</label>
                                    <input class="form-control" type="password">
                                </div>
                                <div class="my-3 manual">
                                    <label class="form-label">Repita la contraseña</label>
                                    <input class="form-control" type="password" name="contrasenaModificar">
                                </div>
                                <p id="advertencia"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button id="submitButton" type="submit" class="btn btn-comedor" disabled>Guardar cambios
                                </button>
                            </div>
                            </form>
        </div>
    </div>

    <div id="datosBar" hidden data-idusuario='<?php echo $idUsuario ?>' data-perfilusuario='<?php echo $perfilUsuario ?>'></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js" integrity="sha512-pUhApVQtLbnpLtJn6DuzDD5o2xtmLJnJ7oBoMsBnzOkVkpqofGLGPaBJ6ayD2zQe3lCgCibhJBi4cj5wAxwVKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        //Generar y descargar QR
        const codigoQR = document.getElementById('codigoQR');
        const btnDescargarQR = document.getElementById('btnDescargarQR');
        const cedula = document.getElementById('cedula');

        new QRious({
            element: codigoQR,
            value: cedula.textContent,
            size: 240,
            foreground: "#000",
            level: "L", // Puede ser L,M,Q y H (L es el de menor nivel, H el mayor)
        });

        btnDescargarQR.onclick = () => {
            const enlace = document.createElement("a");
            enlace.href = codigoQR.src;
            enlace.download = "CódigoQR";
            enlace.click();
        }

        //validaciones
        let manualInputs = [...document.getElementsByClassName('manual')];
        const advertencia = document.getElementById('advertencia');
        const submitButton = document.getElementById('submitButton');
        manualForm.addEventListener('keyup', (event) => {
            let estadoBoton = false;
            manualInputs.forEach(e => {
                if (manualInputs[0].children[1].value !== manualInputs[1].children[1].value) {
                    estadoBoton = true;
                    manualInputs[0].children[1].classList.add('is-invalid');
                    manualInputs[1].children[1].classList.add('is-invalid');
                    manualInputs[0].children[1].classList.remove('is-valid');
                    manualInputs[1].children[1].classList.remove('is-valid');
                    advertencia.textContent = "Las contraseñas deben ser iguales."
                    advertencia.classList.add('text-danger');
                    advertencia.classList.remove('text-success');
                } else {
                    manualInputs[0].children[1].classList.remove('is-invalid');
                    manualInputs[1].children[1].classList.remove('is-invalid');
                    manualInputs[0].children[1].classList.add('is-valid');
                    manualInputs[1].children[1].classList.add('is-valid');
                    advertencia.textContent = "Contraseña válida."
                    advertencia.classList.add('text-success');
                    advertencia.classList.remove('text-danger');
                }
                if (e.children[1].value == "") {
                    estadoBoton = true;
                    manualInputs[0].children[1].classList.remove('is-valid');
                    manualInputs[1].children[1].classList.remove('is-valid');
                    manualInputs[0].children[1].classList.remove('is-invalid');
                    manualInputs[1].children[1].classList.remove('is-invalid');
                    advertencia.textContent = "";
                }
            });
            submitButton.disabled = estadoBoton;
        })

        //IrAInicio
        const datosBar = document.getElementById('datosBar');
        const perfilUsuario = datosBar.dataset.perfilusuario;
        const idUsuario = datosBar.dataset.idusuario;

        function IrAInicio() {
            location.href = `./?dir=cliente&controlador=ClienteInicio&accion=Index&id=${idUsuario}&perfil=${perfilUsuario}`;
        }
    </script>
    <?php
    if (isset($_REQUEST['alerta'])) {
        $nombreAlerta = $_REQUEST['alerta'];
        if ($nombreAlerta == "success")
            echo "<script>alertify.success('Proceso exitoso');</script>";
        else if ($nombreAlerta == "error")
            echo "<script>alertify.error('Hubo un error');</script>";
    }
    ?>

</body>

</html>
