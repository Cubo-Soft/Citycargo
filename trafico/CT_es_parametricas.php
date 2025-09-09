<?php 
include_once '../clases/CL_clase_general.php';

$OB_clase_general=new CL_clase_general();

if($_POST["caso"]==='1'){
    echo json_encode($OB_clase_general->retornar("select * from ".$_POST["datosAEnviar"]["tabla"]));
}

if($_POST["caso"]==='2'){
    
    $sentencia="insert into ".$_POST["datosAEnviar"]["tabla"]." values (null,'".$_POST["datosAEnviar"]["valorCampo"]."')";    
    echo json_encode($OB_clase_general->retornarUltimoIdCreado($sentencia));

}

if($_POST["caso"]==='3'){    
    $sentencia="SELECT * FROM ".$_POST["datosAEnviar"]["tabla"]." WHERE id=".$_POST["datosAEnviar"]["id"].";";    
    echo json_encode($OB_clase_general->retornar($sentencia));
}

if($_POST["caso"]==='4'){
    
    $sentencia="UPDATE ".$_POST["datosAEnviar"]["tabla"]." set nombre='".$_POST["datosAEnviar"]["valorCampo"]."' where id=".$_POST["datosAEnviar"]["id"].";";
    echo json_encode($OB_clase_general->ejecutarInsertUpdateDelete($sentencia));
}