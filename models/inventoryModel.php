<?php

class inventoryModel
{
    private $conn;
    
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

}