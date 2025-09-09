<?php

include '../clases/conexion.php';

class tipoServicio {

    private $conectar;
    private $prepare;
    private $arreglo;
    private $consulta;

    public function retornarDepartamentos() {

        $this->conectar = new Conexion();
        $this->consulta="select * from tipoServicio;";
        $this->prepare = $this->conectar->prepare($this->consulta);
        $this->prepare->execute();
        $this->arreglo = $this->prepare->fetchAll();

        echo '<select name="listaTiposServicio">';
        foreach ($a as $key => $value) {
            echo '<option value=' . $a[$key]['tip_id'] . '>' . $a[$key]['tip_tipoServicio'] . '</option>';
        }
        echo '</select>';

        $this->conectar = null;
    }

}
