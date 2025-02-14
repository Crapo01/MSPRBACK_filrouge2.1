<?php

namespace Lucpa\Model;

class Database
{
    private static $mysqlPdo;
    //private static $postgresPdo;
    private static $mongoClient;

    public static function connectMySQL()
    {
        if (isset($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS'])) {
            $host = $_ENV['DB_HOST'];
            $dbname = $_ENV['DB_NAME'];
            $username = $_ENV['DB_USER'];
            $password = $_ENV['DB_PASS'];
            
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

            try {
                self::$mysqlPdo = new \PDO($dsn, $username, $password);
                self::$mysqlPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                return self::$mysqlPdo; 
            } catch (\PDOException $e) {
                echo "Erreur de connexion MySQL : " . $e->getMessage() . "<br>";
            }
        } else {
            echo "Les variables d'environnement MySQL ne sont pas définies.<br>";
        }
    }

    // public static function connectPostgreSQL()
    // {
    //     if (isset($_ENV['PG_HOST'], $_ENV['PG_NAME'], $_ENV['PG_USER'], $_ENV['PG_PASS'])) {
    //         $host = $_ENV['PG_HOST'];
    //         $dbname = $_ENV['PG_NAME'];
    //         $username = $_ENV['PG_USER'];
    //         $password = $_ENV['PG_PASS'];
            
    //         $dsn = "pgsql:host=$host;dbname=$dbname";

    //         try {
    //             self::$postgresPdo = new \PDO($dsn, $username, $password);
    //             self::$postgresPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    //             return self::$postgresPdo;
    //         } catch (\PDOException $e) {
    //             echo "Erreur de connexion PostgreSQL : " . $e->getMessage() . "<br>";
    //         }
    //     } else {
    //         echo "Les variables d'environnement PostgreSQL ne sont pas définies.<br>";
    //     }
    // }

    public static function connectMongoDB()
    {
        if (isset($_ENV['MONGO_HOST'], $_ENV['MONGO_PORT'], $_ENV['MONGO_DB'], $_ENV['MONGO_USER'], $_ENV['MONGO_PASS'])) {
            $host = $_ENV['MONGO_HOST'];
            $port = $_ENV['MONGO_PORT'];
            $db = $_ENV['MONGO_DB'];
            $username = $_ENV['MONGO_USER'];
            $password = $_ENV['MONGO_PASS'];
            
            $uri = "mongodb://$host/$db";

            try {
                self::$mongoClient = new \MongoDB\Client($uri);
                return self::$mongoClient;
            } catch (\Exception $e) {
                echo "Erreur de connexion MongoDB : " . $e->getMessage() . "<br>";
            }
        } else {
            echo "Les variables d'environnement MongoDB ne sont pas définies.<br>";
        }
    }

    public static function closeMySQLConnection()
    {
        self::$mysqlPdo = null;
    }

    // public static function closePostgreSQLConnection()
    // {
    //     self::$postgresPdo = null;
    // }

    public static function closeMongoConnection()
    {
        self::$mongoClient = null;
    }
}
