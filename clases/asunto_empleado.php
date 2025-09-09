<?php

include '../clases/conexion.php';

class asunto_empleado {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $lista = null;
    private $control = null;
    private $retorno = null;

    public function crearAsuntoEmpleado($idasunto, $nit, $dlDirOrg, $fechaHoraInicio, $fechaHoraFin, $listaContactos, $cedulaEmpleado,$asuntoEmpleado) {
        try {
            $fecha_actual = date("Y-m-d");
            $fechaInicial = date("Y-m-d", strtotime($fecha_actual . "- 8 days"));
            $fechaHoraInicio = str_replace('T', ' ', $fechaHoraInicio);
            $fechaHoraInicio .= ':00';
            $fechaHoraFin = str_replace('T', ' ', $fechaHoraFin);
            $fechaHoraFin .= ':00';
            $this->con = new Conexion();
            
            if($idasunto!=='6'){
                $this->consulta = "insert into asunto_empleado (id,idasunto,iddireccion,fechaHoraInicio,fechaHoraFin,idempresacontactos,cedula,nit,personal,estado) "
                    . "values (null," . $idasunto . "," . $dlDirOrg . ",'" . $fechaHoraInicio . "','" . $fechaHoraFin . "'," . $listaContactos . "," . $cedulaEmpleado . "," . $nit . ",'',1);";
            }else{
                $this->consulta = "insert into asunto_empleado (id,idasunto,iddireccion,fechaHoraInicio,fechaHoraFin,idempresacontactos,cedula,nit,personal,estado) "
                    . "values (null," . $idasunto . ",0,'" . $fechaHoraInicio . "','" . $fechaHoraFin . "',0," . $cedulaEmpleado . ",0,'".$asuntoEmpleado."',1);";
            }
                        
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->retorno=1;
            } else {
                $this->retorno = 0;
            }
            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
  }
