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
                                <label class="form-label">Placa</label>
                                <select class="form-select" name="placa" required>
                                    <option value="">Seleccionar placa</option>
                                    <?php foreach ($placas as $placa): ?>
                                        <option value="<?= htmlspecialchars($placa) ?>"><?= htmlspecialchars($placa) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Nombre Propietario</label>
                                <input type="text" class="form-control" name="Nombre"
                                    placeholder="Nombre (ej. Jhon Doe)" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Identificación</label>
                                <input type="number" class="form-control" name="Cédula"
                                    placeholder="Cédula (ej. 83765422)" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tipo Vehiculo</label>
                                <input type="text" class="form-control" name="tipo_vehiculo"
                                    placeholder="tipo vehiculo (ej. Estacas,Furgon)" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Marca</label>
                                <input type="text" class="form-control" name="marca" placeholder="marca (ej. Toyota)"
                                    required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tipo Combustible</label>
                                <input type="text" class="form-control" name="tipo_combustible"
                                    placeholder="tipo combustible (ej. Diesel)" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha</label>
                                <input type="date" class="form-control" name="fecha" value="<?= date('Y-m-d') ?>"
                                    required>
                            </div>
                        </div>

                        <!-- Acordion de secciones -->
                        <!-- Acordion de secciones -->
                        <div class="accordion" id="accordionInventario">
                            <?php
                            $secciones = [
                                'cabina_interna' => ['título' => '🚗 Cabina Interna', 'items' => $elementos['cabina_interna']],
                                'cabina_externa' => ['título' => '🚙 Cabina Externa', 'items' => $elementos['cabina_externa']],
                                'furgon' => ['título' => '📦 Furgón', 'items' => $elementos['furgon']],
                                'kit_carretera' => ['título' => '🧰 Kit de Carretera', 'items' => $elementos['kit_carretera']],
                                'otros' => ['título' => '🔧 Otros Elementos', 'items' => $elementos['otros']],
                            ];

                            foreach ($secciones as $clave => $datos):
                                ?>
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="heading_<?= $clave ?>">
                                        <button class="accordion-button collapsed bg-gray-100" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse_<?= $clave ?>"
                                            aria-expanded="false" aria-controls="collapse_<?= $clave ?>">
                                            <?= $datos['título'] ?>
                                        </button>
                                    </h2>
                                    <div id="collapse_<?= $clave ?>" class="accordion-collapse collapse"
                                        aria-labelledby="heading_<?= $clave ?>" data-bs-parent="#accordionInventario">
                                        
                                        <div class="accordion-body pt-3">
                                            <div class="row gy-3">
                                                <?php foreach ($datos['items'] as $item): ?>
                                                    <div class="col-12">
                                                        <div class="row align-items-center">
                                                            <div class="col-md-3 fw-bold">
                                                                <?= htmlspecialchars($item) ?>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <select
                                                                    name="detalle[<?= $clave ?>][<?= htmlspecialchars($item) ?>][estado]"
                                                                    class="form-select form-select-sm">
                                                                    <option value="bueno">Bueno</option>
                                                                    <option value="regular">Regular</option>
                                                                    <option value="mal">Mal</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <input type="text"
                                                                    name="detalle[<?= $clave ?>][<?= htmlspecialchars($item) ?>][cantidad]"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="Ej: 1, N/A">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="text"
                                                                    name="detalle[<?= $clave ?>][<?= htmlspecialchars($item) ?>][observacion]"
                                                                    class="form-control form-control-sm"
                                                                    placeholder="Observación">
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
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
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-save"></i> Guardar Inventario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>