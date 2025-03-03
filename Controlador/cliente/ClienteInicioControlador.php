<?php
require_once './Modelo/Conexion.php';
require_once './Modelo/Entidades/Transaccion.php';
require_once './Modelo/Metodos/TransaccionMetodos.php';
require_once './Modelo/Entidades/Estudiante.php';
require_once './Modelo/Metodos/EstudianteMetodos.php';
require_once './Modelo/Entidades/Profesor.php';
require_once './Modelo/Metodos/ProfesorMetodos.php';

class ClienteInicioControlador
{
    public function Index($idUsuario)
    {
        $transaccionMetodos = new TransaccionMetodos();
        $perfil = $_REQUEST['perfil'];
        if ($perfil == "Profesor") {
            $transaccionProfesores = $transaccionMetodos->BuscarTodosProfesor($idUsuario);
            if ($transaccionProfesores != null) {
                $transacciones = array();

                for ($i = 0; $i < sizeof($transaccionProfesores); $i++) {
                    if (!$transaccionProfesores[$i]->getEstado() == 1) continue;

                    $transacciones[$i] = array("comidas" => $transaccionProfesores[$i]->getComidas());
                    $transacciones[$i] += array("fecha" => $transaccionProfesores[$i]->getFecha());
                    $transacciones[$i] += array("hora" => $transaccionProfesores[$i]->getHora());
                }
            }
        } else if ($perfil == "Estudiante") {
            $transaccionEstudiantes = $transaccionMetodos->BuscarTodosEstudiante($idUsuario);
            if ($transaccionEstudiantes != null) {
                $transacciones = array();
                for ($i = 0; $i < sizeof($transaccionEstudiantes); $i++) {
                    if (!$transaccionEstudiantes[$i]->getEstado() == 1) continue;

                    $transacciones[$i] = array("comidas" => $transaccionEstudiantes[$i]->getComidas());
                    $transacciones[$i] += array("fecha" => $transaccionEstudiantes[$i]->getFecha());
                    $transacciones[$i] += array("hora" => $transaccionEstudiantes[$i]->getHora());
                }
            }
        }

        require_once "./Vista/views/cliente/Inicio.php";
    }
}
