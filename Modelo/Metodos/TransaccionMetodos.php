<?php

class TransaccionMetodos
{
    function BuscarTodosEstudiante($idEstudiante)
    {
        $todosTransaccions = array();
        $conexion = new Conexion();

        $sql = "SELECT * FROM `TRANSACCION`  WHERE `IDESTUDIANTE` = '$idEstudiante' ORDER BY `FECHA` DESC";
        $resultado = $conexion->Ejecutar($sql);
        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $transaccion = new Transaccion();
                $transaccion->setId($fila["ID"]);
                $transaccion->setIdEstudiante($fila["IDESTUDIANTE"]);
                $transaccion->setIdProfesor($fila["IDPROFESOR"]);
                $transaccion->setFecha($fila["FECHA"]);
                $transaccion->setHora($fila["HORA"]);
                $transaccion->setComidas($fila["COMIDAS"]);
                $transaccion->setEstado($fila["ESTADO"]);
                $todosTransaccions[] = $transaccion;
            }
        } else {
            $todosTransaccions = null;
        }
        $conexion->Cerrar();
        return $todosTransaccions;
    }

    function BuscarTodosProfesor($idProfesor)
    {
        $todosTransaccions = array();
        $conexion = new Conexion();

        $sql = "SELECT * FROM `TRANSACCION`  WHERE `IDPROFESOR` = '$idProfesor' ORDER BY `FECHA` DESC";
        $resultado = $conexion->Ejecutar($sql);
        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $transaccion = new Transaccion();
                $transaccion->setId($fila["ID"]);
                $transaccion->setIdEstudiante($fila["IDESTUDIANTE"]);
                $transaccion->setIdProfesor($fila["IDPROFESOR"]);
                $transaccion->setFecha($fila["FECHA"]);
                $transaccion->setHora($fila["HORA"]);
                $transaccion->setComidas($fila["COMIDAS"]);
                $transaccion->setEstado($fila["ESTADO"]);
                $todosTransaccions[] = $transaccion;
            }
        } else {
            $todosTransaccions = null;
        }
        $conexion->Cerrar();
        return $todosTransaccions;
    }

    function BuscarEstudiante($idEstudiante)
    {
        $transaccion = new Transaccion();
        $todosTransaccions = array();
        $conexion = new Conexion();

        $sql = "SELECT ID, IDESTUDIANTE, FECHA, HORA, COMIDAS, ESTADO   FROM `TRANSACCION` WHERE `IDESTUDIANTE` = '$idEstudiante'";
        $resultado = $conexion->Ejecutar($sql);

        if (mysqli_num_rows($resultado) > 0)
        {
            while ($fila = $resultado->fetch_assoc()) {
                $transaccion->setId($fila["ID"]);
                $transaccion->setIdEstudiante($fila["IDESTUDIANTE"]);
                $transaccion->setFecha($fila["FECHA"]);
                $transaccion->setHora($fila["HORA"]);
                $transaccion->setComidas($fila["COMIDAS"]);
                $transaccion->setEstado($fila["ESTADO"]);
                $todosTransaccions[] = $transaccion;
            }
        } else {
            $todosTransaccions = null;
        }
        $conexion->Cerrar();
        return $todosTransaccions;
    }
    function Buscar($id)
    {
        $transaccion = new Transaccion();
        $conexion = new Conexion();

        $sql = "SELECT *  FROM `TRANSACCION` WHERE `ID` = '$id'";
        $resultado = $conexion->Ejecutar($sql);

        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $transaccion->setId($fila["ID"]);
                $transaccion->setIdEstudiante($fila["IDESTUDIANTE"]);
                $transaccion->setIdProfesor($fila["IDPROFESOR"]);
                $transaccion->setFecha($fila["FECHA"]);
                $transaccion->setHora($fila["HORA"]);
                $transaccion->setComidas($fila["COMIDAS"]);
                $transaccion->setEstado($fila["ESTADO"]);
            }
        } else {
            $transaccion = null;
        }
        $conexion->Cerrar();
        return $transaccion;
    }

    function BuscarTodos()
    {
        $todosTransaccions = array();
        $conexion = new Conexion();

        $sql = "SELECT * FROM `TRANSACCION`";
        $resultado = $conexion->Ejecutar($sql);
        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $transaccion = new Transaccion();
                $transaccion->setId($fila["ID"]);
                $transaccion->setIdEstudiante($fila["IDESTUDIANTE"]);
                $transaccion->setIdProfesor($fila["IDPROFESOR"]);
                $transaccion->setFecha($fila["FECHA"]);
                $transaccion->setHora($fila["HORA"]);
                $transaccion->setComidas($fila["COMIDAS"]);
                $transaccion->setEstado($fila["ESTADO"]);
                $todosTransaccions[] = $transaccion;
            }
        } else {
            $todosTransaccions = null;
        }
        $conexion->Cerrar();
        return $todosTransaccions;
    }

    function Crear(Transaccion $transaccion)
    {
        $est = false;
        $conexion = new Conexion();

        $sql = "INSERT INTO `TRANSACCION`(`IDESTUDIANTE`, `IDPROFESOR`,`FECHA`,`HORA`,`COMIDAS`,`ESTADO`)
            VALUES('" . $transaccion->getIdEstudiante() . "',
                '" . $transaccion->getIdProfesor() . "',
                '" . $transaccion->getFecha() . "',
                '" . $transaccion->getHora() . "',
                '" . $transaccion->getComidas() . "',
                '" . $transaccion->getEstado() . "')";
        if ($conexion->Ejecutar($sql)) {
            $est = true;
        }
        $conexion->Cerrar();
        return $est;
    }

    function Modificar(Transaccion $transaccion)
    {
        $estado = false;
        $conexion = new Conexion();

        $sql = "UPDATE TRANSACCION SET  IDESTUDIANTE='" . $transaccion->getIdEstudiante() . "',
            IDPROFESOR='" . $transaccion->getIdProfesor() . "',
            FECHA='" . $transaccion->getFecha() . "',
            HORA='" . $transaccion->getHora() . "',
            COMIDAS='" . $transaccion->getComidas() . "',
            ESTADO='" . $transaccion->getEstado() . "'
            Where `ID` =" . $transaccion->getId();
        if ($conexion->Ejecutar($sql)) {
            $estado = true;
        }
        $conexion->Cerrar();
        return $estado;
    }
}
