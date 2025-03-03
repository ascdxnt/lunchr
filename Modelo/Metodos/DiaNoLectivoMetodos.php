<?php

class DiaNoLectivoMetodos
{
    function Buscar($id)
    {
        $diaNoLectivo = new DiaNoLectivo();
        $conexion = new Conexion();

        $sql = "SELECT *  FROM `DIANOLECTIVO` WHERE `ID` = '$id'";
        $resultado = $conexion->Ejecutar($sql);

        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $diaNoLectivo->setId($fila["ID"]);
                $diaNoLectivo->setNombre($fila["NOMBRE"]);
                $diaNoLectivo->setFecha($fila["FECHA"]);
                $diaNoLectivo->setEstado($fila["ESTADO"]);
            }
        } else {
            $diaNoLectivo = null;
        }
        $conexion->Cerrar();
        return $diaNoLectivo;
    }

    function BuscarPorFecha($fecha)
    {
        $diaNoLectivo = new DiaNoLectivo();
        $conexion = new Conexion();

        $sql = "SELECT * FROM `DIANOLECTIVO` WHERE `FECHA` = '$fecha'";
        $resultado = $conexion->Ejecutar($sql);

        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $diaNoLectivo->setId($fila["ID"]);
                $diaNoLectivo->setNombre($fila["NOMBRE"]);
                $diaNoLectivo->setFecha($fila["FECHA"]);
                $diaNoLectivo->setEstado($fila["ESTADO"]);
            }
        } else {
            $diaNoLectivo = null;
        }
        $conexion->Cerrar();
        return $diaNoLectivo;
    }

    function BuscarTodos()
    {
        $diasNoLectivos = array();
        $conexion = new Conexion();

        $sql = "SELECT * FROM `DIANOLECTIVO`";
        $resultado = $conexion->Ejecutar($sql);
        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $diaNoLectivo = new DiaNoLectivo();
                $diaNoLectivo->setId($fila["ID"]);
                $diaNoLectivo->setNombre($fila["NOMBRE"]);
                $diaNoLectivo->setFecha($fila["FECHA"]);
                $diaNoLectivo->setEstado($fila["ESTADO"]);
                $diasNoLectivos[] = $diaNoLectivo;
            }
        } else {
            $diasNoLectivos = null;
        }
        $conexion->Cerrar();
        return $diasNoLectivos;
    }

    function Crear(DiaNoLectivo $diaNoLectivo)
    {
        $est = false;
        $conexion = new Conexion();

        $sql = "INSERT INTO `DIANOLECTIVO`(`FECHA`,`NOMBRE`,`ESTADO`)
                    VALUES('" . $diaNoLectivo->getFecha() . "',
														'" . $diaNoLectivo->getNombre() . "',
                            '" . $diaNoLectivo->getEstado() . "')";
        if ($conexion->Ejecutar($sql)) {
            $est = true;
        }
        $conexion->Cerrar();
        return $est;
    }

    function Modificar(DiaNoLectivo $diaNoLectivo)
    {
        $estado = false;
        $conexion = new Conexion();

        $sql = "UPDATE DIANOLECTIVO SET NOMBRE='" . $diaNoLectivo->getNombre() . "',
                                        ESTADO='" . $diaNoLectivo->getEstado() . "',
                                        FECHA='" . $diaNoLectivo->getFecha() . "'
                                        Where `ID` =" . $diaNoLectivo->getId();
        if ($conexion->Ejecutar($sql)) {
            $estado = true;
        }
        $conexion->Cerrar();
        return $estado;
    }


    function Eliminar(DiaNoLectivo $diaNoLectivo)
    {
        $estado = false;
        $conexion = new Conexion();

        $sql = "UPDATE DIANOLECTIVO SET  ESTADO= 0
                                        WHERE `ID` =" . $diaNoLectivo->getId();
        if ($conexion->Ejecutar($sql)) {
            $estado = true;
        }
        $conexion->Cerrar();
        return $estado;
    }
}
