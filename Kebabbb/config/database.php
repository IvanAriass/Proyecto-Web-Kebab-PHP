<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class Database {
    private $hostname = "127.0.0.1:3306";
    private $database = "db_pedidos";
    private $username = "root";
    private $password = "";
    private $charset = "utf8";
    
    
    function conectar()
    {
        try {
            $conexion = "mysql:host=" . $this->hostname . "; dbname=" . $this->database . ";
                    charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            $pdo = new PDO($conexion, $this->username, $this->password, $options);
        
        return $pdo;
        } catch (PDOException $ex) {
            echo 'Error conexion: ' .$ex->getMessage();
        }   
     }
}