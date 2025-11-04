<!-- Tabla de Mantenimientos -->
<div class="col-md-12 mb-lg-0 mb-4">
    <div class="card-header pb-0 p-3">
        <div class="row">
            <div class="col-6 d-flex align-items-center">
                <!-- <h6 class="mb-0">Registro de Mantenimientos</h6> -->
            </div>
            <div class="col-6 text-end">
                <button class="btn btn-success btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#modalNew">
                    <i class="bi bi-plus-lg"></i> Nuevo Mantenimiento
                </button>
            </div>
        </div>
    </div>
</div>

<!-- <ul class="navbar-nav  justify-content-end">
    <li class="nav-item d-flex align-items-center">
        <a class="btn btn-success btn-sm mb-0 me-3" target="_blank"
            href="">Nuevo Mantenimiento</a>
    </li>
</ul> -->

<div class="row my-4">
    <!-- Tabla 1 -->
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <strong>Listado de Mantenimientos</strong>
                        <hr class="horizontal dark mt-2">
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <div class="table-container">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xs font-weight-bolder opacity- ps-2">
                                                #</th>
                                            <th
                                                class="text-uppercase text-secondary text-xs font-weight-bolder opacity- ps-2">
                                                Factura</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-">
                                                Placa</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-">
                                                Empresa Prestadora</th>
                                            <!-- <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Estado</th> -->
                                            <th
                                                class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-">
                                                Fecha</th>
                                            <!-- <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Conductor</th> -->
                                            <th
                                                class="text-uppercase text-secondary text-xs font-weight-bolder opacity-">
                                                Valor</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-">
                                                Servicio Realizado</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-">
                                                Observaciones</th>
                                            <th class="text-secondary opacity-"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Ordenar mantenimientos por fecha (más reciente primero)
                                        usort($mantenimientos, function ($a, $b) {
                                            return strtotime($b['fecha']) - strtotime($a['fecha']); // Descendente
                                        });
                                        ?>

                                        <?php if (empty($mantenimientos)): ?>
                                            <tr>
                                                <td colspan="9" class="text-center py-4">No hay registros de mantenimientos.
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($mantenimientos as $index => $mant): ?>
                                                <tr>
                                                    <td class="text-xs font-weight-bold"><?= $index + 1 ?></td>
                                                    <td class="text-sm"><?= htmlspecialchars($mant['factura']) ?></td>
                                                    <td class="text-sm text-center"><?= htmlspecialchars($mant['placa']) ?></td>
                                                    <td class="text-sm text-center"><?= htmlspecialchars($mant['empresa']) ?>
                                                    </td>
                                                    <td class="text-sm text-center">
                                                        <?= date('d/m/Y', strtotime($mant['fecha'])) ?>
                                                    </td>
                                                    <td class="text-sm text-end font-weight-bold">
                                                        $<?= number_format($mant['valor'], 0, ',', '.') ?></td>
                                                    <td class="text-sm text-center"><?= htmlspecialchars($mant['servicio']) ?>
                                                    </td>
                                                    <td class="text-sm text-center">
                                                        <?= htmlspecialchars($mant['observaciones']) ?>
                                                    </td>
                                                    <td class="align-middle">
                                                        <a href="javascript:;"
                                                            class="text-secondary font-weight-bolder text-xs btn-ver-detalle"
                                                            data-id="<?= $mant['id'] ?>" data-bs-toggle="modal"
                                                            data-bs-target="#modalDetail">
                                                            Ver
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-12 mt-2">
        <div class="row">
            <!-- Próximas Revisiones -->
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Próximas Revisiones</strong>
                        <a href="#" class="text-secondary text-xs" data-bs-toggle="modal" data-bs-target="#modalNewRev">
                            <i class="bi bi-pencil"></i> Agregar
                        </a>
                    </div>
                    <hr class="horizontal dark mt-2">
                    <ul class="mt-3 list-unstyled small-muted" id="listaRevisiones">
                        <?php if (empty($ultimosServicios)): ?>
                            <li class="mb-2">No hay revisiones programadas.</li>
                        <?php else: ?>
                            <?php foreach ($ultimosServicios as $serv): ?>

                                <?php
                                $fechaProg = new DateTime($serv['fecha_programada']);
                                $hoy = new DateTime();
                                $hoy->setTime(0, 0, 0);
                                $fechaProg->setTime(0, 0, 0);
                                $diasFaltan = (int) (($fechaProg->getTimestamp() - $hoy->getTimestamp()) / (60 * 60 * 24));

                                if ($diasFaltan <= 5) {
                                    $clase = 'text-red';
                                } elseif ($diasFaltan <= 10) {
                                    $clase = 'text-orange';
                                } else {
                                    $clase = 'text-green';
                                }
                                ?>
                                <li id="rev-<?= $serv['id_mantenimiento'] ?>"
                                    class="revision-item d-flex justify-content-between align-items-center py-1 px-1 border-bottom">
                                    <span>
                                        <i class="bi bi-truck me-2"></i>
                                        <a href="javascript:void(0)" class="text-secondary fw-bold text-sm"
                                            onclick="cargarDatosRevision(<?= (int) $serv['id_mantenimiento'] ?>)">
                                            <?= htmlspecialchars($serv['placa']) ?> → <?= htmlspecialchars($serv['servicio']) ?>
                                        </a>
                                        <small class="<?= $clase ?>">
                                            Km: <?= number_format($serv['kilometraje_programado'], 0, ',', '.') ?> —
                                            el <?= date('d/m/Y', strtotime($serv['fecha_programada'])) ?>
                                        </small>
                                    </span>

                                    <i class="fa-solid fa-trash-can text-secondary cursor-pointer eliminar-revision" style="font-size: 0.8rem; cursor:pointer; vertical-align: middle;"
                                        data-id="<?= $serv['id_mantenimiento'] ?>"></i>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- Resumen Mensual -->
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="card p-3">
                    <strong>Resumen Mensual</strong>
                    <hr class="horizontal dark mt-2">
                    <div class="mt-3">
                        <div class="d-flex justify-content-between">
                            <div>Total de Mantenimientos</div>
                            <div><span class="fw-bold"><?= $totalMantenimientos ?></span></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div>Valor Total Pagado</div>
                            <div><span class="fw-bold">$<?= $valorTotal ?></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- Formulario Rápido -->
