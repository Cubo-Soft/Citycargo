<?php

include 'configuracion_db.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of conexion
 *
 * @author wilmer
 */
class Conexion extends PDO {

    //private $contrasena = 'F1i9t7u9X';
    
    /*codificación*/
    /*
    private $nombre_de_base = "sig";
    private $usuario = "root";    
    private $contrasena = "";
    */
    /*produccion*/    
    private $nombre_de_base = "citivillas";
    private $usuario = "root";    
    private $contrasena = "";    
    private $tipo_de_base = 'mysql';
    private $host = 'localhost';

    public function __construct() {
        //Sobreescribo el método constructor de la clase PDO.
        try {
            parent::__construct($this->tipo_de_base . ':host=' . $this->host . ';dbname=' . $this->nombre_de_base, $this->usuario, $this->contrasena, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES  \'UTF8\''));
        } catch (PDOException $e) {
            echo 'Ha surgido un error y no se puede conectar a la base de datos. Detalle: ' . $e->getMessage();
            exit;
        }
    }
}
