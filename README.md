# App1: DATABASE ACCESS LIBRARY

Setup Instructions

    Clone this repository:

git clone https://github.com/Crapo01/MSPRBACK_filrouge2.1.git

Install dependencies using Composer:

composer install

Set up the database and configure your environment as needed.

Create .env file at root

    # Variables  MySQL
    DB_HOST=localhost
    DB_NAME=easyloc
    DB_USER=your_user_name
    DB_PASS=your_user_password

    # Variables  MongoDB
    MONGO_HOST=localhost
    MONGO_PORT=27017
    MONGO_DB=easyloc
    MONGO_USER=your_user_name
    MONGO_PASS=your_user_password


################################################

## Response Class

The ContractService class returns a Response object for all methods. The Response class contains:

    Status Code: The HTTP status code (e.g., 200, 400, 404, 500).
    Message: A descriptive message regarding the request outcome.
    Data (optional): The data returned (e.g., a contract record or confirmation message).

Response Methods

    getCode(): Retrieves the status code of the response.
    getMessage(): Retrieves the message of the response.
    getData(): Retrieves the data (if any) of the response.

Example Usage of Response Class:

$response = $contractService->saveContract(1000, 1);
echo $response->getCode();    // 201
echo $response->getMessage(); // "Contrat enregistré avec succès."
echo $response->getData();    // Contract data object



Error Handling

    All methods include exception handling to ensure that the response contains an appropriate error message if something goes wrong.
    The following status codes are used:
        200: OK (for successful operations like retrieval or listing).
        201: Created (for successful creation of new records).
        400: Bad Request (for validation failures like invalid amount).
        404: Not Found (for cases where the requested contract record is not found).
        500: Internal Server Error (for unexpected errors during database operations).



#####################################################

# `AnalyseService` Class Documentation

**Namespace**: `Lucpa\Service`

## Description:
The `AnalyseService` class provides various methods to analyze rental and payment data, primarily interacting with the `AnalyseRepository` to retrieve information related to ongoing rentals, late rentals, payments, and contracts. This service class is intended to manage the business logic for analyzing rental data in a rental management system.

## Constructor:

### `public function __construct(AnalyseRepository $analyseRepository)`

- **Parameters:**
  - `AnalyseRepository $analyseRepository`: A repository class responsible for data retrieval from the database (either MySQL or MongoDB).
  
- **Description:**
  The constructor initializes the service with the given repository to fetch data related to rentals, payments, and contracts.

---

## Methods:

### `public function listOngoingRentalsByCustomerUid($customerUid)`

- **Parameters:**
  - `string $customerUid`: The unique identifier of the customer.
  
- **Returns:**
  - `Response`: A response object containing the status code, message, and data (ongoing rentals).
  
- **Description:**
  This method retrieves all ongoing rentals for a specific customer by their UID. If the UID is invalid, it returns an error message.

---

### `public function listLateRentals()`

- **Returns:**
  - `Response`: A response object containing the status code, message, and data (late rentals).
  
- **Description:**
  This method retrieves all the rentals that are late. It returns the late rentals data.

---

### `public function listPaymentsByContractId($contractId)`

- **Parameters:**
  - `int $contractId`: The unique identifier of the rental contract.
  
- **Returns:**
  - `Response`: A response object containing the status code, message, and data (payments).
  
- **Description:**
  This method retrieves all payments associated with a given contract ID. It returns an error if the contract ID is invalid.

---

### `public function isRentalFullyPaid($contractId)`

- **Parameters:**
  - `int $contractId`: The unique identifier of the rental contract.
  
- **Returns:**
  - `Response`: A response object with a message indicating whether the rental has been fully paid.
  
- **Description:**
  This method checks whether the rental corresponding to the given contract ID has been fully paid. Returns a success message if fully paid, or an error message if not.

---

### `public function listUnpaidRentals()`

- **Returns:**
  - `Response`: A response object containing the status code, message, and data (unpaid rentals).
  
- **Description:**
  This method retrieves all rentals that have not been paid. It returns the list of unpaid rentals.

---

### `public function countLateRentalsBetweenDates($startDate, $endDate)`

- **Parameters:**
  - `string $startDate`: The start date of the period to count late rentals.
  - `string $endDate`: The end date of the period to count late rentals.
  
- **Returns:**
  - `Response`: A response object containing the count of late rentals between the given dates.
  
