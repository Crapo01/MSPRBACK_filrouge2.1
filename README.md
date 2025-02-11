# App1: DATABASE ACCESS LIBRARY

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

# API documentation: 

## BillingService Class 
### Overview

The BillingService class handles operations related to billing records, including saving, retrieving, deleting, and listing billings. It interacts with the BillingRepository to manage data persistence.  
### Constructor
__construct(BillingRepository $billingRepository)

The constructor initializes the service with the provided BillingRepository.
Parameters:

    BillingRepository $billingRepository: An instance of the BillingRepository to interact with the database.

Example Usage:

    $billingRepository = new BillingRepository($pdo);
    $billingService = new BillingService($billingRepository);

## Methods
### saveBilling($amount, $billing_date, $id = null)

This method saves a new billing or updates an existing one. It validates the inputs and calls the repository to persist the data.
Parameters:

    float $amount: The amount for the billing (must be greater than 0).
    string $billing_date: The date when the billing was issued (must be provided).
    int|null $id: The unique identifier for the billing (optional, required for updating an existing billing).

Returns:

    Response: A response object containing a status code, message, and optionally, the billing data.

Example Usage:

$response = $billingService->saveBilling(200.50, '2025-02-10');  
echo $response->getMessage();  // "Facture enregistrée avec succès."

### getBillingById($id)

This method retrieves a billing record by its ID.
Parameters:

    int $id: The unique identifier of the billing record.

Returns:

    Response: A response object with status code and billing data if found.

Example Usage:

$response = $billingService->getBillingById(1);  
echo $response->getMessage();  // "Facture trouvée."

### deleteBilling($id)

This method deletes a billing record by its ID.
Parameters:

    int $id: The unique identifier of the billing record to be deleted.

Returns:

    Response: A response object indicating the result of the deletion operation.

Example Usage:

$response = $billingService->deleteBilling(1);  
echo $response->getMessage();  // "Facture supprimée avec succès."

### getAllBillings()

This method retrieves all billing records.
Returns:

    Response: A response object containing a list of all billing records.

Example Usage:

$response = $billingService->getAllBillings();  
echo $response->getMessage();  // "Liste des factures récupérée."


###########################################################################

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



#######################################################################################

## ContractService Class
### Overview

The ContractService class provides operations for handling contract records, including saving, retrieving, and deleting contracts. It interacts with the ContractRepository for data persistence.
Constructor
__construct(ContractRepository $contractRepository)

The constructor initializes the service with the provided ContractRepository.

Parameters:

    ContractRepository $contractRepository: An instance of the ContractRepository to interact with the database.

Example Usage:

$contractRepository = new ContractRepository($pdo);
$contractService = new ContractService($contractRepository);

### Methods
### saveContract($amount, $id = null)

This method saves a new contract or updates an existing one. It validates the input and calls the repository to persist the data.

Parameters:

    float $amount: The amount associated with the contract (must be greater than 0).
    int|null $id: The unique identifier of the contract (optional, required if updating an existing contract).

Returns:

    Response: A response object containing a status code, message, and optionally, the contract data.

Example Usage:

$response = $contractService->saveContract(1000.50);  
echo $response->getMessage();  // "Contrat enregistré avec succès."

### getContractById($id)

This method retrieves a contract record by its ID.

Parameters:

    int $id: The unique identifier of the contract record.

Returns:

    Response: A response object with a status code and the contract data if found.

Example Usage:

$response = $contractService->getContractById(1);  
echo $response->getMessage();  // "Contrat trouvé."

### deleteContract($id)

This method deletes a contract record by its ID.

Parameters:

    int $id: The unique identifier of the contract to be deleted.

Returns:

    Response: A response object indicating the result of the deletion operation.

Example Usage:

$response = $contractService->deleteContract(1);  
echo $response->getMessage();  // "Contrat supprimé avec succès."

#############################################################################
## CustomerService Class
### Overview

The CustomerService class provides operations for handling customer records, including saving, retrieving, updating, and deleting customers. It interacts with the CustomerRepository to persist data in a MongoDB database.
Constructor
__construct(CustomerRepository $customerRepository)

The constructor initializes the service with the provided CustomerRepository.

Parameters:

    CustomerRepository $customerRepository: An instance of the CustomerRepository to interact with the MongoDB database.

Example Usage:

$customerRepository = new CustomerRepository($mongoClient);
$customerService = new CustomerService($customerRepository);

