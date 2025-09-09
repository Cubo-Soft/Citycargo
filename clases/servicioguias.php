<?php

include_once '../clases/conexion.php';

class servicioguias {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $lista = null;
    private $control = null;

    public function __construct() {
        $this->con = new Conexion();
    }

    public function __destruct() {
        $this->con = null;
    }

    public function retornarValorTotal($guia) {
        try {
            $auxiliar = 0;
            $parqueadero = 0;
            $otros = 0;
            $valorManejo = 0;
            $valorCobrado = 0;
            $valorDeclarado = 0;
            $valorTotal = 0;
            $totalValorManejo = 0;
            $this->consulta = "select idservicio,auxiliar,parqueadero,otros,valorManejo,valorCobrado,valorDeclarado "
                    . "from servicio_guias "
                    . "where numeroGuia=" . $guia . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            //var_dump($this->arreglo);

            if (count($this->arreglo) === 0) {

                $this->consulta = "select idservicio,auxiliar,parqueadero,otros,valorManejo,valorCobrado,valorDeclarado "
                        . "from entregas "
                        . "where guiaEntrega=" . $guia . ";";
                //echo $this->consulta;
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
            }

            $auxiliar = $this->arreglo[0]["auxiliar"];
            $parqueadero = $this->arreglo[0]["parqueadero"];
            $otros = $this->arreglo[0]["otros"];
            $valorManejo = $this->arreglo[0]["valorManejo"];
            $valorCobrado = $this->arreglo[0]["valorCobrado"];
            $valorDeclarado = $this->arreglo[0]["valorDeclarado"];

            //$valorTotal = $valorTotal + $auxiliar + $parqueadero + $otros + $valorCobrado;

            //20230418 lo comentado va a dejar de hacerse porque el valor 
            //del manejo ya esta incluido en el valor del flete
            //if ($valorManejo !== '0.00') {
                //el valor declarado se aumenta sobre el valor del flete
            //    $totalValorManejo = ($valorDeclarado * $valorManejo) / 100;
            //    $valorTotal = $valorCobrado + $totalValorManejo;
            //} else {
                $valorTotal += $valorCobrado;
            //}

            return $valorTotal;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarValorManejo($numeroGuia, $valorManejo) {
        try {
            $this->consulta = "update servicio_guias set valorManejo=" . $valorManejo . " "
                    . "where numeroGuia=" . $numeroGuia . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarRemision($remision, $numeroGuia) {
        try {
            $this->consulta = "update servicio_guias set remision='" . $remision . "' "
                    . "where numeroGuia=" . $numeroGuia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarPlanilla($planilla, $numeroGuia) {
        try {
            $this->consulta = "update servicio_guias set planilla='" . $planilla . "' "
                    . "where numeroGuia=" . $numeroGuia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarFacturaCliente($factura, $numeroGuia) {
        try {
            $this->consulta = "update servicio_guias set factura='" . $factura . "' "
                    . "where numeroGuia=" . $numeroGuia . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarOrdenCompra($ordenCompra, $numeroGuia) {
        try {
            $this->consulta = "update servicio_guias set ordenCompra='" . $ordenCompra . "' "
                    . "where numeroGuia=" . $numeroGuia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarDatosGuia($guia) {
        try {
            $this->consulta = "select sg.idservicio,sg.numeroGuia,sg.unidades,sg.planilla,sg.remision,sg.factura,sg.ordenCompra,"
                    . "sg.auxiliar,sg.parqueadero,sg.otros,sg.valorDeclarado,sg.valorManejo,sg.valorPagado,sg.valorCobrado,sg.nit,"
                    . "sg.fechaFactura,sg.numeroFactura,sg.fechaPago,sg.fechaPruebaEntrega,"
                    . "(select d.direccion from direcciones as d where d.iddireccion=sg.iddireccionorigen) as direccionOrigen,"
                    . "(select mun_nombre from municipios where mun_id=(select d.ciudad from direcciones as d where d.iddireccion=sg.iddireccionorigen)) as ciudadOrigen,"
                    . "(select d.direccion from direcciones as d where d.iddireccion=sg.iddirecciondestino) as direccionDestino,"
                    . "(select mun_nombre from municipios where mun_id=(select d.ciudad from direcciones as d where d.iddireccion=sg.iddirecciondestino)) as ciudadDestino "
                    . "from servicio_guias as sg "
                    . "where sg.numeroGuia=" . $guia . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo["datosGuias"] = $this->prepare->fetchAll();
            $idservicio = $this->arreglo["datosGuias"][0]["idservicio"];

            $this->consulta = "select * "
                    . "from servicio "
                    . "where idservicio=" . $idservicio . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo["servicio"] = $this->prepare->fetchAll();

            $this->consulta = "select s.valorDeclarado,s.valorManejo,s.valorCobrado,s.guiaEntrega,s.planilla,s.remision,s.factura,"
                    . "s.ordenCompra,s.unidades,"
                    . "(select d.direccion from direcciones as d where d.iddireccion=s.iddirecciondestino) as direccionDestino,"
                    . "(select mun_nombre from municipios where mun_id=(select d.ciudad from direcciones as d where d.iddireccion=s.iddirecciondestino)) as ciudadDestino "
                    . "from entregas as s "
                    . "where idservicio=" . $idservicio . ";";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo["entregas"] = $this->prepare->fetchAll();

            $this->consulta = "select tipovehiculo "
                    . "from vehiculo "
                    . "where placa='" . $this->arreglo["servicio"][0]["placa"] . "';";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo["tipoVehiculo"] = $this->prepare->fetchAll();

            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarServicioGuias($idservicio) {
        try {
            $this->consulta = "select * from servicio_guias "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
