<?php

require_once 'vendor/autoload.php';
Dotenv\Dotenv::createImmutable(__DIR__)->load();
use Lucpa\Repository\AnalyseRepository;
use Lucpa\Service\AnalyseService;
use Lucpa\Repository\BillingRepository;
use Lucpa\Repository\ContractRepository;
use Lucpa\Repository\CustomerRepository;
use Lucpa\Repository\VehicleRepository;
use Lucpa\Service\BillingService;
use Lucpa\Service\ContractService;
use Lucpa\Service\CustomerService;
use Lucpa\Model\Database as ModelDatabase;
use Lucpa\Service\VehicleService;

$db = new ModelDatabase();
$pdo = $db->getMySQLConnection();
$mongoClient = $db->getMongoConnection();

// Initialisation du repository et du service
// $customerRepository = new CustomerRepository($mongoClient);
// $customerService = new CustomerService($customerRepository);

// $billingRepository = new BillingRepository($pdo);
// $billingService = new BillingService($billingRepository);

// $contractRepository = new ContractRepository($pdo);
// $contractService = new ContractService($contractRepository);

// $vehicleRepository = new VehicleRepository($mongoClient);
// $vehicleService = new VehicleService($vehicleRepository);

 $analyseRepository = new AnalyseRepository($pdo);
 $analyseService = new AnalyseService($analyseRepository);

// Example usage of saveCustomer
//$response = $customerService->saveCustomer('John', 'Doe', '1234 Elm Street', 'P12345678');


// Example usage of getCustomerByFullName
//$response = $customerService->getCustomerByFullName('John', 'Doe');


// Example usage of updateCustomer
//$response = $customerService->updateCustomer(1, 'John', 'Smith', '5678 Oak Avenue', 'P87654321');


// Example usage of saveVehicle
//$response = $vehicleService->saveVehicle('Toyota Corolla', 'ABC1234', 'Sedan, 5 doors', 15000);


// Example usage of getVehicleByLicencePlate
//$response = $vehicleService->getVehicleByLicencePlate('ABC1234');


// Example usage of updateVehicle
//$response = $vehicleService->updateVehicle(1, 'Honda Civic', 'XYZ9876', 'Coupe, 2 doors', 25000);


// Example usage of countVehiclesWithMoreThanKm
//$response = $vehicleService->countVehiclesWithMoreThanKm(20000);


// Example usage of deleteVehicle
//$response = $vehicleService->deleteVehicle(1);


// Example usage of saveContract
//$response = $contractService->saveContract(
//     'V001',           // vehicleUid
//     'C003',           // customerUid
//     '2025-02-11 10:00:00', // signDatetime
//     '2025-02-11 14:00:00', // locBeginDatetime
//     '2025-02-12 14:00:00', // locEndDatetime
//     '2025-02-12 15:00:00', // returningDatetime
//     100.00            // price
// );


// Example usage of getContractById
//$response = $contractService->getContractById(1); // Use an actual contract ID


// Example usage of deleteContract
//$response = $contractService->deleteContract(1); // Use an actual contract ID


// Example usage of createTable (if you need to create the contracts table)
//$response = $contractService->createTable();


// Example usage of saveBilling
//$response = $billingService->saveBilling(
//     1,       // contract_id (use an actual contract ID)
//     200.50   // amount (use an actual amount greater than 0)
// );


// Example usage of getBillingById
//$response = $billingService->getBillingById(1); // Use an actual billing ID


// Example usage of deleteBilling
//$response = $billingService->deleteBilling(1); // Use an actual billing ID


// Example usage of createTable (if you need to create the billing table)
//$response = $billingService->createTable();


// Example usage: List ongoing rentals by customer UID
$customerUid = 'C003';
$response = $analyseService->listOngoingRentalsByCustomerUid($customerUid);

// Example usage: List late rentals
//$response = $analyseService->listLateRentals();

// Example usage: List payments by contract ID
//$contractId = 1;
//$response = $analyseService->listPaymentsByContractId($contractId);

// Example usage: Check if rental is fully paid
//$contractId = 2;
//$response = $analyseService->isRentalFullyPaid($contractId);

// Example usage: List unpaid rentals
//$response = $analyseService->listUnpaidRentals();

// Example usage: Count late rentals between dates
//$startDate = '2025-01-01';
//$endDate = '2025-01-31';
//$response = $analyseService->countLateRentalsBetweenDates($startDate, $endDate);

// Example usage: Count average late rentals per customer
//$response = $analyseService->countAverageLateRentalsPerCustomer();

// Example usage: List contracts by vehicle UID
//$vehicleUid = 'vehicle123';
//$response = $analyseService->listContractsByVehicleUid($vehicleUid);

// Example usage: Get average delay by vehicle
//$response = $analyseService->getAverageDelayByVehicle();

// Example usage: Get contracts grouped by vehicle
//$response = $analyseService->getContractsGroupedByVehicle();

// Example usage: Get contracts grouped by customer
//$response = $analyseService->getContractsGroupedByCustomer();

// display Json response
echo ($response->toJson());




