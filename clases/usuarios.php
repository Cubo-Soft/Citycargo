<?php

include '../clases/conexion.php';

class usuarios {

    private $conexion;
    private $prepare;
    private $arreglo;
    private $consulta;

    public function retornarNombresEmpleados() {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select emp_nombres,emp_apellidos,emp_cedula from "
                    . "empleados where estado='A' and (departamento_dep_id=5 or departamento_dep_id=3) "
                    //. "empleados where estado='A' "
                    . "order by emp_nombres asc;";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if ($this->arreglo <> null) {
                return $this->arreglo;
            } else {
                return "error";
            }
            $this->conexion = null;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    /*
     * 20170630 Se necesita que a conductores,empresas y personas se les pueda ingresar
     * guias.
     * Función retornarConductoresPropietarios
     * Función retornarEmpresas
     */

    public function retornarConductoresPropietarios() {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select cond_identificacion,cond_nombres,cond_apellidos "
                    . "from conductores "
                    . "where estado='ACTIVO' and cond_identificacion > 46 "
                    . "and cond_identificacion <> 79725743 and cond_nombres <> 'prueba' "
                    . "order by cond_nombres asc;";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if ($this->arreglo <> null) {
                return $this->arreglo;
            } else {
                return "error";
            }
            $this->conexion = null;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function retornarEmpresas() {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select cli_nombre,cli_documento from cliente "
                    . "where estado='ACTIVO' and cli_documento > 100 "
                    . "order by cli_nombre asc;";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if ($this->arreglo <> null) {
                return $this->arreglo;
            } else {
                return "error";
            }
            $this->conexion = null;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function validarUsuario($usuario, $clave) {
        try {
            $this->consulta = "select empleados.emp_cedula,empleados.emp_id,empleados.emp_nombres,"
                    . "empleados.emp_apellidos,roles.rol_nombre,roles.rol_id,departamento.dep_nombre,empleados.estado "
                    . "from empleados,roles,departamento "
                    . "where empleados.roles_rol_id=roles.rol_id "
                    . "and empleados.departamento_dep_id=departamento.dep_id "
                    . "and empleados.emp_usuario='" . $usuario . "' and empleados.emp_clave='" . $clave . "';";
            //echo $this->consulta;
            $this->conexion = new Conexion();
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if ($this->arreglo <> null) {
                return $this->arreglo;
            } else {
                return "error";
            }
            $this->conexion = null;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function retornarImpuestos($año) {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select valoresimpuestos.valimp_valor,valoresimpuestos.base,impuestos.imp_nombre "
                    . "from impuestos,valoresimpuestos "
                    . "where valoresimpuestos.valimp_imp_id=impuestos.imp_id "
                    . "and valoresimpuestos.valimp_ano = " . $año . ";";

            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function registrarIngreso($cedula, $fechaHora) {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "insert into ingresos values (null," . $cedula . ",'" . $fechaHora . "','0000-00-00 00:00:00');";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->prepare = $this->conexion->lastInsertId();
            $this->conexion = null;
            return $this->prepare;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function registrarSalida($id, $fechaHora) {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "update ingresos set fechaHoraSalida='" . $fechaHora . "' where id=" . $id . ";";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->conexion = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /*
     * Esta funcion esta en la clase empleados
     * retorna nombreEmpleado en usuarios
     * retorna nombreAsesor
     */

    public function retornarAsesores($opcion) {
        $arregloDos = array();
        $arregleTres = array();
        try {
            $this->conexion = new Conexion();

            if ($opcion === 1) {
                $this->consulta = "select concat(e.emp_nombres,' ',e.emp_apellidos) as nombreEmpleado, e.emp_cedula "
                        . "from empleados as e "
                        . "where e.roles_rol_id=8 "
                        . "and e.estado='A' "
                        . "order by nombreEmpleado asc;";
                $this->prepare = $this->conexion->prepare($this->consulta);
                $this->prepare->execute();
                $arregloDos = $this->prepare->fetchAll();

                $this->consulta = "select concat(e.emp_nombres,' ',e.emp_apellidos) as nombreEmpleado, e.emp_cedula "
                        . "from empleados as e "
                        . "where e.roles_rol_id=7 "
                        . "and e.estado='A' "
                        . "order by nombreEmpleado asc;";
                $this->prepare = $this->conexion->prepare($this->consulta);
                $this->prepare->execute();
                $arregleTres = $this->prepare->fetchAll();
                $this->arreglo = array_merge($arregloDos, $arregleTres);
            }
            
            if($opcion===2){
                
                 $this->consulta = "select concat(e.emp_nombres,' ',e.emp_apellidos) as nombreEmpleado, e.emp_cedula "
                        . "from empleados as e "
                        . "where e.roles_rol_id=11 "
                        . "and e.estado='A' "
                        . "order by nombreEmpleado asc;";
                $this->prepare = $this->conexion->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
            }


            $this->conexion = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }
}
