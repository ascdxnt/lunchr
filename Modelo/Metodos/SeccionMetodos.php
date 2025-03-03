<?php

class SeccionMetodos
{
    function Buscar($id)
    {
        $seccion = new Seccion();
        $conexion = new Conexion();

        $sql = "SELECT *  FROM `SECCION` WHERE `ID` = '$id'";
        $resultado = $conexion->Ejecutar($sql);

        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $seccion->setId($fila["ID"]);
                $seccion->setDescripcion($fila["DESCRIPCION"]);
                $seccion->setEstado($fila["ESTADO"]);
            }
        } else {
            $seccion = null;
        }
        $conexion->Cerrar();
        return $seccion;
    }

    function BuscarPorDescripcion($descripcion)
    {
        $seccion = new Seccion();
        $conexion = new Conexion();

        $sql = "SELECT *  FROM `SECCION` WHERE `DESCRIPCION` = '$descripcion'";
        $resultado = $conexion->Ejecutar($sql);

        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $seccion->setId($fila["ID"]);
                $seccion->setDescripcion($fila["DESCRIPCION"]);
                $seccion->setEstado($fila["ESTADO"]);
            }
        } else {
            $seccion = null;
        }
        $conexion->Cerrar();
        return $seccion;
    }

    function BuscarTodos()
    {
        $todosSecciones = array();
        $conexion = new Conexion();

        $sql = "SELECT * FROM `SECCION`";
        $resultado = $conexion->Ejecutar($sql);
        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $seccion = new Seccion();
                $seccion->setId($fila["ID"]);
                $seccion->setDescripcion($fila["DESCRIPCION"]);
                $seccion->setEstado($fila["ESTADO"]);
                $todosSecciones[] = $seccion;
            }
        } else {
            $todosSecciones = null;
        }
        $conexion->Cerrar();
        return $todosSecciones;
    }

    function Crear(Seccion $seccion)
    {
        $est = false;
        $conexion = new Conexion();

        $sql = "INSERT INTO `SECCION`(`DESCRIPCION`,`ESTADO`)
                    VALUES('" . $seccion->getDescripcion() . "',
                            '" . $seccion->getEstado() . "')";
        if ($conexion->Ejecutar($sql)) {
            $est = true;
        }
        $conexion->Cerrar();
        return $est;
    }

    function Modificar(Seccion $seccion)
    {
        $estado = false;
        $conexion = new Conexion();

        $sql = "UPDATE SECCION SET  DESCRIPCION='" . $seccion->getDescripcion() . "',
                                        ESTADO='" . $seccion->getEstado() . "'
                                        Where `ID` =" . $seccion->getId();
        if ($conexion->Ejecutar($sql)) {
            $estado = true;
        }
        $conexion->Cerrar();
        return $estado;
    }
}
