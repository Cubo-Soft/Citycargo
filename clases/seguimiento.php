<?php

include '../clases/conexion.php';
include '../clases/hora.php';

class seguimiento {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $retorno;

    /*
     * Esta funcion retorna los servicios de las tablas anteriores y las nuevas
     * en función del parámetro $sentencia.
     */

    public function retornarServiciosPendientes($sentencia) {
        try {
            $this->con = new Conexion();
            $this->evaluarGuiasFinalizadas();

            if ($sentencia === 0) {
                $this->consulta = "select ss.idservicio,s.fecha as fechaServicio,s.placa as placa,va.val_numeroGuia as guias "
                        . "from seguimiento_servicio as ss,servicios as s,valoresanticipos as va "
                        . "where ss.idservicio=s.idservicio "
                        . "and ss.idservicio=va.idservicio "
                        . "and ss.estado=0 "
                        . "order by s.fecha desc;";
                //echo $this->consulta;
            }

            if ($sentencia === 1) {
                $this->consulta = "select ss.idservicio,s.`fechaServicio`,s.placa,sg.`numeroGuia` as guias,sg.fechaHoraEntrega "
                        . "from seguimiento_servicio as ss,servicio as s,servicio_guias as sg "
                        . "where ss.idservicio=s.idservicio "
                        . "and ss.idservicio=sg.idservicio "
                        . "and ss.estado=0 "
                        . "order by s.`fechaServicio`,ss.idservicio desc;";
                //echo $this->consulta;
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

    public function evaluarGuiasFinalizadas() {
        $idservicios = array();
        try {
            $this->con = new Conexion();
            $this->consulta = "select ss.idservicio "
                    . "from seguimiento_servicio as ss "
                    . "where ss.estado=0 "
                    . "and ss.idservicio <> 0;";

            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            //print_r($this->arreglo);

            for ($index = 0; $index < count($this->arreglo); $index++) {
                $this->consulta = "select count(numeroGuia) "
                        . "from servicio_guias as ss "
                        . "where ss.idservicio=" . $this->arreglo[$index]["idservicio"] . ";";
                //echo $this->consulta;
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarFechaServicio($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select fechaHora "
                    . "from seguimiento "
                    . "where guia=" . $idservicio . " "
                    . "order by idseguimiento desc limit 1;";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return @$this->arreglo[0]["fechaHora"];
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function rectificarEstadoSeguimiento($guias) {
        try {
            $this->con = new Conexion();
            $guias = explode("-", $guias);
            $cantidadGuias = count($guias);
            $servicioTerminado = 0;
            $idservicio = null;
            for ($index = 0; $index < count($guias); $index++) {
                $this->consulta = "select estado,guia,idservicio "
                        . "from seguimiento "
                        . "where guia=" . $guias[$index] . " "
                        . "and estado=1;";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                if (count($this->arreglo) > 0) {
                    if ($this->arreglo[0]["estado"] === '1') {
                        $servicioTerminado += 1;
                        $idservicio = $this->arreglo[0]["idservicio"];
                    }
                }
            }
            if ($servicioTerminado === $cantidadGuias) {
                $this->consulta = "update seguimiento_servicio "
                        . "set estado=1 "
                        . "where idservicio=" . $idservicio . ";";
                //echo $this->consulta;
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
            }

            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarPlacaDos($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select placa "
                    . "from servicios "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo[0]["placa"];
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarPlaca($idservicio, $opcion) {
        try {
            $this->con = new Conexion();
            if ($opcion === 0) {
                $this->consulta = "select placa "
                        . "from servicio "
                        . "where idservicio=" . $idservicio . ";";
            }
            if ($opcion === 1) {
                $this->consulta = "select placa "
                        . "from servicio "
                        . "where idservicio=" . $idservicio . ";";
            }

            //echo $this->consulta;

            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (count($this->arreglo) <= 0) {
                return 0;
            } else {
                return $this->arreglo[0]["placa"];
            }
            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarDatosPlaca($placa) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * "
                    . "from usuariosgps "
                    . "where placa='" . $placa . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarConductor($placa) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select concat(c.cond_nombres,' ',c.cond_apellidos) as nombresConductor,c.cond_telefono,c.cond_id,c.cond_identificacion "
                    . "from conductor_vehiculo as cv,conductores as c "
                    . "where cv.identificacion=c.cond_identificacion "
                    . "and cv.placa='" . $placa . "' "
                    . "and c.perfil=10;";
            //var_dump($this->consulta);
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (count($this->arreglo) === 0) {
                $this->consulta = "select concat(c.cond_nombres,' ',c.cond_apellidos) as nombresConductor,c.cond_telefono,c.cond_id,c.cond_identificacion "
                        . "from conductor_vehiculo as cv,conductores as c "
                        . "where cv.identificacion=c.cond_identificacion "
                        . "and cv.placa='" . $placa . "' "
                        . "and c.perfil=9";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
            }
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarPropietario($placa) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select concat(c.cond_nombres,' ',c.cond_apellidos) as nombresConductor,c.cond_telefono,c.cond_id,c.cond_identificacion "
                    . "from conductor_vehiculo as cv,conductores as c "
                    . "where cv.identificacion=c.cond_identificacion "
                    . "and cv.placa='" . $placa . "' "
                    . "and c.perfil=9";
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

    public function retornarPlandeRutaManifiesto($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select manifiesto,planderuta "
                    . "from seguimiento_servicio "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarUsuarioGPS($placa) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select operador,usuario,clave "
                    . "from usuariosgps "
                    . "where placa='" . $placa . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarNotasGuia($guia) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select Notas from servicio_guias where numeroGuia=" . $guia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarGuias($idservicio) {
        try {
            $guias = array();
            $valorDeclarado = array();
            $this->con = new Conexion();
            $this->consulta = "select * "
                    . "FROM datosmostrar,servicio_guias "
                    . "WHERE datosmostrar.idservicio=servicio_guias.idservicio "
                    . "AND datosmostrar.guia=servicio_guias.numeroGuia "
                    . "AND datosmostrar.idservicio=" . $idservicio . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            if (count($this->arreglo) === 0) {
                $arregloValorDeclarado = $this->solucionarValorDeclarado($idservicio);
                for ($index = 0; $index < count($arregloValorDeclarado); $index++) {
                    $this->consulta = "insert into datosmostrar (iddatosmostrar,idservicio,guia,empresa,origen,destino,valordeclarado,valorservicio) "
                            . "values(null," . $idservicio . "," . $arregloValorDeclarado[$index]["guia"] . ",'','',''," . $arregloValorDeclarado[$index]["valor"] . ",0);";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                    $this->consulta = "select m.mun_nombre "
                            . "from serviciovariasguias as svg,municipios as m "
                            . "where svg.idciudaddestino=m.mun_id "
                            . "and svg.guia=" . $arregloValorDeclarado[$index]["guia"] . ";";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                    $this->arreglo = $this->prepare->fetchAll();
                    $this->consulta = "update datosmostrar "
                            . "set destino='" . $this->arreglo[0]["mun_nombre"] . "' "
                            . "where guia=" . $arregloValorDeclarado[$index]["guia"] . ";";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();

                    $this->consulta = "select c.cli_nombre "
                            . "from cliente as c,serviciovariasguias as svg "
                            . "where svg.idcliente=c.cli_documento "
                            . "and svg.guia=" . $arregloValorDeclarado[$index]["guia"] . ";";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                    $this->arreglo = $this->prepare->fetchAll();
                    $this->consulta = "update datosmostrar "
                            . "set empresa='" . $this->arreglo[0]["cli_nombre"] . "' "
                            . "where guia=" . $arregloValorDeclarado[$index]["guia"] . ";";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();

                    $this->consulta = "select valorFacturar from serviciovariasguias "
                            . "where guia=" . $arregloValorDeclarado[$index]["guia"] . ";";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                    $valorFacturar = $this->prepare->fetchAll();
                    if ($valorFacturar[0]["valorFacturar"] !== '0') {
                        $this->consulta = "update datosmostrar "
                                . "set valorservicio=" . $valorFacturar[0]["valorFacturar"] . " "
                                . "where guia=" . $arregloValorDeclarado[$index]["guia"] . ";";
                        $this->prepare = $this->con->prepare($this->consulta);
                        $this->prepare->execute();
                    } else {

                        $this->consulta = "select valorcliente from servicios "
                                . "where idservicio=" . $idservicio . ";";
                        $this->prepare = $this->con->prepare($this->consulta);
                        $this->prepare->execute();
                        $valorcliente = $this->prepare->fetchAll();

                        if ($index === 0) {
                            $this->consulta = "update datosmostrar "
                                    . "set valorservicio=" . $valorcliente[$index]["valorcliente"] . " "
                                    . "where guia=" . $arregloValorDeclarado[$index]["guia"] . ";";
                            $this->prepare = $this->con->prepare($this->consulta);
                            $this->prepare->execute();
                        } else {
                            $this->consulta = "update datosmostrar "
                                    . "set valorservicio=0 "
                                    . "where guia=" . $arregloValorDeclarado[$index]["guia"] . ";";
                            $this->prepare = $this->con->prepare($this->consulta);
                            $this->prepare->execute();
                        }
                    }
                }
                $this->consulta = "select m.mun_nombre "
                        . "from municipios as m,servicio as s "
                        . "where m.mun_id=s.idciudadorigen "
                        . "and s.idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();

                //var_dump($this->consulta);

                $this->consulta = "update datosmostrar "
                        . "set origen='" . $this->arreglo[0]["mun_nombre"] . "' "
                        . "where idservicio=" . $idservicio . ";";

                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();

                $this->consulta = "select * "
                        . "FROM datosmostrar,servicio_guias "
                        . "WHERE datosmostrar.idservicio=servicio_guias.idservicio "
                        . "AND datosmostrar.guia=servicio_guias.numeroGuia "
                        . "AND datosmostrar.idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $this->retorno = $this->arreglo;
            } else {
                $this->consulta = "select * "
                        . "FROM datosmostrar,servicio_guias "
                        . "WHERE datosmostrar.idservicio=servicio_guias.idservicio "
                        . "AND datosmostrar.guia=servicio_guias.numeroGuia "
                        . "AND datosmostrar.idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $this->retorno = $this->arreglo;
            }
            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function solucionarValorDeclarado($idservicio) {
        $this->consulta = "select guia,valor "
                . "from valordeclarado "
                . "where idservicio=" . $idservicio . ";";
        $this->prepare = $this->con->prepare($this->consulta);
        $this->prepare->execute();
        $arregloValorDeclarado = $this->prepare->fetchAll();
        if (count($arregloValorDeclarado) === 0) {
            $this->consulta = "select guia "
                    . "from serviciovariasguias "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $guias = $this->prepare->fetchAll();
            for ($index = 0; $index < count($guias); $index++) {
                $this->consulta = "insert into valordeclarado (id,guia,idservicio,valor) "
                        . "values (null," . $guias[$index]["guia"] . "," . $idservicio . ",200000);";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
            }

            $this->consulta = "select guia,valor "
                    . "from valordeclarado "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $arregloValorDeclarado = $this->prepare->fetchAll();
            return $arregloValorDeclarado;
        } else {
            return $arregloValorDeclarado;
        }
    }

    public function retornarSeguimiento($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select s.guia,s.fechaHora,s.ubicacion,s.observacion,s.imagen,s.emp_cedula,s.planderuta,s.estadoseguimiento,"
                    . "concat(e.emp_nombres,' ',e.emp_apellidos) as empleado "
                    . "from seguimiento as s,empleados as e "
                    . "where s.emp_cedula=e.emp_cedula "
                    . "and s.idservicio=" . $idservicio . " "
                    . "and (s.estado=1 or s.estado=0) "
                    . "order by guia,fechaHora asc;";
                    //. "order by guia asc;";
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

    public function crearSeguimiento($datos, $archivo) {
        try {

            $this->retorno = 0;
            $hora = new hora();
            $this->I_hora = $hora->retornarHora();

            if ($datos["crearCalificacion"] === '1') {

                if ($datos["mismoPropietario"] === '1') {
                    $this->crearCalificacionPropietario($datos["cedulaPropietario"], $datos["idservicio"], 1, '');
                    $this->crearCalificacionPlaca($datos["placa"], $datos["idservicio"], 1, '');
                } else {
                    $this->crearCalificacionPropietario($datos["cedulaPropietario"], $datos["idservicio"], 1, '');
                    $this->crearCalificacionConductor($datos["cedulaConductor"], $datos["idservicio"], 1, '');
                    $this->crearCalificacionPlaca($datos["placa"], $datos["idservicio"], 1, '');
                }
            }

            $this->con = new Conexion();

            $this->consulta = "select idseguimientoServicio "
                    . "from seguimiento_servicio "
                    . "where idservicio=" . $datos["idservicio"] . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $idseguimientoServicio = $this->prepare->fetchAll();

            if ($datos["estados"] === 'Despacho finalizado con novedad' || $datos["estados"] === 'Despacho finalizado sin novedad' || $datos["estados"] === 'Guia entregada') {
                $estado = 1;
            } else {
                $estado = 0;
            }

            if ($archivo["imagenGPS"]["name"] === '') {
                $rutaGuardarArchivo = '0';
            } else {
                $rutaCarpeta = "../imagenes/seguimientoGPS/";
                $nombreArchivo = "gps_" . date("Ymdhms") . "_" . $datos["idservicio"] . "." . pathinfo($archivo["imagenGPS"]["name"], PATHINFO_EXTENSION);
                $rutaGuardarArchivo = $rutaCarpeta . $nombreArchivo;
                if (move_uploaded_file($archivo["imagenGPS"]["tmp_name"], $rutaGuardarArchivo)) {
                    $this->retorno += 1;
                } else {
                    $this->retorno += 0;
                }
            }

            if ($datos["todasLasGuias"] === '1') {

                $guias = $this->retornarValorDeclarado($datos["idservicio"]);

                for ($index = 0; $index < count($guias); $index++) {
                    $this->consulta = "insert into seguimiento (idseguimiento,idseguimientoServicio,idservicio,guia,"
                            . "fechaHora,ubicacion,observacion,imagen,emp_cedula,planderuta,estadoseguimiento,estado) "
                            . "values (null," . $idseguimientoServicio[0]["idseguimientoServicio"] . "," . $datos["idservicio"] . "," . $guias[$index]["guia"] . ","
                            . "'" . $datos["fechaHora"] . "','" . $datos["ubicacion"] . "','" . $datos["observacion"] . "','" . $rutaGuardarArchivo . "'," . $datos["emp_cedula"] . ","
                            . "" . $datos["planderuta"] . ",'" . $datos["estados"] . "'," . $estado . ");";
                    //echo $this->consulta;
                    $this->prepare = $this->con->prepare($this->consulta);
                    if ($this->prepare->execute()) {
                        $this->retorno = +1;
                    } else {
                        $this->retorno = +0;
                    }

                    if ($datos["estados"] === 'Despacho finalizado con novedad') {
                        $this->consulta = "insert into serviciosporcancelar values "
                                . "(null," . $datos["idservicio"] . "," . $datos["emp_cedula"] . ",'" . date("Y-m-d h:m:s") . "','Servicio finalizado con novedad.','CANCELAR');";
                        //echo $this->consulta;
                        $this->prepare = $this->con->prepare($this->consulta);
                        $this->prepare->execute();
                    }
                }
            } else {

                $guias = explode('-', $datos["todasLasGuias"]);

                for ($index1 = 0; $index1 < count($guias); $index1++) {
                    $this->consulta = "insert into seguimiento (idseguimiento,idseguimientoServicio,idservicio,guia,"
                            . "fechaHora,ubicacion,observacion,imagen,emp_cedula,planderuta,estadoseguimiento,estado) "
                            . "values (null," . $idseguimientoServicio[0]["idseguimientoServicio"] . "," . $datos["idservicio"] . "," . $guias[$index1] . ","
                            . "'" . $datos["fechaHora"] . "','" . $datos["ubicacion"] . "','" . $datos["observacion"] . "','" . $rutaGuardarArchivo . "'," . $datos["emp_cedula"] . ","
                            . "" . $datos["planderuta"] . ",'" . $datos["estados"] . "'," . $estado . ");";
                    //echo $this->consulta;
                    $this->prepare = $this->con->prepare($this->consulta);
                    if ($this->prepare->execute()) {
                        $this->retorno = +1;
                    } else {
                        $this->retorno = +0;
                    }

                    if ($datos["estados"] === 'Despacho finalizado con novedad') {
                        $this->consulta = "insert into serviciosporcancelar values "
                                . "(null," . $datos["idservicio"] . "," . $datos["emp_cedula"] . ",'" . date("Y-m-d h:m:s") . "','Servicio finalizado con novedad.','CANCELAR');";
                        //echo $this->consulta;
                        $this->prepare = $this->con->prepare($this->consulta);
                        $this->prepare->execute();
                    }
                }
            }

            $this->con = null;

            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarValorDeclarado($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select guia "
                    . "from valordeclarado "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $guias = $this->prepare->fetchAll();
            if (count($guias) === 0) {
                $this->consulta = "select numeroGuia as guia "
                        . "from servicio_guias "
                        . "where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $guias = $this->prepare->fetchAll();
            }
            return $guias;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarEstadoGuia($guia, $idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select idseguimiento "
                    . "from seguimiento "
                    . "where estado=1 "
                    . "and guia=" . $guia . " "
                    . "and idservicio=" . $idservicio . ";";
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

    public function verificarEstadoServicio($idservicio) {
        try {
            $cantidadGuias = null;
            $pruebas = 0;
            $this->con = new Conexion();
            $this->consulta = "select guia "
                    . "from seguimiento "
                    . "where idservicio=" . $idservicio . " "
                    . "group by guia asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $guias = null;
            $guias = $this->prepare->fetchAll();
            if (count($guias) > 0) {

                $cantidadGuias = count($guias);
                $this->consulta = "select estado,guia "
                        . "from seguimiento "
                        . "where idservicio=" . $idservicio . " "
                        . "and estado=1 "
                        . "group by guia asc;";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();

                for ($index = 0; $index < count($this->arreglo); $index++) {
                    if ($this->arreglo[$index]["estado"] === '1') {
                        $pruebas += 1;
                    }
                }
                if ($cantidadGuias === $pruebas) {
                    $this->consulta = "update seguimiento_servicio "
                            . "set estado=1 "
                            . "where idservicio=" . $idservicio . ";";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                }
            }

            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarSeguimientoGuia($guia) {
        try {
            $this->con = new Conexion();

            $this->consulta = "select sg.idservicio,"
                    . "(select mun_nombre from municipios where mun_id=(select d.ciudad from direcciones as d where d.iddireccion=sg.iddireccionorigen)) as origen,"
                    . "(select mun_nombre from municipios where mun_id=(select d.ciudad from direcciones as d where d.iddireccion=sg.iddirecciondestino)) as destino,"
                    . "(select distinct cli_nombre from cliente where cli_documento=sg.nit and cliente.estado='ACTIVO') as cliente,"
                    . "s.placa "
                    . "from servicio_guias as sg,servicio as s "
                    . "where sg.idservicio=s.idservicio "
                    . "and sg.numeroGuia=" . $guia . " ;";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->retorno["cliente"] = $this->arreglo[0]["cliente"];
            $this->retorno["origen"] = $this->arreglo[0]["origen"];
            $this->retorno["destino"] = $this->arreglo[0]["destino"];
            $this->retorno["idservicio"] = $this->arreglo[0]["idservicio"];
            $this->retorno["placa"] = $this->arreglo[0]["placa"];
            $this->consulta = "select s.idservicio,s.guia,s.fechaHora,s.ubicacion,s.observacion,s.imagen,s.planderuta,s.estadoseguimiento,"
                    . "concat(e.emp_nombres,' ',e.emp_apellidos) as nombreEmpleado "
                    . "from seguimiento as s,empleados as e "
                    . "where s.emp_cedula=e.emp_cedula "
                    . "and s.guia=" . $guia . " "
                    . "order by s.fechaHora asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (count($this->arreglo) > 0) {
                $idservicio = $this->arreglo[0]["idservicio"];
                $this->retorno["datosGuia"] = $this->arreglo;
                $this->consulta = "select estado "
                        . "from seguimiento_servicio "
                        . "where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $this->retorno["estadoServicio"] = $this->arreglo[0]["estado"];
            } else {
                $this->retorno["datosGuia"] = 0;
                $this->retorno["estadoServicio"] = 0;
                $this->consulta = "select idservicio "
                        . "from serviciovariasguias "
                        . "where guia=" . $guia . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                if (count($this->arreglo) > 0) {
                    $this->consulta = "select idseguimientoServicio,idservicio "
                            . "from seguimiento_servicio "
                            . "where idservicio=" . $this->arreglo[0]["idservicio"] . ";";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                    $this->arreglo = $this->prepare->fetchAll();
                    if (count($this->arreglo) > 0) {
                        $this->retorno["idservicio"] = $this->arreglo[0]["idservicio"];
                    } else {
                        $this->retorno["seguimiento_servicio"] = 1;
                    }
                } else {
                    $this->retorno["seguimiento_servicio"] = 0;
                }
            }


            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /* Esta función esta en la clase servicios.php
     */

    public function borrarServicio($idservicio) {

        try {
            $this->con = new Conexion();
            /*
             * 201801172008 
             * averiguo si el servicio tiene anticipos hechos
             * Si los tiene no se puede pasar el servicio a la tabla de borrados
             */

            $this->consulta = "select idservicio,evento from trasabilidad where idservicio=" . $idservicio . " and evento='ANTICIPO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            if (@$this->arreglo[0]["evento"] === 'ANTICIPO') {
                return 0;
            } else {
                $this->consulta = "select * from servicios where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                if (@$this->arreglo[0]["guia"] !== '0') {
                    $referencia = @$this->arreglo[0]["guia"];
                } else if ($this->arreglo[0]["planilla"] !== '0') {
                    $referencia = $this->arreglo[0]["planilla"];
                } else {
                    $referencia = $this->arreglo[0]["auxiliar"];
                }
                $consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) "
                        . "values (" . $idservicio . ",'" . date("Y-m-d h:m:s") . "','" . $referencia . "','BORRADO');";
                $this->prepare = $this->con->prepare($consulta);
                $this->prepare->execute();

                @$this->consulta = "insert into serviciosborrados values (" . $this->arreglo[0]["idservicio"] . "," . $this->arreglo[0]["idempleado"] . ","
                        . "'" . $this->arreglo[0]["fecha"] . "'," . $this->arreglo[0]["idcliente"] . ",'" . $this->arreglo[0]["placa"] . "',"
                        . "" . $this->arreglo[0]["guia"] . "," . $this->arreglo[0]["planilla"] . ",'" . $this->arreglo[0]["auxiliar"] . "',"
                        . "'" . $this->arreglo[0]["direccionorigen"] . "'," . $this->arreglo[0]["telefonoorigen"] . "," . $this->arreglo[0]["idciudadorigen"] . ","
                        . "'" . $this->arreglo[0]["direcciondestino"] . "'," . $this->arreglo[0]["telefonodestino"] . "," . $this->arreglo[0]["idciudaddestino"] . ","
                        . "" . $this->arreglo[0]["cedulapropietario"] . "," . $this->arreglo[0]["cedulaconductor"] . "," . $this->arreglo[0]["valortotal"] . ","
                        . "" . $this->arreglo[0]["valorapagar"] . ",'" . date("Y-m-d h:m:s") . "');";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();

                $this->consulta = "delete from servicios where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();

                $this->consulta = "delete from posiblesanticipos where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();

                $this->consulta = "delete from comentariosservicios where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();

                $this->consulta = "delete from valordeclarado where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();

                $this->consulta = "delete from seguimiento_servicio where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();

                $this->consulta = "delete from seguimiento where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();

                return 1;
            }

            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarOrigenServicio($idservGuia, $busqueda) {
        try {
            $this->con = new Conexion();
            if ($busqueda === 0) {
                $this->consulta = "select m.mun_nombre "
                        . "from servicios as s,municipios as m "
                        . "where s.idciudadorigen=m.mun_id "
                        . "and s.idservicio=" . $idservGuia . ";";
            }

            if ($busqueda === 1) {
                $this->consulta = "select m.mun_nombre "
                        . "from servicio_guias as sg,direcciones as d,municipios as m "
                        . "where sg.iddireccionorigen=d.iddireccion "
                        . "and d.ciudad=m.mun_id "
                        . "and sg.numeroGuia=" . $idservGuia . ";";
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

    public function retornarDestinoGuia($idservGuia, $busqueda) {
        try {
            $this->con = new Conexion();
            if ($busqueda === 0) {
                $this->consulta = "select m.mun_nombre "
                        . "from serviciovariasguias as svg,municipios as m "
                        . "where svg.idciudaddestino=m.mun_id "
                        . "and svg.guia=" . $idservGuia . ";";
            }

            if ($busqueda === 1) {
                $this->consulta = "select m.mun_nombre "
                        . "from servicio_guias as sg,direcciones as d,municipios as m "
                        . "where sg.iddirecciondestino=d.iddireccion "
                        . "and d.ciudad=m.mun_id "
                        . "and sg.numeroGuia=" . $idservGuia . ";";
            }

            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarUltimoSeguimiento($guia) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select fechaHora "
                    . "from seguimiento "
                    . "where guia=" . $guia . " "
                    . "order by idseguimiento desc limit 1;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarClienteGuia($guia, $opcion) {
        try {
            $this->con = new Conexion();

            if ($opcion === 0) {
                $this->consulta = "select c.cli_nombre "
                        . "from serviciovariasguias as svg,cliente as c "
                        . "where svg.idcliente=c.cli_documento "
                        . "and svg.guia=" . $guia . ";";
            }

            if ($opcion === 1) {
                $this->consulta = "select c.cli_nombre "
                        . "from servicio_guias as sg, cliente as c "
                        . "where sg.nit=c.cli_documento "
                        . "and sg.numeroGuia=" . $guia . ";";
            }
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return @$this->arreglo[0]["cli_nombre"];
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarColor($fechaUltimoSeguimiento) {
        $fechaActual = date("Y-m-d G:m:s");
        $objFeAc = new DateTime($fechaActual);
        $objFeUlSeg = new DateTime($fechaUltimoSeguimiento);
        $difTiempo = $objFeAc->diff($objFeUlSeg);
        $difTiempo = $difTiempo->format("%H:%I:%S");
        $difTiempo = substr($difTiempo, 0, 2);
        if ($difTiempo <= 3) {
            $clase = "#CEF6CE";
        } else if ($difTiempo > 3 && $difTiempo <= 6) {
            $clase = "#fff486";
        } else {
            $clase = "#F6CECE";
        }
    }

    /*
     * Estas funciones estan en la clase calificaciones
     */

    private function crearCalificacionPropietario($cedula, $idservicio, $calificacion, $Detalle) {
        try {
            $this->con = new Conexion();
            $this->consulta = "insert into calificacionCedula (id,cedula,idservicio,calificacion,Detalle) "
                    . "values (null," . $cedula . "," . $idservicio . "," . $calificacion . ",'" . $Detalle . "');";
            //var_dump($this->consulta);exit();
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

    private function crearCalificacionConductor($cedula, $idservicio, $calificacion, $Detalle) {
        try {
            $this->con = new Conexion();
            $this->consulta = "insert into calificacionCedula (id,cedula,idservicio,calificacion,Detalle) "
                    . "values (null," . $cedula . "," . $idservicio . "," . $calificacion . ",'" . $Detalle . "');";
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

    private function crearCalificacionPlaca($placa, $idservicio, $calificacion, $Detalle) {
        try {
            $this->con = new Conexion();
            $this->consulta = "insert into calificacionPlaca (id,placa,idservicio,calificacion,Detalle) "
                    . "values (null,'" . $placa . "'," . $idservicio . "," . $calificacion . ",'" . $Detalle . "');";
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

 public function cambiarEstadoSeguimiento($idseguimiento, $idservicio) {
        try {
            $this->con = new Conexion();
       
            $this->consulta = "update seguimiento_servicio set estado=0 where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "update seguimiento set estado=2 where idseguimiento=" . $idseguimiento . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->retorno = $this->con->exec($this->consulta);
            
            $this->con = null;

            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarSeguimientosPorGuia($guia) {

        try {

          $this->consulta = "SELECT seguimiento.fechaHora,seguimiento.estadoseguimiento "
                    . "FROM seguimiento "
                    . "WHERE seguimiento.guia=".$guia." "
                    ."and (seguimiento.estado=1 or seguimiento.estado=0)";  
            
            //echo $this->consulta;

            $this->con = new Conexion();
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