- **Description:**
  This method counts the number of late rentals between two specific dates. Returns an error if the dates are invalid.

---

### `public function countAverageLateRentalsPerCustomer()`

- **Returns:**
  - `Response`: A response object containing the average number of late rentals per customer.
  
- **Description:**
  This method calculates the average number of late rentals per customer and returns the result.

---

### `public function listContractsByVehicleUid($vehicleUid)`

- **Parameters:**
  - `string $vehicleUid`: The unique identifier of the vehicle.
  
- **Returns:**
  - `Response`: A response object containing the status code, message, and data (contracts).
  
- **Description:**
  This method retrieves all contracts associated with a specific vehicle based on its UID. If the vehicle UID is invalid, it returns an error message.

---

### `public function getAverageDelayByVehicle()`

- **Returns:**
  - `Response`: A response object containing the average delay by vehicle.
  
- **Description:**
  This method calculates the average delay for each vehicle and returns the results.

---

### `public function getContractsGroupedByVehicle()`

- **Returns:**
  - `Response`: A response object containing the contracts grouped by vehicle.
  
- **Description:**
  This method groups contracts by vehicle and returns them in a structured format.

---

### `public function getContractsGroupedByCustomer()`

- **Returns:**
  - `Response`: A response object containing the contracts grouped by customer.
  
- **Description:**
  This method groups contracts by customer and returns them in a structured format.

---

## Error Handling:
Each method in the `AnalyseService` class uses `try-catch` blocks to handle exceptions gracefully. If an exception is thrown, a `Response` object with a 500 status code and an error message is returned. Additionally, invalid inputs (e.g., an invalid `customerUid` or `contractId`) trigger 400 status codes with appropriate error messages.

---

## Example Usage:

```php
$analyseService = new AnalyseService($analyseRepository);

// List ongoing rentals for a customer
$response = $analyseService->listOngoingRentalsByCustomerUid('customer123');

// List late rentals
$response = $analyseService->listLateRentals();

// Get payments for a specific contract
$response = $analyseService->listPaymentsByContractId(1234);

// Check if a rental is fully paid
$response = $analyseService->isRentalFullyPaid(1234);
```
#####################################################
# `BillingService` Class Usage Documentation

**Namespace**: `Lucpa\Service`

## Description:
The `BillingService` class is responsible for managing billing-related operations, such as saving, retrieving, and deleting invoices. It interacts with the `BillingRepository` for handling the data persistence layer. The service also provides functionality to create a table for storing billing information if it doesn't already exist.

## Constructor:

### `public function __construct(BillingRepository $billingRepository)`

- **Parameters:**
  - `BillingRepository $billingRepository`: A repository responsible for performing database operations related to billing data.
  
- **Description:**
  Initializes the `BillingService` with the given `BillingRepository`.

---

## Methods:

### `public function saveBilling($contract_id, $amount, $id = null)`

- **Parameters:**
  - `int $contract_id`: The unique identifier of the rental contract associated with the billing.
  - `float $amount`: The amount to be billed.
  - `int|null $id`: The unique identifier for the billing record (optional, for updating an existing record).
  
- **Returns:**
  - `Response`: A response object with the status code, message, and the saved `Billing` object.

- **Description:**
  This method saves a billing record for a given contract. If the amount is invalid (less than or equal to zero), it returns an error message.

---

### `public function getBillingById($id)`

- **Parameters:**
  - `int $id`: The unique identifier of the billing record.
  
- **Returns:**
  - `Response`: A response object containing the status code, message, and the found `Billing` object (if available).

- **Description:**
  Retrieves a billing record by its unique ID. If no billing record is found, it returns a 404 response indicating that the invoice was not found.

---

### `public function deleteBilling($id)`

- **Parameters:**
  - `int $id`: The unique identifier of the billing record to delete.
  
- **Returns:**
  - `Response`: A response object with the status code and a success message after deletion.

- **Description:**
  Deletes the billing record identified by the given ID. If the operation is successful, a success message is returned.

---

### `public function createTable()`

- **Returns:**
  - `Response`: A response object indicating whether the table creation was successful or if an error occurred.

- **Description:**
  Creates the billing table in the database if it doesn't already exist. If there is an error during the table creation, it returns a 500 error message.

---

