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
            href="">Nueva Orden</a>
    </li>
</ul> -->

<div class="row my-4">
    <!-- Tabla 1 -->
    <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <strong>Listado de Mantenimientos</strong>
                        <hr class="horizontal dark mt-2">
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">
                                            #</th>
                                        <th
                                            class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">
                                            Factura</th>
                                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Placa</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">
                                            Empresa Prestadora</th>
                                        <!-- <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Estado</th> -->
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Fecha</th>
                                        <!-- <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Conductor</th> -->
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Valor</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Servicio Realizado</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                                <td class="text-sm text-center"><?= htmlspecialchars($mant['empresa']) ?></td>
                                                <td class="text-sm text-center"><?= date('d/m/Y', strtotime($mant['fecha'])) ?>
                                                </td>
                                                <!-- <td class="text-sm"><?= htmlspecialchars($mant['conductor']) ?></td> -->
                                                <td class="text-sm text-end font-weight-bold">
                                                    $<?= number_format($mant['valor'], 0, ',', '.') ?></td>
                                                <td class="text-sm text-center"><?= htmlspecialchars($mant['servicio']) ?></td>
                                                <td class="text-sm text-center"><?= htmlspecialchars($mant['observaciones']) ?></td>
                                                <td class="align-middle">
                                                    <a href="javascript:;"
                                                        class="text-secondary font-weight-bold text-xs btn-ver-detalle"
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

    <!-- 2a Tabla: Resumen -->
    <div class="col-lg-4">
        <div class="card p-3 mb-3">
            <strong>Resumen General</strong>
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

        <div class="card p-3">
            <strong>Próximas Revisiones</strong>
            <hr class="horizontal dark mt-2">
            <ul class="mt-3 list-unstyled small-muted">
                <?php if (empty($ultimosServicios)): ?>
                    <li class="mb-2">No hay revisiones programadas.</li>
                <?php else: ?>
                    <?php foreach ($ultimosServicios as $serv): ?>
                        <li class="mb-2">
                            <i class="bi bi-truck"></i>
                            <?= htmlspecialchars($serv['placa']) ?> →
                            <?= htmlspecialchars($serv['servicio']) ?>
                            <span class="text-muted">el <?= date('d/m/Y', strtotime($serv['fecha_programada'])) ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<!-- Formulario Rápido -->
<div class="card p-3">
    <strong>Crear Mantenimiento Rápido</strong>
    <hr class="horizontal dark mt-2">
    <form id="quickForm" class="row g-2 mt-2">
        <div class="col-md-4"><input class="form-control" name="placa" placeholder="Placa (ej. UUU-123)" required></div>
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


<!-- Modal: Detail -->
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
                <button class="btn btn-success" data-bs-dismiss="modal">Cerrar</button>
                <!-- <button class="btn btn-success" id="">Ver Inventario</button> -->
            </div>
        </div>
    </div>
</div>


<!-- Modal: New -->
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
                            <label class="form-label">Placa</label>
                            <input class="form-control" name="placa" placeholder="Placa (ej. UUU-123)" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Empresa</label>
                            <select class="form-select" name="id_prestador" required>
                                <option value="">Seleccionar empresa</option>
                                <?php foreach ($prestadores as $p): ?>
                                    <option value="<?= $p['id_prestador'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Servicio</label>
                            <select class="form-select" name="id_tipo_manteni" required>
                                <option value="">Seleccionar servicio</option>
                                <?php foreach ($tiposMantenimiento as $t): ?>
                                    <option value="<?= $t['id_tipo_manteni'] ?>"><?= htmlspecialchars($t['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
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
                            <input class="form-control" name="kilometraje" placeholder="120000">
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