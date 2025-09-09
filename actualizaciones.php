<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Requerimientos</title>
        <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="js/jquery-1.11.2.js" type="text/javascript"></script>
        <script src="js/js_index.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js"></script> 
        <link href="css/css2.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
        if (@$_POST["clave"] === '830112988') {
            ?>
            Regresar a la <a href="index.php">página principal</a>
            <div class="col-lg-6" style="overflow-y:visible; height: 500px;">
                <h1>Requerimientos gerencia</h1>  
                <p>
                    Fecha: 202202221001 <br>
                    En los módulos mosrarServicio.php y administrarServiciosDos.php se habilita mostrar la fecha
                    de transferencia y el número de cuenta del conductor<br>                    
                </p>
                 <p>
                    Fecha: 202202171629 <br>
                    Se habilita opción de ingreso de la fecha de transferencia de la cuenta de cobro en el 
                    modulo gestionarServicios.php<br>                    
                </p>
                <p>
                    Fecha: 201904111235<br>
                    En el módulo Servicios pendientes por facturar que salga:<br>
                    - El valor a facturar, el propietario y la placa.Terminado 20190411<br>
                    En el módulo Pendientes por pagar<br>
                    - Cambiar la palabra "Diferencia" por "Saldo". Terminado 20190411<br>                    
                </p>
                <p>
                    Fecha: 201903011918<br>
                    Se incluye en el proyecto los modulos correspondientes a la gestión de seguimiento de guías.
                    Se realizan los ajustes necesarios para visualizar tanto el origen como el destino de los 
                    servicios.
                    Se corrigen las fallas en la subida de archivos de tipo png o jpg. Hubo subida de archivos
                    de tipo .docx.
                    Se añade la opción de reactivar cuenta de cobro para un servicio especifico en el módulo 
                    ADMINISTRAR SERVICIOS, esto al realizar la consulta del número de servicio.
                </p>
                <p>
                    Fecha: 201811061145<br>
                    Que los valores: Auxiliar, Parqueadero y Otros afecten el porcentaje y la 
                    rentabilidad en cada servicio. Se debe ver reflejado en el resumen del
                    servicio por parte de la Sra. Alba<br>
                <p class="text-success">Terminado 201811120947</p>
                <p>
                    Fecha: 201812121709<br>
                    El anterior requerimiento afecta directamente las consultas realizadas
                    desde el módulo de consultas. Así que se programa dichas consultas para 
                    que se vea reflejado los valores Auxiliar, Parqueadero y Otros cuando
                    son superiores a cero.
                </p>
                <p>
                    Fecha: 201810251552<br>
                    Modificar valores: Auxiliar, Parqueadero y Otros.<br>
                <p class="text-success">Terminado 201810251555</p>
                </p>
                <p>
                    Fecha: 201810251036<br>
                    Incluir en el anticipo (documento pdf) el valor declarado por cada guía.<br>
                <p class="text-success">Terminado 201810251036</p>
                </p>
                <p>
                    Fecha: 201810181014<br>
                    Incluir origen y destino en consultas Sra. Alba por fecha y
                    Luis Arnulfo Herrera que pueda ver los servicios de cada asesor, también
                    con origen y destino.<br>
                <p class="text-success">Terminado 201810221543</p>
                </p>
                <p>
                    Fecha: 201810031130<br>
                    Se termina posibilidad de cambiar valores a conductor y empresa
                    cuando el servicio no ha sido facturado
                </p>
                <p>
                    Fecha: 201808211508 <br>
                    En reunión realizada el día de hoy en la mañana con la Sra. Alba solicita complementar SIG con
                    los siguientes requerimientos:
                <ol>
                    <li class="text-danger">Control para que se paguen aquellos servicios a los que se les registre el ingreso del cumplido en el sistema.
                        Actualmente se selecciona con una casilla en la cuenta de cobro si trajo o no el "cumplido" de un servicio, pero
                        el procedimiento se inicia en el área de recepción donde se recibe dicho "cumplido"<br>
                        <strong>Terminado 201809061717</strong><br>
                        En realidad, no todas las veces que un conductor deja de entregar el cumplido el servicio queda por pagar. Hay excepciones 
                        a esta regla. Se modifica la cuenta de cobro para que muestre aquellos servicios que ya tienen cumplido y se deja la opción
                        de pagar o no en el lado derecho de la cuenta de cobro.
                    </li>
                    <li class="text-success">
                        Agregar una advertencia de porcentaje cuando se esta digitando el valor total del servicio al crearlo.<strong>Terminado 201808211633</strong>
                    </li>
                </ol>
                <br>
                El requerimiento 201808011149 No. 4 de mostrar origen y destino en cada guía, se debe reinterpretar. El sitio donde 
                se recoge es común a todas las guías involucradas en el servicio.
                </p>
                <p>
                    Fecha: 201808011149 <br>
                    En reunión realizada el miercoles 01 con la Sra. Alba solicita complementar el software sig con
                    los siguientes requerimientos:
                <ol>
                    <li class="text-warning">Agregar el campo "Valor declarado" al formulario de creación de servicios<br>
                        <strong>201808301625</strong>Pendiente recibir información sobre mejora del resultado
                    </li>
                    <li class="text-warning">Cuando la guía se digita dos veces, informar sobre que servicios se esta digitando en la lista de servicios a cancelar y enviar correo electronico<br>
                        <strong>201808080938</strong> Pendiente recibir información sobre mejora del resultado</li>
                    <li class="text-success">Crear consulta por número de factura, mostrar todos los servicios asociados a esa factura <strong>Terminado 201808081242</strong><br>
                    <li class="text-warning">En el documento del anticipo, poner el origen y el destino de cada guía<br>                        
                        <strong>201808291451</strong>Pendiente recibir información sobre mejora de resultado
                    </li>
                    <li class="text-success">En el proceso de "Cambio de valor de un anticipo" se debe poder realizar dos acciones:
                        <ol>
                            <li>Agregar un valor a un anticipo</li>                            
                        </ol>
                        Se solicita darle prioridad a este requerimiento<br>
                        <p>
                            <strong>Fecha: 201808141632</strong><br>
                            El requerimiento estuvo mal expuesto. En realidad se trataba de realizar un sobre anticipo a un servicio 
                            realizado por algún vehículo. Queda pendiente por recibir información sobre mejora del proceso.<br>
                            Cuando en el módulo de ADMINISTRAR SERVICIOS se digita en la casilla Anticipo el número de anticipos al 
                            que se le va a agregar un valor, se muestra aquellos datos correspondientes a dicho anticipo. Siempre
                            y cuando el servicio no haya sido pagado se muestra el botón CREAR NUEVO ANTICIPO. Dicho botón lo lleva
                            al módulo AGREGAR ANTICIPO donde de manera predeterminada muestra el posible valor del sobre anticipo
                            a realizar, el saldo y el valor del servicio nuevamente.
                            Se debe poner en la casilla V/r sobreanticipo el valor a anticipar picar fuera de la casilla para que los
                            valores se ajusten y si todo esta bien, se presiona el botón AGREGAR ANTICIPO                            
                        </p>
                    </li>
                </ol>
                PD: El proceso iniciado el 201807111729 aún esta pendiente<br>                
                </p>  
                <p>
                    Fecha: 201807241553 <br>
                    En el módulo "ADMINISTRAR SERVICIOS", al consultar el número de servicio en la casilla: "Número servicio"
                    se muestra todos los datos del servicio, en la parte inferior; siempre y cuando el servicio tenga número de 
                    factura asociado, se puede realizar el cambio de este número digitando el nuevo y presionando la tecla
                    de tabulado o danto clic fuera de la casilla donde esta el número de factura
                </p>
                <p>
                    Fecha: 201807181642 <br>
                    Se crea el botón "CONSULTAS", al presionar sobre él lo lleva al módulo "CONSULTAS SERVICIOS",
                    donde se encuentra una lista desplegable para selecionar el tipo de consulta a realizar.
                    <br>
                    En dicha lista se encuentra la opción: Por fecha inicial a fecha final. Al presionar en dicha
                    opción muestra en la parte inferior las opciones: "Fecha Inicial" y "Fecha Final" y el botón:
                    "CONSULTAR SERVICIOS".
                    Con esta opción se encontraría cubierto los requerimientos 1 y 2 del 201807141019.
                    Se continuar con: Cambiar número de factura por parte de Sra. Alba.
                </p> 
                <p>
                    Fecha: 201807181642 <br>
                    Se crea el botón "CONSULTAS", al presionar sobre él lo lleva al módulo "CONSULTAS SERVICIOS",
                    donde se encuentra una lista desplegable para selecionar el tipo de consulta a realizar.
                    <br>
                    En dicha lista se encuentra la opción: Por fecha inicial a fecha final. Al presionar en dicha
                    opción muestra en la parte inferior las opciones: "Fecha Inicial" y "Fecha Final" y el botón:
                    "CONSULTAR SERVICIOS".
                    Con esta opción se encontraría cubierto los requerimientos 1 y 2 del 201807141019.
                    Se continuar con: Cambiar número de factura por parte de Sra. Alba.
                </p>                
                <p>
                    Fecha: 201807141019 <br>
                    En reunión realizada el jueves 12 con la Sra. Alba solicita complementar el software sig con
                    los siguientes requerimientos:
                <ol>
                    <li class="text-success">Ver servicios desde fecha inicial hasta fecha final <strong>Terminado</strong></li>
                    <li class="text-success">Ver cantidad de servicios y cuales son los realizados a diario <strong>Terminado</strong></li>
                    <li class="text-success">Si se digita mal el número de factura, poder cambiarlo por parte de la Sra. Alba <strong>Terminado</strong></li>
                    <li class="text-success">Ver servicios por asesor <strong>Terminado</strong></li>
                    <li class="text-success">Que el asesor pueda realizar seguimiento a sus servicios <strong>Terminado 201808011433</strong><br>                        
                    <li class="text-success">Incluir en el resumen de servicio que se genera en el módulo Administrar Servicios <br>
                        la fecha en que se crea el servicio <strong>Terminado 201801081244</strong></li>
                    <li class="text-success">Poder modificar el asesor por parte de la Sra. Alba <strong>Terminado 201808011430</strong></li>
                </ol>

                PD: El proceso iniciado el 201807111729 queda pendiente para darle prioridad al requerimiento<br>
                número 1 de esta lista.
                </p>            
                <p>
                    Fecha: 201807111729 <br>
                    Se inicia proceso para módulo Administrar Servicios en la lista de 
                    empleados<br>
                </p>            
                <p>
                    Fecha: 201807111703 <br>
                    Se realiza actualización del módulo que permite la revisión del servicio con todos los datos
                    que se encuentran en la base. Un fallo en la consulta a la base de datos emitía datos erroneos<br>
                    Se crean las funciones ALT para los botones del menú principal, permitiendo de esta manera facilitar 
                    el entendimiento de las funciones de cada botón.<br>
                    Pendiente hacer que la lista de empleados en el módulo Administrar Servicios muestre los servicios
                    adjudicados a cada uno junto con los datos pertinentes que solicite la Sra. Alba.
                </p>
               
            </div>            
            <div class="col-lg-6" style="overflow-y:visible; height: 250px;">
                <div>
                    <h2>Fallas</h2>
                    <p>
                        Fecha: 202202221006<br>
                        Mostraba un mensaje de no contenido de variable en la linea 283 de mostrarServicio.php. Se corrigió 
                        preguntando si la variable viene con contenido para continuar con la lógica del programa.
                    </p>
                    <p>
                        Fecha: 202202181256<br>
                        Al momento de registrar el usuario en la tabla ingresos, estaba para registrar la clave. Se cambia por la cédula del usuario.
                    </p>
                    <p>
                        Fecha: 20190411<br>
                        En el módulo Servicios pendientes por facturar, algunas veces salen guias dos veces pero no le da la alerta.<br>
                        Se corrige de manera que se muestren estos mensajes.
                    </p>
                    <p>
                        Fecha: 201902281108<br>
                        Cuando en la cuenta de cobro se seleccionaba varios servicios a pagar, algunos no salian en el pdf
                        generado. Se corrigen /modulos/cuentaCobro.php, /trafico/generarpdfctacobro.php y /js/js_cuentaCobro.js.
                    </p>
                     <p>
                        Fecha: 201812111100<br>
                        Se soluciona problema en consultas que mostraba valores en cero cuando se hacian varias veces.
                    </p>
                    <p>
                        Fecha: 201810251138<br>
                        Cuando se cambia el valor declarado varias veces, hace la inserción las veces que se cambia.
                    </p>
                    <p>
                        Fecha: 201810221608<br>
                        Al agregar un anticipo se debe dejar el mismo estado del pago del servicio, es decir si un servicio
                        ya fue pagado, el nuevo anticipo debe quedar como pagado. De lo contrario se deja por pagar
                    </p>
                    <p>
                        Fecha: 201809301035<br>
                        Falla en el módulo MOSTRAR SERVICIOS no mostraba la cantidad de guías reales de un servicio<br>
                        Solución: 201809301040<br>
                        Se corrige permitiendo mostrar todas las guías asociadas a un servicio. Queda corregido.<br>
                    </p>
                    <p>
                        Fecha: 201809281445<br>
                        Falla en el módulo ADMINISTRAR SERVICIOS cuando un servicio es de varias empresas se muestran valores en cero<br>
                        Solución: 201809281445<br>
                        Se revisa por que no estaba mostrando dichos valores. Queda corregido.<br>
                    </p>
                    <p>
                        Fecha: 201809201029<br>
                        Falla en el módulo ADMINISTRAR SERVICIOS cuando un servicio es de varias empresas se muestran valores en cero<br>
                        Solución: 201809201040<br>
                        Se corrige de manera que muestre dichos valores.<br>
                    </p>
                    <p>
                        Fecha: 201809121714<br>
                        No se muestran datos de servicio en el módulo ADMINISTRAR SERVICIOS cuando el servicio no tiene una posible factura<br>
                        Solución: 201809121714<br>
                        Se añade la opción que informe de dicha falla para que pueda ser eliminado por parte de gerencia.<br>
                    </p>
                    <p>
                        Fecha: 201809111101<br>
                        Cuando el servicio llevaba auxilar u otros valores, se guardaba dicho valor en la base y se confundía con el valor real en la cuenta de cobro<br>
                        Solución: 201809111102<br>
                        Se guarda el valor del servicio sin sumar dichos valores. Clase servicios, funcion modificarServicioDos.<br>
                    </p>
                    <p>
                        Fecha: 201808161124<br>
                        Aparecia un botón del menú principal por fuera de la lista de botones<br>
                        Solución: 201808161124<br>
                        La clase BOOSTRAP que se estaba manejando en el div de los botones era inferior en tamaño a 
                        la que estaba arriba. Se dejo en 12.<br>
                    </p>
                    <p>
                        Fecha: 201808021000<br>
                        Cuando se digitaba un servicio que no había concluido, no mostraba ninguna información<br>
                        Solución: 201808021001<br>
                        Se muestra información sobre la persona que intento crearlo.<br>
                    </p> 
                    <p>
                        Fecha: 201808020905<br>
                        Se debe corregir quien crea el servicio y el asesor al que esta asociado dicho servicio<br>
                        Solución: 20180802<br>
                        Se soluciona problema en js_administrarServicios.js en variable lmp.<br>
                    </p> 
                    <p>
                        Fecha: 201808011443<br>
                        Al consultar por número de anticipo, si este estaba como pago ya en al base de datos, no mostraba
                        los datos del anticipo en el módulo ADMINISTRAR SERVICIOS<br>
                        Solución: 201808011444<br>
                        Se quita la condición vl.prueba_entrega='N' en la consulta realizada en la función retornarDatosAnticipo($numeroAnticipo) de
                        la clase anticipos y se corrige de acuerdo a si es 'P' o 'N' en el módulo ADMINISTRAR SERVICIOS.<br>
                    </p> 
                    <p>
                        Fecha: 201807271231<br>
                        Presentó fallo al momento de generar anticipo. No estaba cargando bien el número de servicio en 
                        el módulo ANTICIPOS<br>
                        Solución: 201807271458<br>
                        Se corrige fallo en el módulo ANTICIPOS permitiendo que se pueda mostrar el número de servicio y
                        al momento de enviarlo a ../trafico/generarpdfanticipo.php la consulta de inserción en la tabla 
                        valoresanticipos pueda ser ejecutada de manera correcta.<br>
                    </p> 
                    <p>
                        Fecha: 201807271231<br>
                        Se detecta que al momento de hacer un anticipo, si la persona presiona varias veces el botón "GENERAR PDF",
                        guarda dicho número de veces en los anticipos como si se hubiera hecho dicho anticipo en esa cantidad.<br>
                        Solución: 201807271609 <br>
                        Se modifican los archivos generarpdfanticipo.php y generarpdfagregarAnticipo.php para que solo lo hagan una vez.
                        En caso de volver a intentar generar el anticipo, muestra mensaje de que ya dicho anticipo fue generado.<br>
                    </p> 
                    <p>
                        Fecha: 201807241637<br>
                        Es necesario que al mostrar los anticipos realizados y los que están por realizar se muestre el número 
                        del servicio asociado al anticipo.<br>
                        Solución: 201807241713<br>
                        Se incluyen los números de servicio en la creación del anticipo.<br>
                    </p>                    
                    <p> Fecha: 201807190926 <br>
                        Se detecta falla en la creación del empleado que crea el servicio. Posible falla en la clase
                        servicios en las funciones crearserviciovarios(){ y crearserviciovariasempresas() pendiente
                        revisar<br>
                        Solución: 201807241636<br>
                        El problema se presentaba cuando se creaba el servicio, no estaba incluyendo en la tabla
                        servicioempleadohora los datos del empleado, el servicio y la hora.<br>
                    </p>                    
                </div>
                <div style="overflow-y:visible; height: 250px;">
                    <h2>Mejoras</h2>
                    <p>
                        Fecha:201906061646<br>
                        En el módulo de prefactura se incluye el origen y destino de cada una de las guías consultadas
                    </p>
                    <p>
                        Recordar primero crear la migración de la tabla cliente.<br>
                        Esto es para la agenda de los comerciales y de cualquier persona en la empresa.<br>                       
                        
                        Fecha: 201906181418<br>
                        Inicia creación de modulos para Comercial.<br>
                        Programas modificados:<br>
                        /trafico/redirigir.php<br>
                        /clases/asesor_empresa.php<br>
                        /clases/empleados.php<br>
                        /trafico/Empleados.php<br>
                        /trafico/migracion.php<br>
                        /modulos/cliente.php<br>
                        /js/js_cliente.js<br>
                        /trafico/Clientes.php
                        
                        Programas creados:<br>
                        /modulos/agendaComercial.php<br>
                        /js/js_agendaComercial.php<br>
                        /clases/asunto.php<br>
                        /clases/asunto_empleado.php<br>
                        /trafico/AsuntoEmpleado.php<br>
                        /trafico/Asunto.php<br>
                        /trafico/Empresacontactos.php<br>
                        /clasa/empresacontactos.php<br>
                        
                        Tablas creadas:<br>
                        asunto,asunto_empleado,empresacontactos<br>                        
                        
                        Tablas borradas:<br>
                        climun
                    </p>
                    <p>
                        Fecha: 201903141631<br>
                        Se incluye la opción de cambiar el valor declarado de las guías en el módulo "ADMINISTRAR SERVICIOS".
                    </p>
                    <p>
                        Fecha: 201903122126<br>
                        Llamada de Don Luis Arnulfo. Informa que había cerrado el seguimiento de un servicio
                        sin haberlo terminado.Pendiente, realizar trabajo para que cuando esto ocurra se pueda 
						continuar sin afectar el servicio.
                    </p>
                    <p>
                        Fecha: 201903121152<br>
                        Se había agregado la opción de cambiar el número de gúia. Estaba fallando.<br>
                        Se soluciona y confirma que esta opción se encuentra habilitada.<br>
                        En días pasados también se añadio la opción de volver a habilitar la cuenta de cobro.
                        Este proceso se estaba haciendo por consulta y era necesario intervención en base de datos.
                    </p>
                    <p>
                        Fecha: 201810251358<br>
                        En el módulo ADMINISTRAR SERVICIOS se incluye en la parte inferior un casilla para realizar comentarios
                        al servicio que se esta visualizando.
                    </p>
                    <p>
                        Fecha: 201810021030<br>
                        En el modulo SERVICIOS POR FACTURAR, se muestra advertencia y se añade color rojo a las guias de meses
                        anteriores que aun no estan facturadas
                    </p>
                    <p>
                        Fecha: 201809251811<br>
                        En el módulo NOTA CRÉDITO, se programan las casillas de guias para que muestre la fecha de creacion
                        y el numero de guia asociado a dicha guia.                       
                    </p>
                    <p>
                        Fecha: 201809201031<br>
                        El módulo de EMPLEADOS está terminado.
                        Se habilita el botón para el acceso.
                        Se esta trabajando en la administración de los roles y la asignación de botones a dichos roles
                    </p>
                    <p>
                        Fecha: 201809122138<br>
                        Se cambia disposición del módulo EMPLEADOS, se busca que tenga una manejo similar al de 
                        conductores.
                        Se deja mostrando los datos de un empleado con una cédula especifica.
                        Aún no se deja activo el vínculo a través del botón del panel general por que no esta terminado.
                    </p>
                    <p>
                        Fecha: 201809121128<br>
                        En el módulo CONSULTAS SERVICIOS se agrega el botón GENERAR EXCEL,su funcionalidad es .... Generar un excel :)                        
                    </p>
                    <p>
                        Fecha: 201809121126<br>
                        Se agrega funcionabilidad al módulo de SERVICIOS POR FACTURAR que permite que al encontrar guías repetidas
                        se pueda picar sobre dicha guía y en otra pestaña del navegador salga el módulo GESTIÓN DE SERVICIOS con 
                        los datos tradicionales de los servicios
                    </p>
                    <p>
                        Fecha: 201809111157<br>
                        Se cambia la fecha en que se realizá el anticipo en la cuenta de cobro por la fecha 
                        en que se hace el servicio. Solicita: Leidy
                    </p>
                    <p>
                        <strong>Fecha: 201808161623<br>
                            <span class="text-danger">Pendiente revisar como ajustar el servicio por horas</span></strong>
                    </p>
                    <p>
                        Fecha: 201808161505<br>
                        Se agrega la opción de enviar a cuenta de cobro desde el modulo ANTICIPOS. Cuando no se tienen anticipos por parte del conductor.
                    </p>
                    <p>
                        Fecha: 201808161303<br>
                        Se mejora la presentación de las guias en la cuenta de cobro. Por ahora mientras sean menos de tres dejan de
                        salir montadas una sobre otra.
                    </p>
                    <p>
                        Fecha: 2018081126<br>
                        En el módulo SERVICIOS POR FACTURAR se hace mas grande el tamaño de la interfaz y se crea un botón al 
                        costado derecho de color rojo cuando se encuentra una guía repetida en algún servicio. Al presionar en
                        dicho botón se agrega un mensaje a los servicios por cancelar que se muestra a la Sra. Alba.
                    </p>
                    <p>
                        Fecha: 201808141757<br>
                        En el módulo ADMINISTRAR SERVICIOS se deja unicamente las opciones de consulta por Número de Servicio,
                        Número de guía y Anticipo. Se mejora la gestión de estas casillas evitando conservar valores digitados
                        y borrando valores cuando se esta trabajando en uno de ellos.
                    </p>
                    <p>
                        Fecha: 2018008011224 <br>
                        En el módulo "ADMINISTRAR SERVICIOS", al consultar por número de servicio, se pone en negrilla los 
                        títulos de los datos.<br>                        
                    </p>
                    <p>
                        Fecha: 201807271638 <br>
                        Se crea la tabla accionesenprograma con los campos boton,fecha,empleado para realizar seguimiento
                        a las "acciones que realizan las personas en la aplicación".<br>                        
                    </p>
                    <p>
                        Fecha: 201807271629 <br>
                        La tabla serviciosporcancelar estaba borrando los datos de los servicios que se querían borrar, 
                        se agrega el campo estado, con dos condiciones: CANCELAR y CANCELADO, esto con el fin de dejar
                        de borrar en esta tabla.<br>                        
                    </p>
                    <p>
                        Fecha: 201807262047 <br>
                        Se agrega el módulo SERVICIOS PARA CANCELAR.Solo se activa cuando hace el ingreso la persona que tiene
                        como rol "GERENCIA".El objetivo es que muestre una lista de servicios a cancelar, el empleado que quiere cancelar el servicio
                        y el motivo por el cual lo cancela. Esta actividad se realiza cuando se pica sobre el numero de servicio en 
                        la cuenta de cobro de un propietario<br>                        
                    </p>
                    <p>
                        Fecha: 201807251718<br>
                        Se muestra en la cuenta de cobro el número de servicio correspondiente a la/las guías usadas.<br> 
                        Se agrega en el módulo ADMINISTRAR SERVICIO en la opción Número servicio el empleado que crea el servicio <br>
                    </p>  
                    <p>
                        Fecha: 201807241816 <br>
                        En el módulo ADMINISTRAR SERVICIOS, en la casilla "Número guía", cuando la guia se encuentra digitada
                        en dos servicios, solo muestra uno.<br>
                        Pendiente hacer que muestre los servicios donde asociados al número de guía.
                        Solucion: 201807251540 <br>
                        Se corrige falla en consulta a base de datos y presentación en módulo.
                    </p>
                    <p>
                        Fecha: 201807241812 <br>
                        Se sugiere para el tema de las "equivocaciones" al momento de realizar el anticipo o crear el 
                        servicio, dos opciones:
                    <ol>
                        <li>Que las personas puedan crear una lista de posibles servicios a cancelar</li>
                        <li>Que se puedan modificar las opciones del servicio cuando éste no ha tenido factura para el cliente</li>
                    </ol>
                    </p>
                    <p>
                        Fecha: 201807181458 <br>
                        Desde el módulo de "LISTAR SERVICIOS" al desplegar la opción "Por fecha inicial a fecha final" muestra la
                        lista de servicios de las fechas ingresadas, se quiere que al picar sobre un botón verde que representa
                        el número de servicio, se lleve al módulo "ADMINISTRAR SERVICIOS" y muestre la información detallada
                        de la misma manera que lo hace cuando se está en "ADMINSITRAR SERVICIOS" y se digita un número de servicio
                        en la casilla "Número servicio";
                    </p>
                </div>                
            </div>                
            <?php
        } else {
            ?>
            <form action="actualizaciones.php" method="post">
                Para ver las modificaciones por favor digite la clave
                <input type="password" name="clave" />
            </form>
            <?php
        }
        ?>
    </body>
</html>
