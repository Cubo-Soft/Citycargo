<!-- Tabla de Mantenimientos -->
<div class="col-md-12 mb-lg-0 mb-4">
    <div class="card-header pb-0 p-3">
        <div class="row">
            <div class="col-6 d-flex align-items-center">
            </div>
            <div class="col-6 text-end">
                <button class="btn btn-success btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#modalNew">
                    <i class="bi bi-plus-lg"></i> Nuevo Inventario
                </button>
            </div>
        </div>
    </div>
</div>

<!-- <ul class="navbar-nav  justify-content-end">
    <li class="nav-item d-flex align-items-center">
        <a class="btn btn-success btn-sm mb-0 me-3" target="_blank"
            href="">Nuevo Inventario</a>
    </li>
</ul> -->

<div class="row my-4">
    <!-- Tabla 1 -->
    <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <strong>Listado de Inventario</strong>
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
