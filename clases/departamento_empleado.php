<?php

include '../clases/conexion.php';

class departamento_empleado {
    
    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    
    public function retornarDepartamentos(){
        try {
            $this->con= new  Conexion();
            $this->consulta="select * from departamento;";
            $this->prepare=  $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo=  $this->prepare->fetchAll();            
            $this->con=null;
            
            echo '<select id="id_departamento_empleado">';
            foreach ($this->arreglo as $key => $value) {
                echo '<option value="'.$this->arreglo[$key]["dep_id"].'">'.$this->arreglo[$key]["dep_nombre"].'</option>';
            }
            echo '</select>';
            
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        }
    
}