<div class="card p-3">
    <strong>Crear Mantenimiento Rápido</strong>
    <hr class="horizontal dark mt-2">
    <form id="quickForm" class="row g-2 mt-2">
        <div class="col-md-4"><input class="form-control" name="placa" placeholder="Placa (ej. UUU123)" required></div>
        <div class="col-md-4">
            <select class="form-select" name="id_prestador" required>
                <option value="">Seleccionar empresa</option>
                <?php foreach ($prestadores as $p): ?>
                    <option value="<?= $p['id_prestador'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <select class="form-select" name="id_tipo_manteni" required>
                <option value="">Seleccionar servicio</option>
                <?php foreach ($tiposMantenimiento as $t): ?>
                    <option value="<?= $t['id_tipo_manteni'] ?>"><?= htmlspecialchars($t['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4"><input class="form-control" name="num_factura" placeholder="Factura (ej. FAC-2025-0487)"
                required></div>
        <div class="col-md-4"><input class="form-control" name="costo" placeholder="Valor ($)" required></div>
        <div class="col-md-4"><input class="form-control" name="fecha" type="date" value="<?= date('Y-m-d') ?>"
                required></div>
        <div class="col-md-4 d-grid"><button class="btn btn-success" type="submit">Crear Registro</button></div>
    </form>
</div>

<!--    ***   Inicio Modales   ***    -->

<!-- Modal: Detalle Mantenimiento-->
<div class="modal fade" id="modalDetail" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle del Mantenimiento</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detailContent"><!-- contenido dinámico --></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btn-edit-maintenance" data-id="">Editar</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal: Nuevo mantenimiento -->
<div class="modal fade" id="modalNew" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Mantenimiento</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNew">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label for="placa" class="form-label">Placa</label>
                            <select class="form-select" name="placa" id="selectPlaca" required>
                                <option value="">Seleccionar placa</option>
                                <?php foreach ($placas as $p): ?>
                                    <option value="<?= htmlspecialchars($p) ?>"><?= htmlspecialchars($p) ?></option>
                                <?php endforeach; ?>
                                <option value="nuevo" class="text-green">➕ Nueva placa</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Empresa</label>
                            <select class="form-select" name="id_prestador" id="selectEmpresa" required>
                                <option value="">Seleccionar empresa</option>
                                <?php foreach ($prestadores as $p): ?>
                                    <option value="<?= $p['id_prestador'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                                <?php endforeach; ?>
                                <option value="nueva" class="text-green">➕ Nueva empresa</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Servicio</label>
                            <select class="form-select" name="id_tipo_manteni" id="selectServicio" required>
                                <option value="">Seleccionar servicio</option>
                                <?php foreach ($tiposMantenimiento as $t): ?>
                                    <option value="<?= $t['id_tipo_manteni'] ?>"><?= htmlspecialchars($t['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="nueva" class="text-green">➕ Nuevo Servicio</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Factura</label>
                            <input class="form-control" name="num_factura" placeholder="FAC-2025-0487" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valor ($)</label>
                            <input class="form-control" name="costo" placeholder="120000" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fecha</label>
                            <input class="form-control" type="date" name="fecha" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kilometraje</label>
                            <input class="form-control" name="kilometraje" placeholder="120000" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observaciones" rows="3"
                                placeholder="Ej: Se realizó en Montallantas, se cambió llanta trasera izquierda"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success" id="saveNew">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Revision-->
<div class="modal fade" id="modalNewRev" tabindex="-1" data-bs-backdrop="static" aria-labelledby="modalNewRev">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newRev">Nueva Revisión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formRev">
                    <div class="mb-3">
                        <label for="placa" class="form-label">Placa</label>
                        <select class="form-select" name="placa" id="selectPlaca" required>
                                <option value="">Seleccionar placa</option>
                                <?php foreach ($placas as $p): ?>
                                    <option value="<?= htmlspecialchars($p) ?>"><?= htmlspecialchars($p) ?></option>
                                <?php endforeach; ?>
                                <option value="nuevo" class="text-green">➕ Nueva placa</option>
                            </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Servicio</label>
                        <select class="form-select" name="id_tipo_manteni" required>
                            <option value="">Seleccionar servicio</option>
                            <?php foreach ($tiposMantenimiento as $t): ?>
                                <option value="<?= $t['id_tipo_manteni'] ?>"><?= htmlspecialchars($t['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha Programada</label>
                        <input type="date" class="form-control" id="fecha" name="fecha_programada"
                            value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="kilometraje_programado" class="form-label">Kilometraje Programado</label>
                        <input type="number" class="form-control" name="kilometraje_programado" placeholder="Ej: 150000"
                            min="0">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="saveNewRev">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Revision -->
<div class="modal fade" id="modalEditRev" tabindex="-1" data-bs-backdrop="static" aria-labelledby="modalEditRev">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Revisión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formEditRev">
                    <!-- Campo oculto para el ID -->
                    <input type="hidden" id="id_mantenimiento" name="id_mantenimiento">

                    <div class="mb-3">
                        <label for="placa_edit" class="form-label">Placa</label>
                        <input id="placa_edit" class="form-control" name="placa" placeholder="Placa" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Servicio</label>
                        <select class="form-select" name="id_tipo_manteni" required>
                            <option value="">Seleccionar servicio</option>
                            <?php foreach ($tiposMantenimiento as $t): ?>
                                <option value="<?= $t['id_tipo_manteni'] ?>"><?= htmlspecialchars($t['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_edit" class="form-label">Fecha Programada</label>
                        <input type="date" class="form-control" id="fecha_edit" name="fecha_programada" required>
                    </div>

                    <div class="mb-3">
                        <label for="kilometraje_edit" class="form-label">Kilometraje Programado</label>
                        <input type="number" class="form-control" id="kilometraje_edit" name="kilometraje_programado"
                            placeholder="Ej: 150000" min="0" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success" id="saveEditRev">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Submodal: Nueva Empresa -->
<div class="modal fade" id="modalNuevaEmpresa" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Empresa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaEmpresa">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre *</label>
                            <input type="text" class="form-control" name="nombre_empresa" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIT *</label>
                            <input type="text" class="form-control" name="nit" placeholder="Ej: 987654321" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" name="direccion" placeholder="Calle 123" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contacto (teléfono)</label>
                            <input type="text" class="form-control" name="contacto" placeholder="Ej: 5555555" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="ejemplo@gmail.com"
                                required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnGuardarEmpresa">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!-- Submodal: Nuevo Servicio -->
<div class="modal fade" id="modalNuevoServicio" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoServicio">
                    <div class="row g-3">
                        <div class="mb-3">
                            <label class="form-label">Nombre *</label>
                            <input type="text" class="form-control" name="nombre_servicio"
                                placeholder="Ej: Cambio de llantas"></textarea required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion_servicio" rows="2"
                                placeholder="Detalles del servicio"></textarea required >
                    </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnGuardarServicio">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Submodal: Nueva Placa -->
<div class="modal fade" id="modalNuevaPlaca" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Nuevo Vehículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <!-- Placa -->
                    <div class="col-md-4">
                        <label class="form-label">Placa *</label>
                        <input type="text" class="form-control" name="placa" required>
                    </div>

                    <!-- Marca -->
                    <div class="col-md-4">
                        <label class="form-label">Marca *</label>
                        <select class="form-select" name="id_marca" required>
                            <option value="">Seleccionar...</option>
                            <?php foreach ($marcas as $m): ?>
                                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['marca']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tipo Vehiculo -->
                    <div class="col-md-4">
                        <label class="form-label">Tipo Vehiculo *</label>
                        <select class="form-select" name="tipovehiculo" required>
                            <option value="">Seleccionar...</option>
                            <?php foreach ($tiposVehiculo as $t): ?>
                                        <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tipo de carrocería -->
                    <div class="col-md-4">
                        <label class="form-label">Tipo Carrocería *</label>
                        <input type="text" class="form-control" name="tipocarroceria" placeholder="Ej: Furgon/Estacas" required>
                    </div>

                    <!-- Modelo (año) -->
                    <div class="col-md-4">
                        <label class="form-label">Modelo (año) *</label>
                        <input type="number" class="form-control" name="modelo" min="1900" max="2030" required>
                    </div>

                    <!-- Capacidad -->
                    <div class="col-md-4">
                        <label class="form-label">Capacidad (kg) *</label>
                        <input type="number" class="form-control" name="capacidadcarga" placeholder="Ej: 2500" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnGuardarPlaca">Guardar Vehículo</button>
            </div>
        </div>
    </div>
</div>