<?php

include '../clases/conexion.php';

class conductores {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $lista = null;
    private $control = null;
    private $placa;
    private $cedula;
    private $nombre;

    public function retornarDatosPropietariosConductores($placa) {
        try {
            $this->con = new Conexion();
            if ($placa === '0') {
                $this->consulta = "select distinct c.cond_nombres,c.email,c.cond_apellidos,c.cond_telefono,"
                        . "cv.placa,cv.fecha,v.tipovehiculo,(select rol_nombre from roles where rol_id=c.perfil) as perfil,c.reportar_novedad "
                        . "from conductores as c,conductor_vehiculo as cv,vehiculo as v "
                        . "where c.cond_identificacion=cv.identificacion "
                        . "and cv.placa=v.placa "
                        . "and c.estado='ACTIVO' "
                        . "and cv.estado='ACTIVO' "
                        . "order by cv.placa asc;";
            } else {
                $this->consulta = "select distinct c.cond_nombres,c.cond_apellidos,c.cond_telefono,c.email,"
                        . "cv.placa,cv.fecha,v.tipovehiculo,(select rol_nombre from roles where rol_id=c.perfil) as perfil,c.reportar_novedad "
                        . "from conductores as c,conductor_vehiculo as cv,vehiculo as v "
                        . "where c.cond_identificacion=cv.identificacion "
                        . "and cv.placa=v.placa "
                        . "and c.estado='ACTIVO' "
                        . "and cv.estado='ACTIVO' "
                        . "and cv.placa='" . $placa . "' "
                        . "order by cv.placa asc;";
            }
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

    /*
     * 2017142121108
     * Para buscar la cédula de un propietario en la tabla de conductores
     */

    public function buscarPropietario($cedula, $condicion) {
        try {
            $this->con = new Conexion();
            if ($condicion === 1) {
                $this->consulta = "select cond_id,cond_nombres,cond_apellidos from conductores where cond_identificacion='" . $cedula . "' and perfil=9 and estado='ACTIVO';";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                if (count($this->arreglo) > 0) {
                    echo json_encode($this->arreglo);
                } else {
                    echo json_encode(array('0' => array('cond_id' => '0')));
                }
            }

            if ($condicion === 2) {
                $this->consulta = "select cond_id,cond_nombres,cond_apellidos from conductores where cond_identificacion='" . $cedula . "' and perfil=10 and estado='ACTIVO';";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                if (count($this->arreglo) > 0) {
                    echo json_encode($this->arreglo);
                } else {
                    echo json_encode(array('0' => array('cond_id' => '0')));
                }
            }
            $this->con = null;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarPropietario($identificacion, $placa, $estadoRelacion) {
        try {
            $this->con = new Conexion();
            
            if ($estadoRelacion === 'INACTIVO') {
                $this->consulta = "delete from conductor_vehiculo "
                        . "where identificacion=" . $identificacion . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    return 1;
                }
            } else {
                $this->consulta = "select id "
                        . "from conductor_vehiculo "
                        . "where placa='" . $placa . "';";
                //echo $this->consulta; exit();
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                if ($this->arreglo[0]["id"] === null) {
                    $this->consulta = "insert into conductor_vehiculo (id,identificacion,placa,fecha,estado) "
                            . "values (null," . $identificacion . ",'" . $placa . "','" . date("Y-m-d h:m:s") . "','" . $estadoRelacion . "');";
                } else {
                    $this->consulta = "update conductor_vehiculo set identificacion=" . $identificacion . " where placa='" . $placa . "';";
                }

                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    return 1;
                }
            }


            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /*
     * 201703101552 Agrego la consulta que selecciona el cond_id de la tabla conductores
     * para verificar antes de crear el conductor
     */

    public function crearConductor($email, $identificacion, $nombres, $apellidos, $direccion, $telefono, $municipio, $perfil, $placa,$reportar_novedad) {
        try {
            $this->con = new Conexion();
            $this->control = null;
            $this->consulta = "select cond_id from conductores where cond_identificacion=" . $identificacion . " and estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (!empty($this->arreglo)) {
                echo json_encode(array('0' => array('estado' => '0')));
            } else {

                $this->consulta = "insert into conductores (cond_id,cond_identificacion,cond_nombres,cond_apellidos,"
                        . "cond_direccion,email,cond_telefono,municipios_mun_id,estado,perfil,reportar_novedad) "
                        . "values (null,'" . $identificacion . "','" . $nombres . "','" . $apellidos . "','" . $direccion . "',"
                        . "'" . $email . "'," . $telefono . ","
                        . "" . $municipio . ",'ACTIVO'," . $perfil . ",".$reportar_novedad.");";

                $this->prepare = $this->con->prepare($this->consulta);
                $a = $this->prepare->execute();

                if ($a) {
                    $this->control = 1;
                };

                $this->consulta = "insert into conductor_vehiculo (identificacion,placa,fecha,estado) values "
                        . "(" . $identificacion . ",'" . $placa . "','" . date('Y-m-d H:m:s') . "','ACTIVO')";

                $this->prepare = $this->con->prepare($this->consulta);
                $a = $this->prepare->execute();

                if ($a) {
                    $this->control = $this->control + 1;
                }

                if ($this->control === 2) {
                    echo json_encode(array('0' => array('estado' => '1')));
                } else {
                    echo json_encode(array('0' => array('estado' => '2')));
                }
            }

            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

//201703061130 Agregué la condición and cv.estado='ACTIVO' en la consulta   
    // 201703062151 aun no estoy seguro de dejarla 

    public function consultarConductor($identificacion) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select c.cond_id,c.cond_identificacion,c.cond_nombres,c.cond_apellidos,c.cond_direccion,
c.cond_telefono,c.email,c.municipios_mun_id,c.estado as estadoConductor,c.perfil,cv.placa,cv.estado as estadoRelacion,c.reportar_novedad 
from conductores as c
inner join conductor_vehiculo as cv
on c.cond_identificacion=cv.identificacion
where c.cond_identificacion=" . $identificacion . " and c.estado<>'REPETIDO';";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (!$this->arreglo) {
                $this->consulta = "select * from conductores where cond_identificacion=" . $identificacion . " and estado<>'REPETIDO';";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
            }
            echo json_encode($this->arreglo);
            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    public function agregarPlacaPropietario($cond_id, $cedulaConductor, $nombres, $apellidos, $direccion, $telefono, $municipio, $perfil, $placa, $estado, $estadoRelacion) {
        try {

            $this->con = new Conexion();

            //Busca si el conductor se encuentra creado
            $this->consulta = "select cond_id from conductores where cond_identificacion=" . $cedulaConductor . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            if (empty($this->arreglo)) {
                echo json_encode(array('0' => array('estado' => '1')));
                exit();
            } else {

                if ($perfil === '10') {
                    echo json_encode(array('0' => array('estado' => '6')));
                    exit();
                }

                $this->consulta = "update conductores set cond_nombres='" . $nombres . "',"
                        . " cond_apellidos='" . $apellidos . "',"
                        . " cond_direccion='" . $direccion . "',"
                        . " cond_telefono='" . $telefono . "',"
                        . " municipios_mun_id=" . $municipio . ","
                        . " estado='" . $estado . "',"
                        . " perfil=" . $perfil . " "
                        . " where cond_id=" . $cond_id . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $a = $this->prepare->execute();

                if ($a) {
                    $this->control = 1;
                }

                //Antes de hacer el cambio en la tabla-relacion conductor_vehiculo, se debe verificar si el conductor
                //ya se encuentra relacionado allí. Si es así, se cambian los datos; si no se crea el conductor

                $this->consulta = "select placa,estado from conductor_vehiculo where identificacion=" . $cedulaConductor . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();

                if (empty($this->arreglo)) {
                    echo json_encode(array('0' => array('estado' => '4')));
                    exit();
                } else if (ltrim($this->arreglo[0]["placa"], " ") === ltrim($placa, " ")) {
                    echo json_encode(array('0' => array('estado' => '5')));
                    exit();
                } else {
                    $this->consulta = "insert into conductor_vehiculo (placa,fecha,estado,identificacion) "
                            . "values ('" . $placa . "','" . date('Y-m-d H:m:s') . "','" . $estadoRelacion . "'," . $cedulaConductor . ");";
                }

                $this->prepare = $this->con->prepare($this->consulta);
                $a = $this->prepare->execute();

                if ($a) {
                    $this->control = $this->control + 1;
                }

                if ($this->control === 2) {
                    echo json_encode(array('0' => array('estado' => '2')));
                    exit();
                } else {
                    echo json_encode(array('0' => array('estado' => '3')));
                    exit();
                }
            }
            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    public function modificarConductor($email, $cond_id, $cedulaConductor, $nombres, $apellidos, $direccion, $telefono, $municipio, $perfil, $placa, $estado, $estadoRelacion,$reportar_novedad) {
        try {

            $this->con = new Conexion();

            //Busca si el conductor se encuentra creado
            $this->consulta = "select cond_id from conductores where cond_identificacion=" . $cedulaConductor . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

           // echo 'hola'; exit();

            if (empty($this->arreglo)) {
                echo json_encode(array('0' => array('estado' => '1')));
                exit();
            } else {

                $this->consulta = "update conductores set cond_nombres='" . $nombres . "',"
                        . " cond_apellidos='" . $apellidos . "',"
                        . " cond_direccion='" . $direccion . "',"
                        . " cond_telefono='" . $telefono . "',"
                        . " email='" . $email . "',"
                        . " municipios_mun_id=" . $municipio . ","
                        . " estado='" . $estado . "',"
                        . " perfil=" . $perfil . ","
                        . "reportar_novedad=".$reportar_novedad." "
                        . " where cond_id=" . $cond_id . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $this->control = 1;
                }

                //echo $perfil; exit();

                if ($perfil === '9') {
                    $estado = $this->cambiarPropietario($cedulaConductor, $placa, $estadoRelacion);                    
                    //echo $this->cambiarPropietario($cedulaConductor, $placa, $estadoRelacion);                    
                    //exit();
                }

                if ($perfil === '10') {
                    $estado = $this->cambiarConductor($cedulaConductor, $placa, $estadoRelacion);
                }

                if ($estado === 1) {
                    $this->control = $this->control + 1;
                }

                if ($this->control === 2) {
                    echo json_encode(array('0' => array('estado' => '2')));
                    exit();
                } else {
                    echo json_encode(array('0' => array('estado' => '3')));
                    exit();
                }
            }
            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    public function cambiarConductor($cedula, $placa) {
        try {
            $this->con = new Conexion();

            $this->consulta = "delete from conductor_vehiculo "
                    . "where identificacion='" . $cedula . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->consulta = "insert into conductor_vehiculo (id,identificacion,placa,fecha,estado) "
                    . "values (null," . $cedula . ",'" . $placa . "','" . date("Y-m-d h:m:s") . "','ACTIVO');";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                return 1;
            }
            $this->con = null;


            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarPlacaConductor($identificacion) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select placa from conductor_vehiculo "
                    . " where identificacion=" . $identificacion . " and estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    public function retornarListaPlacas() {
        try {
            $this->lista = null;
            $this->con = new Conexion();
            $this->consulta = "select placa from vehiculo where estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->lista = '<select name="listaPlacas" id="listaPlacas" class="form-control">';
            $this->lista .= '<option value="0" selected>...</option>';
            foreach ($this->arreglo as $key => $value) {
                $this->lista .= '<option value=' . $this->arreglo[$key]['placa'] . '>' . $this->arreglo[$key]['placa'] . '</option>';
            }
            $this->lista .= '</select>';
            echo $this->lista;
            $this->con = null;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarMunicipios() {
        $this->lista = null;
        $this->con = new Conexion();
        $this->prepare = $this->con->prepare("select mun_id,mun_nombre from municipios;");
        $this->prepare->execute();
        $this->arreglo = $this->prepare->fetchAll();
        $this->lista = '<select name="listaMunicipios" id="listaMunicipios" class="form-control" >';
        $this->lista .= '<option value="0" selected>...</option>';
        foreach ($this->arreglo as $key => $value) {
            $this->lista .= '<option value=' . $this->arreglo[$key]['mun_id'] . '>' . $this->arreglo[$key]['mun_nombre'] . '</option>';
        }
        $this->lista .= '</select>';
        echo $this->lista;
        $this->con = null;
    }

    public function vincularPlacaPropietario($placa, $cedula) {
        try {
            $this->con = new Conexion();
            $this->consulta = "insert into conductor_vehiculo values(" . $cedula . ",'" . $placa . "',NOW(),'ACTIVO');";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->control = $this->prepare->execute();
            $this->con = null;
            echo json_encode($this->control);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function vincularNuevaPlacaNuevoPropietario($cedula, $placa, $nombre) {
        try {
            $arreglo = array();
            $this->placa = $placa;
            $this->cedula = $cedula;
            $this->nombre = $nombre;
            $this->con = new Conexion();
            //crear propietario sin datos
            $this->consulta = "insert into conductores values (null,'" . $this->cedula . "','" . $this->nombre . "','','',null,'',1248,'ACTIVO',9);";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $arreglo['propietario'] = $this->arreglo;
            //vincular propietario a placa
            $this->consulta = "insert into conductor_vehiculo values (" . $this->cedula . ",'" . $this->placa . "','NOW()','ACTIVO');";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $arreglo['vinculado'] = $this->arreglo;
            $this->con = null;
            echo json_encode($arreglo);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function vincularNuevaPlacaNuevoCondcutor($cedula, $placa, $nombre) {
        try {
            $arreglo = array();
            $this->placa = $placa;
            $this->cedula = $cedula;
            $this->nombre = $nombre;
            $this->con = new Conexion();
            //crear propietario sin datos
            $this->consulta = "insert into conductores values (null,'" . $this->cedula . "','" . $this->nombre . "','','',null,'',1248,'ACTIVO',10);";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $arreglo['propietario'] = $this->arreglo;
            //vincular propietario a placa
            $this->consulta = "insert into conductor_vehiculo values (" . $this->cedula . ",'" . $this->placa . "','NOW()','ACTIVO');";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $arreglo['vinculado'] = $this->arreglo;
            $this->con = null;
            echo json_encode($arreglo);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function vincularNuevaPlacaNuevoConductor($cedula, $placa, $nombre) {
        try {
            $arreglo = array();
            $this->placa = $placa;
            $this->cedula = $cedula;
            $this->nombre = $nombre;
            $this->con = new Conexion();
            //crear propietario sin datos
            $this->consulta = "insert into conductores values (null,'" . $this->cedula . "','" . $this->nombre . "','','',null,'',1248,'ACTIVO',9);";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $arreglo['propietario'] = $this->arreglo;
            //vincular propietario a placa
            $this->consulta = "insert into conductor_vehiculo values (" . $this->cedula . ",'" . $this->placa . "','NOW()','ACTIVO');";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $arreglo['vinculado'] = $this->arreglo;
            $this->con = null;
            echo json_encode($arreglo);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarTelefonoConductor($telefono, $cond_id) {
        try {
            $this->con = new Conexion();
            $this->consulta = "update conductores "
                    . "set cond_telefono='" . $telefono . "'"
                    . "where cond_id=" . $cond_id . ";";
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

}