## Error Handling:
Each method in the `BillingService` class is wrapped in a `try-catch` block to handle exceptions. If any exception occurs during the execution of a method, a `Response` object is returned with an appropriate error message and status code.

---

## Example Usage:

```php
// Create a new BillingService instance
$billingService = new BillingService($billingRepository);

// Save a new billing record
$response = $billingService->saveBilling(1234, 250.50);

// Get a billing record by ID
$response = $billingService->getBillingById(1);

// Delete a billing record by ID
$response = $billingService->deleteBilling(1);

// Create the billing table (if it does not exist)
$response = $billingService->createTable();
```

#####################################################

# `ContractService` Class Usage Documentation

**Namespace**: `Lucpa\Service`

## Description:
The `ContractService` class is responsible for managing contract-related operations, such as saving, retrieving, deleting contracts, and creating the contract table in the database. It interacts with the `ContractRepository` for performing the data persistence layer operations. The service validates inputs, including vehicle and customer IDs, contract dates, and the contract price.

## Constructor:

### `public function __construct(ContractRepository $contractRepository)`

- **Parameters:**
  - `ContractRepository $contractRepository`: A repository responsible for performing database operations related to contract data.

- **Description:**
  Initializes the `ContractService` with the given `ContractRepository`.

---

## Methods:

### `public function saveContract($vehicleUid, $customerUid, $signDatetime, $locBeginDatetime, $locEndDatetime, $returningDatetime, $price, $id = null)`

- **Parameters:**
  - `string $vehicleUid`: The unique identifier of the vehicle being rented.
  - `string $customerUid`: The unique identifier of the customer renting the vehicle.
  - `string $signDatetime`: The date and time when the contract was signed (format: `Y-m-d H:i:s`).
  - `string $locBeginDatetime`: The date and time when the rental period begins (format: `Y-m-d H:i:s`).
  - `string $locEndDatetime`: The date and time when the rental period ends (format: `Y-m-d H:i:s`).
  - `string $returningDatetime`: The date and time when the vehicle is returned (format: `Y-m-d H:i:s`).
  - `float $price`: The price of the rental contract.
  - `int|null $id`: The unique identifier of the contract (optional, for updating an existing contract).

- **Returns:**
  - `Response`: A response object with the status code, message, and the saved `Contract` object.

- **Description:**
  This method saves a rental contract in the repository. It performs multiple validations, including checking the price, verifying vehicle and customer IDs, and ensuring that the contract dates are valid. If any of the validation checks fail, it returns a 400 error response.

---

### `private function isValidDateTime($dateTime)`

- **Parameters:**
  - `string $dateTime`: A date and time string (format: `Y-m-d H:i:s`).

- **Returns:**
  - `bool`: `true` if the provided date is valid, otherwise `false`.

- **Description:**
  Validates if the provided date and time string is in the correct format (`Y-m-d H:i:s`).

---

### `public function getContractById($id)`

- **Parameters:**
  - `int $id`: The unique identifier of the contract.

- **Returns:**
  - `Response`: A response object with the status code, message, and the found `Contract` object (if available).

- **Description:**
  Retrieves a contract by its unique ID. If the contract is not found, it returns a 404 response indicating the contract was not found.

---

### `public function deleteContract($id)`

- **Parameters:**
  - `int $id`: The unique identifier of the contract to delete.

- **Returns:**
  - `Response`: A response object with the status code and a success message after deletion.

- **Description:**
  Deletes a contract identified by its unique ID. If successful, it returns a success message.

---

### `public function createTable()`

- **Returns:**
  - `Response`: A response object indicating whether the table creation was successful or if an error occurred.

- **Description:**
  Creates the contract table in the database if it doesn't already exist. If an error occurs during table creation, it returns a 500 error message.

---

## Error Handling:
Each method in the `ContractService` class is wrapped in a `try-catch` block to handle exceptions. If any exception occurs during the execution of a method, a `Response` object is returned with an appropriate error message and status code.

---

## Example Usage:

```php
// Create a new ContractService instance
$contractService = new ContractService($contractRepository);

// Save a new contract
$response = $contractService->saveContract('vehicle123', 'customer456', '2025-02-01 10:00:00', '2025-02-01 12:00:00', '2025-02-05 12:00:00', '2025-02-05 14:00:00', 100.50);

// Get a contract by ID
$response = $contractService->getContractById(1);

// Delete a contract by ID
$response = $contractService->deleteContract(1);

// Create the contract table (if it does not exist)
$response = $contractService->createTable();

```

