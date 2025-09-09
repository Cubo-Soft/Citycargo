<?php

include_once '../../clases/conexion.php';

/**
 * Description of ventasMensual
 * Agrega los valores mensuales de las ventas a la tabla ventasMensual
 *
 * @author Wilmer P. Silva
 */
class ventasMensual {

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

    public function insertarValores($arreglo) {
        try {

            $this->consulta = "insert into ventasmensual (id,anio,mes,cantidadServicios,totalValorCliente,totalValorContratista,totalOtrosPagos,diferencia,porcentajeGanancia)"
                    . "values (null," . $arreglo["anio"] . "," . $arreglo["mes"] . "," . $arreglo["cantidadServicios"] . "," . $arreglo["totalValorCliente"] . "," . $arreglo["totalValorContratista"] . "," . $arreglo["totalOtrosPagos"] . "," . $arreglo["diferencia"] . "," . $arreglo["porcentajeGanancia"] . ");";
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

    public function retornarValoresMesAnterior() {
        $mesAnterior = date('m', strtotime('-1 month'));
        $anioActual = date("Y");

        $this->consulta = "select * from ventasmensual "
                . "where anio=" . $anioActual . " "
                . "and mes=" . $mesAnterior . ";";
        //echo $this->consulta;
        $this->prepare = $this->con->prepare($this->consulta);
        $this->prepare->execute();
        return $this->prepare->fetchAll();
    }

    public function consultarValores($fechaInicial, $fechaFinal) {
        try {
            $this->consulta = "select distinct s.idservicio,s.idempleado,s.fechaServicio,s.placa,v.tipovehiculo,s.cedulaPropietario,s.cedulaConductor,"
                    . "sg.numeroGuia,sg.auxiliar,sg.parqueadero,sg.otros,"
                    . "sg.valorDeclarado,sg.valorPagado,sg.valorCobrado,sg.nit,sg.fechaFactura,sg.numeroFactura,sg.fechaPago,sg.fechaPruebaEntrega,"
                    . "(select cli_nombre from cliente where cli_documento=sg.nit and estado='ACTIV0') as nombreCliente,"
                    . "(select mun_nombre from municipios where mun_id=(select d.ciudad from direcciones as d where d.iddireccion=sg.iddireccionorigen)) as ciudadOrigen,"
                    . "(select mun_nombre from municipios where mun_id=(select d.ciudad from direcciones as d where d.iddireccion=sg.iddirecciondestino)) as ciudadDestino,"
                    . "(select concat(emp_nombres,' ',emp_apellidos) as nombreAsesor from empleados where emp_cedula=sg.nit) as nombreCliente,"
                    . "(select ss.manifiesto from seguimiento_servicio as ss where ss.idservicio=s.idservicio) as manifiesto,pf.factura "
                    . "from servicio_guias as sg,servicio as s,posiblesfacturas as pf,vehiculo as v "
                    . "where s.idservicio=sg.idservicio "
                    . "and sg.numeroGuia=pf.numeroguia "
                    . "and s.placa=v.placa "
                    . "and s.fechaServicio>='" . $fechaInicial . "' "
                    . "and s.fechaServicio<='" . $fechaFinal . "' "
                    . "order by sg.numeroGuia desc;";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            return $this->prepare->fetchAll();
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarDatosPorMeses($mesInicial, $mesFinal) {
        try {
            $this->consulta = "SELECT * "
                    . "FROM ventasmensual "
                    . "WHERE mes BETWEEN " . $mesInicial . " AND " . $mesFinal . " "
                    . "AND anio=".date("Y")." "
                    . "ORDER BY mes ASC;";            
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            return $this->prepare->fetchAll();
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
