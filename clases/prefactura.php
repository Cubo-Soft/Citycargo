<?php

include '../clases/conexion.php';

class prefactura {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $retorno = array();

    public function consultarFacturaServicio($factura) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select factura,numeroguia,idservicio "
                    . "from posiblesfacturas "
                    . "where factura=" . $factura . ";";
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

    public function crearMensajeFacturaRepetida($guiaAnterior, $guiaNueva, $factura, $cedula) {
        try {
            $this->con = new Conexion();

            $this->consulta = "select idservicio from servicio_guias where numeroGuia=" . $guiaNueva . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $idservicio = $this->arreglo[0]["idservicio"];

            $this->consulta = "insert into serviciosporcancelar (id,idservicio,idempleado,fecha,motivo,estado) "
                    . "values (null," . $idservicio . "," . $cedula . ",'" . date("Y-m-d h:m:s") . "',"
                    . "'Mensaje automatico generado, el número de factura: " . $factura . " "
                    . "se encuentra en la gu&iacute;a anterior:" . $guiaAnterior . ", se ha ingresado en la gu&iacute;a nueva: " . $guiaNueva . "','CANCELAR');";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function consultarFactura($guia) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select factura from posiblesfacturas where numeroguia=" . $guia . ";";
            //echo $this->consulta; exit();
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (count($this->arreglo) > 0) {
                $this->retorno["resultado"] = $this->arreglo[0]["factura"];
            } else {
                $this->retorno["resultado"] = 2;
            }
            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarDatosFactura($guia) {
        try {
            $this->con = new Conexion();

            $this->consulta = "select factura,fecha "
                    . "from posiblesfacturas "
                    . "where numeroguia=" . $guia . " ";
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

    public function modificarFactura($factura, $guia) {
        try {
            $this->con = new Conexion();

            $this->consulta = "update servicio_guias "
                    . "set fechaFactura='" . date('Y-m-d') . "',"
                    . "numeroFactura=" . $factura . " "
                    . "where numeroGuia=" . $guia . ";";
            //echo $this->consulta; 
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "select idservicio from servicio_guias where numeroGuia=" . $guia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $servicio = $this->arreglo[0]["idservicio"];

            $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) "
                    . "values (" . $servicio . ",'" . date("Y-m-d h:m:s") . "','" . $guia . "','FACTURADO')";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "update posiblesfacturas set factura=" . $factura . ",fecha='" . date("Y-m-d h:m:s") . "' where numeroguia='" . $guia . "';";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->retorno = 1;
            } else {
                $this->retorno = 0;
            }
            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function facturaCero($guia) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select idservicio,fechaServicio from servicio_guias where numeroGuia=" . $guia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $fechaServicio = $this->arreglo[0]["fechaServicio"];
            $idservicio = $this->arreglo[0]["idservicio"];
            $this->consulta = "update posiblesfacturas set factura=0,fecha='" . $fechaServicio . "' where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "update servicio_guias set factura=0,fecha='0000-00-00' where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) "
                    . "values (" . $idservicio . ",'" . date("Y-m-d h:m:s") . "','0','FACTURA A CERO')";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarFacturaGrupo($servicios, $facturaNueva) {
        try {
            $arregloServicios = array();
            $retorno = 0;
            $facNva = 0;

            if (intval($facturaNueva) === 0) {
                $facNva = 0;
            } else {
                $facNva = $facturaNueva;
            }

            $this->con = new Conexion();

            $arregloServicios = explode("-", $servicios);

            for ($index = 0; $index < count($arregloServicios); $index++) {

                $this->consulta = "update servicio_guias "
                        . "set numeroFactura=" . $facNva . ",fechaFactura='1000-01-01' "
                        . "where idservicio=" . $arregloServicios[$index] . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();

                $this->consulta = "update posiblesfacturas "
                        . "set factura=" . $facNva . " "
                        . "where idservicio=" . $arregloServicios[$index] . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $retorno += 1;
                } else {
                    $retorno = 0;
                }
            }

            $this->con = null;
            return $retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }
}