#####################################################

# `CustomerService` Class Usage Documentation

**Namespace**: `Lucpa\Service`

## Description:
The `CustomerService` class is responsible for managing customer-related operations, including saving, retrieving, updating, and deleting customer records. It interacts with the `CustomerRepository` to handle the data persistence layer. The service validates input data and ensures that required fields are provided when performing operations.

## Constructor:

### `public function __construct(CustomerRepository $customerRepository)`

- **Parameters:**
  - `CustomerRepository $customerRepository`: The repository responsible for performing database operations related to customer data.

- **Description:**
  Initializes the `CustomerService` with the provided `CustomerRepository`.

---

## Methods:

### `public function saveCustomer($firstName, $secondName, $address, $permitNumber)`

- **Parameters:**
  - `string $firstName`: The first name of the customer.
  - `string $secondName`: The second name (last name) of the customer.
  - `string $address`: The address of the customer.
  - `string $permitNumber`: The permit number associated with the customer.

- **Returns:**
  - `Response`: A response object with a status code, message, and optional data.

- **Description:**
  This method saves a new customer in the repository. It performs validation to ensure all fields are provided and converts the input data to lowercase before saving. If any required fields are missing, a 400 error is returned.

---

### `public function getCustomerByFullName($firstName, $secondName)`

- **Parameters:**
  - `string $firstName`: The first name of the customer.
  - `string $secondName`: The second name (last name) of the customer.

- **Returns:**
  - `Response`: A response object with the status code, message, and the found `Customer` object (if available).

- **Description:**
  Retrieves a customer by their full name. If the customer is found, a 200 response is returned with the customer data; otherwise, a 404 response indicating the customer was not found.

---

### `public function updateCustomer($id, $firstName, $secondName, $address, $permitNumber)`

- **Parameters:**
  - `int $id`: The unique identifier of the customer to update.
  - `string $firstName`: The updated first name of the customer.
  - `string $secondName`: The updated second name (last name) of the customer.
  - `string $address`: The updated address of the customer.
  - `string $permitNumber`: The updated permit number associated with the customer.

- **Returns:**
  - `Response`: A response object with a status code and message indicating whether the update was successful or the customer was not found.

- **Description:**
  This method updates the customer's information. It performs validation to ensure that all fields are provided and converts the input data to lowercase before updating the record in the repository. If the customer is not found, a 404 response is returned.

---

### `public function deleteCustomer($id)`

- **Parameters:**
  - `int $id`: The unique identifier of the customer to delete.

- **Returns:**
  - `Response`: A response object with a status code and message indicating whether the deletion was successful or the customer was not found.

- **Description:**
  This method deletes a customer by their unique ID. If the customer is found and deleted successfully, a 200 response is returned; otherwise, a 404 response is returned if the customer was not found.

---

## Error Handling:
Each method in the `CustomerService` class includes a `try-catch` block to handle exceptions. If an exception occurs during any method execution, a `Response` object is returned with an appropriate error message and status code.

---

## Example Usage:

```php
// Create a new CustomerService instance
$customerService = new CustomerService($customerRepository);

// Save a new customer
$response = $customerService->saveCustomer('John', 'Doe', '123 Main St', 'PERMIT123');

// Get a customer by full name
$response = $customerService->getCustomerByFullName('John', 'Doe');

// Update an existing customer
$response = $customerService->updateCustomer(1, 'John', 'Doe', '456 Elm St', 'PERMIT456');

// Delete a customer by ID
$response = $customerService->deleteCustomer(1);

```

#####################################################

# `VehicleService` Class Usage Documentation

**Namespace**: `Lucpa\Service`

## Description:
The `VehicleService` class is responsible for managing vehicle-related operations, such as saving, retrieving, updating, counting, and deleting vehicles. It interacts with the `VehicleRepository` for database operations. Input validation is done before performing operations on the vehicle data.

## Constructor:

### `public function __construct(VehicleRepository $vehicleRepository)`

- **Parameters:**
  - `VehicleRepository $vehicleRepository`: The repository responsible for performing database operations related to vehicle data.

- **Description:**
  Initializes the `VehicleService` with the provided `VehicleRepository`.

---

## Methods:

### `public function saveVehicle($model, $licencePlate, $informations, $km)`

