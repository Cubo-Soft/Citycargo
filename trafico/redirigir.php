<?php

session_start();

include_once '../clases/funcionesVarias.php';

$decicion = $_POST["boton"];


switch ($decicion) {

    case "DIRECCIONES":
        header("Location: ../modulos/direcciones.php");
        break;

    case "ENTREGAS":
        header("Location: ../modulos/crearEntregas.php");
        break;

    case "CONSULTAS VEHICULOS":
        header("Location: ../modulos/consultasvehiculos.php");
        break;

    case "COTIZACIÓN":
        header("Location: ../modulos/cotizacion.php");
        break;

    case "SERVICIOS POR SEGUIMIENTO":
        header("Location: ../modulos/seguimiento.php");
        break;

    case "SERVICIOS POR ASESOR":
        header("Location: ../modulos/serviciosporasesor.php");
        break;


    case "ADMINISTRAR SERVICIOS":
        header("Location: ../modulos/administrarServiciosDos.php");
        break;

    case "CUENTA DE COBRO":
        header("Location: ../modulos/cuentaCobro.php");
        break;

    case "SALIR":
        header("Location: ../trafico/salir.php");
        break;

    case "EMPL":
        header("Location: ../modulos/empleados.php");
        break;

    case "MODULOS":
        header("Location: ../modulos/modulosempleados.php");
        break;

    // case "MANTENIMIENTO E INVENTARIO":
    //     header("Location: ../index.php?module=maintenance");
    //     exit; // ¡IMPORTANTE! Detiene la ejecución

    case "MANTENIMIENTO E INVENTARIO":
    header("Location: ../modulos/mantenimiento.php");
    break;

    case "CLIENTES":
        header("Location: ../modulos/clientes.php");
        break;

    case "NOTA CREDITO":
        header("Location: ../modulos/notaCredito.php");
        break;

    case "AGENDA":
        header("Location: ../modulos/agendaComercial.php");
        break;

    case "DETALLE SERVICIOS":
        header("Location: ../modulos/detalladoServicios.php");
        break;

    case "PROCESOS GUIAS":
        header("Location: ../modulos/procesosGuias.php");
        break;

    case "CONDUCTORES":
        header("Location: ../modulos/conductores.php");
        break;

    case "VEHÍCULOS":
        header("Location: ../modulos/vehiculos.php");
        break;

    case "SERVICIOS":
        header("Location: ../modulos/serviciosVariosTres.php");
        break;

    case "CONSULTAS":
        header("Location: ../modulos/consultasServicios.php");
        break;

    case "EMPLEADOS":
        header("Location: ../modulos/empleados.php");
        break;

    case "ROLES":
        header("Location: ../modulos/roles.php");
        break;

    case "MIGRACION":
        header("Location: ../modulos/migracion.php");
        break;

    case "AGENDA":
        header("Location: ../modulos/agendaComercial.php");
        break;

    case "REGRESAR":
        header("Location: ../modulos/index.php");
        break;

    case "IMPUESTOS":
        header("Location: ../modulos/impuestos.php");
        break;

    case "SOBRECOSTOS":
        header("Location: ../modulos/mostrarServicio.php");
        break;

    case "INTELIGENCIA DE NEGOCIO":
        header("Location: ../modulos/inteligenciaNegocio.php");
        break;

    case "GESTION DE SERVICIOS":
        header("Location: ../modulos/gestionarServicios.php");
        break;

    case "REVISAR SEGUIMIENTOS":
        header("Location: ../modulos/revisarSeguimientos.php");
        break;

    case "PARAM. ESTUDIO SEGURIDAD":
        header("Location: ../modulos/es_parametricas.php");
        break;

    case "PLANTILLA ESTUDIO SEGURIDAD":
        header("Location: ../modulos/es_plantilla_estudio_seguridad.php");
        break;

    case "ESTUDIO DE SEGURIDAD":
        header("Location: ../modulos/estudioSeguridad.php");
        break;

    case "CONSULTA ESTUDIO DE SEG.":
        header("Location: ../modulos/consultaEstudioSeguridad.php");
        break;

    default:
        header("Location: ../trafico/index.php?msj=4");
        break;
}
