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
    
    public function obtenerPlacas()
    {
        $sql = "SELECT DISTINCT placa FROM tarje_prop_vehiculos WHERE placa IS NOT NULL AND placa != '' ORDER BY placa";
        $result = $this->conn->query($sql);
        $placas = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $placas[] = $row['placa'];
            }
        }
        return $placas;
    }


    public function obtenerElementosInventario() {
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


public function obtenerInventariosRegistrados() {
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



}