- **Parameters:**
  - `string $model`: The model of the vehicle.
  - `string $licencePlate`: The vehicle's license plate number.
  - `string $informations`: Additional information about the vehicle (optional).
  - `int $km`: The mileage (kilometers) of the vehicle.

- **Returns:**
  - `Response`: A response object with a status code, message, and optional data.

- **Description:**
  Saves a new vehicle in the repository. The method performs input validation to ensure that the model, license plate, and mileage are correct. If any required field is invalid, a 400 error response is returned.

---

### `public function getVehicleByLicencePlate($licencePlate)`

- **Parameters:**
  - `string $licencePlate`: The vehicle's license plate number.

- **Returns:**
  - `Response`: A response object with the status code, message, and the found `Vehicle` object (if available).

- **Description:**
  Retrieves a vehicle by its license plate. If the vehicle is found, a 200 response is returned with the vehicle data; otherwise, a 404 response indicating the vehicle was not found.

---

### `public function updateVehicle($id, $model, $licencePlate, $informations, $km)`

- **Parameters:**
  - `int $id`: The unique identifier of the vehicle to update.
  - `string $model`: The updated model of the vehicle.
  - `string $licencePlate`: The updated license plate number.
  - `string $informations`: The updated vehicle information (optional).
  - `int $km`: The updated mileage of the vehicle.

- **Returns:**
  - `Response`: A response object with a status code and message indicating whether the update was successful or the vehicle was not found.

- **Description:**
  Updates an existing vehicle. This method checks if all fields are valid before updating the vehicle's information. If the vehicle is not found, a 404 response is returned.

---

### `public function countVehiclesWithMoreThanKm($km)`

- **Parameters:**
  - `int $km`: The mileage threshold to count vehicles that have more kilometers than the provided value.

- **Returns:**
  - `Response`: A response object with the status code, message, and the count of vehicles that exceed the given mileage.

- **Description:**
  Counts the number of vehicles that have more kilometers than the specified value. A 200 response with the count is returned, or a 400 error if the provided mileage is invalid.

---

### `public function deleteVehicle($id)`

- **Parameters:**
  - `int $id`: The unique identifier of the vehicle to delete.

- **Returns:**
  - `Response`: A response object with a status code and message indicating whether the deletion was successful or the vehicle was not found.

- **Description:**
  Deletes a vehicle from the repository by its unique ID. A 200 response is returned if the vehicle was deleted successfully, otherwise, a 404 response is returned if the vehicle was not found.

---

## Error Handling:
Each method in the `VehicleService` class includes a `try-catch` block to handle exceptions. If an exception occurs, a `Response` object is returned with an appropriate error message and status code.

---

## Example Usage:

```php
// Create a new VehicleService instance
$vehicleService = new VehicleService($vehicleRepository);

// Save a new vehicle
$response = $vehicleService->saveVehicle('Toyota Corolla', 'AB123CD', 'Blue sedan, 4 doors', 15000);

// Get a vehicle by license plate
$response = $vehicleService->getVehicleByLicencePlate('AB123CD');

// Update an existing vehicle
$response = $vehicleService->updateVehicle(1, 'Honda Civic', 'XY456ZT', 'Red sedan, 4 doors', 20000);

// Count vehicles with more than 10000 km
$response = $vehicleService->countVehiclesWithMoreThanKm(10000);

// Delete a vehicle by ID
$response = $vehicleService->deleteVehicle(1);

```

#####################################################

# Usage exemples for all methods

