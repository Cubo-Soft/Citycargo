<?php

include '../clases/conexion.php';

class valoresanticipos {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $lista = null;
    private $control = null;
    private $retorno = array();

    public function crearSobreAnticipo($guia, $valorSobreAnticipo, $placa) {        
        try {
            $this->con = new Conexion();
            
            $this->retorno["valoresAnticipos"]=null;

            $this->consulta = "select * "
                    . "from valoresanticipos "
                    . "where val_numeroGuia=" . $guia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $val_id_empresa = $this->arreglo[0]["val_id_empresa"];
            $conductores_cond_id = $this->arreglo[0]["conductores_cond_id"];
            $idservicio = $this->arreglo[0]["idservicio"];
            $placa = $this->arreglo[0]["placa"];

            $this->consulta = "select numero from numerossobreanticipos order by numero desc limit 1;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $num_numeroAnticipo = intval($this->arreglo[0]["numero"]);
            
            $this->consulta = "insert into valoresanticipos (val_ant_id,val_fechaAnticipo,val_valorAdelanto,valorservicio,val_id_empresa,val_numeroGuia,conductores_cond_id,placa,totalesAnticipos_val_id,val_numeroAnticipo,prueba_entrega,idservicio) "
                    . "values (null,'" . date("Y-m-d h:m") . "','" . $valorSobreAnticipo . "','0','" . $val_id_empresa . "','" . $guia . "','" . $conductores_cond_id . "','" . $placa . "','2628','" . $num_numeroAnticipo . "','N','" . $idservicio . "');";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->retorno["valoresAnticipos"] += 1;
            }

            $this->retorno["numeroAnticipo"]=$num_numeroAnticipo;

            $num_numeroAnticipo += 1;
                        
            $this->consulta = "insert into numerossobreanticipos (id,numero) "
                    . "values(null," . $num_numeroAnticipo . ");";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) "
                    . "values(" . $idservicio . ",'" . date("Y-m-d h:m") . "','" . $guia . "','SOBREANTICIPO');";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();

            $this->con = null;
            return $this->retorno;

        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}