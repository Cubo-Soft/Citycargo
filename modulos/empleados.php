<?php
session_start();

include_once '../clases/empleados.php';
include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {
    $empleado = new empleados();
    $departamentos = $empleado->retornarDepartamentos();
    $roles = $empleado->retornarRoles();
    $empleados = $empleado->retornarEmpleados();

    $mostrarDep = "<select class='form-control' id='departamentos' >"
            . "<option value='0'>...</option>";
    for ($index = 0; $index < count($departamentos); $index++) {
        $mostrarDep .= "<option value='" . $departamentos[$index]["dep_id"] . "'>" . $departamentos[$index]["dep_nombre"] . "</option>";
    }
    $mostrarDep .= "</select>";

    $mostrarRol = "<select class='form-control' id='roles' >"
            . "<option value='0'>...</option>";
    for ($index = 0; $index < count($roles); $index++) {
        $mostrarRol .= "<option value='" . $roles[$index]["rol_id"] . "'>" . $roles[$index]["rol_nombre"] . "</option>";
    }
    $mostrarRol .= "</select>";
    ?>
    <!DOCTYPE html>    
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Empleados</title>
            <link rel="icon" href="../imagenes/camion256.png">            
            
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        
            
            <script src="../js/js_empleados.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>            
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>
            <!-- Evitar cache -->
            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">
        </head>
        <body>            
            <div id="contenedor-index" class="row">                       
                <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                <div id="texoDocumento" class="col-lg-4">
                    <h1>Empleados</h1>
                </div>                                        
                <div id="divDatosIniciales" class="col-lg-4">   
                    <ul class="list-group">
                        <li class="list-group-item">
                            <span class="badge"><?php echo $_SESSION["nombre_usuario"]; ?></span>
                            Usuario
                        </li>
                        <li class="list-group-item">
                            <span class="badge"><?php echo $_SESSION["departamento"]; ?></span>
                            Departamento
                        </li>                                        
                        <li class="list-group-item">
                            <span class="badge"><?php echo date('Y-m-d'); ?></span>
                            Fecha
                        </li>
                    </ul>
                </div>
            </div>
            <div id="separador2">
            </div>				
            <div id="divEmpleados"  class="col-lg-12"> 
                <div class="col-lg-12">
                    <table class="table">
                        <tr>
                            <td width="40%"><strong>Mostrar lista de empleados activos</strong></td>
                            <td width="60%"><input type="checkbox" id="listaEmpleados" placeholder="Mostrar lista de empleados" ></td>                        
                        </tr>
                    </table>
                </div>
                <div class="col-lg-12" id="divFormularioEmpleado"> 
                    <table class="table">                        
                        <tr>
                            <td>
                                <strong>C&eacute;dula</strong></strong>
                            </td>
                            <td>
                                <strong>Nombres</strong>
                            </td>
                            <td>
                                <div id="msjcedulaEmpleado"></div>
                                <div id="msjNombresEmpleado"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input class="form-control" id="cedulaEmpleado" name="cedulaEmpleado" type="number" value="">
                                <input type="hidden" name="emp_id" id="emp_id" value="" />                                    
                            </td>
                            <td>
                                <input class="form-control" id="nombresEmpleado" name="nombresEmpleado" type="text" onkeyup="cambiaTamanio(this);">                            
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Apellidos</strong>
                                </td>
                            <td>
                                <strong>Direcci&oacute;n</strong>
                            </td>
                            <td>
                                <div id="msjApellidosEmpleado"></div>
                                <div id="msjDireccionEmpleado"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input class="form-control" id="apellidosEmpleado" name="apellidosEmpleado" type="text" onkeyup="cambiaTamanio(this);">
                            </td>
                            <td>
                                <input class="form-control" id="direccionEmpleado" name="direccionEmpleado" type="text" onkeyup="cambiaTamanio(this);">
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><strong>Tel&eacute;fono</strong></td>
                            <td>
                                <strong>Correo</strong>
                            </td>
                            <td>
                                <div id="msjTelefonoEmpleado"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input class="form-control" id="telefonoEmpleado" name="telefonoEmpleado"  type="number">
                            </td>
                            <td>
                                <input class="form-control" id="correoEmpleado" name="correoEmpleado" type="email">
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><strong>Fecha de ingreso</strong></td>
                            <td>
                                <strong>Departamento</strong>
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input class="form-control" id="fechaIngreso" name="fechaIngreso"  type="date">
                            </td>
                            <td>
                                <?= $mostrarDep ?>
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td><strong>Clave</strong></td>
                            <td>
                                <strong>Usuario</strong>
                                
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input class="form-control" id="claveEmpleado" name="claveEmpleado"  type="text">
                            </td>
                            <td>
                                <input class="form-control" id="usuarioEmpleado" name="usuarioEmpleado"  type="text">
                                
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td><strong>Rol</strong></td>
                            <td>
                                <strong>Estado</strong>
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                    <?= $mostrarRol ?>
                            </td>
                            <td>
                                <select id="estadoEmpleado" name="estadoEmpleado" class="form-control">
                                    <option value="0">...</option>
                                    <option value="A">ACTIVO</option>
                                    <option value="I">INACTIVO</option>
                                </select>
                            </td>
                            <td>

                            </td>
                        </tr>
                    </table> 
                </div>
            </div>
            <div id="mensajes" class="col-lg-12" >

            </div>
            <div class="col-lg-12" id="divListaEmpleados" >
                <table class="table table-hover" >
                    <thead>
                        <tr><th>Nombre</th><th>Apellido</th><th>Cédula</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($index1 = 0; $index1 < count($empleados); $index1++) {
                            echo '<tr><td>' . $empleados[$index1]["emp_nombres"] . '</td><td>' . $empleados[$index1]["emp_apellidos"] . '</td><td>' . $empleados[$index1]["emp_cedula"] . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>                
            </div>
            <div id="botones" class="col-lg-12">                                                
                <button type="button" value="REGRESAR" id="botonRegresar" name="botonRegresar" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
                <button type="button" class="btn btn-success btn-ls botonPropio" value="CREAR EMPLEADO" name="botonCrearEmpleado" id="botonCrearEmpleado" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" title="Crea un empleado" > CREAR <img src="../imagenes/save_16.png"></button>
                <button type="button" class="btn btn-success btn-ls botonPropio" value="MODIFICAR EMPLEADO" name="botonModificarEmpleado" id="botonModificarEmpleado" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" title="Modifica datos de un empleado" > MODIFICAR <img src="../imagenes/user_16.png"></button>
                <button type="button" class="btn btn-success btn-ls botonPropio" value="LIMPIAR" name="botonLimpiar" id="botonLimpiar" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > LIMPIAR <img src="../imagenes/clipboard_16.png"></button>
                <button type="button" value="SALIR" id="botonSalir" name="botonSalir" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>
            </div>

        </body>
    </html>
    <?php
}
?>
