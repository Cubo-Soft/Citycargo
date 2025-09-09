<?php 
include_once '../clases/CL_clase_general.php';

$OB_clase_general=new CL_clase_general();

var_dump($OB_clase_general->retornarUltimoIdCreado("insert into es_documentos values (null,'LICENCIA DE TRANSITO')"));

var_dump($OB_clase_general->retornar("select * from es_documentos"));