## Methods
### saveCustomer($name)

This method saves a new customer. It validates the input and calls the repository to persist the data.

Parameters:

    string $name: The name of the customer (must not be empty).

Returns:

    Response: A response object containing a status code, message, and optionally the customer data.

Example Usage:

$response = $customerService->saveCustomer("John Doe");  
echo $response->getMessage();  // "Client enregistré avec succès."

### getCustomerByName($name)

This method retrieves a customer record by their name.

Parameters:

    string $name: The name of the customer (must not be empty).

Returns:

    Response: A response object with a status code and customer data if found.

Example Usage:

$response = $customerService->getCustomerByName("John Doe");  
echo $response->getMessage();  // "Client trouvé."

### updateCustomerName($id, $newName)

This method updates the name of an existing customer.

Parameters:

    string $id: The unique identifier (MongoDB ObjectID) of the customer to be updated.
    string $newName: The new name for the customer (must not be empty).

Returns:

    Response: A response object indicating the result of the update operation.

Example Usage:

$response = $customerService->updateCustomerName('606c72ef6e46926f8c6f65bb', "Jane Doe");  
echo $response->getMessage();  // "Nom du client mis à jour avec succès."

### deleteCustomer($id)

This method deletes a customer record by their MongoDB ObjectID.

Parameters:

    string $id: The unique identifier (MongoDB ObjectID) of the customer to be deleted.

Returns:

    Response: A response object indicating the result of the deletion operation.

Example Usage:

$response = $customerService->deleteCustomer('606c72ef6e46926f8c6f65bb');  
echo $response->getMessage();  // "Client supprimé avec succès."

####################################################################################

## VehicleService Class 

The VehicleService class handles the business logic for managing vehicle records. It interacts with the VehicleRepository, which handles the MongoDB operations. This service ensures validation, error handling, and response structuring for operations like saving, retrieving, updating, and deleting vehicle records.
Constructor
__construct(VehicleRepository $vehicleRepository)

This constructor initializes the service by injecting the VehicleRepository. The repository is responsible for MongoDB database interactions (like saving and retrieving data). The id of each vehicle is handled as an auto-increment integer.

Parameters:

    VehicleRepository $vehicleRepository: An instance of the VehicleRepository to handle the persistence layer for vehicle data.

Example Usage:

$vehicleRepository = new VehicleRepository($mongoClient);
$vehicleService = new VehicleService($vehicleRepository);

## Methods
### saveVehicle($model)

This method saves a new vehicle document to MongoDB. It validates the provided model and creates a new Vehicle instance. The VehicleRepository handles persisting the vehicle in MongoDB with an auto-increment integer id.

Parameters:

    string $model: The model of the vehicle (must not be empty).

Returns:

    Response: A response object with the status code and message.

Example Usage:

$response = $vehicleService->saveVehicle("Toyota Corolla");  
echo $response->getMessage();  // "Véhicule enregistré avec succès."

### getVehicleByModel($model)

This method retrieves a vehicle from MongoDB based on its model.

Parameters:

    string $model: The model of the vehicle (must not be empty).

Returns:

    Response: A response object containing a status code and either the found vehicle data or an error message if the vehicle is not found.

Example Usage:

$response = $vehicleService->getVehicleByModel("Toyota Corolla");  
echo $response->getMessage();  // "Véhicule trouvé."

### updateVehicleModel($id, $newModel)

This method updates the model of an existing vehicle in MongoDB. The vehicle is identified by its unique auto-increment integer id.

Parameters:

    int $id: The unique identifier of the vehicle (auto-increment integer).
    string $newModel: The new model to update the vehicle with (must not be empty).

Returns:

    Response: A response object indicating success or failure of the update operation.

Example Usage:

$response = $vehicleService->updateVehicleModel(5, "Honda Civic");  
echo $response->getMessage();  // "Modèle du véhicule mis à jour avec succès."

### deleteVehicle($id)

This method deletes a vehicle document from MongoDB using the vehicle’s unique auto-increment integer id.

Parameters:

    int $id: The unique identifier of the vehicle (auto-increment integer).

Returns:

    Response: A response object indicating whether the deletion was successful or if the vehicle was not found.

Example Usage:

$response = $vehicleService->deleteVehicle(5);  
echo $response->getMessage();  // "Véhicule supprimé avec succès."


