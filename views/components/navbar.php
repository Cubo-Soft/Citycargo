<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-sm px-0 mx-4 mt-3 shadow border-radius-xl bg-corporativo position-sticky top-0 z-index-sticky"
    id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3">

        <?php
        // Determinar el nombre del módulo actual
        $moduloActual = "Dashboard";
        $scriptActual = basename($_SERVER['PHP_SELF']);

        // Mapeo de archivos a nombres de módulo
        $mapaModulos = [
            'mantenimiento.php' => 'Mantenimiento e Inventario',
            'inventario.php'    => 'Inventario',
            'clientes.php'      => 'Clientes',
            // Añade más según necesites
        ];

        if (isset($mapaModulos[$scriptActual])) {
            $moduloActual = $mapaModulos[$scriptActual];
        }
        ?>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm">
                    <a class="opacity-6 text-white" href="/Citycargo/modulos/index.php">Inicio</a>
                </li>
                <li class="breadcrumb-item text-sm text-white active" aria-current="page">
                    <?= htmlspecialchars($moduloActual) ?>
                </li>
            </ol>
            <br>
            <h6 class="font-weight-bolder mb-0 text-white">
                Estás en el Módulo de <span class="text-white"><?= htmlspecialchars($moduloActual) ?></span>
            </h6>
        </nav>

        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <div class="d-flex align-items-center">
                    <?php if (!empty($_SESSION['foto_perfil'])): ?>
                        <img src="<?= htmlspecialchars($_SESSION['foto_perfil']) ?>" class="avatar avatar-sm me-3" alt="Usuario">
                    <?php else: ?>
                        <img src="../assets/img/avatar1.jpg" class="avatar avatar-sm me-3" alt="Usuario">
                    <?php endif; ?>
                    <span class="text-white">
                        <?= htmlspecialchars($_SESSION['nombre_usuario'] ?? 'Usuario') ?>
                    </span>
                    <i class="feather icon-chevron-down text-white ms-1"></i>
                </div>
            </div>

            <ul class="navbar-nav justify-content-end">
                <li class="nav-item d-flex align-items-center">
                    <a href="/Citycargo/trafico/salir.php" class="nav-link text-body font-weight-bold px-0">
                        <i class="fa fa-sign-out me-sm-1"></i>
                        <span class="d-sm-inline d-none text-white">Salir</span>
                    </a>
                </li>

                <!-- Toggle sidebar en móvil -->
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->