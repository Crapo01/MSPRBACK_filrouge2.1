<?php
namespace Lucpa\Model;
class Database {
    private $mysqlPdo;
    private $mongoClient;

    // Constructeur qui se charge de la connexion à la base de données
    public function __construct() {
        $this->connectMySQL();
        $this->connectMongoDB();
    }

    // Connexion à MySQL
    private function connectMySQL() {
        if (isset($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS'])) {
            $host = $_ENV['DB_HOST'];
            $dbname = $_ENV['DB_NAME'];
            $username = $_ENV['DB_USER'];
            $password = $_ENV['DB_PASS'];

            // Construction de la chaîne de connexion DSN
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

            try {
                // Création de l'objet PDO pour la connexion MySQL
                $this->mysqlPdo = new \PDO($dsn, $username, $password);
                $this->mysqlPdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // Mode d'erreur : exception
                echo "Connexion réussie à MySQL !<br>";
            } catch (\PDOException $e) {
                echo "Erreur de connexion MySQL : " . $e->getMessage() . "<br>";
            }
        } else {
            echo "Les variables d'environnement MySQL ne sont pas définies.<br>";
        }
    }

    // Connexion à MongoDB avec authentification (user et password)
    private function connectMongoDB() {
        if (isset($_ENV['MONGO_HOST'], $_ENV['MONGO_PORT'], $_ENV['MONGO_DB'], $_ENV['MONGO_USER'], $_ENV['MONGO_PASS'])) {
            $host = $_ENV['MONGO_HOST'];
            $port = $_ENV['MONGO_PORT'];
            $db = $_ENV['MONGO_DB'];
            $username = $_ENV['MONGO_USER'];
            $password = $_ENV['MONGO_PASS'];

            // Construction de l'URI de connexion MongoDB avec authentification
            //$uri = "mongodb://$username:$password@$host:$port/$db";
            $uri= "mongodb://localhost:27017/easyloc";

            try {
                // Création du client MongoDB avec l'URI
                $this->mongoClient = new \MongoDB\Client($uri);
                echo "Connexion réussie à MongoDB !<br>";
            } catch (\Exception $e) {
                echo "Erreur de connexion MongoDB : " . $e->getMessage() . "<br>";
            }
        } else {
            echo "Les variables d'environnement MongoDB ne sont pas définies.<br>";
        }
    }

    // Récupérer la connexion MySQL
    public function getMySQLConnection() {
        return $this->mysqlPdo;
    }

    // Récupérer la connexion MongoDB
    public function getMongoConnection() {
        return $this->mongoClient;
    }

    // Méthode pour fermer la connexion MySQL (si nécessaire)
    public function closeMySQLConnection() {
        $this->mysqlPdo = null;
    }

    // Méthode pour fermer la connexion MongoDB (si nécessaire)
    public function closeMongoConnection() {
        $this->mongoClient = null;
    }
}
