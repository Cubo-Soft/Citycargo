<?php

include '../clases/conexion.php';

class empleados {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $lista = null;
    private $control = null;

    public function crearEmpleado($datos) {
        try {
            $this->con = new Conexion();
            $this->consulta = "insert into empleados (emp_id,emp_nombres,emp_apellidos,"
                    . "emp_cedula,emp_fechaIngreso,emp_usuario,emp_clave,emp_telefono,emp_correo,"
                    . "emp_direccion,estado,departamento_dep_id,roles_rol_id) "
                    . "values (null,'" . $datos["nombresEmpleado"] . "','" . $datos["apellidosEmpleado"] . "'"
                    . ",'" . $datos["cedulaEmpleado"] . "','" . $datos["fechaIngreso"] . "','" . $datos["usuarioEmpleado"] . "'"
                    . ",'" . $datos["claveEmpleado"] . "','" . $datos["telefonoEmpleado"] . "','" . $datos["correoEmpleado"] . "','" . $datos["direccionEmpleado"] . "'"
                    . ",'" . $datos["estadoEmpleado"] . "','" . $datos["departamentos"] . "','" . $datos["roles"] . "');";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function modificarEmpleado($datos) {
        try {
            $this->con = new Conexion();
            $this->consulta = "update empleados set "
                    . "emp_nombres='" . $datos["nombresEmpleado"] . "',"
                    . "emp_apellidos='" . $datos["apellidosEmpleado"] . "',"
                    . "emp_fechaIngreso='" . $datos["fechaIngreso"] . "',"
                    . "emp_usuario='" . $datos["usuarioEmpleado"] . "',"
                    . "emp_clave='" . $datos["claveEmpleado"] . "',"
                    . "emp_telefono='" . $datos["telefonoEmpleado"] . "',"
                    . "emp_correo='" . $datos["correoEmpleado"] . "',"
                    . "emp_direccion='" . $datos["direccionEmpleado"] . "',"
                    . "estado='" . $datos["estadoEmpleado"] . "',"
                    . "departamento_dep_id='" . $datos["departamentos"] . "',"
                    . "roles_rol_id='" . $datos["roles"] . "' "
                    . "where emp_id=" . $datos["emp_id"] . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarDepartamentos() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * "
                    . "from departamento;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarRoles() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * "
                    . "from roles;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarDatosEmpleado($cedula) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * "
                    . "from empleados "
                    . "where emp_cedula='" . $cedula . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarEmpleados() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select *, concat(empleados.emp_nombres,' ',empleados.emp_apellidos) as nombreCompleto "
                    . "from empleados "
                    . "where estado='A' "
                    . "order by emp_nombres asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarAsesores() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select concat(emp_nombres,' ',emp_apellidos) as nombreAsesor,emp_cedula "
                    . "from empleados "
                    . "where departamento_dep_id=5 "
                    . "and roles_rol_id=8 "
                    . "and estado='A';";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
