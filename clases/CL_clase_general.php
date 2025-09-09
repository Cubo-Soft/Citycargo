<?php

include_once '../clases/CL_conexion2.php';

 class CL_clase_general extends CL_conexion2{

    /**
     * retornar
     */
    public function retornar($sentencia){
        return parent::retornar($sentencia);
    }

    /**
     * crear 
     */
    public function retornarUltimoIdCreado($sentencia){
        return parent::retornarUltimoIdCreado($sentencia);
    }

    /**
     * modificar
     */
    public function ejecutarInsertUpdateDelete($sencentia){
        return parent::ejecutarInsertUpdateDelete($sencentia);
    }
 }

