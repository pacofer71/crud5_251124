<?php

namespace App\Bd;

use \PDO;
use \PDOException;

class Conexion
{
    private static ?PDO $conexion = null;

    protected static function getConexion(): PDO
    {
        if (is_null(self::$conexion)) self::setConexion();
        return self::$conexion;
    }
    private static function setConexion(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__."/../../");
        $dotenv->load();

        $usuario=$_ENV['USUARIO'];
        $pass=$_ENV['PASS'];
        $host=$_ENV['HOST'];
        $port=$_ENV['PORT'];
        $db=$_ENV['DB'];

        $dsn="mysql:host=$host;dbname=$db;port=$port;charset=utf8mb4";
        $options=[
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT=>true
        ];
        try{
            self::$conexion=new PDO($dsn, $usuario, $pass, $options);
        }catch(PDOException $ex){
            throw new PDOException("Error en conexion: {$ex->getMessage()}");
        }

    }
    protected static function cerrarConexion(): void{
        self::$conexion=null;
    }
}