```
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

$mongoClient = ModelDatabase::connectMongoDB();
$pdo = ModelDatabase::connectMySQL();
$pdo2 = ModelDatabase::connectPostgreSQL();

// Initialisation repository and service
$customerRepository = new CustomerRepository($mongoClient);
$customerService = new CustomerService($customerRepository);

$billingRepository = new BillingRepository($pdo);
$billingService = new BillingService($billingRepository);

$contractRepository = new ContractRepository($pdo);
$contractService = new ContractService($contractRepository);

$vehicleRepository = new VehicleRepository($mongoClient);
$vehicleService = new VehicleService($vehicleRepository);

$analyseRepository = new AnalyseRepository($pdo);
$analyseService = new AnalyseService($analyseRepository);


// Example usage of saveCustomer
$response = $customerService->saveCustomer('John', 'Doe', '1234 Elm Street', 'P12345678');


// Example usage of getCustomerByFullName
$response = $customerService->getCustomerByFullName('John', 'Doe');


// Example usage of updateCustomer
$response = $customerService->updateCustomer(1, 'John', 'Smith', '5678 Oak Avenue', 'P87654321');


// Example usage of saveVehicle
$response = $vehicleService->saveVehicle('Toyota Corolla', 'ABC1234', 'Sedan, 5 doors', 15000);


// Example usage of getVehicleByLicencePlate
$response = $vehicleService->getVehicleByLicencePlate('ABC1234');


// Example usage of updateVehicle
$response = $vehicleService->updateVehicle(1, 'Honda Civic', 'XYZ9876', 'Coupe, 2 doors', 25000);


// Example usage of countVehiclesWithMoreThanKm
$response = $vehicleService->countVehiclesWithMoreThanKm(20000);


// Example usage of deleteVehicle
$response = $vehicleService->deleteVehicle(1);


// Example usage of saveContract
$response = $contractService->saveContract(
    'V001',           // vehicleUid
    'C003',           // customerUid
    '2025-02-11 10:00:00', // signDatetime
    '2025-02-11 14:00:00', // locBeginDatetime
    '2025-02-12 14:00:00', // locEndDatetime
    '2025-02-12 15:00:00', // returningDatetime
    100.00            // price
);


// Example usage of getContractById
$response = $contractService->getContractById(1); // Use an actual contract ID


// Example usage of deleteContract
$response = $contractService->deleteContract(1); // Use an actual contract ID


// Example usage of createTable (if you need to create the contracts table)
$response = $contractService->createTable();


// Example usage of saveBilling
$response = $billingService->saveBilling(
    1,       // contract_id (use an actual contract ID)
    200.50   // amount (use an actual amount greater than 0)
);


// Example usage of getBillingById
$response = $billingService->getBillingById(1); // Use an actual billing ID


// Example usage of deleteBilling
$response = $billingService->deleteBilling(1); // Use an actual billing ID


// Example usage of createTable (if you need to create the billing table)
$response = $billingService->createTable();


// Example usage: List ongoing rentals by customer UID
$customerUid = 'customer123';
$response = $analyseService->listOngoingRentalsByCustomerUid($customerUid);

// Example usage: List late rentals
$response = $analyseService->listLateRentals();

// Example usage: List payments by contract ID
$contractId = 1;
$response = $analyseService->listPaymentsByContractId($contractId);

// Example usage: Check if rental is fully paid
$contractId = 2;
$response = $analyseService->isRentalFullyPaid($contractId);

// Example usage: List unpaid rentals
$response = $analyseService->listUnpaidRentals();

// Example usage: Count late rentals between dates
$startDate = '2025-01-01';
$endDate = '2025-01-31';
$response = $analyseService->countLateRentalsBetweenDates($startDate, $endDate);

// Example usage: Count average late rentals per customer
$response = $analyseService->countAverageLateRentalsPerCustomer();

// Example usage: List contracts by vehicle UID
$vehicleUid = 'vehicle123';
$response = $analyseService->listContractsByVehicleUid($vehicleUid);

// Example usage: Get average delay by vehicle
$response = $analyseService->getAverageDelayByVehicle();

// Example usage: Get contracts grouped by vehicle
$response = $analyseService->getContractsGroupedByVehicle();

// Example usage: Get contracts grouped by customer
$response = $analyseService->getContractsGroupedByCustomer();

// display Json response
echo ($response->toJson());
```

# development notes
## set up

in xampp htdocs create folder App1

see how to make virtualhost in welcome page

http://localhost/dashboard/docs/configure-vhosts.html

## init git repository

add .gitignore

    vendor
    .env

## composer

    composer init

add/update autoload script in composer.json

    "autoload": {
    "psr-4": {
      "Lucpa\\Model\\": "src/Model/",
      "Lucpa\\Service\\": "src/Service/",
      "Lucpa\\Repository\\": "src/Repository/"
    }

this is to match this structure:

    scr/  
        model/
        repository/
        service/
    .env
    index.php

## mongoDb

    composer require mongodb/mongodb

## dotEnv

    composer require vlucas/phpdotenv

remember to load env variables:

    require_once 'vendor/autoload.php';
    Dotenv\Dotenv::createImmutable(__DIR__)->load();