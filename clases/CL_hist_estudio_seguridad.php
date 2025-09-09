<?php 
include_once '../clases/CL_conexion2.php';

class CL_hist_estudio_seguridad {

    private $conn,$consulta,$retorno,$respuesta;    

    public function __construct()
    {
        $this->conn=new CL_conexion2();
    }

    public function __destruct()
    {
        $this->conn=null;
    }

    public function retornarHistEstudioSeguridad($placa,$parametro2){
        try{
            
            $this->consulta="select * from hist_estudio_seguridad where placa='".$placa."'";
            $this->respuesta=$this->conn->retornar($this->consulta);

            return $this->respuesta;

        }catch(Exception $exc){
            echo $exc->getTraceAsString();
        }
    }

    public function crearHistEstudioSeguridad($parametro1,$parametro2){
        try{
            
            $this->consulta="insert into hist_estudio_seguridad (id,placa,modelo,id_tipovehiculo,licenciatransito,fechasoat,revisiontecno,polizarespo,
            imagen1,imagen2,imagen3,imagen4,rndcvehiculo,fasecoldavehiculo,nomultempresacargue,conultempresacargue,fecultempresacargue,fotoconductor,
            hojavidacond,cedulaconductor,nombreconductor,rutalicencia,rutalicencia2,vencimientolicencia,direccionconductor,telefonoconductor,id_eps,
            id_arl,id_pension,estadoeps,estadoarl,estadopension,fechadatospersonales,telefonoemergencia,nombrecontacto,id_parentescocontacto,nombrerefcond,
            id_parenrefcond,dirrefcond,telrefcond,verifirefcond,id_parenrefcondlab,nombrereflab,direcreflab,telecreflab,vercreflab,fecvenciponal,fecvencipersone,
            fecvenciprocu,fecvencicontrola,fecvencisimit,fecvencirnmc,fecvenciinhab,cedulapropietario,nombrepropietario,direccionpropietario,telefonopropietario,
            rutaruntprop,fecvenciruntprop,rutadatperprop,fechadatperprop,rutarutprop,numrutpropietario,rutacertbanprop,fecruntproveh,fecpolantproveh,
            fecpersproveh,fecprocproveh,feccontrproveh,fecrnmcproveh,fecinhabproveh,identloctencom,nomloctencom,dirloctencom,telloctencom,id_documentosoporte,
            fecruntltcveh,fecpolantltcveh,fecpersltcveh,fecprocltcveh,feccontrltcveh,fecrnmcltcveh,fecinhabltcveh,numrutltc,rutarutltc,cercarmaniali,
            cerfumig,cerconsan,placaremol,tarregremol,cedpropremol,nompropremol,rutafototrailer,runtremol,polremol,proremol,conremol,inharemol,
            rnmcremol,fechaestudio,cedulaempleado,observaciones,fechacambio,cedulaempcambio) values ('".$parametro1[0]["id"]."','".$parametro1[0]["placa"]."'
            ,".$parametro1[0]["modelo"].",".$parametro1[0]["id_tipovehiculo"].",'".$parametro1[0]["licenciatransito"]."','".$parametro1[0]["fechasoat"]."'
            ,'".$parametro1[0]["revisiontecno"]."','".$parametro1[0]["polizarespo"]."','".$parametro1[0]["imagen1"]."','".$parametro1[0]["imagen2"]."','".$parametro1[0]["imagen3"]."'
            ,'".$parametro1[0]["imagen4"]."','".$parametro1[0]["rndcvehiculo"]."','".$parametro1[0]["fasecoldavehiculo"]."','".$parametro1[0]["nomultempresacargue"]."'
            ,'".$parametro1[0]["conultempresacargue"]."','".$parametro1[0]["fecultempresacargue"]."','".$parametro1[0]["fotoconductor"]."','".$parametro1[0]["hojavidacond"]."'
            ,'".$parametro1[0]["cedulaconductor"]."','".$parametro1[0]["nombreconductor"]."','".$parametro1[0]["rutalicencia"]."','".$parametro1[0]["rutalicencia2"]."'
            ,'".$parametro1[0]["vencimientolicencia"]."','".$parametro1[0]["direccionconductor"]."','".$parametro1[0]["telefonoconductor"]."',".$parametro1[0]["id_eps"]."
            ,".$parametro1[0]["id_arl"].",".$parametro1[0]["id_pension"].",".$parametro1[0]["estadoeps"].",".$parametro1[0]["estadoarl"].",".$parametro1[0]["estadopension"]."
            ,'".$parametro1[0]["fechadatospersonales"]."','".$parametro1[0]["telefonoemergencia"]."','".$parametro1[0]["nombrecontacto"]."','".$parametro1[0]["id_parentescocontacto"]."'
            ,'".$parametro1[0]["nombrerefcond"]."','".$parametro1[0]["id_parenrefcond"]."','".$parametro1[0]["dirrefcond"]."','".$parametro1[0]["telrefcond"]."'
            ,'".$parametro1[0]["verifirefcond"]."','".$parametro1[0]["id_parenrefcondlab"]."','".$parametro1[0]["nombrereflab"]."','".$parametro1[0]["direcreflab"]."'
            ,'".$parametro1[0]["telecreflab"]."','".$parametro1[0]["vercreflab"]."','".$parametro1[0]["fecvenciponal"]."','".$parametro1[0]["fecvencipersone"]."'
            ,'".$parametro1[0]["fecvenciprocu"]."','".$parametro1[0]["fecvencicontrola"]."','".$parametro1[0]["fecvencisimit"]."','".$parametro1[0]["fecvencirnmc"]."'
            ,'".$parametro1[0]["fecvenciinhab"]."','".$parametro1[0]["cedulapropietario"]."','".$parametro1[0]["nombrepropietario"]."','".$parametro1[0]["direccionpropietario"]."'
            ,'".$parametro1[0]["telefonopropietario"]."','".$parametro1[0]["rutaruntprop"]."','".$parametro1[0]["fecvenciruntprop"]."','".$parametro1[0]["rutadatperprop"]."'
            ,'".$parametro1[0]["fechadatperprop"]."','".$parametro1[0]["rutarutprop"]."','".$parametro1[0]["numrutpropietario"]."','".$parametro1[0]["rutacertbanprop"]."'
            ,'".$parametro1[0]["fecruntproveh"]."','".$parametro1[0]["fecpolantproveh"]."','".$parametro1[0]["fecpersproveh"]."','".$parametro1[0]["fecprocproveh"]."'
            ,'".$parametro1[0]["feccontrproveh"]."','".$parametro1[0]["fecrnmcproveh"]."','".$parametro1[0]["fecinhabproveh"]."','".$parametro1[0]["identloctencom"]."'
            ,'".$parametro1[0]["nomloctencom"]."','".$parametro1[0]["dirloctencom"]."','".$parametro1[0]["telloctencom"]."',".$parametro1[0]["id_documentosoporte"]."
            ,'".$parametro1[0]["fecruntltcveh"]."','".$parametro1[0]["fecpolantltcveh"]."','".$parametro1[0]["fecpersltcveh"]."','".$parametro1[0]["fecprocltcveh"]."'
            ,'".$parametro1[0]["feccontrltcveh"]."','".$parametro1[0]["fecrnmcltcveh"]."','".$parametro1[0]["fecinhabltcveh"]."','".$parametro1[0]["numrutltc"]."'
            ,'".$parametro1[0]["rutarutltc"]."','".$parametro1[0]["cercarmaniali"]."','".$parametro1[0]["cerfumig"]."','".$parametro1[0]["cerconsan"]."'
            ,'".$parametro1[0]["placaremol"]."','".$parametro1[0]["tarregremol"]."','".$parametro1[0]["cedpropremol"]."','".$parametro1[0]["nompropremol"]."'
            ,'".$parametro1[0]["rutafototrailer"]."','".$parametro1[0]["runtremol"]."','".$parametro1[0]["polremol"]."','".$parametro1[0]["proremol"]."'
            ,'".$parametro1[0]["conremol"]."','".$parametro1[0]["inharemol"]."','".$parametro1[0]["rnmcremol"]."','".$parametro1[0]["fechaestudio"]."','".$parametro1[0]["cedulaempleado"]."'
            ,'".$parametro1[0]["observaciones"]."','".date('Y-m-d H:m:s')."','".$parametro2["cedulaempcambio"]."');";
            //echo $this->consulta;
            $this->respuesta=$this->conn->ejecutarInsertUpdateDelete($this->consulta);

            return $this->respuesta;

        }catch(Exception $exc){
            echo $exc->getTraceAsString();
        }
    }


}