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
            m.fecha
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
            tm.nombre AS servicio,
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


    // ✅ CONTAR RESUMENES
    public function contarMantenimientosTotales()
    {
        $sql = "SELECT COUNT(*) as total FROM mantenimiento";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public function valorTotalPagado()
    {
        $sql = "SELECT SUM(costo) as total FROM mantenimiento";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return number_format($row['total'] ?? 0, 0, ',', '.');
    }

    public function obtenerProximasRevisiones()
    {
        $sql = "SELECT 
                    m.placa,
                    tm.nombre AS servicio,
                    mp.fecha_programada
                FROM mantenimiento_programado mp
                INNER JOIN tipo_mantenimiento tm ON mp.id_manteni = tm.id_tipo_manteni
                INNER JOIN mantenimiento m ON mp.placa = m.placa
                WHERE mp.fecha_programada >= CURDATE()
                ORDER BY mp.fecha_programada ASC LIMIT 3";
        $result = $this->conn->query($sql);
        $revisiones = [];
        while ($row = $result->fetch_assoc()) {
            $revisiones[] = $row;
        }
        return $revisiones;
    }

    // ✅ OBTENER TODOS LOS PRESTADORES (para el formulario)
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

    // ✅ OBTENER TODOS LOS TIPOS DE MANTENIMIENTO (para el formulario)
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


    
}