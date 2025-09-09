<?php

/*
 * Arma la gráfica mostrada en el index.php al ingresar al sistema por parte
 * del gerente o el de sistemas
 */

require_once '../../clases/ventasMensual.php';
include_once '../../clases/funcionesVarias.php';

$ventasMensual = new ventasMensual();

/*
 * Averiguo si existen valores del mes y del anio inmediatamente anterior
 */
if (count($ventasMensual->retornarValoresMesAnterior()) === 0) {

    $arreglo = array();
    $totalValorCliente = 0;
    $totalValorContratista = 0;
    $totalOtrosPagos = 0;
    $totalAuxiliar = 0;
    $diferencia = 0;
    $porcentajeGanancia=0;

    $mesAnterior = date('m', strtotime('-1 month'));
    $cantidadDiasMes = cal_days_in_month(CAL_GREGORIAN, $mesAnterior, date('Y'));
    $fechaInicial = date('Y') . "-" . $mesAnterior . "-01";
    $fechaFinal = date('Y') . "-" . $mesAnterior . "-" . $cantidadDiasMes;

    $resultadoConsulta = $ventasMensual->consultarValores($fechaInicial, $fechaFinal);

    for ($index = 0; $index < count($resultadoConsulta); $index++) {
        $cantidadServicios = $index + 1;
        $totalValorCliente += $resultadoConsulta[$index]["valorCobrado"];
        $totalValorContratista += $resultadoConsulta[$index]["valorPagado"];
        $totalOtrosPagos += $resultadoConsulta[$index]["otros"] + $resultadoConsulta[$index]["parqueadero"] + $resultadoConsulta[$index]["auxiliar"];        
    }

    $diferencia = $totalValorCliente - ($totalValorContratista + $totalOtrosPagos);
    
    $porcentajeGanancia = round(100 - (100 - ($diferencia * 100) / $totalValorCliente),2);

    $arreglo = ["anio" => date('Y'), "mes" => $mesAnterior, "cantidadServicios" => $cantidadServicios,
        "totalValorCliente" => $totalValorCliente, "totalValorContratista" => $totalValorContratista,
        "totalOtrosPagos" => $totalOtrosPagos, "diferencia" => $diferencia,"porcentajeGanancia"=>$porcentajeGanancia];

    $ventasMensual->insertarValores($arreglo);
} else {    
    
require_once ('./jpgraph/src/jpgraph.php');
require_once ('./jpgraph/src/jpgraph_line.php');

$mesFinal=intval(date("m"));
$mesInicial=$mesFinal-6;

$resultadoConsulta=$ventasMensual->retornarDatosPorMeses($mesInicial, $mesFinal);


$meses=array();
$cantidadServicios=array();
$totalValorCliente=array();
$totalValorContratista=array();
$totalOtrosPagos=array();
$diferencia=array();
$porcentajeGanancia=array();

for ($index1 = 0; $index1 < count($resultadoConsulta); $index1++) {
    $meses[$index1]=nombreMes($resultadoConsulta[$index1]["mes"]);
    $cantidadServicios[$index1]=$resultadoConsulta[$index1]["cantidadServicios"];
    $totalValorCliente[$index1]= retornarMilesMillones($resultadoConsulta[$index1]["totalValorCliente"]);
    $totalValorContratista[$index1]= retornarMilesMillones($resultadoConsulta[$index1]["totalValorContratista"]);
//    $totalOtrosPagos[$index1]= retornarMilesMillones($resultadoConsulta[$index1]["totalOtrosPagos"]);
//    $diferencia[$index1]=retornarMilesMillones($resultadoConsulta[$index1]["diferencia"]);
    $porcentajeGanancia[$index1]=$resultadoConsulta[$index1]["porcentajeGanancia"];
}
 
// Setup the graph
$graph = new Graph(1000,350);
$graph->SetScale("textlin");
 
$theme_class=new UniversalTheme;
 
$graph->SetTheme($theme_class);
$graph->img->SetAntiAliasing(false);
$graph->title->Set('Grafico de ventas de los ultimos seis meses');
$graph->SetBox(false);
 
$graph->img->SetAntiAliasing();
 
$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);
 
$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$graph->xaxis->SetTickLabels($meses);
$graph->xgrid->SetColor('#E3E3E3');
 
// Create the first line
$p1 = new LinePlot($totalValorCliente);
$graph->Add($p1);
$p1->SetColor("#6495ED");
$p1->SetLegend('Total valores a clientes');
 
// Create the second line
//$p2 = new LinePlot($totalValorContratista);
//$graph->Add($p2);
//$p2->SetColor("#B22222");
//$p2->SetLegend('Total valores a contratista');
 
// Create the third line
//$p3 = new LinePlot($diferencia);
//$graph->Add($p3);
//$p3->SetColor("#FF1493");
//$p3->SetLegend('Diferencia');
// 
$graph->legend->SetFrameWeight(1);
 
$graph->legend->SetPos(0.5,0.98,'center','bottom');
 
// Output line
$graph->Stroke();
}

