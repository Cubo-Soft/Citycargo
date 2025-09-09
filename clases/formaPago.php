<?php

include './conexion';
include './consultas.php';

class formaPago {

    private $conectar;
    private $consulta;

    public function retornarDepartamentos() {

        $this->conectar = new Conexion();
        $this->consulta="select * from formaPago;";
        $prepare = $this->conectar->prepare($this->consulta);
        $prepare->execute();
        $a = $prepare->fetchAll();

        echo '<select name="listaFormaPago">';
        foreach ($a as $key => $value) {
            echo '<option value=' . $a[$key]['fom_id'] . '>' . $a[$key]['form_tipo'] . '</option>';
        }
        echo '</select>';

        $this->conectar = null;
    }

}
