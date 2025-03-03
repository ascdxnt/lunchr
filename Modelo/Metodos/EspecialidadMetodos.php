<?php

class EspecialidadMetodos
{
    function Buscar($id)
    {
        $especialidad = new Especialidad();
        $conexion = new Conexion();

        $sql = "SELECT *  FROM `ESPECIALIDAD` WHERE `ID` = '$id'";
        $resultado = $conexion->Ejecutar($sql);

        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $especialidad->setId($fila["ID"]);
                $especialidad->setDescripcion($fila["DESCRIPCION"]);
                $especialidad->setEstado($fila["ESTADO"]);
            }
        } else {
            $especialidad = null;
        }
        $conexion->Cerrar();
        return $especialidad;
    }

    function BuscarPorDescripcion($descripcion)
    {
        $especialidad = new Especialidad();
        $conexion = new Conexion();

        $sql = "SELECT * FROM `ESPECIALIDAD` WHERE `DESCRIPCION` = '$descripcion'";
        $resultado = $conexion->Ejecutar($sql);

        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $especialidad->setId($fila["ID"]);
                $especialidad->setDescripcion($fila["DESCRIPCION"]);
                $especialidad->setEstado($fila["ESTADO"]);
            }
        } else {
            $especialidad = null;
        }
        $conexion->Cerrar();
        return $especialidad;
    }

    function BuscarTodos()
    {
        $todosEspecialidades = array();
        $conexion = new Conexion();

        $sql = "SELECT * FROM `ESPECIALIDAD`";
        $resultado = $conexion->Ejecutar($sql);
        if (mysqli_num_rows($resultado) > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $especialidad = new Especialidad();
                $especialidad->setId($fila["ID"]);
                $especialidad->setDescripcion($fila["DESCRIPCION"]);
                $especialidad->setEstado($fila["ESTADO"]);
                $todosEspecialidades[] = $especialidad;
            }
        } else {
            $todosEspecialidades = null;
        }
        $conexion->Cerrar();
        return $todosEspecialidades;
    }

    function Crear(Especialidad $especialidad)
    {
        $est = false;
        $conexion = new Conexion();

        $sql = "INSERT INTO `ESPECIALIDAD`(`DESCRIPCION`,`ESTADO`)
                    VALUES('" . $especialidad->getDescripcion() . "',
                            '" . $especialidad->getEstado() . "')";
        if ($conexion->Ejecutar($sql)) {
            $est = true;
        }
        $conexion->Cerrar();
        return $est;
    }

    function Modificar(Especialidad $especialidad)
    {
        $estado = false;
        $conexion = new Conexion();

        $sql = "UPDATE ESPECIALIDAD SET  DESCRIPCION='" . $especialidad->getDescripcion() . "',
                                        ESTADO='" . $especialidad->getEstado() . "'
                                        Where `ID` =" . $especialidad->getId();
        if ($conexion->Ejecutar($sql)) {
            $estado = true;
        }
        $conexion->Cerrar();
        return $estado;
    }
}
