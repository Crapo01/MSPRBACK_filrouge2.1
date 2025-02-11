<?php

use Lucpa\Repository\AnalyseRepository;
use Lucpa\Service\AnalyseService;
require_once 'vendor/autoload.php';
Dotenv\Dotenv::createImmutable(__DIR__)->load();

use Lucpa\Repository\BillingRepository;
use Lucpa\Repository\ContractRepository;
use Lucpa\Repository\CustomerRepository;
use Lucpa\Repository\VehicleRepository;
use Lucpa\Service\BillingService;
use Lucpa\Service\ContractService;
use Lucpa\Service\CustomerService;
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

//$response = $vehicleService->saveVehicle("Toyota Corolla");
//$response = $contractService->createTable();
//echo $response->getMessage();
//$response = $billingService->createTable();

// Output the message from the response
//echo $response->getMessage();




// Create an instance of the repository
$analyseRepository = new AnalyseRepository($pdo);

// Create an instance of the AnalyseService
$analyseService = new AnalyseService($analyseRepository);

// Example: List all ongoing rentals for a specific customer
$customerUid = "C003"; // Use an actual customer UID
$response = $analyseService->listOngoingRentalsByCustomerUid($customerUid);
echo "<pre>";
var_dump($response);
echo "<pre>";
// Example: List all late rentals (rentals where returning datetime is more than 1 hour after loc_end_datetime)
$response = $analyseService->listLateRentals();
echo "<pre>";
var_dump($response);
echo "<pre>";

// Example: List all payments for a specific contract ID
$contractId = 8; // Use an actual contract ID
$response = $analyseService->listPaymentsByContractId($contractId);
echo "<pre>";
var_dump($response);
echo "<pre>";

// Example: Check if a rental has been fully paid
$response = $analyseService->isRentalFullyPaid($contractId);
echo "<pre>";
var_dump($response);
echo "<pre>";

// Example: List all unpaid rentals
$response = $analyseService->listUnpaidRentals();
echo "<pre>";
var_dump($response);
echo "<pre>";

// Example: Count late rentals between two dates
$startDate = '2025-01-01'; // Example start date
$endDate = '2025-03-31'; // Example end date
$response = $analyseService->countLateRentalsBetweenDates($startDate, $endDate);
echo "<pre>";
var_dump($response);
echo "<pre>";

// Example: Count the average number of late rentals per customer
$response = $analyseService->countAverageLateRentalsPerCustomer();
echo "<pre>";
var_dump($response);
echo "<pre>";

// Example: List contracts associated with a vehicle UID
$vehicleUid = 'V001'; // Example vehicle UID
$response = $analyseService->listContractsByVehicleUid($vehicleUid);
echo "<pre>";
var_dump($response);
echo "<pre>";

// Example: Get the average delay by vehicle
$response = $analyseService->getAverageDelayByVehicle();
echo "<pre>";
var_dump($response);
echo "<pre>";

// Example: Retrieve all contracts grouped by vehicle
$response = $analyseService->getContractsGroupedByVehicle();
echo "<pre>";
var_dump($response);
echo "<pre>";

// Example: Retrieve all contracts grouped by customer
$response = $analyseService->getContractsGroupedByCustomer();
echo "<pre>";
var_dump($response);
echo "<pre>";





//echo "<pre>";
//var_dump($result->toArray()['message']);
//var_dump($response);
//echo "<pre>";


