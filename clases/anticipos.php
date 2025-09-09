<?php

include '../clases/conexion.php';

class anticipos {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $lista = null;
    private $control = null;

    public function datosConductor($identificacion,$placa) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select c.cond_id,c.cond_identificacion,c.cond_nombres,c.cond_apellidos,"
                . "c.cond_direccion,c.email,c.cond_telefono,c.perfil,"
                . "c.municipios_mun_id,r.rol_nombre,cv.placa,cv.estado as estadoRelacion "
                . "from conductores as c "
                . "inner join roles as r "
                . "on c.perfil = r.rol_id "
                . "inner join conductor_vehiculo as cv "
                . "on c.cond_identificacion=cv.identificacion "
                . "where c.cond_identificacion=" . $identificacion . " "
                . "and cv.placa='".$placa."';";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetch(PDO::FETCH_ASSOC);
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            return $exc->getMessage();
        }
    }

    public function retornarNumeroManifiesto($idservicio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select manifiesto from seguimiento_servicio where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetch(PDO::FETCH_ASSOC);
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarNumeroAnticipo() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select max(num_numeroAnticipo) as numeroAnticipo from numerosanticipos;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetch(PDO::FETCH_ASSOC);
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            return $exc->getMessage();
        }
    }
    
     public function retornarNumeroCtaCobro() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select max(numero) as numeroCtaCobro from numeros_cta_cobro;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetch(PDO::FETCH_ASSOC);
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            return $exc->getMessage();
        }
    }

    public function retonarCiudades() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select mun_id,mun_nombre from municipios;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            return $exc->getMessage();
        }
    }

    public function retornarPropietario($placa) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select c.cond_nombres,c.cond_apellidos 
            from conductores as c 
            inner join conductor_vehiculo as cv 
            on c.cond_identificacion=cv.identificacion 
            where cv.placa='" . $placa . "' and c.perfil=9;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            return $exc->getMessage();
        }
    }

    public function retornarClientes() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select cli_documento,cli_nombre from cliente where cli_documento > 100 and 
            estado='ACTIVO' order by cli_nombre asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            return $exc->getMessage();
        }
    }

    public function mostrarAnticipos($cond_id, $condicion, $placa) {
        try {
            $this->con = new Conexion();

            switch ($condicion) {
                case 1:
                    $this->consulta = "select va.val_ant_id,va.val_fechaAnticipo,va.val_valorAdelanto,"
                        . "va.valorservicio as val_total,va.val_numeroGuia,"
                        . "c.cli_nombre as nombreEmpresa,c.cli_documento as nitEmpresa,va.val_numeroGuia,"
                        . "va.val_numeroAnticipo,va.idservicio "
                        . "from valoresanticipos as va "
                        . "inner join cliente as c "
                        . "on va.val_id_empresa=c.cli_id "
                        . "where va.prueba_entrega='N' and va.conductores_cond_id=" . $cond_id . ";";
                    //echo $this->consulta;

                    break;
                case 2:
                    /* $this->consulta = "SELECT cond_id FROM `conductores` WHERE `cond_identificacion` LIKE '" . $cond_id . "' and estado='ACTIVO';";
                    $this->prepare = $this->con->prepare($this->consulta);
                    $this->prepare->execute();
                    $this->arreglo = $this->prepare->fetchAll();
                    $cond_id = $this->arreglo[0]["cond_id"]; */

                    /*$this->consulta = "select va.val_ant_id,va.val_fechaAnticipo,va.val_valorAdelanto,"
                            . "va.valorservicio as val_total,"
                            . "c.cli_nombre as nombreEmpresa,c.cli_documento as nitEmpresa,va.val_numeroGuia,"
                            . "va.val_numeroAnticipo,va.idservicio "
                            . "from valoresanticipos as va "
                            . "inner join cliente as c "
                            . "on va.val_id_empresa=c.cli_id "
                            . "where va.prueba_entrega='N' "
                            . "and va.conductores_cond_id=" . $cond_id . " "
                            . "and va.placa='" . $placa . "' "
                            . "order by va.idservicio asc;"; */
                    $this->consulta = "SELECT va.val_ant_id,va.val_fechaAnticipo,va.val_valorAdelanto,
                            va.valorservicio AS val_total, c.cli_nombre AS nombreEmpresa,
                            c.cli_documento AS nitEmpresa,va.val_numeroGuia, va.val_numeroAnticipo, va.idservicio 
                            FROM valoresanticipos AS va INNER JOIN cliente AS c 
                            ON va.val_id_empresa=c.cli_id 
                            WHERE va.prueba_entrega='N' AND va.conductores_cond_id IN (
                                SELECT cond_id FROM `conductores` WHERE `cond_identificacion` LIKE '" . $cond_id . "' and estado='ACTIVO' )   
                            AND va.placa='" . $placa . "' ORDER BY va.idservicio asc;";
            }

            //echo "consulta:".$this->consulta;exit;

            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarConductores($placa) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select c.cond_identificacion,c.cond_nombres,c.cond_apellidos "
                    . "from conductores as c,conductor_vehiculo as cv "
                    . "where c.cond_identificacion=cv.identificacion "
                    . "and c.perfil=10 "
                    . "and c.estado='ACTIVO' "
                    . "and cv.placa='" . $placa . "';";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            return $exc->getTraceAsString();
        }
    }

    public function retornarPosiblesAnticipos($placa, $variasEmpresas) {
        try {
            $this->con = new Conexion();

            if ($variasEmpresas === '1') {
                $this->consulta = "select pa.idservicio,pa.nitempresa,pa.placa,pa.fechaservicio,pa.posiblevaloranticipo,pa.valorservicio,"
                        . "cl.cli_nombre,pa.guias "
                        . "from cliente as cl, servicios as se, posiblesanticipos as pa "
                        . "where pa.idservicio=se.idservicio "
                        . "and pa.nitempresa=cl.cli_documento "
                        . "and pa.placa=se.placa "
                        . "and pa.estado='CREACION' "
                        . "and pa.placa='" . $placa . "';";
                //echo $this->consulta;
            } else {
                $this->consulta = "select pa.idservicio,pa.nitempresa,pa.placa,pa.fechaservicio,pa.posiblevaloranticipo,pa.valorservicio,pa.guias,c.cli_nombre "
                        . "from posiblesanticipos as pa,cliente as c "
                        . "where  c.cli_documento=pa.nitempresa "
                        . "and pa.placa='" . $placa . "' "
                        . "and pa.estado='CREACION';";
                //echo $this->consulta.'aqui';
            }

            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            return $exc->getTraceAsString();
        }
    }

    public function crearValorCuentaCobro($valortotal, $valorapagar, $guia, $nitEmpresa, $cedulaConductor, $placa) {
        try {
            $arreglo = array();
            $this->con = new Conexion();
            $this->consulta = "select cli_id from cliente where cli_documento=" . $nitEmpresa . " and estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $cli_id = $this->arreglo[0]["cli_id"];

            $this->consulta = "select cond_id from conductores where cond_identificacion=" . $cedulaConductor . " and estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $cond_id = $this->arreglo[0]["cond_id"];

            $this->consulta = "insert into totalesanticipos values (null,'" . $valortotal . "','A','2555555','" . date("Y/m/d h:m") . "','0')";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $totalesAnticipos_val_id = $this->con->lastInsertId();

            $this->consulta = "insert into valoresanticipos values (null,'" . date("Y-m-d h:m") . "','0'," . $valorapagar . ",'" . $cli_id . "','" . $guia . "'," . $cond_id . ",'" . $placa . "'," . $totalesAnticipos_val_id . ",0,'N');";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "select val_ant_id "
                    . "from valoresanticipos where val_numeroGuia=" . $guia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $arreglo["val_ant_id"] = $this->arreglo[0]["val_ant_id"];
            return $arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarDatosAnticipo($numeroAnticipo) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select vl.idservicio "
                    . "from valoresanticipos as vl "
                    . "where vl.val_numeroAnticipo='" . $numeroAnticipo . "';";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $idServicio = $this->arreglo[0]["idservicio"];
            $this->consulta = "select distinct vl.val_ant_id,vl.`val_fechaAnticipo`,vl.`val_valorAdelanto`,vl.valorservicio,vl.val_id_empresa,vl.`val_numeroGuia`,vl.conductores_cond_id, "
                    . "vl.`totalesAnticipos_val_id`,vl.`val_numeroAnticipo`,vl.prueba_entrega,vl.idservicio,vl.prueba_entrega,vl.val_id_empresa,"
                    . "c.cond_identificacion,concat(c.cond_nombres,' ',c.cond_apellidos) as nombresPropietario,cl.cli_nombre,cv.placa  "
                    . "from valoresanticipos as vl,conductores as c,vehiculo as v,conductor_vehiculo as cv,cliente as cl "
                    . "where vl.conductores_cond_id=c.cond_id "
                    . "and cv.identificacion=c.cond_identificacion "
                    . "and cl.cli_id=vl.val_id_empresa "
                    . "and vl.idservicio='" . $idServicio . "'; ";
            //. "group by vl.val_ant_id; ";
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

    public function cancelarAnticipo($numeroAnticipo, $idservicio, $motivo) {
        try {
            $this->con = new Conexion();

            $this->consulta = "select referencia from trasabilidad where idservicio=" . $idservicio . " and evento='CREACION';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            for ($index = 0; $index < count($this->arreglo); $index++) {
                $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) "
                        . "values (" . $idservicio . ",'" . date('Y-m-d H:m:s') . "'," . $this->arreglo[$index]["referencia"] . ",'" . $motivo . "');";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
            }
            $this->consulta = "delete from valoresanticipos where val_numeroAnticipo='" . $numeroAnticipo . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();

            $this->consulta = "delete from serviciosporcancelar where idservicio='" . $idservicio . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();

            $this->consulta = "update posiblesanticipos set estado='CREACION' where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /*
     * 201904152023
     * Se elimina la opción de variasEmpresas distinto de 1
     */

    public function crearPosibleAnticipo($servicio, $variasEmpresas, $placa) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select svg.guia,s.idservicio,svg.idcliente,s.placa,s.fecha,svg.valorContratista,svg.costoTotal,svg.valorFacturar  
from servicios as s,serviciovariasguias as svg,cliente as c 
where s.idservicio=svg.idservicio 
and svg.idcliente=c.cli_documento 
and svg.idservicio=" . $servicio . "";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            for ($index = 0; $index < count($this->arreglo); $index++) {
                $posibleValorAnticipo = ($this->arreglo[$index]["valorContratista"] * 60) / 100;
                $this->consulta = "insert into posiblesanticipos (idservicio,nitempresa,placa,fechaservicio,posiblevaloranticipo,valorservicio,estado,guias) "
                        . "values (" . $servicio . ",'" . $this->arreglo[$index]["idcliente"] . "','" . $placa . "','" . $this->arreglo[$index]["fecha"] . "'," . $posibleValorAnticipo . "," . $this->arreglo[$index]["valorContratista"] . ",'CREACION','" . $this->arreglo[$index]["guia"] . "');";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->control = $this->prepare->execute();

                $this->consulta = "select cli_nombre from cliente where cli_documento=" . $this->arreglo[$index]["idcliente"] . " and estado='ACTIVO';";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $arreglo = $this->prepare->fetchAll();

                $this->consulta = "insert into posiblesfacturas (pos_id,nit,empresa,fecha,numeroguia,valorservicio,valorempresa,factura,idservicio) values "
                        . "(null," . $this->arreglo[$index]["idcliente"] . ",'" . $arreglo[0]["cli_nombre"] . "','" . $this->arreglo[$index]["fecha"] . "','" . $this->arreglo[$index]["guia"] . "'," . $this->arreglo[$index]["costoTotal"] . "," . $this->arreglo[$index]["valorFacturar"] . ",0," . $servicio . ");";

                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
            }
            echo $this->control;
            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarFechaPruebaEntrega($guia) {
        try {
            $fecha = null;
            $this->con = new Conexion();
            $this->consulta = "select fecha "
                    . "from trasabilidad "
                    . "where referencia=" . $guia . " "
                    . "and evento='PRUEBA ENT';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            if (count($this->arreglo) === 0) {
                $fecha = 0;
            } else {
                $fecha = 1;
            }

            $this->con = null;
            return $fecha;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarPosibleFechaPago($guia,$idservicio){
        try{            
            $this->consulta = "select fecha "
                    . "from trasabilidad "
                    . "where idservicio=" . $idservicio . " and referencia=".$guia." and evento='CUENTA DE COBRO A CONDUCTOR';";
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

    public function crearAdvertencia($guia,$idservicio){
        try{            
            $this->consulta = "insert into trasabilidad values(".$idservicio.",'".date("Y-m-d H:m:s")."',".$guia.",'ADVERTENCIA CTA COBRO GENERADA');";            
            $this->con = new Conexion();
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo=$this->prepare->execute();            
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarFechaServicio($idservicio) {
        try {
            $this->con = new Conexion();
            /* quito esta consulta por no existir la tabla servicios */
            /* $this->consulta = "select fecha "
                    . "from servicios "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (count($this->arreglo) === 0) { */
                $this->consulta = "select fechaServicio as fecha "
                        . "from servicio "
                        . "where idservicio=" . $idservicio . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
            //}
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function borrarSobreAnticipo($numSobreAnticipo) {
        try {
            $this->con = new Conexion();
            $this->consulta = "delete from valoresanticipos where val_numeroAnticipo like '" . $numSobreAnticipo . "';";
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

}
