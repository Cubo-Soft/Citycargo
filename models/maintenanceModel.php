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
            ORDER BY mp.fecha_programada";

        $result = $this->conn->query($sql);
        $revisiones = [];
        while ($row = $result->fetch_assoc()) {
            $revisiones[] = $row;
        }
        return $revisiones;
    }

    // ✅ ACTUALIZAR REVISIÓN PROGRAMADA
    public function actualizarMantenimientoProgramado($id, $id_tipo_manteni, $fecha_programada)
    {
        if (!$id || $id <= 0) {
            return false;
        }

        $sql = "UPDATE mantenimiento_programado 
                SET 
                    id_manteni = ?, 
                    fecha_programada = ? 
                WHERE id_mantenimiento = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iss", $id_tipo_manteni, $fecha_programada, $id);
        return $stmt->execute();
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



}