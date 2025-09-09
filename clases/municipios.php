<?php

include '../clases/conexion.php';

class municipios {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;

    public function retornarMunicipios() {

        $this->con = new Conexion();
        $this->consulta="select mun_id,mun_nombre from municipios order by mun_nombre asc;";
        $this->prepare = $this->con->prepare($this->consulta);
        $this->prepare->execute();
        $this->arreglo = $this->prepare->fetchAll();
        echo '<select name="listaMunicipios" id="listaMunicipios" class="form-control" >';
        echo '<option value="" selected>...</option>';
        foreach ($this->arreglo as $key => $value) {
            echo '<option value=' . $this->arreglo[$key]['mun_id'] . '>' . $this->arreglo[$key]['mun_nombre'] . '</option>';
        }
        echo '</select>';

        $this->con = null;
    }

    public function retornarIdMunicipio($nombreMunicipio) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select mun_id from municipios where mun_nombre='" . $nombreMunicipio . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /* esta función no va aquí, pero la tengo que dejar para que no me genere
     * error al crear al conductor o al modificar la placa. La idea es que 
     * pueda retornar desde aquí el listado de placas de los vehículos activos
     */

    public function retornarListaPlacas() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select placa from vehiculo where estado='ACTIVO';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            echo '<select name="listaPlacas" id="listaPlacas" class="form-control" >';
            echo '<option value="" selected>...</option>';
            foreach ($this->arreglo as $key => $value) {
                echo '<option value=' . $this->arreglo[$key]['placa'] . '>' . $this->arreglo[$key]['placa'] . '</option>';
            }
            echo '</select>';


            $this->con = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}
