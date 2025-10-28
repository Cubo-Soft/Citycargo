<div class="row my-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <strong>Registro de Inventario Vehículo</strong>
                <hr class="horizontal dark mt-2">
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="p-4">
                    <form id="formInventario" method="POST" action="">

                        <!-- Datos del vehículo -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label for="placa" class="form-label">Placa</label>
                                <select class="form-select" name="placa" id="selectPlaca" required>
                                    <option value="">Seleccionar placa</option>
                                    <?php foreach ($placas as $p): ?>
                                        <option value="<?= htmlspecialchars($p) ?>"><?= htmlspecialchars($p) ?></option>
                                    <?php endforeach; ?>
                                    <option value="nuevo" class="text-green">➕ Nueva placa</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Nombre Propietario</label>
                                <input type="text" class="form-control" name="nombre_propietario" id="nombre_propietario"
                                    placeholder="Nombre (ej. Jhon Doe)" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Identificación</label>
                                <input type="number" class="form-control" name="identificacion" id="identificacion"
                                    placeholder="Cédula (ej. 83765422)" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tipo Vehiculo</label>
                                <input type="text" class="form-control" name="tipo_vehiculo" id="tipo_vehiculo"
                                    placeholder="tipo vehiculo (ej. Estacas,Furgon)" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Marca</label>
                                <input type="text" class="form-control" name="marca" placeholder="marca (ej. Toyota)"
                                    id="marca" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tipo Carroceria</label>
                                <input type="text" class="form-control" name="tipo_carroceria" id="tipo_carroceria"
                                    placeholder="tipo combustible (ej. Diesel)" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha</label>
                                <input type="date" class="form-control" name="fecha" value="<?= date('Y-m-d') ?>"
                                    required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kilometraje</label>
                                <input type="number" class="form-control" name="kilometraje"
                                    placeholder="marca (ej. 123000)" required>
                            </div>
                        </div>

                        <!-- Acordion de secciones -->
                        <div class="accordion" id="accordionInventario">
                            <?php
                            // Mapeo: nombre de la BD → ícono + clave para el formulario
                            $mapeo = [
                                'Cabina Interna' => ['icono' => '🚗', 'clave_form' => 'cabina_interna'],
                                'Cabina Externa' => ['icono' => '🚙', 'clave_form' => 'cabina_externa'],
                                'Furgón' => ['icono' => '📦', 'clave_form' => 'furgon'],
                                'Kit de Carretera' => ['icono' => '🧰', 'clave_form' => 'kit_carretera'],
                                'Botiquín Médico' => ['icono' => '🩹', 'clave_form' => 'botiquin'],
                                'Otros Elementos' => ['icono' => '🔧', 'clave_form' => 'otros'],
                                
                            ];

                            foreach ($mapeo as $nombreSeccionBD => $config):
                                // Obtener elementos de esta sección (o array vacío si no existe)
                                $items = $elementos[$nombreSeccionBD] ?? [];
                                ?>
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header"
                                        id="heading_<?= htmlspecialchars($config['clave_form']) ?>">
                                        <button class="accordion-button collapsed bg-gray-100" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapse_<?= htmlspecialchars($config['clave_form']) ?>"
                                            aria-expanded="false">
                                            <?= $config['icono'] ?>     <?= htmlspecialchars($nombreSeccionBD) ?>
                                        </button>
                                    </h2>
                                    <div id="collapse_<?= htmlspecialchars($config['clave_form']) ?>"
                                        class="accordion-collapse collapse" data-bs-parent="#accordionInventario">
                                        <div class="accordion-body pt-3">
                                            <div class="row gy-3">
                                                <?php if (empty($items)): ?>
                                                    <div class="col-12 text-center text-muted">No hay elementos definidos</div>
                                                <?php else: ?>
                                                    <?php foreach ($items as $item): ?>
                                                        <div class="col-12">
                                                            <div class="row align-items-center">
                                                                <div class="col-md-3 fw-bold text-sm"><?= htmlspecialchars($item) ?></div>
                                                                <div class="col-md-3">
                                                                    <select
                                                                        name="detalle[<?= htmlspecialchars($config['clave_form']) ?>][<?= htmlspecialchars($item) ?>][estado]"
                                                                        class="form-select form-select-sm" autocomplete="off">
                                                                        <option value="">-- Seleccione --</option>
                                                                        <option value="bueno">Bueno</option>
                                                                        <option value="regular">Regular</option>
                                                                        <option value="mal">Mal</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <input type="text"
                                                                        name="detalle[<?= htmlspecialchars($config['clave_form']) ?>][<?= htmlspecialchars($item) ?>][cantidad]"
                                                                        class="form-control form-control-sm"
                                                                        placeholder="Ej: 1, N/A">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="text"
                                                                        name="detalle[<?= htmlspecialchars($config['clave_form']) ?>][<?= htmlspecialchars($item) ?>][observacion]"
                                                                        class="form-control form-control-sm"
                                                                        placeholder="Observación">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Observaciones generales -->
                        <div class="mt-4">
                            <label class="form-label">Observaciones Generales</label>
                            <textarea class="form-control" name="observaciones_generales" rows="3"></textarea>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success btn-sm" id="btnGuardarInventario">
                                <i class="bi bi-save"></i> Guardar Inventario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Sección 2: Tabla de inventarios registrados -->
