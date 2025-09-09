<?php

include_once '../clases/conexion.php';

class entregas {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $lista = null;
    private $control = null;
    private $retorno = null;

    public function __construct() {
        $this->con = new Conexion();
    }

    public function __destruct() {
        $this->con = null;
    }

    public function crearEntrega($idservicio,$numeroGuia,$unidades,$guiaEntrega,$iddirecciondestino,$planilla,$remision,$factura,$ordenCompra,$auxiliar,$parqueadero,$otros,$valorDeclarado,$valorManejo,$valorCobrado,$valorPagado,$Notas,$nit) {
        try {
            $this->consulta = "insert into entregas (id,idservicio,numeroGuia,unidades,guiaEntrega,iddirecciondestino,planilla,remision,factura,ordenCompra,auxiliar,parqueadero,otros,valorDeclarado,valorManejo,valorCobrado,valorPagado,Notas,nit) "
                    . "values(null,".$idservicio.",".$numeroGuia.",".$unidades.",".$guiaEntrega.",".$iddirecciondestino.",'".$planilla."','".$remision."','".$factura."','".$ordenCompra."',".$auxiliar.",".$parqueadero.",".$otros.",".$valorDeclarado.",0.0,".$valorPagado.",".$valorCobrado.",'".$Notas."',".$nit.");";
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

    public function borrarEntrega($idservicio) {
        try {
            $this->consulta = "delete from entregas where idservicio=" . $idservicio . ";";
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
    
    /*
     * La estoy repitiendo en la clase servicios.php y rol_boton.php
     */
    public function retornarEntregas($idservicio){
        try {
            $this->consulta = "select e.numeroGuia,e.planilla,e.remision,e.factura,e.ordenCompra,e.valorPagado,e.valorCobrado,e.guiaEntrega,e.unidades,"
                    . "(select direccion from direcciones where iddireccion=e.iddirecciondestino) as direccionDestino,"
                    . "(select mun_nombre from municipios where mun_id=(select d.ciudad from direcciones as d where d.iddireccion=e.iddirecciondestino)) as ciudadDestino "
                    . "from entregas as e "
                    . "where idservicio=".$idservicio.";";            
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            return $this->prepare->fetchAll();
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    public function borrarEntregas($idservicio){
        try {
            $this->consulta = "delete from entregas "            
                    . "where idservicio=".$idservicio.";";            
            $this->prepare = $this->con->prepare($this->consulta);
            if($this->prepare->execute()){
                return 1;
            }else{
                return 0;
            }            
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
