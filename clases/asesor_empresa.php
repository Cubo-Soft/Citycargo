<?php

include '../clases/conexion.php';

class asesor_empresa {

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

    public function retornarAsunto() {
        try {
            $this->consulta = "select * from asunto order by evento asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarEmpresasAsesor($cedula) {
        try {
            $this->consulta = "select c.cli_nombre,c.cli_documento "
                    . "from asesor_empresa as ae,empleados as e,cliente as c "
                    . "where ae.cedula=e.emp_cedula "
                    . "and ae.nit=c.cli_documento "
                    . "and ae.cedula=" . $cedula . " "
                    . "order by c.cli_nombre asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function agregarAsesorEmpresa($cedula, $nit) {
        try {
            $this->consulta = "insert into asesor_empresa(id,cedula,nit)"
                    . "values(null," . $cedula . "," . $nit . ");";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retirarEmpresaAsesor($cedula, $nit) {
        try {
            $this->consulta = "delete from asesor_empresa "
                    . "where nit=" . $nit . " and cedula=" . $cedula . "; ";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarAsesorEmpresa($nit) {
        try {
            $this->consulta = "select cedula "
                    . "from asesor_empresa "
                    . "where nit=" . $nit;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarNitActual($nit, $cedula) {
        try {
            $this->consulta = "select id "
                    . "from asesor_empresa "
                    . "where nit=" . $nit . "; ";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarAsesorEmpresa($cedula, $id) {
        try {
            $this->consulta = "update asesor_empresa "
                    . "set cedula=" . $cedula . " "
                    . "where id=" . $id . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarDirecciones($nit, $tipo, $ciudad) {
        try {
            if ($ciudad === 0) {
                $this->consulta = "select telefono,direccion,"
                        . "(select mun_nombre from municipios where mun_id=ciudad) nombreCiudad,ciudad,iddireccion "
                        . "from direcciones "
                        . "where documento=" . $nit . " "
                        . "and tipo=" . $tipo . " "
                        . "and estado=1 "
                        . "order by direccion asc;";
            } else {
                $this->consulta = "select telefono,direccion,"
                        . "(select mun_nombre from municipios where mun_id=ciudad) nombreCiudad,ciudad,iddireccion "
                        . "from direcciones "
                        . "where documento=" . $nit . " "
                        . "and tipo=" . $tipo . " "
                        . "and estado=1 "
                        . "and ciudad=" . $ciudad . " "
                        . "order by direccion asc;";
            }
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarDireccion($iddireccion) {
        try {
            $this->consulta = "select telefono,ciudad "
                    . "from direcciones "
                    . "where iddireccion=" . $iddireccion . "; ";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function crearDireccion($documento, $telefono, $direccion, $ciudad, $tipo, $condicion) {
        try {
            $this->consulta = "insert into direcciones (iddireccion,documento,telefono,direccion,ciudad,estado,tipo) "
                    . "values(null," . $documento . "," . $telefono . ",'" . $direccion . "'," . $ciudad . ",1," . $tipo . ");";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();

            if ($condicion === 0) {
                return $this->retornarDirecciones($documento, $tipo, 0);
            } else {
                return $this->retornarDirecciones($documento, $tipo, $ciudad);
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarEmpresa($nit) {
        try {
            $this->consulta = "select cli_nombre "
                    . "from cliente "
                    . "where cli_documento=" . $nit . " "
                    . "and estado='ACTIVO'; ";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarDatosAgenda($cedula, $condicion, $fechaInicial, $fechaFinal, $nitEmpresa) {
        try {
            if ($condicion !== 3) {
                $fechaInicial = $fechaInicial;
                $fechaFinal = $fechaFinal;
            } else {
                $fechaInicial = $fechaInicial . ' 00:00:00';
                $fechaFinal = strtotime('+1 day', strtotime($fechaFinal));
                $fechaFinal = date('Y-m-d', $fechaFinal);
                $fechaFinal = $fechaFinal . ' 00:00:00';
            }
            if ($condicion === 1) {
                $this->consulta = "select ae.id,a.evento,d.direccion,ec.correo,ae.fechaHoraInicio,ae.fechaHoraFin,ec.nombre,ec.cargo,ec.telefono,c.cli_nombre,ae.estado "
                        . "from asunto as a,direcciones as d, empresacontactos as ec,empleados as e,cliente as c,asunto_empleado as ae "
                        . "where ae.iddireccion=d.iddireccion "
                        . "and ae.idempresacontactos=ec.id "
                        . "and ae.cedula=e.emp_cedula "
                        . "and ae.nit=c.cli_documento "
                        . "and ae.idasunto=a.id "
                        . "and ae.estado=1 "
                        . "and ae.cedula=" . $cedula . ";";
            }
            if ($condicion === 2) {
                $this->consulta = "select ae.id,a.evento,d.direccion,ec.correo,ae.fechaHoraInicio,ae.fechaHoraFin,ec.nombre,ec.cargo,ec.telefono,c.cli_nombre,ae.estado "
                        . "from asunto as a,direcciones as d, empresacontactos as ec,empleados as e,cliente as c,asunto_empleado as ae "
                        . "where ae.iddireccion=d.iddireccion "
                        . "and ae.idempresacontactos=ec.id "
                        . "and ae.cedula=e.emp_cedula "
                        . "and ae.nit=c.cli_documento "
                        . "and ae.idasunto=a.id "
                        . "and ae.cedula=" . $cedula . " "
                        . "and ae.fechaHoraInicio>='" . $fechaInicial . "' "
                        . "and ae.fechaHoraFin<='" . $fechaFinal . "' "
                        . "order by ae.fechaHoraInicio asc;";
            }
            if ($condicion === 3) {
                $this->consulta = "select ae.id,a.evento,d.direccion,ec.correo,ae.fechaHoraInicio,ae.fechaHoraFin,ec.nombre,ec.cargo,ec.telefono,c.cli_nombre,ae.estado "
                        . "from asunto as a,direcciones as d, empresacontactos as ec,empleados as e,cliente as c,asunto_empleado as ae "
                        . "where ae.iddireccion=d.iddireccion "
                        . "and ae.idempresacontactos=ec.id "
                        . "and ae.cedula=e.emp_cedula "
                        . "and ae.nit=c.cli_documento "
                        . "and ae.idasunto=a.id "
                        . "and ae.cedula=" . $cedula . " "
                        . "and ae.fechaHoraInicio>='" . $fechaInicial . "' "
                        . "and ae.fechaHoraFin<='" . $fechaFinal . "' "
                        . "and ae.nit=" . $nitEmpresa . " "
                        . "order by ae.fechaHoraInicio asc;";
            }
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo["empresas"] = $this->prepare->fetchAll();
            $this->consulta = "select * from asunto_empleado "
                    . "where cedula=" . $cedula . " "
                    . "and idasunto=6 "
                    . "and estado=1;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo["personales"] = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarMunicipios() {
        try {
            $this->consulta = "select mun_id,mun_nombre "
                    . "from municipios;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
