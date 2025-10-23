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
            ie.tipo_combustible,
            ie.fecha_inven AS fecha
        FROM inve_encabezado ie
        ORDER BY ie.fecha_inven DESC, ie.creado_en DESC
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



}