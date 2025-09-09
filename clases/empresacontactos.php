<?php

include '../clases/conexion.php';

class empresacontactos {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $arregloRetorno;

    public function __construct() {
        $this->con = new Conexion();
    }

    function __destruct() {
        $this->con = null;
    }

    public function crearContacto($nit, $nombreContacto, $cargoContacto, $telefonoContacto, $correo) {
        try {
            $this->consulta = "insert into empresacontactos(id,nit,nombre,correo,cargo,telefono,estado)"
                    . "values(null," . $nit . ",'" . $nombreContacto . "','" . $correo . "','" . $cargoContacto . "'," . $telefonoContacto . ",1);";
//            echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->consulta = "select * "
                        . "from empresacontactos "
                        . "where nit=" . $nit . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                return $this->prepare->fetchAll();
            } else {
                return 0;
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarContactos($nit) {
        try {
            $this->consulta = "select * "
                    . "from empresacontactos "
                    . "where nit=" . $nit . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (count($this->arreglo) === 0) {
                $this->consulta = "select cli_contacto,cli_direccion,cli_telefono,cli_correo "
                        . "from cliente "
                        . "where cli_documento=" . $nit . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo= $this->prepare->fetchAll();                
                $this->consulta="insert into empresacontactos (id,nit,nombre,correo,cargo,telefono,estado) "
                        . "values (null,".$nit.",'".$this->arreglo[0]["cli_contacto"]."','".$this->arreglo[0]["cli_correo"]."','',".$this->arreglo[0]["cli_telefono"].",1);";                
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->consulta = "select * "
                        . "from empresacontactos "
                        . "where nit=" . $nit . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->arreglo = $this->prepare->fetchAll();
            }

            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarDatosContacto($id) {
        try {
            $this->consulta = "select * "
                    . "from empresacontactos "
                    . "where id=" . $id . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            return $this->prepare->fetchAll();
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarEstado($id, $estado) {
        try {
            $this->consulta = "update asunto_empleado "
                    . "set estado=" . $estado . " "
                    . "where id=" . $id . ";";            
            $this->prepare = $this->con->prepare($this->consulta);
            return $this->prepare->execute();
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
