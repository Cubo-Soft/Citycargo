<?php
class maintenanceModel
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

    // ✅ OBTENER TODOS LOS MANTENIMIENTOS REALIZADOS
    public function obtenerMantenimientos()
    {
        $sql = "
        SELECT 
            m.id_mant_realizado AS id,
            m.num_factura AS factura,
            m.placa,
            p.nombre AS empresa,
            tm.nombre AS servicio,
            m.costo AS valor,
            m.fecha,
            COALESCE(m.observaciones, '') AS observaciones
        FROM mantenimiento m
        INNER JOIN prestadores_servicios p ON m.id_prestador = p.id_prestador
        INNER JOIN tipo_mantenimiento tm ON m.id_manteni = tm.id_tipo_manteni
        ORDER BY m.fecha DESC
    ";

        $result = $this->conn->query($sql);
        $mantenimientos = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $mantenimientos[] = $row;
            }
        }
        return $mantenimientos;
    }

    // ✅ OBTENER DETALLE DE UN MANTENIMIENTO POR ID
    public function obtenerDetalleMantenimiento($id)
    {
        $sql = "
        SELECT 
            m.id_mant_realizado AS id,
            m.num_factura AS factura,
            m.placa,
            p.nombre AS empresa,
            p.id_prestador AS id_prestador,
            tm.nombre AS servicio,
            tm.id_tipo_manteni AS id_tipo_manteni,
            m.costo AS valor,
            m.fecha,
            m.kilometraje,
            m.observaciones
        FROM mantenimiento m
        INNER JOIN prestadores_servicios p ON m.id_prestador = p.id_prestador
        INNER JOIN tipo_mantenimiento tm ON m.id_manteni = tm.id_tipo_manteni
        WHERE m.id_mant_realizado = ?
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // ✅ GUARDAR NUEVO MANTENIMIENTO
    public function guardarMantenimiento($factura, $placa, $id_prestador, $id_tipo_manteni, $valor, $fecha, $kilometraje, $obs)
    {
        $sql = "INSERT INTO mantenimiento (num_factura, placa, id_prestador, id_manteni, costo, fecha, kilometraje, observaciones) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssiiisss", $factura, $placa, $id_prestador, $id_tipo_manteni, $valor, $fecha, $kilometraje, $obs);
        return $stmt->execute();
    }

    // ✅ ACTUALIZAR MANTENIMIENTO EXISTENTE
    public function actualizarMantenimiento($id, $factura, $placa, $id_prestador, $id_tipo_manteni, $valor, $fecha, $kilometraje, $obs)
    {
        // Validación básica
        if (!$id || $id <= 0) {
            return false;
        }

        $sql = "UPDATE mantenimiento SET 
                    num_factura = ?, 
                    placa = ?, 
                    id_prestador = ?, 
                    id_manteni = ?, 
                    costo = ?, 
                    fecha = ?, 
                    kilometraje = ?, 
                    observaciones = ? 
                WHERE id_mant_realizado = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssiiissss", $factura, $placa, $id_prestador, $id_tipo_manteni, $valor, $fecha, $kilometraje, $obs, $id);
        return $stmt->execute();
    }

    // ✅ CONTAR RESUMENES
    // public function contarMantenimientosTotales()
    // {
    //     $sql = "SELECT COUNT(*) as total FROM mantenimiento";
    //     $result = $this->conn->query($sql);
    //     $row = $result->fetch_assoc();
    //     return $row['total'] ?? 0;
    // }

    // public function valorTotalPagado()
    // {
    //     $sql = "SELECT SUM(costo) as total FROM mantenimiento";
    //     $result = $this->conn->query($sql);
    //     $row = $result->fetch_assoc();
    //     return number_format($row['total'] ?? 0, 0, ',', '.');
    // }

    // ✅ CONTAR RESUMENES MENSUALES
    public function contarMantenimientosMensuales()
    {
        $sql = "SELECT COUNT(*) as total FROM mantenimiento 
            WHERE YEAR(fecha) = YEAR(CURDATE()) 
            AND MONTH(fecha) = MONTH(CURDATE())";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public function valorTotalPagadoMensual()
    {
        $sql = "SELECT SUM(costo) as total FROM mantenimiento 
            WHERE YEAR(fecha) = YEAR(CURDATE()) 
            AND MONTH(fecha) = MONTH(CURDATE())";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return number_format($row['total'] ?? 0, 0, ',', '.');
    }

    // ✅ PROXIMAS REVISIONES
    public function obtenerProximasRevisiones()
    {
        $sql = "SELECT 
                mp.placa,
                tm.nombre AS servicio,
                mp.fecha_programada,
                mp.id_mantenimiento,
                mp.kilometraje_programado
            FROM mantenimiento_programado mp
            INNER JOIN tipo_mantenimiento tm ON mp.id_manteni = tm.id_tipo_manteni
            WHERE mp.fecha_programada >= CURDATE()
            AND mp.estado = 1
            ORDER BY mp.fecha_programada";

        $result = $this->conn->query($sql);
        $revisiones = [];
        while ($row = $result->fetch_assoc()) {
            $revisiones[] = $row;
        }
        return $revisiones;
    }


    // ✅ ACTUALIZAR PROXIMA REVISIÓN 
    public function actualizarProximaRevision($id, $id_tipo_manteni, $fecha_programada, $kilometraje_programado)
    {
        if (!$id || $id <= 0) {
            return false;
        }

        $sql = "UPDATE mantenimiento_programado 
            SET 
                id_manteni = ?, 
                fecha_programada = ?,
                kilometraje_programado = ?
            WHERE id_mantenimiento = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issi", $id_tipo_manteni, $fecha_programada, $kilometraje_programado, $id);
        return $stmt->execute();
    }

    // ✅ DESACTIVAR REVISION (marcar como estado = 0)
    public function desactivarRevision($id)
    {
        if (!$id || $id <= 0) {
            return false;
        }

        $sql = "UPDATE mantenimiento_programado SET estado = 0 WHERE id_mantenimiento = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }


    // ✅ GUARDAR PROXIMA REVISIÓN 
    public function guardarProximaRevision($placa, $fecha_programada, $id_tipo_manteni, $kilometraje)
    {
        $sql = "INSERT INTO mantenimiento_programado (placa, fecha_programada, id_manteni, kilometraje_programado) 
            VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssii", $placa, $fecha_programada, $id_tipo_manteni, $kilometraje);
        return $stmt->execute();
    }

    // ✅ OBTENER PRÓXIMA REVISION POR ID
    public function obtenerProximaRevisionPorId($id)
    {
        if (!$id || $id <= 0) {
            return false;
        }

        $sql = "SELECT mp.id_mantenimiento,
                    mp.placa,
                    mp.fecha_programada,
                    mp.id_manteni,
                    mp.kilometraje_programado,
                    tm.nombre AS servicio
                    FROM mantenimiento_programado mp
                    INNER JOIN tipo_mantenimiento tm ON mp.id_manteni = tm.id_tipo_manteni
                    WHERE mp.id_mantenimiento = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : false;
    }

    // ✅ OBTENER TODOS LOS PRESTADORES
    public function obtenerPrestadores()
    {
        $sql = "SELECT id_prestador, nombre FROM prestadores_servicios ORDER BY nombre";
        $result = $this->conn->query($sql);
        $prestadores = [];
        while ($row = $result->fetch_assoc()) {
            $prestadores[] = $row;
        }
        return $prestadores;
    }

    // ✅ OBTENER TODOS LOS TIPOS DE MANTENIMIENTO
    public function obtenerTiposMantenimiento()
    {
        $sql = "SELECT id_tipo_manteni, nombre FROM tipo_mantenimiento ORDER BY nombre";
        $result = $this->conn->query($sql);
        $tipos = [];
        while ($row = $result->fetch_assoc()) {
            $tipos[] = $row;
        }
        return $tipos;
    }

    public function __destruct()
    {
        $this->conn->close();
    }

    // ✅ VERIFICAR SI PRESTADOR EXISTE (por nombre o NIT)
    public function prestadorExiste($nombre, $nit)
    {
        $sql = "SELECT id_prestador FROM prestadores_servicios WHERE nombre = ? OR nit = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $nombre, $nit);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // ✅ CREAR NUEVO PRESTADOR (EMPRESA)
    public function crearPrestador($nombre, $nit, $direccion = '', $contacto = '', $mail_prestador = '')
    {
        if (empty(trim($nombre)) || empty(trim($nit))) {
            return false;
        }

        // Verificar duplicado usando el nuevo método
        if ($this->prestadorExiste($nombre, $nit)) {
            return false;
        }

        $sql = "INSERT INTO prestadores_servicios (nombre, nit, direccion, contacto, mail_prestador) 
            VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss", $nombre, $nit, $direccion, $contacto, $mail_prestador);
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }

    // ✅ VERIFICAR SI SERVICIO EXISTE (por nombre)
    public function servicioExiste($nombre)
    {
        $sql = "SELECT id_tipo_manteni FROM tipo_mantenimiento WHERE nombre = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    // ✅ CREAR NUEVO SERVICIO (tipo mantenimiento)
    public function crearServicio($nombre, $descripcion)
    {
        if (empty(trim($nombre)) || empty(trim($descripcion))) {
            return false;
        }

        // ✅ Verificar duplicado 
        if ($this->servicioExiste($nombre)) {
            return false;
        }

        $sql = "INSERT INTO tipo_mantenimiento (nombre, descripcion) 
            VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $nombre, $descripcion);
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
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

    //✅ Verificar si la placa existe
    // public function existePlaca($placa)
    // {
    //     $stmt = $this->conn->prepare("SELECT 1 FROM tarje_prop_vehiculos WHERE placa = ?");
    //     $stmt->bind_param("s", $placa);
    //     $stmt->execute();
    //     return $stmt->get_result()->num_rows > 0;
    // }

    //✅ Crear nuevo vehículo
    // public function crearVehiculo($data)
    // {
    //     $sql = "INSERT INTO tarje_prop_vehiculos (
    //     placa, id_marca, id_linea, modelo, cilindraje, id_color,
    //     id_servicio, id_clase, id_carroce, id_combust, capacidad,
    //     num_motor, vin, num_serie, num_chasis, id_propietario,
    //     decla_importacion, blindaje, potencia, fec_matricula,
    //     fec_exp_li_tto, org_tto_matricula, id_grabador
    // ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bind_param(
    //         "siiiiiiiiiiissssisiiisssi",
    //         $placa,
    //         $id_marca,
    //         $id_linea,
    //         $modelo,
    //         $cilindraje,
    //         $id_color,
    //         $id_servicio,
    //         $id_clase,
    //         $id_carroce,
    //         $id_combust,
    //         $capacidad,
    //         $num_motor,
    //         $vin,
    //         $num_serie,
    //         $num_chasis,
    //         $id_propietario,
    //         $decla_importacion,
    //         $blindaje,
    //         $potencia,
    //         $fec_matricula,
    //         $fec_exp_li_tto,
    //         $org_tto_matricula,
    //         $id_grabador
    //     );

    //     if ($stmt->execute()) {
    //         return $this->conn->insert_id;
    //     }

    //     return false;
    // }

    //✅ Verificar si la placa existe
    public function existePlaca($placa)
    {
        $stmt = $this->conn->prepare("SELECT 1 FROM vehiculo WHERE placa = ?");
        $stmt->bind_param("s", $placa);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
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








}