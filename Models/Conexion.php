<?php

class Conexion
{

    static public function conectar()
    {

        try {

            $link = new PDO(

                "mysql:host=localhost;dbname=bdagenda2026",
                "root",
                ""

            );

            $link->exec("SET NAMES utf8");

            $link->setAttribute(

                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION

            );

            return $link;
        } catch (PDOException $e) {

            die("Error de conexión: " . $e->getMessage());
        }
    }
}
