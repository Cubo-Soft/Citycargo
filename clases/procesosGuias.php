<?php

/*
 * Trabaja todo lo que tiene que ver con las guias
 */

/**
 * Description of procesosGuias
 *
 * @author Wilmer P. Silva
 * date 20161004 13:19
 */
include '../clases/conexion.php';

class procesosGuias {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;

    public function retornarUltimaGuia() {
        try {
            $this->con = new Conexion();
            $this->consulta = "Select numeroGuia from guias order by id_guia DESC LIMIT 1";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetch(PDO::FETCH_ASSOC);
            $this->con = null;
            echo json_encode($this->arreglo);
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /*
     * Crea las guias 
     */

    public function ingresarGuias($documento, $numeroGuia, $nombreEmpresa) {
        try {
            $this->con = new Conexion();
            $this->consulta = "insert into guias (id_guia,identificacion,nombre,fechaHora,numeroGuia,usada,pagada,observaciones) "
                    . "values (null," . $documento . ",'" . $nombreEmpresa . "','" . date("Y-m-d") . "'," . $numeroGuia . ",'1000-01-01','1000-01-01','');";            
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                return 1;
            } else {
                return 0;
            }
            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /*
     * Busca si una guia ya fue creada y retorna una S en caso afirmativo
     */

    public function retornarGuiaExistente($numeroGuia) {
        try {
            $control = null;
            $this->con = new Conexion();
            $this->consulta = "select * from guias where numeroGuia=" . $numeroGuia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $arregloUno = $this->prepare->fetch(PDO::FETCH_ASSOC);

            if ($arregloUno == false) {
                echo json_encode($arregloUno);
                $control = 0;
                exit();
            } else {
                $documento = $arregloUno["identificacion"];
                $control = 1;
            }

            if ($control == 1) {
                $this->consulta = "select cond_nombres,cond_apellidos from conductores where cond_identificacion=" . $documento . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $arregloDos = $this->prepare->fetch(PDO::FETCH_ASSOC);

                if ($arregloDos == false) {
                    $control = 2;
                }
            }

            if ($control == 2) {
                $this->consulta = "select cli_nombre from cliente where cli_documento=" . $documento . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $arregloDos = $this->prepare->fetch(PDO::FETCH_ASSOC);
                if ($arregloDos == false) {
                    $control = 3;
                }
            }

            if ($control == 3) {
                $this->consulta = "select emp_nombres,emp_apellidos from empleados where emp_cedula=" . $documento . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $arregloDos = $this->prepare->fetch(PDO::FETCH_ASSOC);
            }

            $this->con = null;
            $this->arreglo = array_merge($arregloDos, $arregloUno);
            //return json_encode($control);
            echo json_encode($this->arreglo);
            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarGuiasPendientes($valores) {
        try {
            $this->con = new Conexion();

            if ($valores["ordenarPor"] === '1' && $valores["condicion"] === '1') {
                $this->consulta = "select * "
                        . "from guias "
                        . "where identificacion=" . $valores["documento"] . " "
                        . "and fechaHora<='" . $valores["fechaFinal"] . "' "
                        . "and fechaHora>='" . $valores["fechaInicial"] . "' "
                        . "and usada<>'1000-01-01';";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
            }

            if ($valores["ordenarPor"] === '1' && $valores["condicion"] === '2') {
                $this->consulta = "select * "
                        . "from guias "
                        . "where identificacion=" . $valores["documento"] . " "
                        . "and fechaHora<='" . $valores["fechaFinal"] . "' "
                        . "and fechaHora>='" . $valores["fechaInicial"] . "' "
                        . "and usada='1000-01-01';";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
            }

            if ($valores["ordenarPor"] === '1' && $valores["condicion"] === '3') {
                $this->consulta = "select * "
                        . "from guias "
                        . "where identificacion=" . $valores["documento"] . " "
                        . "and fechaHora<='" . $valores["fechaFinal"] . "' "
                        . "and fechaHora>='" . $valores["fechaInicial"] . "';";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
            }

            if ($valores["ordenarPor"] === '2' && $valores["condicion"] === '1') {
                $this->consulta = "select count(id_guia) as totalGuias "
                        . "from guias "
                        . "where identificacion=" . $valores["documento"] . " "
                        . "and fechaHora<='" . $valores["fechaFinal"] . "' "
                        . "and fechaHora>='" . $valores["fechaInicial"] . "' "
                        . "and usada<>'1000-01-01';";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
            }

            if ($valores["ordenarPor"] === '2' && $valores["condicion"] === '2') {
                $this->consulta = "select count(id_guia) as totalGuias "
                        . "from guias "
                        . "where identificacion=" . $valores["documento"] . " "
                        . "and fechaHora<='" . $valores["fechaFinal"] . "' "
                        . "and fechaHora>='" . $valores["fechaInicial"] . "' "
                        . "and usada='1000-01-01';";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
            }

            if ($valores["ordenarPor"] === '2' && $valores["condicion"] === '3') {
                $this->consulta = "SELECT count(usada) as usada,fechaHora "
                        . "FROM guias "
                        . "where usada<>'1000-01-01' "
                        . "and identificacion=".$valores["documento"]." "
                        . "GROUP BY fechaHora;";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo[0] = $this->prepare->fetchAll();
                
                $this->consulta = "SELECT count(usada) as porUsar,fechaHora "
                        . "FROM guias "
                        . "where usada='1000-01-01' "
                        . "and identificacion=".$valores["documento"]." "
                        . "GROUP BY fechaHora;";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo[1] = $this->prepare->fetchAll();                
            }


            $this->con = null;
            echo json_encode($this->arreglo);
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarGuias($identificacion, $guia) {
        try {
            $nombre = $this->retornarNombre($identificacion);
            $this->con = new Conexion();
            $this->consulta = "update guias set identificacion=" . $identificacion . ", nombre='" . $nombre . "' where id_guia=" . $guia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarNombre($identificacion) {
        try {
            $control = 0;
            $this->con = new Conexion();

            $this->consulta = "select concat(cond_nombres,' ',cond_apellidos) as nombre from conductores where cond_identificacion=" . $identificacion . " and estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $arreglo = $this->prepare->fetch(PDO::FETCH_ASSOC);

            if ($arreglo == false) {
                $control = 1;
            }

            if ($control == 1) {

                $this->consulta = "select concat(emp_nombres,' ',emp_apellidos) as nombre from empleados where emp_cedula=" . $identificacion . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $arreglo = $this->prepare->fetch(PDO::FETCH_ASSOC);
            }

            if ($arreglo == false) {
                $control = 2;
            }

            if ($control == 2) {
                $this->consulta = "select cli_nombre as nombre from cliente where cli_documento=" . $identificacion . " and estado='ACTIVO';";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $arreglo = $this->prepare->fetch(PDO::FETCH_ASSOC);
            }
            $this->con = null;

            return $arreglo["nombre"];
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function verificarGuiaServicio($guia, $idempleado) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select s.idservicio,s.fechaServicio,sg.numeroGuia "
                    . "from servicio as s,servicio_guias as sg "
                    . "where s.idservicio=sg.idservicio "
                    . "and sg.numeroGuia=" . $guia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            if (count($this->arreglo) === 0) {
                $this->arreglo = false;
            }

            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarMensajeBorrado($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "update serviciosporcancelar "
                    . "set estado='MANTENIDO' "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
