<?php

include '../clases/conexion.php';

class rol_boton extends Conexion {

    private $conexion;
    private $prepare;
    private $arreglo;
    private $consulta;

    public function retornarGuiasPorFacturar() {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select count(*) as cantidad from posiblesfacturas where factura=0";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarNumeroCotizacion() {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select max(num_numeroCotizacion) as numeroCotizacion from numeroscotizacion;";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarServiciosPorCancelar($opcion) {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select spc.id,spc.idservicio,spc.fecha,spc.motivo,concat(e.emp_nombres,' ',e.emp_apellidos) as nombreEmpleado "
                    . "from serviciosporcancelar as spc,empleados as e "
                    . "where spc.idempleado=e.emp_cedula "
                    . "and spc.estado='CANCELAR' ";

            if ($opcion === 1) {
                $this->consulta .= "and spc.motivo='Servicio finalizado con novedad.'";
            }

            if ($opcion === 2) {
                $this->consulta .= "and spc.motivo<>'Servicio finalizado con novedad.'";
            }
            $this->consulta .= "order by spc.idservicio asc;";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return count($this->arreglo);
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarAnticiposPendientes($condicion, $devolucion, $fechaInicial, $fechaFinal) {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select va.val_fechaAnticipo,va.idservicio,va.val_valorAdelanto,va.valorservicio,"
                    . "va.val_numeroGuia,s.placa,concat(c.cond_nombres,' ',c.cond_apellidos) as nombresPropietario,s.fechaServicio,"
                    . "(select cli_nombre from cliente where cli_id=va.val_id_empresa) as cliente "
                    //. "(select valorCobrado from servicio_guias where numeroGuia=va.val_numeroGuia) as valorCobrado "
                    . "from valoresanticipos as va,servicio as s,conductores as c "
                    . "where va.idservicio=s.idservicio "
                    . "and s.cedulaPropietario=c.cond_identificacion "
                    . "and va.prueba_entrega='N' ";
            if ($devolucion === 0) {
                $this->consulta .= "and s.fechaServicio>='" . $fechaInicial . "' "
                        . "and s.fechaServicio<='" . $fechaFinal . "' ";
            }
            $this->consulta .= "order by va.idservicio,s.placa asc;";
            //echo $this->consulta; exit();
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            if ($devolucion === 1) {
                return count($this->arreglo);
            } else {
                return $this->arreglo;
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function validarPruebaEntrega($guia) {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select * "
                    . "from trasabilidad "
                    . "where referencia=" . $guia . " "
                    . "and evento='PRUEBA ENT';";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /*
     * Funcion repetida en modulos_empleados 
     */

    public function retornarBotones($cedula) {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select b.clsboostrap,b.valor,b.id_boton,b.title,b.id "
                    . "from empleado_botones as eb,botones as b "
                    . "where eb.idboton=b.id_boton "
                    . "and eb.cedulaempleado=" . $cedula . " "
                    . "order by b.valor asc;";
            //echo $this->consulta;
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarClientes() {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select cli_documento,cli_nombre from cliente where cli_documento > 100 and estado='ACTIVO' order by cli_nombre asc;";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarConductores() {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select cond_identificacion,cond_nombres,cond_apellidos from conductores where cond_identificacion > 100 and cond_identificacion <> 79725743 and estado = 'ACTIVO' order by cond_nombres asc;";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarPropietarios() {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select cond_identificacion,cond_nombres,cond_apellidos from conductores where cond_identificacion > 100 and cond_identificacion <> 79725743 and estado = 'ACTIVO' and perfil=9 order by cond_nombres asc;";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarPlacas($opcion=0) {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select cv.placa,c.cond_identificacion "
                    . "from conductor_vehiculo as cv "
                    . "inner join conductores as c "
                    . "on cv.identificacion=c.cond_identificacion "
                    . "where c.estado='ACTIVO' "
                    . "and c.perfil=9 "
                    . "and cv.estado='ACTIVO' "
                    . "order by cv.placa asc;";
            if($opcion==1){
                /* $this->consulta = "select cv.placa,c.cond_identificacion "
                . "from conductor_vehiculo as cv "
                . "inner join conductores as c "
                . "on cv.identificacion=c.cond_identificacion "
                . "where c.estado='ACTIVO' "
                . "and c.perfil=9 "
                . "and cv.estado='ACTIVO' "
                . "AND cv.identificacion IN ( SELECT s.cedulaPropietario FROM servicio s, servicio_guias sg WHERE sg.idservicio=s.idservicio and sg.numeroCuentaCobro = 0 ) "
                . "order by cv.placa asc;"; */
                $this->consulta = "SELECT cv.placa,c.cond_identificacion FROM conductor_vehiculo AS cv 
                INNER JOIN conductores as c ON cv.identificacion=c.cond_identificacion 
                WHERE c.estado='ACTIVO' AND c.perfil=9 AND cv.estado='ACTIVO' 
                AND cv.identificacion IN ( SELECT s.cedulaPropietario FROM servicio s, servicio_guias sg 
                     WHERE sg.idservicio=s.idservicio AND sg.numeroCuentaCobro = 0 ) 
                ORDER BY cv.placa asc;";
            }
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarIdCliente($nit) {
        try {

            $this->conexion = new Conexion();
            $this->consulta = "select cliente.cli_id from cliente where cliente.cli_documento='" . $nit . "' and cliente.estado='ACTIVO';";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarTelDirCliente($cli_id) {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select * from cliente where cli_documento=" . $cli_id . ";";
            //echo $this->consulta;
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /*
     * La estoy repitiendo en la clase servicios.php y rol_boton.php
     */

    public function retornarEntregas($idservicio) {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select e.idservicio,e.numeroGuia,e.planilla,e.remision,e.factura,e.ordenCompra,e.valorPagado,e.valorCobrado,e.guiaEntrega,e.unidades,"
                    . "(select direccion from direcciones where iddireccion=e.iddirecciondestino) as direccionDestino,"
                    . "(select mun_nombre from municipios where mun_id=(select d.ciudad from direcciones as d where d.iddireccion=e.iddirecciondestino)) as ciudadDestino,"
                    . "e.valorDeclarado,e.valorManejo "
                    . "from entregas as e "
                    . "where e.idservicio=" . $idservicio . ";";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->conexion = null;
            return $this->prepare->fetchAll();
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarDatosCliente($cli_id, $fechaInicio, $fechaFinal) {
        try {

            $this->consulta = "SELECT servicio_guias.idservicio,servicio_guias.numeroGuia,servicio_guias.unidades,"
                    . "servicio_guias.planilla,servicio_guias.remision,servicio_guias.factura,servicio_guias.ordenCompra,"
                    . "servicio_guias.iddireccionorigen,servicio_guias.iddirecciondestino,servicio_guias.auxiliar,"
                    . "servicio_guias.parqueadero,servicio_guias.otros,servicio_guias.valorDeclarado,servicio_guias.valorManejo,"
                    . "servicio_guias.valorPagado,servicio_guias.valorCobrado,servicio_guias.Notas,servicio_guias.nit,"
                    . "servicio_guias.fechaFactura,servicio_guias.numeroFactura,servicio_guias.fechaPago,servicio_guias.fechaPruebaEntrega,"
                    . "servicio.fechaServicio "
                    . "FROM servicio_guias,servicio  "
                    . "WHERE servicio_guias.idservicio=servicio.idservicio "
                    . "AND servicio_guias.numeroFactura='0' "
                    . "AND servicio_guias.nit = ". $cli_id . " "
                    . "AND servicio.fechaServicio BETWEEN '" . $fechaInicio . "' AND '" . $fechaFinal . "' "                    
                    . "ORDER BY servicio.idservicio,servicio.fechaServicio;";
            
            //echo $this->consulta;
            //exit();
            $this->conexion = new Conexion();
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarMunicipioOrigen($guia, $idservicio) {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select m.mun_nombre "
                    . "from servicio_guias as sg,direcciones as d,municipios as m "
                    . "where sg.iddireccionorigen=d.iddireccion "
                    . "and d.ciudad=m.mun_id "
                    . "and sg.numeroGuia=" . $guia . " "
                    . "and sg.idservicio=" . $idservicio . ";";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarMunicipioDestino($guia, $idservicio) {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select m.mun_nombre "
                    . "from servicio_guias as sg,direcciones as d,municipios as m "
                    . "where sg.iddirecciondestino=d.iddireccion "
                    . "and d.ciudad=m.mun_id "
                    . "and sg.numeroGuia=" . $guia . " "
                    . "and sg.idservicio=" . $idservicio . ";";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /*
     * Esta funcion esta en la clase servicios.php
     */

    public function retornarServiciosPorFacturar($condicion) {
        try {
            $this->conexion = new Conexion();
            if ($condicion === 1) {
                $this->consulta = "select sg.idservicio,sg.numeroGuia,sg.valorCobrado,sg.nit,"
                        . "(select distinct cliente.cli_nombre from cliente where cliente.cli_documento=sg.nit and cliente.estado='ACTIVO') as empresa,"
                        . "s.cedulaPropietario,s.cedulaConductor,s.fechaServicio as fecha "
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
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;

            if ($condicion === 1) {
                return $this->arreglo;
            } else {
                return $this->arreglo[0]["total"];
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarEmpleados() {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select emp_cedula as cedula,concat(emp_nombres,' ',emp_apellidos) as nombre "
                    . "from empleados "
                    . "where departamento_dep_id=5 "
                    . "and roles_rol_id=8 "
                    . "and estado='A';";
            //echo $this->consulta;
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function seguimientosPendientes() {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select ss.idservicio,s.`fechaServicio`,s.placa,sg.`numeroGuia` as guias "
                    . "from seguimiento_servicio as ss,servicio as s,servicio_guias as sg "
                    . "where ss.idservicio=s.idservicio "
                    . "and ss.idservicio=sg.idservicio "
                    . "and ss.estado=0 "
                    . "order by s.`fechaServicio`,ss.idservicio desc;";
            //echo $this->consulta;
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarCantAgendaPendientes($cedula) {
        try {
            $this->conexion = new Conexion();
            $this->consulta = "select count(estado) as cantidad "
                    . "from asunto_empleado as ae "
                    . "where cedula=" . $cedula . " "
                    . "and estado=1;";
            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarPlacaPropietario($idservicio, $opcion) {
        try {
            $this->conexion = new Conexion();

            if ($opcion === 1) {
                $this->consulta = "select concat(c.cond_nombres,' ',c.cond_apellidos) as nombrePropietario,s.placa,c.cond_telefono "
                        . "from conductores as c,servicio as s "
                        . "where s.cedulapropietario=c.cond_identificacion "
                        . "and s.idservicio=" . $idservicio . ";";
            }

            if ($opcion === 2) {
                $this->consulta = "select concat(c.cond_nombres,' ',c.cond_apellidos) as nombreConductor,c.cond_telefono "
                        . "from conductores as c,servicio as s "
                        . "where s.cedulaConductor=c.cond_identificacion "
                        . "and s.idservicio=" . $idservicio . ";";
            }

            //echo $this->consulta;

            $this->prepare = $this->conexion->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->conexion = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /*
     * 20190411
     * Esta función esta también en la clase serviciosporcancelar
     * pero solo esta insertando
     */

    public function crearServicio($idservicio, $idempleado, $fecha, $motivo) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * from serviciosporcancelar where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (count($this->arreglo) === 0) {
                $this->consulta = "insert into serviciosporcancelar values "
                        . "(null," . $idservicio . "," . $idempleado . ",'" . $fecha . "','" . $motivo . "','CANCELAR');";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->arreglo = $this->prepare->execute();
                $this->con = null;
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarValorACobrar($guia) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select valorCobrado from servicio_guias where numeroGuia=" . $guia . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo[0]["valorCobrado"];
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarServiciosConNovedad() {

        $this->con = new Conexion();
        $this->consulta = "select spc.id,spc.idservicio,spc.fecha,spc.motivo,concat(e.emp_nombres,' ',e.emp_apellidos) as nombreEmpleado "
                . "from serviciosporcancelar as spc,empleados as e "
                . "where spc.idempleado=e.emp_cedula "
                . "and spc.estado='CANCELAR' "
                . "and spc.motivo='Servicio finalizado con novedad.' "
                . "order by spc.idservicio asc;";
        $this->prepare = $this->con->prepare($this->consulta);
        $this->prepare->execute();
        $this->arreglo = $this->prepare->fetchAll();
        $this->con = null;
        return $this->arreglo;
    }
}
