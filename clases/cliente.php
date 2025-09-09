<?php

include '../clases/conexion.php';

class cliente {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $retorno;

    public function retornarDatosCliente($identificacion) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select c.cli_id,c.cli_nombre,c.cli_contacto,c.cli_direccion,c.cli_correo,"
                    . "c.cli_telefono,c.cli_objeto,c.estado,m.mun_nombre,m.mun_id "
                    . "from cliente as c,municipios as m "
                    . "where c.cli_id_municipio=m.mun_id "
                    . "and c.cli_documento='" . $identificacion . "' "
                    . "and c.estado<>'DUPLICADO';";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $cli_id = $this->arreglo[0]["cli_id"];
            $this->retorno["empresa"] = $this->arreglo;
            $this->consulta = "select cedula from asesor_empresa where nit=" . $identificacion . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->retorno["asesor"] = $this->arreglo;            
            $this->con = null;
            return $this->retorno;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function modificarDatosCliente($cli_id, $municipio, $nit, $nombreCliente, $contacto, $direccion, $correo, $telefonoUno, $objeto, $cedulaAsesor, $estadoCliente) {
        try {
            $this->con = new Conexion();

            $this->consulta = "select cli_documento "
                    . "from cliente "
                    . "where cli_id=" . $cli_id . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $nitAnterior = $this->arreglo[0]["cli_documento"];

            $this->consulta = "update direcciones set documento=" . $nit . " where documento=" . $nitAnterior . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "update cliente "
                    . "set cli_nombre='" . $nombreCliente . "',cli_contacto='" . $contacto . "',cli_direccion='" . $direccion . "',cli_correo='" . $correo . "'"
                    . ",cli_documento='" . $nit . "',cli_id_municipio=".$municipio.",cli_telefono='" . $telefonoUno . "',cli_objeto='" . $objeto . "',estado='" . $estadoCliente . "' "
                    . "where cli_id=" . $cli_id . ";";                
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->retorno["estado"] = 1;
            }

            $this->consulta = "select id "
                    . "from asesor_empresa "
                    . "where nit=" . $nit . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            if (count($this->arreglo) > 0) {
                $id = $this->arreglo[0]["id"];
                $this->consulta = "update asesor_empresa "
                        . "set cedula=" . $cedulaAsesor . ",nit=" . $nit . " "
                        . "where id=" . $id . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $this->retorno["estado"] += 1;
                }
            } else {
                $this->consulta = "insert into asesor_empresa (id,cedula,nit) values (null," . $cedulaAsesor . "," . $nit . ")";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $this->retorno["estado"] += 1;
                }
            }

            /*
             * Modificar las tablas donde se encuentra el nit viejo
             */
            $this->consulta = "update servicio_guias "
                    . "set Nit=" . $nit . " "
                    . "where Nit=" . $nitAnterior . ";";            
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->retorno["estado"] += 1;
            }
            
            $this->consulta = "update asesor_empresa "
                    . "set nit=" . $nit . " "
                    . "where nit=" . $nitAnterior . ";";            
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->retorno["estado"] += 1;
            }
            
            $this->consulta = "update direcciones "
                    . "set documento=" . $nit . " "
                    . "where documento=" . $nitAnterior . ";";            
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->retorno["estado"] += 1;
            }
            
            $this->consulta = "update posiblesanticipos "
                    . "set nitempresa=" . $nit . " "
                    . "where nitempresa=" . $nitAnterior . ";";            
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->retorno["estado"] += 1;
            }
            
            $this->consulta = "update posiblesfacturas "
                    . "set nit=" . $nit . " "
                    . "where nit=" . $nitAnterior . ";";            
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->retorno["estado"] += 1;
            }
            
            $this->consulta = "update serviciosborrados "
                    . "set idcliente=" . $nit . " "
                    . "where idcliente=" . $nitAnterior . ";";            
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->retorno["estado"] += 1;
            }

            $this->con = null;
            return $this->retorno;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarNombreEmpresa($nit) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select cli_nombre from cliente where cli_documento=" . $nit . " and estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetch(PDO::FETCH_ASSOC);
            $this->con = null;
            echo json_encode($this->arreglo);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarAsesores() {
        try {
            $this->con = new Conexion();
            $this->retorno = array();
            $this->consulta = "select concat(emp_nombres,' ',emp_apellidos) as nombreAsesor,emp_cedula "
                    . "from empleados "
                    . "where departamento_dep_id=5 "
                    . "and roles_rol_id=8 "
                    . "and estado='A';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            array_push($this->retorno, $this->arreglo);
            $this->consulta = "select concat(emp_nombres,' ',emp_apellidos) as nombreAsesor,emp_cedula "
                    . "from empleados "
                    . "where departamento_dep_id=5 "
                    . "and roles_rol_id=7 "
                    . "and estado='A';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            array_push($this->retorno, $this->arreglo);
            //var_dump($this->retorno);
            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarMunicipios() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * from municipios order by mun_nombre asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function crearEmpresa($nit, $nombreCliente, $contacto, $direccion, $correo, $telefonoUno, $objeto, $cedulaAsesor, $municipio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * "
                    . "from cliente "
                    . "where cli_documento=" . $nit . ";";                        
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();            
            
            if (count($this->arreglo)=== 0) {
                $this->consulta = "insert into cliente (cli_id,cli_nombre,cli_contacto,cli_direccion,cli_correo,"
                        . "cli_documento,cli_id_municipio,cli_telefono,cli_objeto,estado) "
                        . "values (null,'" . $nombreCliente . "','" . $contacto . "','" . $direccion . "','" . $correo . "',"
                        . "'" . $nit . "','".$municipio."','" . $telefonoUno . "','" . $objeto . "','ACTIVO');";                
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $this->retorno = 1;
                }
                $this->consulta = "insert into asesor_empresa (id,cedula,nit) "
                        . "values (null," . $cedulaAsesor . "," . $nit . ")";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $this->retorno += 1;
                }                
                return $this->retorno;
            } else {
                return 0;
            }
            $this->con = null;
        } catch (PDOException $ex) {
            echo $ex->getTraceAsString();
        }
    }

    public function retornarClientes() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select cli_documento,cli_nombre "
                    . "from cliente "
                    . "where estado='ACTIVO' "
                    . "and cli_documento>100 "
                    . "order by cli_nombre asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $ex) {
            echo $ex->getTraceAsString();
        }
    }

}
