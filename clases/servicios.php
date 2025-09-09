<?php

include '../clases/conexion.php';

class servicios {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $arregloRetorno;

    public function __construct() {

        $this->consulta = "SELECT servicio.idservicio,empleados.emp_cedula,servicio.fechaServicio,servicio.placa,"
                . "servicio.cedulaPropietario,servicio.cedulaConductor,servicio_guias.numeroGuia,servicio_guias.auxiliar,"
                . "servicio_guias.parqueadero,servicio_guias.otros,"
                . "(SELECT SUM(otrosCostos.valor) as otrosCostos FROM otrosCostos WHERE otrosCostos.numeroGuia=servicio_guias.numeroGuia) as otrosCostos,"
                . "servicio_guias.valorDeclarado,servicio_guias.valorPagado,servicio_guias.valorCobrado,servicio_guias.nit,"
                . "servicio_guias.fechaFactura,servicio_guias.numeroFactura as factura,servicio_guias.fechaPago,servicio_guias.fechaPruebaEntrega,"
                . "concat(empleados.emp_nombres,' ',empleados.emp_apellidos) as nombreAsesor,"
                . "servicio_guias.numeroCuentaCobro,servicio_guias.fechaTransferencia,"
                . "(SELECT seguimiento_servicio.manifiesto FROM  seguimiento_servicio WHERE seguimiento_servicio.idservicio=servicio.idservicio) as manifiesto,"
                . "(SELECT vehiculo.tipovehiculo FROM vehiculo WHERE vehiculo.placa=servicio.placa) as tipovehiculo,"
                . "(select mun_nombre from municipios where municipios.mun_id=(select direcciones.ciudad from direcciones where direcciones.iddireccion=servicio_guias.iddireccionorigen)) as ciudadOrigen,(select mun_nombre from municipios where municipios.mun_id=(select direcciones.ciudad from direcciones where direcciones.iddireccion=servicio_guias.iddirecciondestino)) as ciudadDestino,"
                . "trasabilidad.fecha AS fechaCreacion "
                . "FROM servicio_guias,servicio,asesor_empresa,empleados,trasabilidad "
                . "WHERE servicio_guias.idservicio=servicio.idservicio "
                . "AND servicio_guias.nit=asesor_empresa.nit "
                . "AND empleados.emp_cedula=asesor_empresa.cedula "
                . "AND servicio_guias.numeroGuia=trasabilidad.referencia "
                . "AND trasabilidad.evento='CREACION' ";
    }

