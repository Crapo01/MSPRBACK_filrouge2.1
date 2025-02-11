<?php

namespace Lucpa\Model;

class Database
{
    private $mysqlPdo;
    private $mongoClient;


    public function __construct()
    {
        $this->connectMySQL();
        $this->connectMongoDB();
    }


    private function connectMySQL()
    {
        if (isset($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS'])) {
            $host = $_ENV['DB_HOST'];
            $dbname = $_ENV['DB_NAME'];
            $username = $_ENV['DB_USER'];
            $password = $_ENV['DB_PASS'];


            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

            try {

                $this->mysqlPdo = new \PDO($dsn, $username, $password);
                $this->mysqlPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                
            } catch (\PDOException $e) {
                echo "Erreur de connexion MySQL : " . $e->getMessage() . "<br>";
            }
        } else {
            echo "Les variables d'environnement MySQL ne sont pas définies.<br>";
        }
    }


    private function connectMongoDB()
    {
        if (isset($_ENV['MONGO_HOST'], $_ENV['MONGO_PORT'], $_ENV['MONGO_DB'], $_ENV['MONGO_USER'], $_ENV['MONGO_PASS'])) {
            $host = $_ENV['MONGO_HOST'];
            $port = $_ENV['MONGO_PORT'];
            $db = $_ENV['MONGO_DB'];
            $username = $_ENV['MONGO_USER'];
            $password = $_ENV['MONGO_PASS'];


            //$uri = "mongodb://$username:$password@$host:$port/$db";
            $uri = "mongodb://localhost:27017/easyloc";

            try {

                $this->mongoClient = new \MongoDB\Client($uri);                
            } catch (\Exception $e) {
                echo "Erreur de connexion MongoDB : " . $e->getMessage() . "<br>";
            }
        } else {
            echo "Les variables d'environnement MongoDB ne sont pas définies.<br>";
        }
    }


    public function getMySQLConnection()
    {
        return $this->mysqlPdo;
    }


    public function getMongoConnection()
    {
        return $this->mongoClient;
    }


    public function closeMySQLConnection()
    {
        $this->mysqlPdo = null;
    }


    public function closeMongoConnection()
    {
        $this->mongoClient = null;
    }
}
