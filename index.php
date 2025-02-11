<?php

use Lucpa\Repository\BillingRepository;
use Lucpa\Repository\ContractRepository;
use Lucpa\Repository\CustomerRepository;
use Lucpa\Repository\VehicleRepository;
use Lucpa\Service\BillingService;
use Lucpa\Service\ContractService;
use Lucpa\Service\CustomerService;
require_once 'vendor/autoload.php';

Dotenv\Dotenv::createImmutable(__DIR__)->load();
use Lucpa\Model\Database as ModelDatabase;
use Lucpa\Service\VehicleService;

$db= new ModelDatabase();
$pdo= $db->getMySQLConnection();

$mongoClient= $db->getMongoConnection();

// Initialisation du repository et du service
$customerRepository = new CustomerRepository($mongoClient);
$customerService = new CustomerService($customerRepository);

$billingRepository = new BillingRepository($pdo);
$billingService = new BillingService($billingRepository);

$contractRepository = new ContractRepository($pdo);
$contractService = new ContractService($contractRepository);

$vehicleRepository = new VehicleRepository($mongoClient);
$vehicleService = new VehicleService($vehicleRepository);

// Ajouter un client
//$result= $customerService->saveCustomer('test');  

// find customer by name
//$customerName = 'John Doe'; 
//$result = $customerService->getCustomerByName($customerName);
// delete customer by id
//$id = 1;
//$result= $customerService->deleteCustomer($id);  

//$result = $customerService->updateCustomerName(2, "John Smith");

//$response = $billingService->saveBilling(200.50, '2025-02-10');  

//$response = $contractService->saveContract(1000.50);

$response = $vehicleService->saveVehicle("Toyota Corolla");

echo "<pre>";
//var_dump($result->toArray()['message']);
var_dump($response);
echo "<pre>";