<div class="row my-4">
    <!-- Tabla 1 -->
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <strong>Inventarios Registrados</strong>
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
                                                class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-">
                                                Placa</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-">
                                                Propietario</th>
                                            <!-- <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Estado</th> -->
                                            <th
                                                class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-">
                                                Fecha</th>
                                            <!-- <th
                                            class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">
                                            Conductor</th> -->
                                            <th
                                                class="text-uppercase text-secondary text-xs font-weight-bolder opacity-">
                                                Cédula</th>
                                            <th
                                                class="text-uppercase text-secondary text-xs font-weight-bolder opacity-">
                                                Tipo Vehiculo</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-">
                                                Marca</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-">
                                                Tipo Carrocería</th>
                                            <th class="text-secondary opacity-"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Ordenar inventarios por fecha (más reciente primero)
                                        usort($inventarios, function ($a, $b) {
                                            return strtotime($b['fecha']) - strtotime($a['fecha']); // Descendente
                                        });
                                        ?>

                                        <?php if (empty($inventarios)): ?>
                                            <tr>
                                                <td colspan="9" class="text-center py-4">No hay registro de Inventarios.
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($inventarios as $index => $inv): ?>
                                                <tr>
                                                    <td class="text-xs font-weight-bold"><?= $index + 1 ?></td>
                                                    <td class="text-sm text-center"><?= htmlspecialchars($inv['placa']) ?></td>
                                                    <td class="text-sm text-center"><?= htmlspecialchars($inv['nombre']) ?></td>
                                                    <td class="text-sm text-center">
                                                        <?= date('d/m/Y', strtotime($inv['fecha'])) ?>
                                                    </td>
                                                    <td class="text-sm text-center">
                                                        <?= htmlspecialchars($inv['identificacion']) ?>
                                                    </td>
                                                    <td class="text-sm text-center">
                                                        <?= htmlspecialchars($inv['tipo_vehiculo']) ?>
                                                    </td>
                                                    <td class="text-sm text-center"><?= htmlspecialchars($inv['marca']) ?></td>
                                                    <td class="text-sm text-center">
                                                        <?= htmlspecialchars($inv['tipo_carroceria']) ?>
                                                    </td>
                                                    <td class="align-middle">
                                                        <a href="javascript:;"
                                                            class="text-secondary font-weight-bolder text-xs btn-ver-detalle"
                                                            data-id="<?= $inv['id'] ?>" data-bs-toggle="modal"
                                                            data-bs-target="#modalDetalleInventario">
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
</div>

<!--    ***   inicio modales   ***  -->

<!-- Modal ver inventario -->
<div class="modal fade" id="modalDetalleInventario" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de Inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Aquí se cargará el contenido vía AJAX -->
                <div id="contenidoInventario"></div>
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
                        <input type="text" class="form-control" name="tipocarroceria" placeholder="Ej: Furgon/Estacas"
                            required>
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