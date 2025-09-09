<?php 

include_once '../clases/CL_clase_general.php';

$OB_clase_general=new CL_clase_general();

if($_POST["caso"]==='1'){

    $consulta="SELECT * "
    ."FROM es_estudio_entidad "
    ."WHERE es_estudio_entidad.id_es_entidad=".$_POST["datosAEnviar"]["id_es_entidad"]." "
    ."AND es_estudio_entidad.documento='".$_POST["datosAEnviar"]["documento"]."';";

    $retorno["es_estudio_entidad"]=$OB_clase_general->retornar($consulta);

    if(!empty($retorno["es_estudio_entidad"])){
        
        $consulta="SELECT * "
        ."FROM es_estudio_entidad,es_estudio "
        ."WHERE es_estudio_entidad.id_es_estudio=es_estudio.id "
        ."AND es_estudio_entidad.id_es_estudio=".$retorno["es_estudio_entidad"][0]["id_es_estudio"].";";

        $retorno["es_estudio_entidad"]=$OB_clase_general->retornar($consulta);

    }

    echo json_encode($retorno);
}