<?php

include '../clases/conexion.php';

class vehiculos {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $lista = null;

    public function retornarConductoresTipoVehiculo($tipo, $carroceria, $capacidadInicial, $capacidadFinal) {
        try {
            $this->con = new Conexion();

            $this->consulta = "select v.placa,(select marca from marcasvehiculos where id=v.marca) as marca,"
                    . "v.modelo,v.tipocarroceria,v.capacidadcarga,v.ancho,v.ancho,"
                    . "v.largo,v.alto,v.tipovehiculo,v.reportar_novedad "                    
                    . "from vehiculo as v "
                    . "where v.marca<>0 ";                    

            if ($tipo !== '0') {
                $this->consulta .= "and v.tipovehiculo='" . $tipo . "' ";
            }

            if ($carroceria !== '0') {
                $this->consulta .= "and v.tipocarroceria='" . $carroceria . "' ";
            }

            if ($capacidadInicial !== '0') {
                $this->consulta .= "and v.capacidadcarga=>" . $capacidadInicial . " ";
            }

            $this->consulta .= "and v.capacidadcarga<=" . $capacidadFinal . ";";                    

            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            echo json_encode($this->arreglo);
            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarEstadoPlaca($placa) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * from vehiculo where placa='" . $placa . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (count($this->arreglo) >= 1) {
                echo json_encode($this->arreglo);
            } else {
                $this->arreglo = array('estado' => '0');
                echo json_encode($this->arreglo);
            }

            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    public function retornarVehiculo($placa){
        try {
            $this->con = new Conexion();
            $this->consulta = "select * from vehiculo where placa='" . $placa . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;

            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    public function crearPlaca($placa, $estado, $marca, $modelo, $tipocarroceria, $capacidadcarga, $ancho, $largo, $alto, $tipovehiculo,$reportar_novedad) {
        try {
            $this->con = new Conexion();
            $this->consulta = "insert into vehiculo (placa,marca,modelo,tipocarroceria,capacidadcarga,ancho,largo,alto,tipovehiculo,estado,reportar_novedad) "
                    . "values ('" . $placa . "','" . $marca . "'," . $modelo . ",'" . $tipocarroceria . "'," . $capacidadcarga . "," . $ancho . "," . $largo . "," . $alto . ",'" . $tipovehiculo . "','" . $estado . "',".$reportar_novedad.");";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                return 1;
            } else {
                return 0;
            }
            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    public function modificarPlaca($placa, $estado, $marca, $modelo, $tipocarroceria, $capacidadcarga, $ancho, $largo, $alto, $tipovehiculo,$reportar_novedad) {
        try {
            $this->con = new Conexion();
            $this->consulta = "update vehiculo "
                    . "set estado='" . $estado . "',"
                    . "marca='" . $marca . "',"
                    . "modelo=" . $modelo . ","
                    . "tipocarroceria='" . $tipocarroceria . "',"
                    . "capacidadcarga=" . $capacidadcarga . ","
                    . "ancho=" . $ancho . ","
                    . "largo=" . $largo . ","
                    . "alto=" . $alto . ","
                    . "tipovehiculo='" . $tipovehiculo . "',"
                    . "reportar_novedad=".$reportar_novedad." "
                    . "where placa='" . $placa . "';";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            echo json_encode($this->prepare->execute());
            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    public function retornarPropietario($placa) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select concat (c.cond_nombres,' ',c.cond_apellidos) as nombres,c.cond_identificacion,cv.fecha "
                    . "from conductores as c "
                    . "inner join conductor_vehiculo as cv "
                    . "on c.cond_identificacion=cv.identificacion "
                    . "where c.perfil=9 and cv.estado='ACTIVO' and cv.placa='" . $placa . "';";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            if (!empty($this->arreglo)) {
                echo json_encode($this->arreglo);
            } else {
                echo json_encode(array('0' => array('posicion' => '0')));
            }

            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
    }

    public function retornarMarcasVehiculos() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select id,marca "
                    . "from marcasvehiculos "
                    . "where estado=1 "
                    . "order by marca asc;";
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