    public function actualizarFechaTransferencia($numeroCuentaCobro, $fechaTransferencia) {
        try {
            $this->con = new Conexion();

            $this->consulta = "update servicio_guias "
                    . "set fechaTransferencia='" . $fechaTransferencia . "' "
                    . "where numeroCuentaCobro=" . $numeroCuentaCobro . ";";
            //echo $this->consulta;serviciosPorFechas

            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function serviciosPorNumeroCuentaCobro($numeroCuentaCobro) {
        try {
            $this->con = new Conexion();

            $this->consulta = "select servicio_guias.idservicio,servicio_guias.numeroGuia as guia,servicio.fechaServicio as fecha,servicio_guias.fechaTransferencia,"
                    . "servicio.placa "
                    . "from servicio_guias,servicio "
                    . "where servicio.idservicio=servicio_guias.idservicio "
                    . "and servicio_guias.numeroCuentaCobro=" . $numeroCuentaCobro . ";";
            //echo $this->consulta; exit();
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;

            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function crearValorDeclarado($guia, $valorDeclarado, $idservicio) {
        try {
            $this->con = new Conexion();

            $this->consulta = "select * "
                    . "from valordeclarado "
                    . "where guia=" . $guia . "; ";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            if (count($this->arreglo) === 0) {
                $this->consulta = "insert into valordeclarado (id,guia,idservicio,valor) "
                        . "values (null," . $guia . "," . $idservicio . "," . $valorDeclarado . ")";
            } else {
                $this->consulta = "update valordeclarado "
                        . "set valor=" . $valorDeclarado . " "
                        . "where guia=" . $guia . ";";
            }

            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarClientes() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select cli_documento,cli_nombre from cliente where cli_documento > 100 and estado='ACTIVO' order by cli_nombre asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;

            $clientes = "<select id='listaClientes' name='listaClientes' class='form-control form-control-sm' onchange='mostrarNit(this);'>"
                    . "<option value='0'>...</option>"
                    . "<!--<option value='1'>Crear cliente</option>-->";
            for ($index = 0; $index < count($this->arreglo); $index++) {
                $clientes .= "<option value='" . $this->arreglo[$index]['cli_documento'] . "'>" . $this->arreglo[$index]['cli_nombre'] . "</option>";
            }
            $clientes .= "</select>";

            return $clientes;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarPropietarios() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select concat(cond_nombres,' ',cond_apellidos) as nombresApellidos, cond_identificacion "
                    . "from conductores "
                    . "where estado='ACTIVO' and perfil=9 and cond_identificacion<>'79725743'"
                    . "order by nombresApellidos asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function crearSubvalores($idservicio, $valor, $detalle, $opcion) {
        try {
            $this->con = new Conexion();
            if ($valor > 0) {

                switch ($opcion) {
                    case 'CREACION':
                        $this->consulta = "insert into valores values (null," . $idservicio . "," . $valor . ",'" . $detalle . "');";
                        break;
                    case 'MODIFICACION':
                        $this->consulta = "insert into valores values (null," . $idservicio . "," . $valor . ",'" . $detalle . "');";
                }


                $this->prepare = $this->con->prepare($this->consulta);
                $this->arregloRetorno = $this->prepare->execute();
            }
            $this->con = null;
            return $this->arregloRetorno;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /*
     * 201904161111
     * Quieto buñuelo. Esta trabajando doble
     */

    public function modificarServicioDos($idservicio, $auxiliar, $parqueadero, $otros, $valorcliente, $valorcontratista, $valorfacturar) {
        try {
            $this->con = new Conexion();

            $this->consulta = "select auxiliar,parqueadero,otros from serviciovariasguias "
                    . "where ";

            $this->consulta = "insert into valores (idvalor,idservicio,valor,detalle)"
                    . "values (null," . $idservicio . "," . $auxiliar . ",'AUXILIAR')";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "insert into valores (idvalor,idservicio,valor,detalle)"
                    . "values (null," . $idservicio . "," . $parqueadero . ",'PARQUEADERO')";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "insert into valores (idvalor,idservicio,valor,detalle)"
                    . "values (null," . $idservicio . "," . $otros . ",'OTROS')";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            /*
             * 201809111045
             * El $valorcliente se deja igual que $valorcontratista.
             * Al mostrar en la cuenta de cobro             
              $this->consulta = "update servicios set valortotal=" . $valorcliente . ",valorapagar=" . $valorcontratista . ", valorcliente=" . $valorfacturar . " "
              . "where idservicio=" . $idservicio . ";";
             */
            $this->consulta = "update servicios set valortotal=" . $valorcontratista . ",valorapagar=" . $valorcontratista . ", valorcliente=" . $valorfacturar . " "
                    . "where idservicio=" . $idservicio . ";";

            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();

            /*
             * 201901221256
             * Se crea la inserción en la tabla seguimiento_servicio
             */

            $this->consulta = "select * "
                    . "from seguimiento_servicio "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $arreglo = $this->prepare->fetchAll();
            if (count($arreglo) === 0) {
                $this->consulta = "insert into seguimiento_servicio (idseguimientoServicio,idservicio,manifiesto,planderuta,estado) "
                        . "values(null," . $idservicio . ",'0','',0);";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->arreglo = $this->prepare->execute();
            }
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function modificarServicioTres($idservicio, $variasEmpresas) {
        try {
            $this->con = new Conexion();
            $guias = null;
            /*
             * 201901221256
             * Se crea la inserción en la tabla seguimiento_servicio
             */

            $this->consulta = "select * "
                    . "from seguimiento_servicio "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $arreglo = $this->prepare->fetchAll();
            if (count($arreglo) === 0) {
                $this->consulta = "insert into seguimiento_servicio (idseguimientoServicio,idservicio,manifiesto,planderuta,estado) "
                        . "values(null," . $idservicio . ",'0','',0);";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->arreglo = $this->prepare->execute();
            }

            $this->consulta = "select cedulapropietario,placa from servicios where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $arreglo = $this->prepare->fetchAll();
            $cedulapropietario = $arreglo[0]["cedulapropietario"];
            $placa = $arreglo[0]["placa"];
            $this->consulta = "select cond_id from conductores where cond_identificacion=" . $cedulapropietario . " and estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $arreglo = $this->prepare->fetchAll();
            $cond_id = $arreglo[0]["cond_id"];

            if ($variasEmpresas === '1' || $variasEmpresas === 1) {

                $this->consulta = "select * "
                        . "from serviciovariasguias "
                        . "where idservicio=" . $idservicio . ";";

                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();

                for ($index = 0; $index < count($this->arreglo); $index++) {
                    $guias = $guias . "-" . $this->arreglo[$index]["guia"];
                    $this->consulta = "select cli_id from cliente where cli_documento=" . $this->arreglo[$index]["idcliente"] . " and estado='ACTIVO';";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                    $arreglo = $this->prepare->fetchAll();
                    $id_empresa = $arreglo[0]["cli_id"];

                    $this->consulta = "insert into valoresanticipos (val_ant_id,val_fechaAnticipo,val_valorAdelanto,valorservicio,val_id_empresa,val_numeroGuia,conductores_cond_id,placa,totalesAnticipos_val_id,val_numeroAnticipo,prueba_entrega,idservicio) "
                            . "values (null,'" . date("Y-m-d h:m") . "',0," . $this->arreglo[$index]["costoTotal"] . "," . $id_empresa . "," . $this->arreglo[$index]["guia"] . "," . $cond_id . ",'" . $placa . "',42,0,'N'," . $idservicio . ");";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $prueba["resultado"] = $this->prepare->execute();

                    $this->consulta = "select cli_nombre from cliente where cli_documento=" . $this->arreglo[$index]["idcliente"] . " and estado='ACTIVO';";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                    $arreglo = $this->prepare->fetchAll();

                    $this->consulta = "insert into posiblesfacturas (pos_id,nit,empresa,fecha,numeroguia,valorservicio,valorempresa,factura,idservicio) values "
                            . "(null," . $this->arreglo[$index]["idcliente"] . ",'" . $arreglo[0]["cli_nombre"] . "','" . date("Y-m-d") . " 00:00:00','" . $this->arreglo[$index]["guia"] . "'," . $this->arreglo[$index]["costoTotal"] . "," . $this->arreglo[$index]["valorFacturar"] . ",0," . $idservicio . ");";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                }
            } else {
                $this->consulta = "select  s.valortotal,s.valorcliente,svg.guia,svg.idcliente,s.cedulapropietario,s.fecha "
                        . "from servicios as s, serviciovariasguias as svg "
                        . "where s.idservicio=svg.idservicio "
                        . "and s.idservicio=" . $idservicio . ";";

                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $arreglo = $this->prepare->fetchAll();

                $nitcliente = $arreglo[0]["idcliente"];
                $valortotal = $arreglo[0]["valortotal"];
                $valorcliente = $arreglo[0]["valorcliente"];

                $this->consulta = "select cli_id from cliente where cli_documento=" . $nitcliente . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $id_empresa = $this->arreglo[0]["cli_id"];

                for ($index1 = 0; $index1 < count($arreglo); $index1++) {
                    $guias .= $arreglo[$index1]["guia"] . "-";
                }

                $guias = substr($guias, 0, -1);

                $this->consulta = "select cli_nombre from cliente where cli_documento=" . $nitcliente . " and estado='ACTIVO';";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $arreglo = $this->prepare->fetchAll();

                $this->consulta = "insert into posiblesfacturas (pos_id,nit,empresa,fecha,numeroguia,valorservicio,valorempresa,factura,idservicio) values "
                        . "(null," . $nitcliente . ",'" . $arreglo[0]["cli_nombre"] . "','" . date("Y-m-d") . " 00:00:00','" . $guias . "'," . $valortotal . "," . $valorcliente . ",0," . $idservicio . ");";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();

                $this->consulta = "insert into valoresanticipos (val_ant_id,val_fechaAnticipo,val_valorAdelanto,valorservicio,val_id_empresa,val_numeroGuia,conductores_cond_id,placa,totalesAnticipos_val_id,val_numeroAnticipo,prueba_entrega,idservicio) "
                        . "values (null,'" . date("Y-m-d h:m") . "',0," . $valortotal . "," . $id_empresa . ",'" . $guias . "'," . $cond_id . ",'" . $placa . "',42,0,'N'," . $idservicio . ");";
                $this->prepare = $this->con->prepare($this->consulta);
                $prueba["resultado"] = $this->prepare->execute();
            }


            $this->con = null;

            return $prueba;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function modificarServicio($idservicio, $idempleado, $fecha, $idcliente, $placa, $guia, $planilla, $auxiliar, $direccionorigen, $telefonoorigen, $idciudadorigen, $direcciondestino, $telefonodestino, $idciudaddestino, $cedulapropietario, $cedulaconductor, $valortotal, $valorapagar, $opcion) {
        try {
            $this->con = new Conexion();
            $referencia = null;
            $this->consulta = "update servicios set idempleado=" . $idempleado . ", fecha='" . $fecha . "', idcliente=" . $idcliente . ","
                    . "placa='" . $placa . "',guia=" . $guia . ",planilla=" . $planilla . ",auxiliar='" . $auxiliar . "',direccionorigen='" . $direccionorigen . "',"
                    . "telefonoorigen=" . $telefonoorigen . ",idciudadorigen=" . $idciudadorigen . ",direcciondestino='" . $direcciondestino . "',"
                    . "telefonodestino=" . $telefonodestino . ",idciudaddestino=" . $idciudaddestino . ",cedulapropietario=" . $cedulapropietario . ","
                    . "cedulaconductor=" . $cedulaconductor . ",valortotal=" . $valortotal . ",valorapagar=" . $valorapagar . " "
                    . "where idservicio=" . $idservicio . ";";
            //echo $this->consulta; exit();
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arregloRetorno = $this->prepare->execute();

            if ($guia > 0) {
                $referencia = $guia;
            } else if ($planilla > 0) {
                $referencia = $planilla;
            } else {
                $referencia = $auxiliar;
            }

            if ($opcion === 'CREACION') {
                $this->prepare = $this->con->prepare("insert into trasabilidad (idservicio,fecha,referencia,evento) values "
                        . "(" . $idservicio . ",'" . $fecha . "','" . $referencia . "','" . $opcion . "');");
                $this->prepare->execute();
                $this->prepare = $this->con->prepare("insert into trasabilidad (idservicio,fecha,referencia,evento) values "
                        . "(" . $idservicio . ",'" . $fecha . "','" . $referencia . "','POR FACTURAR');");
                $this->prepare->execute();
            }

            /* 201803260945
             * Está quemado para ser creados los servicios desde Bogotá.
             */
            if ($idciudadorigen !== '1248' || $idciudaddestino !== '1248') {
                $valoranticipo = ($valorapagar * 60) / 100;
                if ($opcion === 'CREACION') {
                    $this->consulta = "insert into posiblesanticipos values (" . $idservicio . ",'" . $idcliente . "','" . $placa . "','" . $fecha . "'," . $valoranticipo . "," . $valorapagar . ",'CREACION');";
                    //echo $this->consulta; exit();
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                }
                if ($opcion === 'MODIFICACION') {

                    $this->consulta = "select idservicio from posiblesanticipos where idservicio=" . $idservicio . ";";
                    //echo $this->consulta; exit();
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                    $this->arreglo = $this->prepare->fetchAll();

                    if (count($this->arreglo) > 0) {
                        $this->consulta = "update posiblesanticipos set  nitempresa='" . $idcliente . "',placa='" . $placa . "',fechaservicio='" . $fecha . "',posiblevaloranticipo=" . $valoranticipo . ",valorservicio=" . $valorapagar . " where idservicio=" . $idservicio . ";";
                        $this->prepare = $this->con->prepare($this->consulta);
                        $this->prepare->execute();
                    } else {
                        $this->consulta = "insert into posiblesanticipos values (" . $idservicio . ",'" . $idcliente . "','" . $placa . "','" . $fecha . "'," . $valoranticipo . "," . $valorapagar . ",'CREACION');";
                        $this->prepare = $this->con->prepare($this->consulta);
                        $this->prepare->execute();
                    }
                }

                //echo $this->consulta;
            }

            $this->con = null;
            return $this->arregloRetorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarMunicipios($nombre, $ciudad = null) {

        $this->con = new Conexion();
        $this->prepare = $this->con->prepare("select mun_id,mun_nombre from municipios order by mun_nombre asc;");
        $this->prepare->execute();
        $this->arreglo = $this->prepare->fetchAll();
        echo '<select name="' . $nombre . '" id="' . $nombre . '" class="form-control" >';
        echo '<option value="0">...</option>';
        foreach ($this->arreglo as $key => $value) {
            if ($ciudad === null) {
                echo '<option value=' . $this->arreglo[$key]['mun_id'] . '>' . $this->arreglo[$key]['mun_nombre'] . '</option>';
            } else if ($ciudad === $this->arreglo[$key]['mun_id']) {
                echo '<option value=' . $this->arreglo[$key]['mun_id'] . ' selected="selected">' . $this->arreglo[$key]['mun_nombre'] . '</option>';
            } else {
                echo '<option value=' . $this->arreglo[$key]['mun_id'] . '>' . $this->arreglo[$key]['mun_nombre'] . '</option>';
            }
        }
        echo '</select>';

        $this->con = null;
    }

    public function crearSubValor($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "insert into valores values(null," . $idservicio . ",0,'')";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->consulta = "select idvalor from valores order by idvalor DESC LIMIT 1;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetch(PDO::FETCH_ASSOC);
            $this->con = null;
            echo json_encode($this->arreglo);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /*
     * 20190320
     * Esta función va a salir sobrando. Solo es para crear el número de
     * servicio, pero causa problemas 
     */

    public function retornarNumeroServicio($fecha, $idempleado) {
        try {
            $this->con = new Conexion();
            $this->consulta = "insert into servicios values (null," . $idempleado . ",'" . $fecha . "',0,'',0,0,0,'',0,0,'',0,0,0,0,0,0,0);";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->consulta = "select idservicio from servicios order by idservicio DESC LIMIT 1;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetch(PDO::FETCH_ASSOC);
            $idservicio = $this->arreglo["idservicio"];
            $this->consulta = "insert into servicioempleadohora values (" . $idservicio . "," . $idempleado . ",'" . date("Y-m-d h:m:s") . "');";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarEmpleados($idpersona) {
        try {
            $this->con = new Conexion();
            $retorno = array();
            $a = 0;
            $this->consulta = "select concat(emp_nombres,' ',emp_apellidos) as nombreEmpleado, emp_cedula "
                    . "from empleados "
                    . "where departamento_dep_id=5 "
                    . "and estado='A';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            for ($index = 0; $index < count($this->arreglo); $index++) {
                $retorno[$index]["nombre"] = $this->arreglo[$index]["nombreEmpleado"];
                $retorno[$index]["cedula"] = $this->arreglo[$index]["emp_cedula"];
                $a = $a + 1;
            }
            $this->consulta = "select concat(emp_nombres,' ',emp_apellidos) as nombreEmpleado, emp_cedula "
                    . "from empleados "
                    . "where emp_cedula='" . $idpersona . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            for ($index1 = 0; $index1 < count($this->arreglo); $index1++) {
                $a = $a + $index1;
                $retorno[$a]["nombre"] = $this->arreglo[$index1]["nombreEmpleado"];
                $retorno[$a]["cedula"] = $this->arreglo[$index1]["emp_cedula"];
            }

            $this->con = null;
            return $retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarDatosDePlaca($placa) {
        try {
            $this->con = new Conexion();
            /*
             * 20171201 Buscar el propietario y conductores de una placa
             * Busco primero el propietario, luego los conductores. Los envio
             * como objetos json
             */

            $this->consulta = "select c.cond_identificacion,concat (c.cond_nombres,' ',c.cond_apellidos) as nombresPropietario,cond_telefono as telefonoPropietario "
                    . "from conductor_vehiculo as cv, conductores as c "
                    . "where cv.identificacion=c.cond_identificacion "
                    . "and c.perfil=9 and c.estado='ACTIVO' and cv.placa='" . $placa . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->arregloRetorno["propietario"] = $this->arreglo;
            $this->consulta = "select c.cond_identificacion as identificacion,concat(c.cond_nombres,' ',c.cond_apellidos) as nombreConductor,cond_telefono as telefonoConductor "
                    . "from conductores as c,conductor_vehiculo as cv "
                    . "where c.cond_identificacion=cv.identificacion "
                    . "and c.perfil=10 "
                    . "and c.estado='ACTIVO' "
                    . "and cv.placa='" . $placa . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if ($this->arreglo === null) {
                $this->arregloRetorno["conductores"] = 0;
            } else {
                $this->arregloRetorno["conductores"] = $this->arreglo;
            }

            $this->con = null;
            echo json_encode($this->arregloRetorno);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarPlacas($placa = null) {

        $arregloPlacas = array();

        try {
            $this->con = new Conexion();
            $this->consulta = "select cv.placa,c.cond_identificacion from conductor_vehiculo as cv inner join conductores as c on cv.identificacion=c.cond_identificacion where c.estado='ACTIVO' and c.perfil=9 and cv.estado='ACTIVO' and cv.placa<>'AAA000' order by cv.placa asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $placas = "<select id='listaPlacas' name='listaPlacas' class='form-control form-control-sm'>"
                    . "<option value='0'>...</option>";
            for ($index = 0; $index < count($this->arreglo); $index++) {

                if (!in_array($this->arreglo[$index]["placa"], $arregloPlacas)) {
                    $arregloPlacas[$index] = $this->arreglo[$index]["placa"];
                    if ($placa === null) {
                        $placas .= "<option value='" . $this->arreglo[$index]["placa"] . "'>" . $this->arreglo[$index]["placa"] . "</option>";
                    } else if ($placa === $this->arreglo[$index]["placa"]) {
                        $placas .= "<option value='" . $this->arreglo[$index]["placa"] . "' selected='selected'>" . $this->arreglo[$index]["placa"] . "</option>";
                    } else {
                        $placas .= "<option value='" . $this->arreglo[$index]["placa"] . "'>" . $this->arreglo[$index]["placa"] . "</option>";
                    }
                }
            }

            $placas .= "<select/>";
            $this->con = null;
            return $placas;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function verificarEstadoNumeroServicio($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * from trasabilidad where idservicio=" . $idservicio . " and evento='FACTURADO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
//            echo json_encode($this->consulta);
            echo json_encode($this->arreglo);
            $this->con = null;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cancelarServicio($idservicio, $guia, $opcion, $motivo) {
        try {
            $this->con = new Conexion();

            /*
             * 201902082011
             * Se agrega el borrado de los documentos comprometidos en el
             * servicio
             */

            $this->consulta = "select imagen "
                    . "from seguimiento "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (count($this->arreglo) > 0) {
                for ($index2 = 0; $index2 < count($this->arreglo); $index2++) {
                    $nombreArchivo = $this->arreglo[$index2]["imagen"];
                    echo unlink($nombreArchivo);
                }
            }

            $this->consulta = "select planderuta "
                    . "from seguimiento_servicio "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (count($this->arreglo) > 0) {
                $nombreArchivo = $this->arreglo[$index2]["planderuta"];
                // echo unlink($nombreArchivo); esta borra¿ndo un archivo que al parece no existe mabl ric 28-nov-2024 si algo descomentar
            }

            /*
             * 201901221117
             * Se agrega el borrado del servicio de la tabla seguimiento_servicio 
             * y seguimiento
             */

            $this->consulta = "delete from seguimiento_servicio where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "delete from seguimiento where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "delete from valordeclarado where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "delete from servicios where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $resultado["resultado"] = $this->prepare->execute();
            $this->consulta = "delete from serviciovariasguias where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->consulta = "delete from posiblesanticipos where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->consulta = "delete from posiblesfacturas where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->consulta = "delete from valoresanticipos where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->consulta = "delete from servicioempleadohora where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->consulta = "delete from trasabilidad where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->consulta = "update serviciosporcancelar set estado='CANCELADO' where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            if ($opcion === 0) {

                if ($guia !== '0') {
                    $this->consulta = "delete from guias where numeroGuia=" . $guia . ";";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                }
                $this->consulta = "select guia from servicios where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $cantidad = count($this->arreglo);
                if ($cantidad > 0) {
                    for ($index = 0; $index < $cantidad; $index++) {
                        $this->consulta = "delete from guias where numeroGuia=" . $this->arreglo[$index]["guia"] . ";";
                        $this->prepare = $this->con->prepare($this->consulta);
                        $this->prepare->execute();
                    }
                }
                $this->consulta = "select guia from serviciovariasguias where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $cantidad = count($this->arreglo);
                if ($cantidad > 0) {
                    for ($index = 0; $index < $cantidad; $index++) {
                        $this->consulta = "delete from guias where numeroGuia=" . $this->arreglo[$index]["guia"] . ";";
                        $this->prepare = $this->con->prepare($this->consulta);
                        $this->prepare->execute();
                    }
                }
                $this->consulta = "delete from servicios where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $resultado["resultado"] = $this->prepare->execute();
                $this->consulta = "delete from serviciovariasguias where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "delete from posiblesanticipos where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "delete from posiblesfacturas where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "delete from valoresanticipos where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "delete from servicioempleadohora where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "delete from trasabilidad where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "update serviciosporcancelar set estado='CANCELADO' where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
            } else if ($opcion === 1) {

                $this->consulta = "select s.idservicio,s.idempleado,s.fecha,s.placa,s.direccionorigen,s.telefonoorigen,s.idciudadorigen,s.cedulapropietario,s.cedulaconductor,s.valortotal,s.valorapagar,s.valorcliente,"
                        . "svg.guia,svg.planilla,svg.otro as auxiliar,svg.idcliente,svg.direccion as direcciondestino,svg.telefono as telefonodestino,svg.idciudaddestino "
                        . "from servicios as s,serviciovariasguias as svg "
                        . "where s.idservicio=svg.idservicio "
                        . "and s.idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();

                for ($index1 = 0; $index1 < count($this->arreglo); $index1++) {
                    $this->consulta = "insert into serviciosborrados (idservicio,idempleado,fecha,idcliente,placa,guia,planilla,auxiliar,direccionorigen,telefonoorigen,idciudadorigen,direcciondestino,telefonodestino,idciudaddestino,cedulapropietario,cedulaconductor,valortotal,valorapagar,fechaborrado,motivo) values"
                            . "(" . $this->arreglo[$index1]["idservicio"] . "," . $this->arreglo[$index1]["idempleado"] . ",'" . $this->arreglo[$index1]["fecha"] . "'," . $this->arreglo[$index1]["idcliente"] . ",'" . $this->arreglo[$index1]["placa"] . "','" . $this->arreglo[$index1]["guia"] . "'," . $this->arreglo[$index1]["planilla"] . ",'" . $this->arreglo[$index1]["auxiliar"] . "','" . $this->arreglo[$index1]["direccionorigen"] . "'," . $this->arreglo[$index1]["telefonoorigen"] . "," . $this->arreglo[$index1]["idciudadorigen"] . ",'" . $this->arreglo[$index1]["direcciondestino"] . "'," . $this->arreglo[$index1]["telefonodestino"] . "," . $this->arreglo[$index1]["idciudaddestino"] . "," . $this->arreglo[$index1]["cedulapropietario"] . "," . $this->arreglo[$index1]["cedulaconductor"] . "," . $this->arreglo[$index1]["valortotal"] . "," . $this->arreglo[$index1]["valorcliente"] . ",'" . date('Y-m-d H:m:s') . "','" . $guia . "');";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                }

                $this->consulta = "select guia from servicios where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $cantidad = count($this->arreglo);
                if ($cantidad > 0) {
                    for ($index = 0; $index < $cantidad; $index++) {
                        $this->consulta = "delete from guias where numeroGuia=" . $this->arreglo[$index]["guia"] . ";";
                        $this->prepare = $this->con->prepare($this->consulta);
                        $this->prepare->execute();
                    }
                }
                $this->consulta = "select guia from serviciovariasguias where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $cantidad = count($this->arreglo);
                if ($cantidad > 0) {
                    for ($index = 0; $index < $cantidad; $index++) {
                        $this->consulta = "delete from guias where numeroGuia=" . $this->arreglo[$index]["guia"] . ";";
                        $this->prepare = $this->con->prepare($this->consulta);
                        $this->prepare->execute();
                    }
                }
                $this->consulta = "delete from servicios where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $resultado["resultado"] = $this->prepare->execute();
                $this->consulta = "delete from serviciovariasguias where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "delete from posiblesanticipos where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "delete from posiblesfacturas where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "delete from valoresanticipos where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "delete from servicioempleadohora where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "delete from trasabilidad where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $resultado = $this->prepare->execute();
                $this->consulta = "update serviciosporcancelar set estado='CANCELADO' where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                /*
                 * 20190502
                 * Agrego la opcion de borrado
                 * a las tablas nuevas
                 */
            } else if ($opcion === 2) {
                $this->consulta = "select sg.idservicio,sg.numeroGuia,sg.iddireccionorigen,sg.iddirecciondestino,sg.auxiliar,sg.parqueadero,sg.otros,"
                        . "sg.valorDeclarado,sg.valorPagado,sg.valorCobrado,sg.nit,s.fechaServicio,s.placa,s.cedulaPropietario,s.cedulaConductor,s.fechaServicio "
                        . "from servicio_guias as sg,servicio as s "
                        . "where sg.idservicio=s.idservicio "
                        . "and s.idservicio=" . $idservicio . "; ";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                for ($index3 = 0; $index3 < count($this->arreglo); $index3++) {
                    $this->consulta = "select cedula "
                            . "from asesor_empresa "
                            . "where nit=" . $this->arreglo[$index3]["nit"] . ";";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                    $idempleado = $this->prepare->fetchAll();
                    $idempleado = $idempleado[0]["cedula"];
                    $this->consulta = "insert into serviciosborrados (idservicio,idempleado,fecha,idcliente,placa,guia,"
                            . "planilla,auxiliar,direccionorigen,telefonoorigen,idciudadorigen,direcciondestino,telefonodestino,"
                            . "idciudaddestino,cedulapropietario,cedulaconductor,valortotal,valorapagar,fechaborrado,motivo) "
                            . "values (" . $idservicio . "," . $idempleado . ",'" . $this->arreglo[$index3]["fechaServicio"] . "','" . $this->arreglo[$index3]["nit"] . "',"
                            . "'" . $this->arreglo[$index3]["placa"] . "','" . $this->arreglo[$index3]["numeroGuia"] . "',0,'" . $this->arreglo[$index3]["auxiliar"] . "',"
                            . "'" . $this->arreglo[$index3]["iddireccionorigen"] . "',0,0,'" . $this->arreglo[$index3]["iddirecciondestino"] . "',0,0,"
                            . "" . $this->arreglo[$index3]["cedulaPropietario"] . "," . $this->arreglo[$index3]["cedulaConductor"] . "," . $this->arreglo[$index3]["valorCobrado"] . ","
                            . "" . $this->arreglo[$index3]["valorPagado"] . ",'" . date("Y-m-d h:m") . "','" . $motivo . "');";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                    $this->consulta = "delete from servicio where idservicio=" . $idservicio . "; ";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                    $this->consulta = "delete from servicio_guias where idservicio=" . $idservicio . "; ";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                    $resultado = true;
                }
            }

            $this->con = null;
            return $resultado;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarDatosServicio($idservicio) {
        try {
            $this->con = new Conexion();
            //averiguar nombres de conductores y propietarios
            $this->consulta = "select s.cedulapropietario, s.cedulaconductor "
                    . "from servicios as s "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo["conductores"] = $this->prepare->fetchAll();
            $this->consulta = "select concat(cond_nombres,' ',cond_apellidos) as nombresPropietario,cond_identificacion "
                    . "from conductores "
                    . "where cond_identificacion=" . $this->arreglo["conductores"][0]["cedulapropietario"];
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo["propietario"] = $this->prepare->fetchAll();
            $this->consulta = "select concat(cond_nombres,' ',cond_apellidos) as nombresConductor,cond_identificacion "
                    . "from conductores "
                    . "where cond_identificacion=" . $this->arreglo["conductores"][0]["cedulaconductor"];
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo["conductor"] = $this->prepare->fetchAll();

            $this->consulta = "select s.idservicio,s.idempleado,s.fecha,c.cli_documento,c.cli_nombre,s.placa,s.guia,s.planilla,s.auxiliar,"
                    . "s.direccionorigen,s.telefonoorigen,s.idciudadorigen,s.direcciondestino,s.telefonodestino,s.idciudaddestino,"
                    . "s.valortotal,s.valorapagar "
                    . "from servicios as s,cliente as c "
                    . "where s.idcliente = c.cli_documento "
                    . "and idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo["servicio"] = $this->prepare->fetchAll();

            $this->consulta = "select * from valores where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo["valores"] = $this->prepare->fetchAll();

            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /*
     * Esta función también esta en la clase seguimiento
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

            if ($this->arreglo[0]["evento"] === 'ANTICIPO') {
                return 0;
            } else {
                $this->consulta = "select * from servicios where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                if ($this->arreglo[0]["guia"] !== '0') {
                    $referencia = $this->arreglo[0]["guia"];
                } else if ($this->arreglo[0]["planilla"] !== '0') {
                    $referencia = $this->arreglo[0]["planilla"];
                } else {
                    $referencia = $this->arreglo[0]["auxiliar"];
                }
                $consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) "
                        . "values (" . $idservicio . ",'" . date("Y-m-d h:m:s") . "','" . $referencia . "','BORRADO');";
                $this->prepare = $this->con->prepare($consulta);
                $this->prepare->execute();

                $this->consulta = "insert into serviciosborrados values (" . $this->arreglo[0]["idservicio"] . "," . $this->arreglo[0]["idempleado"] . ","
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
                return 1;
            }

            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function borrarSubValor($idvalor) {
        try {
            $this->con = new Conexion();
            $this->consulta = "delete from valores where idvalor=" . $idvalor . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarTotalRegistros() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * from servicios;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function verificarEstadoServicio($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select idservicio from evento where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function crearServicioVarios($detencion, $idservicio, $placa, $cedulaPropietario, $cedulaConductor, $direccionOrigen, $telefonoOrigen, $ciudadOrigen, $guia, $planilla, $auxiliar, $nitEmpresa, $direccionDestino, $telefonoDestino, $ciudadDestino, $fecha) {
        try {
            $this->con = new Conexion();
            if ($detencion === '1') {
                //modificar servicio en la tabla servicios
                $this->consulta = "update servicios set idcliente='" . $idservicio . "', placa='" . $placa . "',"
                        . "direccionorigen='" . $direccionOrigen . "',telefonoorigen=" . $telefonoOrigen . ",idciudadorigen=" . $ciudadOrigen . ","
                        . "direcciondestino='" . $idservicio . "',telefonodestino=" . $idservicio . ",idciudaddestino=" . $idservicio . ","
                        . "cedulapropietario=" . $cedulaPropietario . ",cedulaconductor=" . $cedulaConductor . ",fecha='" . $fecha . "' where idservicio=" . $idservicio . ";";
//                echo $this->consulta;
//                exit();
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "insert into serviciovariasguias (id,idservicio,guia,planilla,otro,idcliente,direccion,telefono,idciudaddestino,valorContratista,auxiliar,parqueadero,otros,costoTotal,valorFacturar) "
                        . "values (null," . $idservicio . "," . $guia . "," . $planilla . ",'" . $auxiliar . "','" . $nitEmpresa . "','" . $direccionDestino . "'," . $telefonoDestino . "," . $ciudadDestino . ",0,0,0,0,0,0);";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) values "
                        . "(" . $idservicio . ",'" . date("Y-m-d h:m:s") . "','" . $guia . "','CREACION');";
                //echo $this->consulta;exit();                        
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) values "
                        . "(" . $idservicio . ",'" . date("Y-m-d h:m:s") . "','" . $guia . "','POR FACTURAR');";
                //echo $this->consulta;exit();
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
            } else {
                $this->consulta = "insert into serviciovariasguias (id,idservicio,guia,planilla,otro,idcliente,direccion,telefono,idciudaddestino,valorContratista,auxiliar,parqueadero,otros,costoTotal,valorFacturar) "
                        . "values (null," . $idservicio . "," . $guia . "," . $planilla . ",'" . $auxiliar . "','" . $nitEmpresa . "','" . $direccionDestino . "'," . $telefonoDestino . "," . $ciudadDestino . ",0,0,0,0,0,0);";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                //echo $this->consulta;
//                exit();
                $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) values "
                        . "(" . $idservicio . ",'" . date("Y-m-d h:m:s") . "','" . $guia . "','CREACION');";
                //echo $this->consulta;
                //exit();                        
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) values "
                        . "(" . $idservicio . ",'" . date("Y-m-d h:m:s") . "','" . $guia . "','POR FACTURAR');";
                //echo $this->consulta;
                //exit();
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
            }

            $this->consulta = "select svg.id,svg.guia,svg.planilla,svg.otro,svg.direccion,svg.telefono, c.cli_nombre,mun.mun_nombre
from serviciovariasguias as svg,cliente as c,municipios as mun 
where svg.idcliente=c.cli_documento 
and svg.idciudaddestino=mun.mun_id
and svg.idservicio=" . $idservicio . ";";
//            echo $this->consulta; exit();
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arregloRetorno = $this->prepare->fetchAll();

            //echo $this->consulta;
            $this->con = null;
            return $this->arregloRetorno;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function crearServiciosVariosEmpresas($detencion, $idservicio, $placa, $cedulaPropietario, $cedulaConductor, $direccionOrigen, $telefonoOrigen, $ciudadOrigen, $guia, $planilla, $auxiliar, $nitEmpresa, $direccionDestino, $telefonoDestino, $ciudadDestino, $valorContratista, $auxiliarAyudante, $parqueadero, $otros, $costoServicio, $valorFacturar, $fecha) {
        try {
            $this->con = new Conexion();

            if ($detencion === '1') {
                //modificar servicio en la tabla servicios
                $this->consulta = "update servicios set idcliente='" . $idservicio . "', placa='" . $placa . "',"
                        . "direccionorigen='" . $direccionOrigen . "',telefonoorigen=" . $telefonoOrigen . ",idciudadorigen=" . $ciudadOrigen . ","
                        . "direcciondestino='" . $idservicio . "',telefonodestino=" . $idservicio . ",idciudaddestino=" . $idservicio . ","
                        . "cedulapropietario=" . $cedulaPropietario . ",cedulaconductor=" . $cedulaConductor . ",fecha='" . $fecha . "' where idservicio=" . $idservicio . ";";
//                echo $this->consulta;
//                exit();
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "insert into serviciovariasguias (id,idservicio,guia,planilla,otro,idcliente,direccion,telefono,idciudaddestino,valorContratista,auxiliar,parqueadero,otros,costoTotal,valorFacturar) "
                        . "values (null," . $idservicio . "," . $guia . "," . $planilla . ",'" . $auxiliar . "','" . $nitEmpresa . "','" . $direccionDestino . "'," . $telefonoDestino . "," . $ciudadDestino . "," . $valorContratista . "," . $auxiliarAyudante . "," . $parqueadero . "," . $otros . "," . $costoServicio . "," . $valorFacturar . ");";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) values "
                        . "(" . $idservicio . ",'" . date("Y-m-d h:m:s") . "','" . $guia . "','CREACION')";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) values "
                        . "(" . $idservicio . ",'" . date("Y-m-d h:m:s") . "','" . $guia . "','POR FACTURAR')";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
            } else {
                $this->consulta = "insert into serviciovariasguias (id,idservicio,guia,planilla,otro,idcliente,direccion,telefono,idciudaddestino,valorContratista,auxiliar,parqueadero,otros,costoTotal,valorFacturar) "
                        . "values (null," . $idservicio . "," . $guia . "," . $planilla . ",'" . $auxiliar . "','" . $nitEmpresa . "','" . $direccionDestino . "'," . $telefonoDestino . "," . $ciudadDestino . "," . $valorContratista . "," . $auxiliarAyudante . "," . $parqueadero . "," . $otros . "," . $costoServicio . "," . $valorFacturar . ");";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) values "
                        . "(" . $idservicio . ",'" . date("Y-m-d h:m:s") . "','" . $guia . "','CREACION')";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) values "
                        . "(" . $idservicio . ",'" . date("Y-m-d h:m:s") . "','" . $guia . "','POR FACTURAR')";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
            }

            $this->consulta = "select svg.id,svg.guia,svg.planilla,svg.otro,svg.direccion,svg.telefono, c.cli_nombre,mun.mun_nombre,
svg.`valorContratista`,svg.auxiliar,svg.parqueadero,svg.otros,svg.`costoTotal`,svg.`valorFacturar` 
from serviciovariasguias as svg,cliente as c,municipios as mun 
where svg.idcliente=c.cli_documento 
and svg.idciudaddestino=mun.mun_id 
and svg.idservicio=" . $idservicio . ";";
            //echo $this->consulta; exit();
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arregloRetorno = $this->prepare->fetchAll();

            //echo $this->consulta;
            $this->con = null;
            return $this->arregloRetorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function eliminarServicioVariasGuias($id, $idServicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "delete from serviciovariasguias where id=" . $id . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "select svg.id,svg.guia,svg.planilla,svg.otro,svg.direccion,svg.telefono, c.cli_nombre,mun.mun_nombre,
svg.`valorContratista`,svg.auxiliar,svg.parqueadero,svg.otros,svg.`costoTotal`,svg.`valorFacturar` 
from serviciovariasguias as svg,cliente as c,municipios as mun 
where svg.idcliente=c.cli_documento 
and svg.idciudaddestino=mun.mun_id 
and svg.idservicio=" . $idServicio . ";";
            //echo $this->consulta;exit();
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arregloRetorno = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arregloRetorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function equivocacionPosibleAnticipo($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "update posiblesanticipos set estado='EQUIVOCACION' where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $prueba["resultadoDos"] = $this->prepare->execute();
            $this->con = null;
            return $prueba;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function enviarCtaCobro($idservicio, $variasEmpresas) {
        try {
            $this->con = new Conexion();

            $guias = null;

            $this->consulta = "select cedulapropietario from servicios where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $arreglo = $this->prepare->fetchAll();
            $cedulapropietario = $arreglo[0]["cedulapropietario"];
            $this->consulta = "select cond_id from conductores where cond_identificacion=" . $cedulapropietario . " and estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $arreglo = $this->prepare->fetchAll();
            $cond_id = $arreglo[0]["cond_id"];

            if ($variasEmpresas === '1' || $variasEmpresas === 1) {

                $this->consulta = "select * "
                        . "from serviciovariasguias "
                        . "where idservicio=" . $idservicio . ";";

                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();

                $this->consulta = "select fecha from servicios where idservicio=" . $idservicio . ";";

                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $arreglo2 = $this->prepare->fetchAll();

                $fecha = $arreglo2[0]["fecha"];

                for ($index = 0; $index < count($this->arreglo); $index++) {
                    $guias = $guias . "-" . $this->arreglo[$index]["guia"];
                    $this->consulta = "select cli_id from cliente where cli_documento=" . $this->arreglo[$index]["idcliente"] . " and estado='ACTIVO';";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                    $arreglo = $this->prepare->fetchAll();
                    $id_empresa = $arreglo[0]["cli_id"];
                    $this->consulta = "insert into valoresanticipos (val_ant_id,val_fechaAnticipo,val_valorAdelanto,valorservicio,val_id_empresa,val_numeroGuia,conductores_cond_id,placa,totalesAnticipos_val_id,val_numeroAnticipo,prueba_entrega,idservicio) "
                            . "values (null,'" . date("Y-m-d h:m") . "',0," . $this->arreglo[$index]["costoTotal"] . "," . $id_empresa . "," . $this->arreglo[$index]["guia"] . "," . $cond_id . ",'" . $this->arreglo[$index]["placa"] . "',42,0,'N'," . $idservicio . ");";

                    $this->prepare = $this->con->prepare($this->consulta);
                    $prueba["resultado"] = $this->prepare->execute();
                }
            } else {
                $this->consulta = "select  s.valortotal,s.valorcliente,svg.guia,svg.idcliente,s.cedulapropietario,s.fecha,s.placa "
                        . "from servicios as s, serviciovariasguias as svg "
                        . "where s.idservicio=svg.idservicio "
                        . "and s.idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $arreglo = $this->prepare->fetchAll();

                $nitcliente = $arreglo[0]["idcliente"];
                $valortotal = $arreglo[0]["valortotal"];
                $valorcliente = $arreglo[0]["valorcliente"];
                $fecha = $arreglo[0]["fecha"];
                $placa = $arreglo[0]["placa"];

                $this->consulta = "select cli_id from cliente where cli_documento=" . $nitcliente . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $id_empresa = $this->arreglo[0]["cli_id"];

                for ($index1 = 0; $index1 < count($arreglo); $index1++) {
                    $guias .= $arreglo[$index1]["guia"] . "-";
                }

                $guias = substr($guias, 0, -1);

                $this->consulta = "insert into valoresanticipos (val_ant_id,val_fechaAnticipo,val_valorAdelanto,valorservicio,val_id_empresa,val_numeroGuia,conductores_cond_id,placa,totalesAnticipos_val_id,val_numeroAnticipo,prueba_entrega,idservicio) "
                        . "values (null,'" . date("Y-m-d h:m") . "',0," . $valortotal . "," . $id_empresa . ",'" . $guias . "'," . $cond_id . ",'" . $placa . "',42,0,'N'," . $idservicio . ");";
                $this->prepare = $this->con->prepare($this->consulta);
                $prueba["resultado"] = $this->prepare->execute();
            }


            $this->con = null;

            return $prueba;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarCedulaServicio($cedula, $servicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "update servicios set idempleado=" . $cedula . " where idservicio=" . $servicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $resultado["resultado"] = $this->prepare->execute();
            $this->con = null;
            return $resultado;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /*
     * 201904231408
     * El método retorna un cero cuando no encuentra la guia.
     * Se modifica para buscar en las nuevas tablas creadas
     */

    public function retornarServicioPorGuia($guia) {
        try {
            $this->con = new Conexion();
            $resultado = array();
            $this->consulta = "select svg.idservicio,s.fecha,svg.guia,s.placa "
                    . "from serviciovariasguias as svg, servicios as s "
                    . "where svg.idservicio=s.idservicio "
                    . "and svg.guia=" . $guia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (empty($this->arreglo)) {

                $this->consulta = "select s.idservicio,s.fechaServicio as fecha,"
                        . "sg.numeroGuia as guia,s.placa "
                        . "from servicio as s,servicio_guias as sg "
                        . "where s.idservicio=sg.idservicio "
                        . "and sg.numeroGuia=" . $guia . ";";
                //echo $this->consulta;
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();

                if (empty($this->arreglo)) {
                    return $resultado["idservicio"] = 0;
                } else {
                    return $resultado["guia"] = $this->arreglo;
                }
            } else {
                return $resultado["idservicio"] = $this->arreglo;
            }
            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function mostrarServicioClienteFechas($fechaInicial, $fechaFinal, $idcliente) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select s.idservicio,s.fecha,s.guia as guiaS,svg.guia as guiaSVG "
                    . "from servicios as s, serviciovariasguias as svg "
                    . "where s.idservicio=svg.idservicio "
                    . "and s.fecha>='" . $fechaInicial . "' "
                    . "and s.fecha<='" . $fechaFinal . "' "
                    . "and svg.idcliente=" . $idcliente . " "
                    . "group by s.idservicio;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarDatosServicioDos($idservicio) {
        try {
            $retorno = array();
            $this->con = new Conexion();
            $this->consulta = "select s.placa,svg.idcliente,c.cli_nombre,s.fecha as fechaServicio,s.valorcliente,s.valorapagar,ps.factura,ps.numeroguia,"
                    . "concat(co.cond_nombres,' ',co.cond_apellidos) as nombrePropietario "
                    . "from servicios as s,cliente as c,serviciovariasguias as svg,posiblesfacturas as ps,conductores as co "
                    . "where svg.idcliente=c.cli_documento "
                    . "and s.cedulapropietario=co.cond_identificacion "
                    . "and svg.idservicio=" . $idservicio . " "
                    . "and s.idservicio=" . $idservicio . " "
                    . "and ps.idservicio=" . $idservicio . " "
                    . "group by ps.numeroguia; ";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            /*
             * 201905061427
             * Si no encuentra en las anteriores tablas busca 
             * en las nuevas
             */
            if (count($this->arreglo) === 0) {
                $this->consulta = "select s.idservicio,s.placa,sg.nit as idcliente,c.cli_nombre,s.fechaServicio,sg.valorCobrado as valorcliente,sg.valorPagado as valorapagar,"
                        . "ps.factura,ps.numeroguia,concat(co.cond_nombres,' ',co.cond_apellidos) as nombrePropietario "
                        . "from servicio_guias as sg,servicio as s,cliente as c,conductores as co,posiblesfacturas as ps "
                        . "where sg.idservicio=s.idservicio "
                        . "and sg.nit=c.cli_documento "
                        . "and sg.idservicio=s.idservicio "
                        . "and sg.idservicio=ps.idservicio "
                        . "and s.cedulaPropietario=co.cond_identificacion "
                        . "and s.idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
            }

            $retorno[0] = $this->arreglo;
            $this->consulta = "select * from valoresanticipos where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $retorno[1] = $this->arreglo;
            $this->con = null;
            return $retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function mostrarDatosServicio($idservicio) {
        try {
            $this->con = new Conexion();
            $retorno = array();

            //retorno datos del servicio
            $this->consulta = "select s.idservicio,s.idempleado,s.fecha,s.placa,s.direccionorigen,s.idciudadorigen,"
                    . "s.telefonoorigen,s.cedulapropietario,s.cedulaconductor,s.valortotal,s.valorapagar,s.valorcliente "
                    . "from servicios  as s "
                    . "where s.idservicio=" . $idservicio . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            if (count($this->arreglo) === 0) {
                $this->consulta = "select * "
                        . "from servicio "
                        . "where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();

                if (count($this->arreglo) === 0) {
                    return $retorno["servicios"] = array("decision" => 0);
                } else {
                    return $retorno["servicios"] = $idservicio;
                }
                exit();
            }

            if ($this->arreglo[0]["placa"] === '') {
                //201808020934 Informar quien fue el que intento crear el servicio pero a la final no lo hizo
                $this->consulta = "select idempleado from servicioempleadohora where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();

                if (count($this->arreglo) === 0) {
                    return $retorno["servicios"] = array("decision" => 1);
                } else {
                    $this->consulta = "select concat(e.emp_nombres,' ',e.emp_apellidos) as nombresEmpleado "
                            . "from empleados as e "
                            . "where e.emp_cedula=" . $this->arreglo[0]["idempleado"];
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                    $this->arreglo = $this->prepare->fetchAll();
                    return $retorno["servicios"] = array("decision" => 2, "nombresEmpleado" => $this->arreglo[0]["nombresEmpleado"]);
                    exit();
                }
            }

            $idciudadorigen = $this->arreglo[0]["idciudadorigen"];
            $cedulaAsesor = $this->arreglo[0]["idempleado"];
            $cedulaPropietario = $this->arreglo[0]["cedulapropietario"];
            $cedulaConductor = $this->arreglo[0]["cedulaconductor"];
            $retorno["servicios"] = $this->arreglo;

            //retorno el municipio de origen
            if ($idciudadorigen === '0') {
                $retorno["ciudadorigen"] = "BOGOTA";
            } else {
                $this->consulta = "select mun_nombre from municipios where mun_id=" . $idciudadorigen . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $retorno["ciudadorigen"] = $this->arreglo[0]["mun_nombre"];
            }
            //retorno datos del asesor
            $this->consulta = "select concat(emp_nombres,' ',emp_apellidos) as nombresAsesor,emp_cedula as cedulaAsesor "
                    . "from empleados "
                    . "where emp_cedula=" . $cedulaAsesor . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $retorno["datosAsesor"] = $this->arreglo;

            //retorno datos del empleado que crea el servicio
            $this->consulta = "select concat(e.emp_nombres,' ',e.emp_apellidos) as nombresEmpleado,fechahora,e.emp_cedula "
                    . "from servicioempleadohora as seh,empleados as e "
                    . "where seh.idempleado=e.emp_cedula "
                    . "and seh.idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $retorno["creaServicio"] = $this->arreglo;

            /* 201808011250            
             * retorno la lista de asesores 
             * Para poder modificar el asesor del servicio por parte de la Sra. Alba
             */
            $this->consulta = "select concat(e.emp_nombres,' ',e.emp_apellidos) as nombreEmpelado,e.emp_cedula "
                    . "from empleados as e "
                    . "where e.roles_rol_id=8 "
                    . "and estado='A';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $retorno["asesores"] = $this->arreglo;

            //retorno datos del propietario
            $this->consulta = "select * from conductores where cond_identificacion='" . $cedulaPropietario . "' and estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $retorno["propietario"] = $this->arreglo;

            //retorno datos del conductor
            $this->consulta = "select * from conductores where cond_identificacion='" . $cedulaConductor . "' and estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $retorno["conductor"] = $this->arreglo;

            if (count($this->arreglo) === 0) {
                return $retorno["servicios"] = array("decision" => 3);
                exit();
            }

            //retorno datos por si tiene varias paradas
            $this->consulta = "select svg.id,svg.idservicio,svg.guia,svg.planilla,svg.otro,svg.idcliente,svg.direccion,svg.telefono,svg.idciudaddestino,svg.valorContratista,svg.auxiliar,svg.parqueadero,svg.otros,svg.costoTotal,svg.valorFacturar,"
                    . "c.cli_nombre,m.mun_nombre "
                    . "from serviciovariasguias as svg,cliente as c,municipios as m "
                    . "where svg.idcliente=c.cli_documento "
                    . "and svg.idciudaddestino=m.mun_id "
                    . "and idservicio=" . $idservicio . " "
                    . "and svg.guia>0; ";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            //echo $this->consulta;

            if (count($this->arreglo) === 0) {
                return $retorno["servicios"] = array("decision" => 4);
                exit();
            }

            $retorno["serviciovariasguias"] = $this->arreglo;

            //retorno datos de los anticipos
            $this->consulta = "select * "
                    . "from valoresanticipos "
                    . "where idservicio=" . $idservicio . "; ";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $retorno["valoresanticipos"] = $this->arreglo;

            //retorno datos del posibles facturas
            $this->consulta = "select * from posiblesfacturas where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            if (count($this->arreglo) === 0) {
                return $retorno["servicios"] = array("decision" => 5);
                exit();
            }
            $retorno["posiblesfacturas"] = $this->arreglo;

            //retorno motivos de cancelación
            $this->consulta = "select spc.id,spc.idservicio,spc.idempleado,spc.fecha,spc.motivo,"
                    . "concat(e.emp_nombres,' ',e.emp_apellidos) as nombreEmpleado "
                    . "from serviciosporcancelar as spc,empleados as e "
                    . "where spc.idempleado=e.emp_cedula "
                    . "and spc.idservicio=" . $idservicio . " "
                    . "and spc.estado='CANCELAR';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $retorno["motivo"] = $this->arreglo;

            /*
             * 201808301531
             * Retorno valor declarado por cada guía
             */
            $this->consulta = "select * "
                    . "from valordeclarado "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $retorno["valordeclarado"] = $this->arreglo;

            /*
             * 201809221744
             * Retorno los valores extras, es decir auxiliar, parqueadero y
             * otros
             */
            $this->consulta = "select * "
                    . "from valores "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $retorno["valores"] = $this->arreglo;

            /*
             * 201810251202
             * Retorno los comentarios hechos al servicio
             */

            $this->consulta = "select comentario "
                    . "from comentariosservicios "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $retorno["comentarios"] = $this->arreglo;

            /*
             * 201901291449
             * Retorno los valores contenidos en seguimiento del servicio
             */
            $this->consulta = "select s.guia,s.`fechaHora`,s.ubicacion,s.observacion,s.imagen,s.planderuta,concat(e.emp_nombres,' ',e.emp_apellidos) as empleado,s.estadoseguimiento "
                    . "from seguimiento as s,empleados as e "
                    . "where s.emp_cedula=e.emp_cedula "
                    . "and s.idservicio=" . $idservicio . " "
                    . "order by s.guia asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $retorno["seguimiento"] = $this->arreglo;

            $this->con = null;
            return $retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function crearValorDeclaradoFaltante($idservicio) {
        try {
            $this->con = new Conexion();

            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function reasignarServicio($idservicio, $placa, $cedulaPropietario) {
        try {
            $this->con = new Conexion();
            //reasignar servicio a placa
            $this->consulta = "update servicios set placa='" . $placa . "',cedulapropietario=" . $cedulaPropietario . ",cedulaconductor=" . $cedulaPropietario . " "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            //reasignar posiblesanticipos
            $this->consulta = "update posiblesanticipos set placa='" . $placa . "' where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "select referencia from trasabilidad where idservicio=" . $idservicio . " and evento='CREACION'; ";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            for ($index = 0; $index < count($this->arreglo); $index++) {
                $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) "
                        . "values (" . $idservicio . ",'" . date("Y-m-d h:m:s") . "'," . $this->arreglo[$index]["referencia"] . ",'REASIGNADO " . $placa . "');";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
            }

            $this->consulta = "select valorFacturar from serviciovariasguias where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function serviciosPorFechas($fechaInicial, $fechaFinal, $facturados, $opcion2, $placa) {
        $propietarios = array();
        $conductores = array();
        $arreglo = array();
        $asesores = array();
        $empresas = array();
        try {
            $this->con = new Conexion();

//             $this->consulta .= "and (seguimiento.estadoseguimiento='Despacho finalizado sin novedad' "
//                    . "OR seguimiento.estadoseguimiento='Despacho finalizado con novedad' "
//                    . "OR seguimiento.estadoseguimiento='En transito') ";

            switch ($facturados) {
                case 0:
                    $this->consulta .= "and servicio.fechaServicio between '" . $fechaInicial . "' and '" . $fechaFinal . "' ";
                    break;
                case 1:
                    $this->consulta .= "and servicio.fechaServicio between '" . $fechaInicial . "' and '" . $fechaFinal . "' "
                            . "and servicio_guias.numeroFactura <> '0' ";
                    break;
                case 2:
                    $this->consulta .= "and servicio.fechaServicio between '" . $fechaInicial . "' and '" . $fechaFinal . "' "
                            . "and servicio_guias.numeroFactura = 0 "
                            . "and servicio_guias.fechaPago <> '1000-01-01' ";
                    break;
                case 3:
                    $this->consulta .= "and servicio_guias.numeroFactura=" . $fechaInicial . " ";
                    break;
            }

            switch ($opcion2) {
                case 1:
                    //$this->consulta .= "order by servicio_guias.numeroGuia ";
                    break;
                case 2:
//                    $this->consulta .= "group by servicio_guias.numeroGuia "
//                            . "order by servicio_guias.numeroFactura;";
                    break;
                case 3:
                    $this->consulta .= "and servicio.placa='" . $placa . "' ";
                    break;
                case 4:
                    //$this->consulta .= "";
                    break;
            }

            $this->consulta .= "group by servicio_guias.numeroGuia "
                    . "order by servicio.fechaServicio,servicio_guias.numeroGuia;";

//            echo $this->consulta;
//            exit();

            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arregloRetorno["servicios"] = $this->prepare->fetchAll();
            $arreglo = $this->arregloRetorno["servicios"];

            for ($index = 0; $index < count($arreglo); $index++) {
                $this->consulta = null;
                $this->consulta = "select concat(cond_nombres,' ',cond_apellidos) as nombrePropietario,cond_telefono as telProp "
                        . "from conductores "
                        . "where cond_identificacion=" . $arreglo[$index]["cedulaPropietario"] . " ;";
// se quieta el estado para verificar que los reportes salgan correctos
//                        . "and estado='ACTIVO';";
                //echo $this->consulta;
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $propietarios[$index]["nombrePropietario"] = $this->arreglo[0]["nombrePropietario"];
                $propietarios[$index]["telProp"] = $this->arreglo[0]["telProp"];
                $this->consulta = null;
                $this->consulta = "select concat(cond_nombres,' ',cond_apellidos) as nombreConductor,cond_telefono as telCond "
                        . "from conductores "
                        . "where cond_identificacion=" . $arreglo[$index]["cedulaConductor"] . " "
                        . "and estado='ACTIVO';";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                if (count($this->arreglo) === 0) {
                    $conductores[$index]["nombreConductor"] = '---';
                    $propietarios[$index]["telCond"] = '---';
                } else {
                    $conductores[$index]["nombreConductor"] = $this->arreglo[0]["nombreConductor"];
                    $propietarios[$index]["telCond"] = $this->arreglo[0]["telCond"];
                }
                $this->consulta = null;
                $this->consulta = "select concat(empleados.emp_nombres,' ',empleados.emp_apellidos) as nombreAsesor "
                        . "from asesor_empresa,empleados "
                        . "where asesor_empresa.cedula=empleados.emp_cedula "
                        . "and asesor_empresa.nit=" . $arreglo[$index]["nit"] . ";";
                //echo $this->consulta;
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $asesores[$index]["nombreAsesor"] = $this->arreglo[0]["nombreAsesor"];

                $this->consulta = "select cli_nombre "
                        . "from cliente "
                        . "where cli_documento=" . $arreglo[$index]["nit"] . ";";
                //echo $this->consulta;
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $empresas[$index]["nombreEmpresa"] = $this->arreglo[0]["cli_nombre"];
            }
            $this->arregloRetorno["propietarios"] = $propietarios;
            $this->arregloRetorno["conductores"] = $conductores;
            $this->arregloRetorno["asesores"] = $asesores;
            $this->arregloRetorno["empresas"] = $empresas;

            $arregloServicios = $this->arregloRetorno["servicios"];

            $this->con = null;
            return $this->arregloRetorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarSeguimientosAsesor($fechaInicial, $fechaFinal, $asesor) {
        $this->con = new Conexion();

        $this->consulta = "SELECT seguimiento.fechaHora as fechaSeguimiento,seguimiento.guia,concat(seguimiento.ubicacion,',',seguimiento.observacion) as reporte,"
                . "servicio.placa,concat(empleados.emp_nombres,' ',empleados.emp_apellidos) as nombreEmpleado "
                . "FROM seguimiento,seguimiento_servicio,empleados,servicio "
                . "WHERE seguimiento.idservicio=seguimiento_servicio.idservicio "
                . "AND seguimiento.emp_cedula=empleados.emp_cedula "
                . "AND seguimiento.idservicio=servicio.idservicio "
                . "AND seguimiento.emp_cedula=".$asesor." "
                . "AND seguimiento.fechaHora BETWEEN '".$fechaInicial."' AND '".$fechaFinal."' "
                . "ORDER BY seguimiento.fechaHora; ";

        $this->prepare = $this->con->prepare($this->consulta);
        $this->prepare->execute();
        $this->arreglo = $this->prepare->fetchAll();
        $this->con = null;

        return $this->arreglo;
    }

    public function agrupadoPorFactura($fechaInicial, $fechaFinal) {

        $this->con = new Conexion();

        $this->consulta = "select distinct s.idservicio,s.idempleado,s.fechaServicio,s.placa,v.tipovehiculo,s.cedulaPropietario,s.cedulaConductor,"
                . "sg.numeroGuia,sg.auxiliar,sg.parqueadero,sg.otros,sg.valorDeclarado,sg.valorPagado,sg.valorCobrado,sg.nit,sg.fechaFactura,"
                . "sg.numeroFactura,sg.fechaPago,sg.fechaPruebaEntrega,"
                . "(select cli_nombre from cliente where cli_documento=sg.nit and estado='ACTIV0') as nombreCliente,"
                . "(select mun_nombre from municipios where mun_id=(select d.ciudad from direcciones as d where d.iddireccion=sg.iddireccionorigen)) as ciudadOrigen,"
                . "(select mun_nombre from municipios where mun_id=(select d.ciudad from direcciones as d where d.iddireccion=sg.iddirecciondestino)) as ciudadDestino,"
                . "(select concat(emp_nombres,' ',emp_apellidos) as nombreAsesor from empleados where emp_cedula=sg.nit) as nombreCliente,"
                . "(select cli_nombre from cliente where cli_documento=sg.nit and estado='ACTIVO') as nombreCliente2,"
                . "(select ss.manifiesto from seguimiento_servicio as ss where ss.idservicio=s.idservicio) as manifiesto,"
                . "pf.factura from servicio_guias as sg,servicio as s,posiblesfacturas as pf,vehiculo as v "
                . "where s.idservicio=sg.idservicio "
                . "and sg.numeroGuia=pf.numeroguia "
                . "and s.placa=v.placa "
                . "and sg.fechaFactura>='" . $fechaInicial . "' "
                . "and sg.fechaFactura<='" . $fechaFinal . "' "
                . "order by sg.numeroGuia desc; ";

        $this->prepare = $this->con->prepare($this->consulta);
        $this->prepare->execute();
        $this->arreglo = $this->prepare->fetchAll();

        $this->consulta = "delete from resumen_servicios;";
        $this->prepare = $this->con->prepare($this->consulta);
        $this->prepare->execute();

        for ($index2 = 0; $index2 < count($this->arreglo); $index2++) {
            $this->consulta = "insert into resumen_servicios (idservicio,fechaFactura,nombreCliente,numeroFactura,valorCobrado) "
                    . "values(" . $this->arreglo[$index2]["idservicio"] . ",'" . $this->arreglo[$index2]["fechaFactura"] . "',"
                    . "'" . $this->arreglo[$index2]["nombreCliente2"] . "'," . $this->arreglo[$index2]["numeroFactura"] . ","
                    . "" . $this->arreglo[$index2]["valorCobrado"] . ")";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
        }
        $this->consulta = "SELECT SUM(resumen_servicios.valorCobrado) as valorCobrado,resumen_servicios.nombreCliente,resumen_servicios.fechaFactura,resumen_servicios.numeroFactura "
                . "FROM resumen_servicios "
                . "GROUP BY resumen_servicios.numeroFactura,resumen_servicios.nombreCliente,resumen_servicios.fechaFactura,resumen_servicios.numeroFactura "
                . "ORDER BY resumen_servicios.numeroFactura ASC ";
        $this->prepare = $this->con->prepare($this->consulta);
        $this->prepare->execute();
        $this->con = null;
        return $this->prepare->fetchAll();
    }

    public function serviciosPorFechasAsesor($fechaInicial, $fechaFinal, $idasesor) {
        $propietarios = array();
        $conductores = array();
        $arreglo = array();
        $asesores = array();
        $empresas = array();
        try {
            $this->con = new Conexion();

//            $this->consulta .= "and (seguimiento.estadoseguimiento='Despacho finalizado sin novedad' "
//                    . "OR seguimiento.estadoseguimiento='Despacho finalizado con novedad' "
//                    . "OR seguimiento.estadoseguimiento='En transito') ";

            $this->consulta .= "and servicio.fechaServicio between '" . $fechaInicial . "' and '" . $fechaFinal . "' "
                    . "and asesor_empresa.cedula=" . $idasesor . " "
                    . "group by servicio_guias.numeroGuia "
                    . "order by servicio.fechaServicio,servicio_guias.numeroGuia;";

//            echo $this->consulta;
//            exit();
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arregloRetorno["servicios"] = $this->prepare->fetchAll();
            $arreglo = $this->arregloRetorno["servicios"];

            for ($index = 0; $index < count($arreglo); $index++) {
                $this->consulta = null;
                $this->consulta = "select concat(cond_nombres,' ',cond_apellidos) as nombrePropietario,cond_telefono as telProp "
                        . "from conductores "
                        . "where cond_identificacion=" . $arreglo[$index]["cedulaPropietario"] . " "
                        . "and estado='ACTIVO';";
                //echo $this->consulta;
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $propietarios[$index]["nombrePropietario"] = $this->arreglo[0]["nombrePropietario"];
                $propietarios[$index]["telProp"] = $this->arreglo[0]["telProp"];
                $this->consulta = null;
                $this->consulta = "select concat(cond_nombres,' ',cond_apellidos) as nombreConductor,cond_telefono as telCond "
                        . "from conductores "
                        . "where cond_identificacion=" . $arreglo[$index]["cedulaConductor"] . " "
                        . "and estado='ACTIVO';";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                if (count($this->arreglo) === 0) {
                    $conductores[$index]["nombreConductor"] = '---';
                    $propietarios[$index]["telCond"] = '---';
                } else {
                    $conductores[$index]["nombreConductor"] = $this->arreglo[0]["nombreConductor"];
                    $propietarios[$index]["telCond"] = $this->arreglo[0]["telCond"];
                }
                $this->consulta = null;
                $this->consulta = "select concat(e.emp_nombres,' ',e.emp_apellidos) as nombreAsesor "
                        . "from asesor_empresa as ae,empleados as e "
                        . "where ae.cedula=e.emp_cedula "
                        . "and ae.nit=" . $arreglo[$index]["nit"] . ";";
                //echo $this->consulta;
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $asesores[$index]["nombreAsesor"] = $this->arreglo[0]["nombreAsesor"];

                $this->consulta = "select cli_nombre "
                        . "from cliente "
                        . "where cli_documento=" . $arreglo[$index]["nit"] . ";";
                //echo $this->consulta;
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $empresas[$index]["nombreEmpresa"] = $this->arreglo[0]["cli_nombre"];
            }
            $this->arregloRetorno["propietarios"] = $propietarios;
            $this->arregloRetorno["conductores"] = $conductores;
            $this->arregloRetorno["asesores"] = $asesores;
            $this->arregloRetorno["empresas"] = $empresas;

            $this->con = null;
            return $this->arregloRetorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function serviciosPorFechasEmpresa($fechaInicial, $fechaFinal, $nitEmpresa) {

        $propietarios = array();
        $conductores = array();
        $arreglo = array();
        $asesores = array();
        $empresas = array();
        try {
            $this->con = new Conexion();

            $this->consulta .= "and servicio_guias.nit=" . $nitEmpresa . " "
                    . "group by servicio_guias.numeroGuia "
                    . "order by servicio_guias.numeroGuia;";

            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arregloRetorno["servicios"] = $this->prepare->fetchAll();
            $arreglo = $this->arregloRetorno["servicios"];

            for ($index = 0; $index < count($arreglo); $index++) {
                $this->consulta = null;
                $this->consulta = "select concat(cond_nombres,' ',cond_apellidos) as nombrePropietario "
                        . "from conductores "
                        . "where cond_identificacion=" . $arreglo[$index]["cedulaPropietario"] . " "
                        . "and estado='ACTIVO' ";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $propietarios[$index]["nombrePropietario"] = $this->arreglo[0]["nombrePropietario"];
                $this->consulta = null;
                $this->consulta = "select concat(cond_nombres,' ',cond_apellidos) as nombreConductor "
                        . "from conductores "
                        . "where cond_identificacion=" . $arreglo[$index]["cedulaConductor"] . " "
                        . "and estado='ACTIVO' ";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $conductores[$index]["nombreConductor"] = $this->arreglo[0]["nombreConductor"];
                $this->consulta = null;
                $this->consulta = "select concat(e.emp_nombres,' ',e.emp_apellidos) as nombreAsesor "
                        . "from asesor_empresa as ae,empleados as e "
                        . "where ae.cedula=e.emp_cedula "
                        . "and ae.nit=" . $arreglo[$index]["nit"] . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $asesores[$index]["nombreAsesor"] = $this->arreglo[0]["nombreAsesor"];

                $this->consulta = "select cli_nombre "
                        . "from cliente "
                        . "where cli_documento=" . $arreglo[$index]["nit"] . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $empresas[$index]["nombreEmpresa"] = $this->arreglo[0]["cli_nombre"];
            }
            $this->arregloRetorno["propietarios"] = $propietarios;
            $this->arregloRetorno["conductores"] = $conductores;
            $this->arregloRetorno["asesores"] = $asesores;
            $this->arregloRetorno["empresas"] = $empresas;

            $this->con = null;
            return $this->arregloRetorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarFactura($factura, $idservicio, $guia) {
        try {

            $this->con = new Conexion();

            $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) "
                    . "values (" . $idservicio . ",'" . date('Y-m-d H:m:s') . "','" . $guia . "','CAMB FACT MODU ADMIN');";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "update servicio_guias "
                    . "set fechaFactura='" . date('Y-m-d') . "',"
                    . "numeroFactura=" . $factura . " "
                    . "where idservicio=" . $idservicio . " "
                    . "and numeroGuia=" . $guia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "update posiblesfacturas "
                    . "set factura=" . $factura . ","
                    . "fecha='" . date('Y-m-d H:m:s') . "' "
                    . "where idservicio=" . $idservicio . " "
                    . "and numeroguia='" . $guia . "';";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarAnticiposPendientes($condicion) {
        try {
            $this->con = new Conexion();
            if ($condicion === 1) {
                $this->consulta = "select va.val_fechaAnticipo,va.idservicio,va.val_valorAdelanto,va.valorservicio,va.val_numeroGuia,s.placa,concat(c.cond_nombres,' ',c.cond_apellidos) as nombresPropietario "
                        . "from valoresanticipos as va,servicio as s,conductores as c "
                        . "where va.idservicio=s.idservicio "
                        . "and s.cedulaPropietario=c.cond_identificacion "
                        . "and va.prueba_entrega='N' "
                        . "order by va.idservicio,s.placa asc;";
            }
            if ($condicion === 0) {
                $this->consulta = "select va.val_fechaAnticipo,va.idservicio,va.val_valorAdelanto,va.valorservicio,va.val_numeroGuia,s.placa,concat(c.cond_nombres,' ',c.cond_apellidos) as nombresPropietario "
                        . "from valoresanticipos as va,servicios as s,conductores as c "
                        . "where va.idservicio=s.idservicio "
                        . "and s.cedulapropietario=c.cond_identificacion "
                        . "and va.prueba_entrega='N' "
                        . "order by va.idservicio,s.placa asc;";
            }
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            if ($condicion === 1) {
                return count($this->arreglo);
            } else {
                return $this->arreglo;
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /*
     * Esta funcion esta en la clase rol_boton.php
     */

    public function retornarServiciosPorFacturar($condicion) {
        try {
            $this->con = new Conexion();
            if ($condicion === 1) {
                $this->consulta = "select sg.idservicio,sg.numeroGuia,sg.valorCobrado,sg.nit,"
                        . "(select distinct cliente.cli_nombre from cliente where cliente.cli_documento=sg.nit and cliente.estado='ACTIVO') as empresa,"
                        . "s.cedulaPropietario,s.cedulaConductor,s.fechaServicio "
                        . "from servicio_guias as sg,servicio as s "
                        . "where sg.idservicio=s.idservicio "
                        . "and sg.numeroFactura=0 "
                        . "and sg.numeroGuia<>0 "
                        . "and sg.valorCobrado<>0 "
                        . "order by s.fechaServicio asc;";
            } else {
                $this->consulta = "select count(sg.idservicioguia) as total "
                        . "from servicio_guias as sg,servicio as s "
                        . "where sg.idservicio=s.idservicio "
                        . "and sg.numeroFactura=0 "
                        . "and sg.numeroGuia<>0 "
                        . "and sg.valorCobrado<>0;";
            }
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;

            if ($condicion === 1) {
                return $this->arreglo;
            } else {
                return $this->arreglo[0]["total"];
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarServiciosPorCancelar() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * from serviciosporcancelar where estado='CANCELAR';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return count($this->arreglo);
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarAsesorServicio($idservicio, $idempleado) {
        try {
            $this->con = new Conexion();
            $this->consulta = "update servicios set idempleado=" . $idempleado . " where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

//    public function retornarServiciosPorNumeroFactura($factura) {
//        try {
//            $this->con = new Conexion();
//
////            $this->consulta = "SELECT servicio_guias.valorCobrado as valorempresa ,servicio_guias.numeroGuia as val_numeroGuia,servicio_guias.idservicio,servicio_guias.fechaFactura as fecha,servicio_guias.valorCobrado as valorempresa,"
////                    . "(SELECT concat(conductores.cond_nombres,' ',conductores.cond_apellidos) FROM conductores WHERE conductores.cond_identificacion=servicio.cedulaPropietario AND conductores.estado='ACTIVO') AS nombreConductor,"
////                    . "servicio.placa,servicio.cedulaPropietario,valoresanticipos.val_fechaAnticipo,servicio.idservicio,"
////                    . "cliente.cli_nombre as empresa,servicio_guias.valorPagado as valorservicio,"
////                    . "(servicio_guias.auxiliar+servicio_guias.parqueadero+(SELECT SUM(otrosCostos.valor) as otroscostos FROM otrosCostos WHERE otrosCostos.numeroGuia=servicio_guias.numeroGuia)) as otrosCostos "
////                    . "FROM servicio_guias,servicio,valoresanticipos,cliente "
////                    . "WHERE servicio.idservicio=servicio_guias.idservicio "
////                    . "AND servicio_guias.numeroGuia=valoresanticipos.val_numeroGuia "
////                    . "AND servicio_guias.nit=cliente.cli_documento "
////                    . "AND cliente.estado='ACTIVO' "
////                    . "AND servicio_guias.numeroFactura=" . $factura . " "
////                    . "GROUP BY servicio_guias.numeroGuia;";
//            
//                    $this->consulta = "SELECT servicio.idservicio,empleados.emp_cedula,servicio.fechaServicio,servicio.placa,"
//                . "servicio.cedulaPropietario,servicio.cedulaConductor,servicio_guias.numeroGuia,servicio_guias.auxiliar,"
//                . "servicio_guias.parqueadero,servicio_guias.otros,"
//                . "(SELECT SUM(otrosCostos.valor) as otrosCostos FROM otrosCostos WHERE otrosCostos.idservicio=servicio.idservicio) as otrosCostos,"
//                . "servicio_guias.valorDeclarado,servicio_guias.valorPagado,servicio_guias.valorCobrado,servicio_guias.nit,"
//                . "servicio_guias.fechaFactura,servicio_guias.factura,servicio_guias.fechaPago,servicio_guias.fechaPruebaEntrega,"
//                . "concat(empleados.emp_nombres,' ',empleados.emp_apellidos) as nombreAsesor,"
//                . "servicio_guias.numeroCuentaCobro,servicio_guias.fechaTransferencia,"
//                . "(SELECT seguimiento_servicio.manifiesto FROM  seguimiento_servicio WHERE seguimiento_servicio.idservicio=servicio.idservicio) as manifiesto,"
//                . "(SELECT vehiculo.tipovehiculo FROM vehiculo WHERE vehiculo.placa=servicio.placa) as tipovehiculo,"
//                . "(select mun_nombre from municipios where municipios.mun_id=(select direcciones.ciudad from direcciones where direcciones.iddireccion=servicio_guias.iddireccionorigen)) as ciudadOrigen,(select mun_nombre from municipios where municipios.mun_id=(select direcciones.ciudad from direcciones where direcciones.iddireccion=servicio_guias.iddirecciondestino)) as ciudadDestino,"
//                . "(SELECT concat(conductores.cond_nombres,' ',conductores.cond_apellidos) FROM conductores WHERE conductores.cond_identificacion=servicio.cedulaPropietario AND conductores.estado='ACTIVO') AS nombreConductor "
//                . "FROM servicio_guias,servicio,asesor_empresa,empleados "
//                . "WHERE servicio_guias.idservicio=servicio.idservicio "
//                . "AND servicio_guias.nit=asesor_empresa.nit "
//                . "AND empleados.emp_cedula=asesor_empresa.cedula "
//                                               . "AND servicio_guias.numeroFactura=" . $factura . " "
////                    . "GROUP BY servicio_guias.numeroGuia;";
//
////echo $this->consulta;
//
//            $this->prepare = $this->con->prepare($this->consulta);
//            $this->prepare->execute();
//            $this->arreglo = $this->prepare->fetchAll();
//            $this->con = null;
//            return $this->arreglo;
//        } catch (PDOException $exc) {
//            echo $exc->getTraceAsString();
//        }
//    }

    public function crearPruebaEntrega($guia) {
        try {
            $retorno = array();
            $this->con = new Conexion();
            $this->consulta = "select idservicio "
                    . "from serviciovariasguias "
                    . "where guia=" . $guia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            if (count($this->arreglo) === 0) {
                $this->consulta = "select idservicio "
                        . "from servicio_guias "
                        . "where numeroGuia=" . $guia . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $retorno[0] = $this->arreglo[0]["idservicio"];
            }

            $this->consulta = "update servicio_guias "
                    . "set fechaPruebaEntrega='" . date('Y-m-d') . "' "
                    . "where numeroGuia=" . $guia . ";";
            //echo $this->consulta; exit();
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) "
                    . "values (" . $this->arreglo[0]["idservicio"] . ",'" . date("Y-m-d h:m:s") . "','" . $guia . "','PRUEBA ENT');";
            //echo $this->consulta; exit();
            $this->prepare = $this->con->prepare($this->consulta);
            $retorno[1] = $this->prepare->execute();
            $this->con = null;
            return $retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarNombreNItEmpresa($idEmpresa) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * "
                    . "from cliente "
                    . "where cli_id=" . $idEmpresa . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarValorServiciosVarios($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select valorFacturar "
                    . "from serviciovariasguias "
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

    public function cambiarPrecioServicio($valor, $idservicio, $opcion) {
        try {
            $contador = 0;
            $this->con = new Conexion();

            switch ($opcion) {
                case 0:
                    $this->consulta = "update servicios "
                            . "set valorcliente=" . $valor . " "
                            . "where idservicio=" . $idservicio . "; ";
                    $this->prepare = $this->con->prepare($this->consulta);
                    if ($this->prepare->execute()) {
                        $contador += 1;
                    }
                    $this->consulta = "update posiblesfacturas "
                            . "set valorempresa=" . $valor . " "
                            . "where idservicio=" . $idservicio . "; ";
                    $this->prepare = $this->con->prepare($this->consulta);
                    if ($this->prepare->execute()) {
                        $contador += 1;
                    }
                    break;
                case 1:
                    $this->consulta = "update servicios "
                            . "set valortotal=" . $valor . ","
                            . "valorapagar=" . $valor . " "
                            . "where idservicio=" . $idservicio . "; ";
                    $this->prepare = $this->con->prepare($this->consulta);
                    if ($this->prepare->execute()) {
                        $contador += 1;
                    }
                    $this->consulta = "update posiblesfacturas "
                            . "set valorservicio=" . $valor . " "
                            . "where idservicio=" . $idservicio . "; ";
                    $this->prepare = $this->con->prepare($this->consulta);
                    if ($this->prepare->execute()) {
                        $contador += 1;
                    }
                    $this->consulta = "update valoresanticipos "
                            . "set valorservicio=" . $valor . " "
                            . "where idservicio=" . $idservicio . "; ";
                    $this->prepare = $this->con->prepare($this->consulta);
                    if ($this->prepare->execute()) {
                        $contador += 1;
                    }
                    break;
                case 2:
                    /*
                     * 201810030938
                     * Utilizo $idservicio como ingreso del número de guia
                     */
                    $this->consulta = "update serviciovariasguias "
                            . "set costoTotal=" . $valor . ",valorContratista=" . $valor . " "
                            . "where guia=" . $idservicio . ";";
                    $this->prepare = $this->con->prepare($this->consulta);
                    if ($this->prepare->execute()) {
                        $contador = $contador + 1;
                    }

                    $this->consulta = "update valoresanticipos "
                            . "set valorservicio=" . $valor . " "
                            . "where val_numeroGuia=" . $idservicio . ";";
                    $this->prepare = $this->con->prepare($this->consulta);
                    if ($this->prepare->execute()) {
                        $contador = $contador + 1;
                    }

                    $this->consulta = "update posiblesanticipos "
                            . "set valorservicio=" . $valor . " "
                            . "where guias=" . $idservicio . ";";
                    $this->prepare = $this->con->prepare($this->consulta);
                    if ($this->prepare->execute()) {
                        $contador = $contador + 1;
                    }
                    break;
                case 3:
                    /*
                     * 201810031112
                     * Utilizo $idservicio como ingreso del número de guia                     
                     */
                    $this->consulta = "update serviciovariasguias "
                            . "set valorFacturar=" . $valor . " "
                            . "where guia=" . $idservicio . ";";
                    $this->prepare = $this->con->prepare($this->consulta);
                    if ($this->prepare->execute()) {
                        $contador += 1;
                    }

                    break;
            }

            $this->con = null;
            return $contador;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarGuias($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select numeroGuia "
                    . "from posiblesfacturas "
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

    public function reporteUIAF($fechaInicial, $fechaFinal) {
        try {
            $this->con = new Conexion();

            //echo $this->consulta;
            //exit();
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarMunicipio($idmunicipio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select mun_nombre "
                    . "from municipios "
                    . "where mun_id=" . $idmunicipio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function modificarComentarioServicio($idservicio, $comentario) {
        try {
            $this->con = new Conexion();

            $this->consulta = "select id from comentariosservicios where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            if (count($this->arreglo) === 0) {
                $this->consulta = "insert into comentariosservicios (id,idservicio,comentario) "
                        . "values (null," . $idservicio . ",'" . $comentario . "')";
            } else {
                $this->consulta = "update comentariosservicios "
                        . "set comentario='" . $comentario . "' "
                        . "where idservicio=" . $idservicio . ";";
            }

            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function modificarVarios($idservicio, $valor, $detalle) {
        try {
            $this->con = new Conexion();

            switch ($detalle) {
                case 'auxiliar':
                    $this->consulta = "update valores "
                            . "set valor=" . $valor . " "
                            . "where idservicio=" . $idservicio . " and detalle='AUXILIAR';";
                    break;
                case 'otros':
                    $this->consulta = "update valores "
                            . "set valor=" . $valor . " "
                            . "where idservicio=" . $idservicio . " and detalle='OTROS';";
                    break;
                case 'parqueadero':
                    $this->consulta = "update valores "
                            . "set valor=" . $valor . " "
                            . "where idservicio=" . $idservicio . " and detalle='PARQUEADERO';";
                    break;
            }

            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function mostrarOtrosValores($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * "
                    . "from valores "
                    . "where idservicio=" . $idservicio . ";";
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

    public function cambiarEstadoCtaCobro($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "update servicio_guias "
                    . "set fechaPago='1000-01-01' "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "update valoresanticipos "
                    . "set prueba_entrega='N' "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "update servicio_guias "
                    . "set numeroCuentaCobro='0' "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->con = null;

            if ($this->prepare->execute()) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarGuia($guiaNueva, $guiaAnterior, $idservicio) {
        try {
            $retorno = 0;
            $guias = null;
            $this->con = new Conexion();
            $this->consulta = "update serviciovariasguias set guia=" . $guiaNueva . " "
                    . "where guia=" . $guiaAnterior . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $retorno += 1;
            } else {
                $retorno += 0;
            }
            $this->consulta = "update seguimiento set guia=" . $guiaNueva . " "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $retorno += 1;
            } else {
                $retorno += 0;
            }
            $this->consulta = "update posiblesfacturas set numeroGuia=" . $guiaNueva . " "
                    . "where idservicio=" . $idservicio . " "
                    . "and numeroGuia=" . $guiaAnterior . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $retorno += 1;
            } else {
                $retorno += 0;
            }

            $this->consulta = "update valordeclarado set guia=" . $guiaNueva . " "
                    . "where guia=" . $guiaAnterior . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $retorno += 1;
            } else {
                $retorno += 0;
            }

            $this->consulta = "select val_numeroGuia from valoresanticipos where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            $cantidadGuiones = substr_count($this->arreglo[0]["val_numeroGuia"], '-');
            $nuevasGuias = null;

            if ($cantidadGuiones >= 1) {

                $guiasServicio = explode('-', $this->arreglo[0]["val_numeroGuia"]);

                for ($index = 0; $index < count($guiasServicio); $index++) {
                    if ($guiasServicio[$index] === $guiaAnterior) {
                        $nuevasGuias = $nuevasGuias . $guiaNueva . '-';
                    } else {
                        $nuevasGuias = $nuevasGuias . $guiasServicio[$index] . '-';
                    }
                }
                $nuevasGuias = substr($nuevasGuias, 0, -1);
                $this->consulta = "update valoresanticipos set val_numeroGuia='" . $nuevasGuias . "' "
                        . "where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $retorno += 1;
                } else {
                    $retorno += 0;
                }
            } else {

                $this->consulta = "update valoresanticipos set val_numeroGuia='" . $guiaNueva . "' "
                        . "where val_numeroGuia='" . $guiaAnterior . "';";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $retorno += 1;
                } else {
                    $retorno += 0;
                }
            }
            $this->con = null;
            return $retorno;
        } catch (PDOException $ex) {
            echo $exc->getTraceAsString();
        }
    }

    public function crearServicioTres($arreglo) {
        try {

            $datosServicio = $arreglo["datosServicio"];
            $conAnticipo = $arreglo["conAnticipo"];
            if (isset($arreglo["otrosValores"])) {
                $otrosValores = $arreglo["otrosValores"];
            } else {
                $otrosValores = array();
            }

            $nombreEmpleado = $arreglo["nombreEmpleado"];
            $costoTotalDespacho = 0;
            $valorEmpresa = 0;
            $cantidadServicios = count($datosServicio);
            $retorno = array();
            $ant_sal = $arreglo["ant_sal"];
            $placa = $datosServicio[0]["placa"];

            $this->con = new Conexion();

            $this->consulta = "insert into servicio (idservicio,idempleado,fechaServicio,placa,cedulaPropietario,cedulaConductor) "
                    . "values (null," . $datosServicio[0]["emp_cedula"] . ",'" . $datosServicio[0]["fechaServicio"] . "','" . $datosServicio[0]["placa"] . "',"
                    . "" . $datosServicio[0]["cedulaPropietario"] . "," . $datosServicio[0]["cedulaConductor"] . ");";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $idservicio = $this->con->lastInsertId();

            $this->consulta = "select * from conductores where cond_identificacion='" . $datosServicio[0]["cedulaPropietario"] . "' and estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arregloRetorno["propietario"] = $this->prepare->fetchAll();

            $this->consulta = "select * from conductores where cond_identificacion='" . $datosServicio[0]["cedulaConductor"] . "' and estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arregloRetorno["conductor"] = $this->prepare->fetchAll();

            $this->consulta = "select * from vehiculo where placa='" . $datosServicio[0]["placa"] . "' and estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arregloRetorno["placa"] = $this->prepare->fetchAll();

            $fechaHoy = date("Y-m-d");
            $fechaServicio = $datosServicio[0]["fechaServicio"];

            if ($fechaServicio < $fechaHoy) {
                $this->consulta = "insert into serviciosporcancelar values "
                        . "(null," . $idservicio . "," . $datosServicio[0]["emp_cedula"] . ",'" . date("Y-m-d h:m:s") . "','Mensaje automatico generado. Se ha creado un servicio con fecha anterior a la actual.','CANCELAR');";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
            }

            /*
             * Insertar en la tabla para realizar el seguimiento del servicio
             */

            $this->consulta = "insert into seguimiento_servicio (idseguimientoServicio,idservicio,manifiesto,planderuta,estado) "
                    . "values(null," . $idservicio . ",'0','',0);";
            $this->prepare = $this->con->prepare($this->consulta);
            $arregloSeguimiento = $this->prepare->execute();

            for ($index = 0; $index < count($datosServicio); $index++) {


                $this->consulta = "select s.fechaServicio, sg.idservicio,sg.numeroGuia "
                        . "from servicio as s,servicio_guias as sg "
                        . "where s.idservicio=sg.idservicio "
                        . "and sg.numeroGuia=" . $datosServicio[$index]["guia"] . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $repetidas = $this->prepare->fetchAll();

                if (count($repetidas) > 0) {
                    $this->consulta = "insert into serviciosporcancelar (id,idservicio,idempleado,fecha,motivo,estado) "
                            . "values(null," . $idservicio . "," . $datosServicio[0]["emp_cedula"] . ",'" . date("Y-m-d h:m:s") . "',"
                            . "'" . $nombreEmpleado . " grab&oacute; la gu&iacute;a <a href=../modulos/administrarServiciosDos.php?guia=" . $repetidas[0]["numeroGuia"] . ">" . $repetidas[0]["numeroGuia"] . "</a> en el nuevo servicio " . $idservicio . ". "
                            . "La gu&iacute;a se encuentra en el servicio " . $repetidas[0]["idservicio"] . " con fecha " . $repetidas[0]["fechaServicio"] . "','CANCELAR');";

                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                }


                $costoTotalDespacho += ((int) $datosServicio[$index]["vlrContratista"] + (int) ($datosServicio[$index]["valorAuxiliar"] + (int) $datosServicio[$index]["valorParqueadero"] + (int) $datosServicio[$index]["valorOtros"]));
                $valorEmpresa += $datosServicio[$index]["vlrEmpresa"];

                $this->consulta = "insert into servicio_guias (idservicioguia,idservicio,numeroGuia,unidades,planilla,remision,factura,ordenCompra,iddireccionorigen,"
                        . "iddirecciondestino,auxiliar,parqueadero,otros,valorDeclarado,valorManejo,valorPagado,valorCobrado,Notas,nit,fechaFactura,numeroFactura,"
                        . "fechaPago,fechaPruebaEntrega,fechaHoraEntrega,numeroCuentaCobro,fechaTransferencia)"
                        . " values (null," . $idservicio . "," . $datosServicio[$index]["guia"] . ","
                        . "" . $datosServicio[$index]["unidades"] . ","
                        . "'" . $datosServicio[$index]["planilla"] . "',"
                        . "'" . $datosServicio[$index]["remision"] . "',"
                        . "'" . $datosServicio[$index]["factura"] . "',"
                        . "'" . $datosServicio[$index]["ordenCompra"] . "',"
                        . "" . $datosServicio[$index]["dirOrigen"] . "," . $datosServicio[$index]["dirDestino"] . "," . $datosServicio[$index]["valorAuxiliar"] . ","
                        . "" . $datosServicio[$index]["valorParqueadero"] . "," . $datosServicio[$index]["valorOtros"] . "," . $datosServicio[$index]["vlrDeclarado"] . ","
                        . "" . $datosServicio[$index]["porManejo"] . ","
                        . "" . $datosServicio[$index]["vlrContratista"] . "," . $datosServicio[$index]["vlrEmpresa"] . ",'" . $datosServicio[$index]["notas"] . "',"
                        . "" . $datosServicio[$index]["nit"] . ","
                        . "'1000-01-01',0,'1000-01-01','1000-01-01',"
                        . "'" . $datosServicio[$index]["fechaHoraEntrega"] . "',"
                        . "0,null);";
                //echo $this->consulta;exit();

                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $this->arreglo += 1;
                } else {
                    $this->arreglo = 0;
                }
                $this->consulta = "select m.mun_nombre "
                        . "from municipios as m,direcciones as d "
                        . "where d.ciudad=m.mun_id "
                        . "and d.iddireccion=" . $datosServicio[$index]["dirOrigen"] . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $arr = $this->prepare->fetchAll();
                $ciudadOrigen = $arr[0]["mun_nombre"];

                $this->consulta = "select m.mun_nombre "
                        . "from municipios as m,direcciones as d "
                        . "where d.ciudad=m.mun_id "
                        . "and d.iddireccion=" . $datosServicio[$index]["dirDestino"] . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $arr = $this->prepare->fetchAll();
                $ciudadDestino = $arr[0]["mun_nombre"];

                $this->consulta = "select cli_nombre "
                        . "from cliente "
                        . "where cli_documento=" . $datosServicio[$index]["nit"] . " "
                        . "and estado='ACTIVO'";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $arr = $this->prepare->fetchAll();
                $nombreEmpresa = $arr[0]["cli_nombre"];

                $this->consulta = "insert into datosmostrar (iddatosmostrar,idservicio,guia,empresa,origen,destino,valordeclarado,valorservicio) "
                        . "values (null," . $idservicio . "," . $datosServicio[$index]["guia"] . ",'" . $nombreEmpresa . "','" . $ciudadOrigen . "','" . $ciudadDestino . "'," . $datosServicio[$index]["vlrDeclarado"] . "," . $datosServicio[$index]["vlrEmpresa"] . ");";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $this->arreglo += 1;
                } else {
                    $this->arreglo = 0;
                }

                $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) values "
                        . "(" . $idservicio . ",'" . date("Y-m-d h:m:s") . "','" . $datosServicio[$index]["guia"] . "','CREACION');";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $this->arreglo += 1;
                } else {
                    $this->arreglo = 0;
                }

                $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) values "
                        . "(" . $idservicio . ",'" . date("Y-m-d h:m:s") . "','" . $datosServicio[$index]["guia"] . "','POR FACTURAR');";
                $this->prepare = $this->con->prepare($this->consulta);

                if ($this->prepare->execute()) {
                    $this->arreglo += 1;
                } else {
                    $this->arreglo = 0;
                }

                $this->consulta = "insert into posiblesfacturas (pos_id,nit,empresa,fecha,numeroguia,valorservicio,valorempresa,factura,idservicio) "
                        . "values (null," . $datosServicio[$index]["nit"] . ",'" . $datosServicio[$index]["nombreEmpresa"] . "','" . date("Y-m-d") . " 00:00:00','" . $datosServicio[$index]["guia"] . "',"
                        . "" . $costoTotalDespacho . "," . $datosServicio[$index]["vlrEmpresa"] . ",0," . $idservicio . ");";
                //echo $this->consulta;
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $this->arreglo += 1;
                } else {
                    $this->arreglo = 0;
                }
            }

            if (count($otrosValores) > 0) {
                for ($index1 = 0; $index1 < count($otrosValores); $index1++) {
                    $this->consulta = "insert into otrosCostos(id,idservicio,numeroGuia,concepto,valor,cedulaEmpleado,fechaHora) "
                            . "values (null," . $idservicio . "," . $otrosValores[$index1][2] . ",'" . $otrosValores[$index1][0] . "',"
                            . "" . $otrosValores[$index1][1] . "," . $datosServicio[0]["emp_cedula"] . ",'" . date("Y-m-d h:m:s") . "');";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                }
            }

            $this->arregloRetorno["idservicio"] = $idservicio;
            $this->arregloRetorno["primeraParte"] = $this->arreglo;
            $this->arregloRetorno["segundaParte"] = $this->crearVarEmpConAntSinAnt($datosServicio, $conAnticipo, $idservicio, $ant_sal, $placa);

            if ($conAnticipo === '1') {
                $this->arregloRetorno["idanticipo"] = $this->idanticipo;
            }

            $this->con = null;

            return $this->arregloRetorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function borrarServicioTres($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "delete from servicio where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "delete from servicio_guias where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "delete from trasabilidad where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "delete from posiblesfacturas where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "delete from posiblesanticipos where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "delete from valoresanticipos where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->con = null;
            return 1;
        } catch (PDOException $ex) {
            echo $exc->getTraceAsString();
        }
    }

    /* 20181122
     * Crear varias empresasa con anticipo o sin anticipo
     */

    private function crearVarEmpConAntSinAnt($datosServicio, $conAnticipo, $idservicio, $ant_sal, $placa) {
        try {
            $this->con = new Conexion();

            $guias = null;
            $costoTotalDespacho = 0;
            $cantServicios = count($datosServicio);
            $cantidad = 0;
            $valorEmpresa = 0;

            if ($conAnticipo === '1') {
                $this->idanticipo = $this->retornarNumAnt();
                $anticipo = $ant_sal["vlrAnticipo"];
            } else {
                $this->idanticipo = 0;
                $anticipo = '0';
            }

            for ($index = 0; $index < $cantServicios; $index++) {

                $costoTotalDespacho += (int) $datosServicio[$index]["vlrContratista"];
                $val_id_empresa = $this->retornarCliId($datosServicio[$index]["nit"]);
                $conductores_cond_id = $this->retornarConId($datosServicio[0]["cedulaPropietario"]);

                $fin = $cantServicios - 1;

                if ($index === $fin) {

                    $this->consulta = "insert into valoresanticipos (val_ant_id,val_fechaAnticipo,val_valorAdelanto,valorservicio,val_id_empresa,"
                            . "val_numeroGuia,conductores_cond_id,placa,totalesAnticipos_val_id,val_numeroAnticipo,prueba_entrega,idservicio) "
                            . "values (null,'" . date("Y-m-d") . "','" . $anticipo . "'," . $datosServicio[$index]["vlrContratista"] . ",'" . $val_id_empresa . "',"
                            . "'" . $datosServicio[$index]["guia"] . "'," . $conductores_cond_id . ",'" . $placa . "',2413,'" . $this->idanticipo . "','N'," . $idservicio . ");";
                    //echo $this->consulta;
                    $this->prepare = $this->con->prepare($this->consulta);
                    if ($this->prepare->execute()) {
                        $cantidad += 1;
                    } else {
                        $cantidad = 0;
                    }
                } else {
                    $this->consulta = "insert into valoresanticipos (val_ant_id,val_fechaAnticipo,val_valorAdelanto,valorservicio,val_id_empresa,"
                            . "val_numeroGuia,conductores_cond_id,placa,totalesAnticipos_val_id,val_numeroAnticipo,prueba_entrega,idservicio) "
                            . "values (null,'" . date("Y-m-d") . "','0'," . $datosServicio[$index]["vlrContratista"] . ",'" . $val_id_empresa . "',"
                            . "'" . $datosServicio[$index]["guia"] . "'," . $conductores_cond_id . ",'" . $placa . "',2413,'" . $this->idanticipo . "','N'," . $idservicio . ");";
                    //echo $this->consulta;
                    $this->prepare = $this->con->prepare($this->consulta);
                    if ($this->prepare->execute()) {
                        $cantidad += 1;
                    } else {
                        $cantidad = 0;
                    }
                }
            }

            $this->con = null;

            return $cantidad;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarNumAnt() {
        try {
            $this->con = new Conexion();
            $this->consulta = "SELECT num_numeroAnticipo "
                    . "FROM numerosanticipos "
                    . "ORDER BY num_id DESC LIMIT 1;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $numeroAnticipo = $this->arreglo[0]["num_numeroAnticipo"];
            $numeroAnticipoDos = (int) $numeroAnticipo + 1;
            $this->consulta = "insert into numerosanticipos (num_id,num_numeroAnticipo) "
                    . "values (null," . $numeroAnticipoDos . ");";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            return $numeroAnticipo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarCliId($nit) {
        try {
            $this->con = new Conexion();
            $this->consulta = "SELECT cli_id,cli_nombre FROM cliente WHERE cli_documento='" . $nit . "' AND estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo[0]["cli_id"];
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarConId($cedula) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * "
                    . "from conductores "
                    . "where cond_identificacion=" . $cedula . " "
                    . "and estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo[0]["cond_id"];
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarDatosConductor($cedula) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * "
                    . "from conductores "
                    . "where cond_identificacion=" . $cedula . " "
                    . "and estado!='REPETIDO';";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarDatosEmpleado($cedula) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * "
                    . "from empleados "
                    . "where emp_cedula=" . $cedula . "; ";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarGuiasServicio($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select distinct sg.idservicio,sg.valorManejo,sg.numeroGuia,sg.unidades,sg.planilla,sg.remision,sg.factura,sg.ordenCompra,sg.iddireccionorigen,sg.valorManejo,"
                    . "(select d.direccion from direcciones as d where d.iddireccion=sg.iddireccionorigen) as direccionOrigen,"
                    . "(select d.direccion from direcciones as d where d.iddireccion=sg.iddirecciondestino) as direccionDestino,"
                    . "(select mun_nombre from municipios where mun_id=(select d.ciudad from direcciones as d where d.iddireccion=sg.iddireccionorigen)) as ciudadOrigen,"
                    . "(select mun_nombre from municipios where mun_id=(select d.ciudad from direcciones as d where d.iddireccion=sg.iddirecciondestino)) as ciudadDestino,"
                    . "sg.auxiliar,sg.parqueadero,sg.otros,sg.valorDeclarado,sg.valorPagado,sg.valorCobrado,sg.notas,c.cli_nombre,s.fechaServicio,"
                    . "sg.numeroCuentacobro,sg.fechaTransferencia "
                    . "from servicio_guias as sg,direcciones as d,cliente as c,servicio as s "
                    . "where sg.iddireccionorigen=d.iddireccion "
                    . "and sg.nit=c.cli_documento "
                    . "and sg.idservicio=s.idservicio "
                    . "and c.estado='ACTIVO' "
                    . "and sg.idservicio=" . $idservicio . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarSeguimientos($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select s.idseguimiento,s.guia,s.fechaHora,s.ubicacion,s.observacion,s.imagen,"
                    . "concat(e.emp_nombres,' ',e.emp_apellidos) as nombreEmpleado,s.planderuta,"
                    . "s.estadoseguimiento,s.estado,seguimiento_servicio.manifiesto "
                    . "from seguimiento as s,empleados as e,seguimiento_servicio "
                    . "where s.emp_cedula=e.emp_cedula "
                    . "and s.idservicio=seguimiento_servicio.idservicio "
                    . "and s.idservicio=" . $idservicio . " "
                    . "and (s.estado=1 or s.estado=0) "
                    . "ORDER BY s.fechaHora asc;";

            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarAnticipos($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select val_ant_id,val_valorAdelanto,val_numeroGuia,prueba_entrega "
                    . "from valoresanticipos "
                    . "where idservicio=" . $idservicio . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarSobreAnticipos($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * "
                    . "from valoresanticipos "
                    . "where idservicio=" . $idservicio . " "
                    . "and valorservicio=0;";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarServicioPorIdServicio($idservicio) {
        try {
            $servicio = null;
            $this->con = new Conexion();
            $this->consulta = "select * from servicio where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (count($this->arreglo) === 0) {
                return $this->arregloRetorno["servicio"] = 0;
                exit();
            } else {
                /*
                 * Datos de servicio
                 */
                $this->arregloRetorno["servicio"] = $this->arreglo;
                $servicio = $this->arreglo;
                /*
                 * Datos propietario
                 */
                $this->arregloRetorno["propietario"] = $this->retornarDatosConductor($servicio[0]["cedulaPropietario"]);
                /*
                 * Datos conductor
                 */

                //echo $servicio[0]["cedulaConductor"]; exit();

                $this->arregloRetorno["conductor"] = $this->retornarDatosConductor($servicio[0]["cedulaConductor"]);

                /*
                 * Datos empleado
                 */
                $this->arregloRetorno["empleado"] = $this->retornarDatosEmpleado($servicio[0]["idempleado"]);
                /*
                 * Datos guias
                 */
                $this->arregloRetorno["datosGuias"] = $this->retornarGuiasServicio($idservicio);
                /*
                 * Seguimientos
                 */
                $this->arregloRetorno["seguimientos"] = $this->retornarSeguimientos($idservicio);
                /*
                 * Anticipos 
                 */
                $this->arregloRetorno["anticipos"] = $this->retornarAnticipos($idservicio);
                /*
                 * Sobre anticipos
                 */
                $this->arregloRetorno["sobreanticipos"] = $this->retornarSobreAnticipos($idservicio);
                /*
                 * Asesores
                 */
                $this->arregloRetorno["asesoresServicio"] = $this->retornarAsesoresServicio($idservicio);
                /*
                 * Mensajes de cancelación
                 */
                $this->arregloRetorno["mensajesCancelacion"] = $this->retornarMensajesDeCancelacion($idservicio);
                /*
                 * Facturas
                 */
                $this->arregloRetorno["facturas"] = $this->retornarFacturas($idservicio);
                /*
                 * Calificacion Placa
                 */
                $this->arregloRetorno["calificacionesPlaca"] = $this->retornarCalificacionesPlaca($servicio[0]["placa"]);
                /*
                 * Calificacion Propietario
                 */
                $this->arregloRetorno["calificacionesPropietario"] = $this->retornarCalificacionesPropietario($servicio[0]["cedulaPropietario"]);
                /*
                 * Calificacion Conductor
                 */
                $this->arregloRetorno["calificacionesConductor"] = $this->retornarCalificacionesConductor($servicio[0]["cedulaConductor"]);
                /*
                 * Entregas
                 */
                $this->arregloRetorno["entregas"] = $this->retornarEntregas($idservicio);
                /*
                 * Tipo vehiculo
                 */
                $this->arregloRetorno["tipoVehiculo"] = $this->retornarTipoVehiculo($servicio[0]["placa"]);
                /*
                 * Otros costos de cada guia y los que estan en otrosCostos
                 */
                $this->arregloRetorno["totalOtros"] = $this->retornarCostos($idservicio);
                /*
                 * Las pruebas de entrega 
                 * se incluyen el 02/12/2022
                 */
                $this->arregloRetorno["pruebasEntrega"] = $this->retornarPruebasEntrega($idservicio);
            }
            $this->con = null;
            return $this->arregloRetorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarPruebasEntrega($idservicio) {
        try {

            $this->con = new Conexion();
            $this->consulta = "select * "
                    . "from pruebasentrega "
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

    private function retornarCostos($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select sum(valor) as valor "
                    . "from otrosCostos "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            if ($this->arreglo[0]["valor"] === null) {
                $this->arreglo[0]["valor"] = 0;
            }
            return $this->arreglo[0]["valor"];
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarTipoVehiculo($placa) {
        try {
            try {
                $this->con = new Conexion();
                $this->consulta = "select v.placa,v.tipovehiculo "
                        . "from vehiculo as v "
                        . "where v.placa='" . $placa . "';";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                return $this->prepare->fetchAll();
            } catch (PDOException $exc) {
                echo $exc->getTraceAsString();
            }
        } catch (Exception $ex) {
            
        }
    }

    private function retornarEntregas($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select e.idservicio,e.numeroGuia,e.unidades,e.planilla,e.remision,e.factura,e.ordenCompra,e.valorManejo,"
                    . "(select d.direccion from direcciones as d where d.iddireccion=e.iddirecciondestino) as direccionDestino,"
                    . "(select mun_nombre from municipios where mun_id=(select d.ciudad from direcciones as d where d.iddireccion=e.iddirecciondestino)) as ciudadDestino,"
                    . "e.auxiliar,e.parqueadero,e.otros,e.valorDeclarado,e.valorPagado,e.valorCobrado,e.notas,c.cli_nombre "
                    . "from entregas as e,direcciones as d,cliente as c "
                    . "where e.iddirecciondestino=d.iddireccion "
                    . "and e.nit=c.cli_documento "
                    . "and c.estado='ACTIVO' "
                    . "and e.idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            return $this->prepare->fetchAll();
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarCalificacionesPlaca($placa) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select calificacion from calificacionPlaca where placa='" . $placa . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            return $this->prepare->fetchAll();
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarCalificacionesPropietario($cedula) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select calificacion from calificacionCedula where cedula=" . $cedula . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            return $this->prepare->fetchAll();
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarCalificacionesConductor($cedula) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select calificacion from calificacionCedula where cedula=" . $cedula . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            return $this->prepare->fetchAll();
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarFacturas($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select pos_id,factura,nit,fecha "
                    . "from posiblesfacturas "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarMensajesDeCancelacion($idservicio) {
        $this->con = new Conexion();
        $this->consulta = "select spc.id,spc.fecha,spc.motivo,concat(e.emp_nombres,' ',e.emp_apellidos) as nombresEmpleado "
                . "from serviciosporcancelar as spc,empleados as e "
                . "where spc.idempleado=e.emp_cedula "
                . "and spc.idservicio=" . $idservicio . " "
                . "and spc.estado='CANCELAR'; ";
        $this->prepare = $this->con->prepare($this->consulta);
        $this->prepare->execute();
        $this->arreglo = $this->prepare->fetchAll();
        return $this->arreglo;
    }

    private function retornarAsesoresServicio($idservicio) {
        try {
            $asesores = array();
            $this->con = new Conexion();
            $this->consulta = "select ae.cedula "
                    . "from servicio_guias as sg,asesor_empresa as ae "
                    . "where sg.nit=ae.nit "
                    . "and sg.idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $cedulas = $this->prepare->fetchAll();
            for ($index = 0; $index < count($cedulas); $index++) {
                $this->consulta = "select concat(emp_nombres,' ',emp_apellidos) as nombreAsesor "
                        . "from empleados "
                        . "where emp_cedula=" . $cedulas[$index]["cedula"];
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
                $asesores[$index]["nombreAsesor"] = $this->arreglo[0]["nombreAsesor"];
            }
            return $asesores;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarNumeroServicioPorGuia($guia) {
        try {
            $this->con = new conexion();
            $this->consulta = "select idservicio from servicio_guias where numeroGuia=" . $guia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarNumeroServicioPorAnticipo($anticipo) {
        try {
            $this->con = new conexion();
            $this->consulta = "select idservicio from valoresanticipos where val_numeroAnticipo=" . $anticipo . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarValorACobrar($guia, $valor, $idservicio) {
        try {

            $this->con = new conexion();
            $this->arregloRetorno = 0;
            /*
             * datosmostrar
             * servicio_guias
             * posiblesfacturas
             */

            $this->consulta = "update posiblesfacturas "
                    . "set valorempresa=" . $valor . " "
                    . "where numeroguia=" . $guia . " "
                    . "and idservicio=" . $idservicio . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->arregloRetorno += 1;
            }

            $this->consulta = "update datosmostrar "
                    . "set valorservicio=" . $valor . " "
                    . "where guia=" . $guia . " "
                    . "and idservicio=" . $idservicio . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->arregloRetorno += 1;
            }

            $this->consulta = "update servicio_guias "
                    . "set valorCobrado=" . $valor . " "
                    . "where numeroGuia=" . $guia . " "
                    . "and idservicio=" . $idservicio . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->arregloRetorno += 1;
            }

            $this->con = null;
            return $this->arregloRetorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarValorAPagar($guia, $valor, $idservicio) {
        try {
            $this->con = new conexion();
            $this->arregloRetorno = 0;
            /*
             * valoresanticipos
             * datosmostrar
             * servicio_guias
             * posiblesfacturas
             */

            $this->consulta = "update datosmostrar "
                    . "set valorservicio=" . $valor . " "
                    . "where guia=" . $guia . " "
                    . "and idservicio=" . $idservicio . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->arregloRetorno += 1;
            }

            $this->consulta = "update posiblesfacturas "
                    . "set valorservicio=" . $valor . " "
                    . "where numeroguia=" . $guia . " "
                    . "and idservicio=" . $idservicio . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->arregloRetorno += 1;
            }

            $this->consulta = "update valoresanticipos "
                    . "set valorservicio=" . $valor . " "
                    . "where val_numeroGuia=" . $guia . " "
                    . "and idservicio=" . $idservicio . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->arregloRetorno += 1;
            }


            $this->consulta = "update servicio_guias "
                    . "set valorPagado=" . $valor . " "
                    . "where numeroGuia=" . $guia . " "
                    . "and idservicio=" . $idservicio . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->arregloRetorno += 1;
            }

            $this->con = null;
            return $this->arregloRetorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarValorAuxiliar($guia, $valor, $idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "update servicio_guias "
                    . "set auxiliar=" . $valor . " "
                    . "where idservicio=" . $idservicio . " "
                    . "and numeroGuia=" . $guia . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->arregloRetorno = 1;
            }
            $this->con = null;
            return $this->arregloRetorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarValorParqueadero($guia, $valor, $idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "update servicio_guias "
                    . "set parqueadero=" . $valor . " "
                    . "where idservicio=" . $idservicio . " "
                    . "and numeroGuia=" . $guia . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->arregloRetorno = 1;
            }
            $this->con = null;
            return $this->arregloRetorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarNotasGuia($guia, $valor, $idservicio) {
        try {
            $this->con = new Conexion();
            $mensaje = null;
            $this->arreglo = null;
            $this->consulta = "select Notas from servicio_guias where numeroGuia=" . $guia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            $this->consulta = "update servicio_guias "
                    . "set Notas='" . $valor . "' "
                    . "where idservicio=" . $idservicio . " "
                    . "and numeroGuia=" . $guia . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->arregloRetorno += 1;
            }
            $this->con = null;
            return $this->arregloRetorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarUsuarioFechaServicio($arreglo) {
        try {
            $this->con = new Conexion();
            $this->consulta = "SELECT trasabilidad.fecha, CONCAT(empleados.emp_nombres,' ',empleados.emp_apellidos) as nombreEmpleado "
                    . "FROM servicio,trasabilidad,empleados "
                    . "WHERE servicio.idservicio=trasabilidad.idservicio "
                    . "AND servicio.idempleado=empleados.emp_cedula "
                    . "AND trasabilidad.idservicio=" . $arreglo["idservicio"] . " "
                    . "AND trasabilidad.referencia=" . $arreglo["guia"] . " "
                    . "GROUP BY trasabilidad.referencia "
                    . "ORDER BY trasabilidad.fecha;";
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

