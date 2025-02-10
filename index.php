<?php
require_once 'vendor/autoload.php';

use Lucpa\Model\Database as ModelDatabase;



Dotenv\Dotenv::createImmutable(__DIR__)->load();
$db= new ModelDatabase();
$pdo= $db->getMySQLConnection();

$client= $db->getMongoConnection();
$collection = $client->easyloc->customers;
$name = "john";
// Créer un document client avec un nom
$result = $collection->insertOne(['name' => $name]);