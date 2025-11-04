<?php

class inventoryModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli("localhost", "root", "", "citivillas");
        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8");
    }

    //✅ obtener elementos del inventario
    public function obtenerElementosInventario()
    {
        $sql = "
        SELECT 
            ti.id_tip_inve,
            ti.des_tipo_inve AS seccion,
            te.des_elemento AS elemento
        FROM tipos_inve ti
        LEFT JOIN tipos_elementos te ON ti.id_tip_inve = te.id_tipo_inve
        ORDER BY ti.id_tip_inve, te.des_elemento
    ";

        $result = $this->conn->query($sql);
        $elementos = [];

        while ($row = $result->fetch_assoc()) {
            $seccion = $row['seccion'];
            $elemento = $row['elemento'];

            // Si la sección no existe, créala
            if (!isset($elementos[$seccion])) {
                $elementos[$seccion] = [];
            }

            // Si hay un elemento (puede ser NULL si no hay ítems)
            if ($elemento) {
                $elementos[$seccion][] = $elemento;
            }
        }

        return $elementos;
    }

    //✅ obtener los inventarios registrados
    public function obtenerInventariosRegistrados()
    {
        $sql = "
        SELECT 
            ie.id,
            ie.placa,
            ie.nombre_propietario AS nombre,
            ie.identificacion,
            ie.tipo_vehiculo,
            ie.marca,
            ie.tipo_carroceria,
            ie.fecha_inven AS fecha
        FROM inve_encabezado ie
        ORDER BY ie.fecha_inven DESC
    ";

        $result = $this->conn->query($sql);
        $inventarios = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $inventarios[] = $row;
            }
        }

        return $inventarios;
    }

    //✅ Verificar si la placa existe
    public function existePlaca($placa)
    {
        $stmt = $this->conn->prepare("SELECT 1 FROM vehiculo WHERE placa = ?");
        $stmt->bind_param("s", $placa);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    //✅ Obtener marcas usando tabla ya usada
    public function obtenerMarcas()
    {
        $sql = "SELECT id, marca FROM marcasvehiculos WHERE estado = 1 ORDER BY marca";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    //✅ Obtener líneas (relacionadas con marcasvehiculos)
    public function obtenerLineas()
    {
        $sql = "SELECT id_linea, id_marca, des_linea FROM lineas_vehi ORDER BY des_linea";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    //✅ Obtener colores
    public function obtenerColores()
    {
        $sql = "SELECT id_color, des_color FROM colores ORDER BY des_color";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    //✅ Obtener tipos de servicio
    public function obtenerTiposServicio()
    {
        $sql = "SELECT id_tipo_serv, des_tip_servicio FROM tipos_servicio ORDER BY des_tip_servicio";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    //✅ Obtener clases de vehículo
    public function obtenerClasesVehi()
    {
        $sql = "SELECT id_clase, des_clase FROM clases_vehi ORDER BY des_clase";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    //✅ Obtener carrocerías
    public function obtenerCarrocerias()
    {
        $sql = "SELECT id_tip_carroce, des_carroce FROM tipo_carrocerias ORDER BY des_carroce";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    //✅ Obtener tipos de combustible
    public function obtenerCombustibles()
    {
        $sql = "SELECT id_tip_combus, des_combus FROM tipos_combust ORDER BY des_combus";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // ✅ OBTENER PLACAS DESDE VEHICULOS
    public function obtenerPlacas()
    {
        $sql = "SELECT placa FROM vehiculo WHERE estado = 'ACTIVO' ORDER BY placa";
        $result = $this->conn->query($sql);
        $placas = [];
        while ($row = $result->fetch_assoc()) {
            $placas[] = $row['placa'];
        }
        return $placas;
    }

    // ✅ Obtener tipos de vehículo activos
    public function obtenerTiposVehiculo()
    {
        $sql = "SELECT id, nombre FROM tipovehiculo WHERE id_estados_tablas = 1 ORDER BY nombre";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // ✅ OBTENER DATOS DE VEHICULOS
    public function obtenerDatosVehiculo($placa)
    {
        $sql = "
        SELECT 
            v.placa,
            v.marca AS id_marca,
            m.marca AS nombre_marca,
            v.tipovehiculo AS tipo_vehiculo,
            v.tipocarroceria AS carroceria,
            v.capacidadcarga AS capacidad
        FROM vehiculo v
        LEFT JOIN marcasvehiculos m ON v.marca = m.id
        WHERE v.placa = ? AND v.estado = 'ACTIVO'
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $placa);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ✅ Obtener datos COMPLETOS del vehículo incluyendo propietario
    public function obtenerDatosVehiculoCompleto($placa)
    {
        $sql = "
        SELECT 
            cv.identificacion AS Cedula,
            CONCAT(c.cond_nombres, ' ', c.cond_apellidos) AS Nombre,
            m.marca AS marca,
            v.tipovehiculo AS tipo_vehiculo,
            v.tipocarroceria AS tipo_carroceria
        FROM vehiculo v
        INNER JOIN conductor_vehiculo cv ON v.placa = cv.placa
        INNER JOIN conductores c ON cv.identificacion = c.cond_identificacion
        INNER JOIN marcasvehiculos m ON v.marca = m.id
        WHERE v.placa = ? 
        AND v.estado = 'ACTIVO'
        AND c.perfil = 9
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $placa);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    //✅ Crear nuevo vehículo
    public function crearVehiculo($data)
    {
        // ✅ Extraer TODOS los campos con valores por defecto
        $placa = $data['placa'] ?? '';
        $id_marca = (int) ($data['id_marca'] ?? 0);
        $modelo = (int) ($data['modelo'] ?? 0);
        $tipocarroceria = $data['tipocarroceria'] ?? '';
        $capacidadcarga = (int) ($data['capacidadcarga'] ?? 0);
        $ancho = 0.00;
        $largo = 0.00;
        $alto = 0.00;
        $tipovehiculo = $data['tipovehiculo'] ?? '';
        $estado = 'ACTIVO';
        $reportar_novedad = 0;

        // ✅ Validar obligatorios
        if (
            empty($placa) || !$id_marca || empty($tipovehiculo) ||
            empty($tipocarroceria) || !$modelo || !$capacidadcarga
        ) {
            return false;
        }

        if ($this->existePlaca($placa)) {
            return false;
        }

        $sql = "INSERT INTO vehiculo (
        placa, marca, modelo, tipocarroceria, capacidadcarga,
        ancho, largo, alto, tipovehiculo, estado, reportar_novedad
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "siisidddssi",
            $placa,
            $id_marca,
            $modelo,
            $tipocarroceria,
            $capacidadcarga,
            $ancho,
            $largo,
            $alto,
            $tipovehiculo,
            $estado,
            $reportar_novedad
        );

        return $stmt->execute();
    }

    // ✅ Guardar Inventario
    public function guardarInventarioCompleto($encabezado, $detalle, $idUsuario)
    {
        $this->conn->autocommit(FALSE);
        try {
            // 1. Insertar encabezado
            $sqlEnc = "INSERT INTO inve_encabezado (
            placa, nombre_propietario, identificacion, tipo_vehiculo,
            marca, tipo_carroceria, kilometraje, fecha_inven, observaciones_generales, id_grabador
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($sqlEnc);

            $stmt->bind_param(
                "sssssssssi",
                $encabezado['placa'],
                $encabezado['nombre_propietario'],
                $encabezado['identificacion'],
                $encabezado['tipo_vehiculo'],
                $encabezado['marca'],
                $encabezado['tipo_carroceria'],
                $encabezado['kilometraje'],
                $encabezado['fecha'],
                $encabezado['observaciones_generales'],
                $idUsuario
            );
            $stmt->execute();
            $idEncabezado = $this->conn->insert_id;

            // 2. Obtener mapa de elementos
            $mapa = $this->obtenerMapaElementos();

            // 3. Insertar SOLO los elementos que tienen estado definido
            $totalInsertados = 0;
            foreach ($detalle as $seccion => $elementos) {
                foreach ($elementos as $nombre => $valores) {
                    // ✅ Validar que 'estado' sea uno de los permitidos
                    $estado = trim($valores['estado'] ?? '');
                    if (!in_array($estado, ['bueno', 'regular', 'mal'])) {
                        continue; // Ignorar si no es válido
                    }

                    $idElemento = $mapa[strtoupper($nombre)] ?? null;
                    if (!$idElemento) {
                        continue;
                    }

                    $idElemento = $mapa[strtoupper($nombre)] ?? null;
                    if (!$idElemento)
                        continue;

                    $estadoMap = ['bueno' => 1, 'regular' => 2, 'mal' => 3];
                    $idEstado = $estadoMap[$valores['estado']] ?? 1;

                    $sqlDet = "INSERT INTO inve_vehiculo (
                    placa, id_inve_encabezado, id_elemen_inve, id_estado_inve, 
                    cantidad, observacion_uno, fecha_inven, id_grabador
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmtDet = $this->conn->prepare($sqlDet);
                    $cantidad = !empty($valores['cantidad']) ? $valores['cantidad'] : null;
                    $obs = !empty($valores['observacion']) ? $valores['observacion'] : null;
                    $stmtDet->bind_param(
                        "siiiissi",
                        $encabezado['placa'],
                        $idEncabezado,
                        $idElemento,
                        $idEstado,
                        $cantidad,
                        $obs,
                        $encabezado['fecha'],
                        $idUsuario
                    );
                    $stmtDet->execute();
                    $totalInsertados++;
                }
            }

            // ✅ Validar que se hayan insertado elementos
            if ($totalInsertados == 0) {
                throw new Exception("Debe llenar al menos un elemento del inventario.");
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        } finally {
            $this->conn->autocommit(TRUE);
        }
    }

    private function obtenerMapaElementos()
    {
        $sql = "SELECT id_tip_elemento, UPPER(des_elemento) as clave FROM tipos_elementos";
        $result = $this->conn->query($sql);
        $mapa = [];
        while ($row = $result->fetch_assoc()) {
            $mapa[$row['clave']] = $row['id_tip_elemento'];
        }
        return $mapa;
    }

    // ✅ OBTENER DETALLE DEL INVENTARIO (encabezado + items)
    public function obtenerDetalleInventario($idInventario)
    {
        // 1. Obtener encabezado
        $sqlEnc = "SELECT * FROM inve_encabezado WHERE id = ?";
        $stmtEnc = $this->conn->prepare($sqlEnc);
        $stmtEnc->bind_param("i", $idInventario);
        $stmtEnc->execute();
        $encabezado = $stmtEnc->get_result()->fetch_assoc();

        if (!$encabezado) {
            return null;
        }

        // 2. Obtener detalle con nombres de elementos y secciones
        $sqlDet = "
        SELECT 
            iv.*,
            te.des_elemento AS elemento,
            ti.des_tipo_inve AS seccion
        FROM inve_vehiculo iv
        LEFT JOIN tipos_elementos te ON iv.id_elemen_inve = te.id_tip_elemento
        LEFT JOIN tipos_inve ti ON te.id_tipo_inve = ti.id_tip_inve
        WHERE iv.id_inve_encabezado = ?
        ORDER BY ti.id_tip_inve, te.des_elemento
    ";
        $stmtDet = $this->conn->prepare($sqlDet);
        $stmtDet->bind_param("i", $idInventario);
        $stmtDet->execute();
        $detalle = [];
        $result = $stmtDet->get_result();
        while ($row = $result->fetch_assoc()) {
            $detalle[] = $row;
        }

        return [
            'encabezado' => $encabezado,
            'detalle' => $detalle
        ];
    }

    // ✅ Actualizar inventario completo
    public function actualizarInventarioCompleto($encabezado, $detalle, $idUsuario)
    {
        $this->conn->autocommit(FALSE);
        try {
            // 1. Actualizar encabezado
            $sqlUpd = "UPDATE inve_encabezado SET 
            nombre_propietario = ?, identificacion = ?, tipo_vehiculo = ?,
            marca = ?, tipo_carroceria = ?, kilometraje = ?, 
            fecha_inven = ?, observaciones_generales = ?
            WHERE id = ?";

            $stmt = $this->conn->prepare($sqlUpd);
            $stmt->bind_param(
                "sssssisss",
                $encabezado['nombre_propietario'],
                $encabezado['identificacion'],
                $encabezado['tipo_vehiculo'],
                $encabezado['marca'],
                $encabezado['tipo_carroceria'],
                $encabezado['kilometraje'],
                $encabezado['fecha'],
                $encabezado['observaciones_generales'],
                $encabezado['id']
            );
            $stmt->execute();

            // 2. Eliminar detalle anterior
            $this->conn->query("DELETE FROM inve_vehiculo WHERE id_inve_encabezado = " . (int) $encabezado['id']);

            // 3. Insertar nuevo detalle (igual que al crear)
            $mapa = $this->obtenerMapaElementos();
            $totalInsertados = 0;

            foreach ($detalle as $seccion => $elementos) {
                foreach ($elementos as $nombre => $valores) {
                    $estado = trim($valores['estado'] ?? '');
                    if (!in_array($estado, ['bueno', 'regular', 'mal'])) {
                        error_log("❌ Estado no válido, se salta: $nombre");//borrar
                        continue;
                    }

                    $idElemento = $mapa[strtoupper($nombre)] ?? null;

                    if (!$idElemento){
                    error_log("❌ Elemento no encontrado en mapa: $nombre");
                    continue;
                }

                    $idEstado = ['bueno' => 1, 'regular' => 2, 'mal' => 3][$estado] ?? 1;
                    $cantidad = !empty($valores['cantidad']) ? $valores['cantidad'] : null;
                    $obs = !empty($valores['observacion']) ? $valores['observacion'] : null;

                    $sqlDet = "INSERT INTO inve_vehiculo (
                    placa, id_inve_encabezado, id_elemen_inve, id_estado_inve, 
                    cantidad, observacion_uno, fecha_inven, id_grabador
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmtDet = $this->conn->prepare($sqlDet);
                    $stmtDet->bind_param(
                        "siiiisss",
                        $encabezado['placa'],
                        $encabezado['id'],
                        $idElemento,
                        $idEstado,
                        $cantidad,
                        $obs,
                        $encabezado['fecha'],
                        $idUsuario
                    );
                    $stmtDet->execute();
                    $totalInsertados++;

                }
            }
            if ($totalInsertados == 0) {
                throw new Exception("Debe llenar al menos un elemento del inventario.");
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        } finally {
            $this->conn->autocommit(TRUE);
        }
    }








}