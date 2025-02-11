<?php
namespace Lucpa\Repository;

use Lucpa\Model\Customer;

class CustomerRepository {
    private $mongoClient;
    private $collection;

    // Constructor to initialize MongoDB connection
    public function __construct($mongoClient) {
        $this->mongoClient = $mongoClient;
        $this->collection = $this->mongoClient->easyloc->customers;  // MongoDB collection name
    }

    // Get customer by first name and second name
    public function getByFullName($firstName, $secondName) {
        try {
            // Search for customer by first and second name
            $customer = $this->collection->findOne([
                'first_name' => $firstName,
                'second_name' => $secondName
            ]);

            if ($customer) {
                return new Customer(
                    (int)$customer['id'],  // Treat id as integer
                    $customer['first_name'], 
                    $customer['second_name'], 
                    $customer['address'], 
                    $customer['permit_number']
                );
            }

            return null;  // If no customer is found
        } catch (\Exception $e) {
            throw new \Exception("Error retrieving customer by name: " . $e->getMessage());
        }
    }

    // Save customer to MongoDB
    public function save(Customer $customer) {
        try {
            if ($customer->getId()) {
                // Update existing customer by integer ID
                $this->collection->updateOne(
                    ['id' => $customer->getId()],
                    ['$set' => [
                        'first_name' => $customer->getFirstName(),
                        'second_name' => $customer->getSecondName(),
                        'address' => $customer->getAddress(),
                        'permit_number' => $customer->getPermitNumber()
                    ]]
                );
            } else {
                // Insert new customer, calculate new integer ID
                $lastCustomer = $this->collection->find([], ['sort' => ['id' => -1], 'limit' => 1])->toArray();
                $newId = (count($lastCustomer) > 0) ? $lastCustomer[0]['id'] + 1 : 1; // Get next available ID

                $result = $this->collection->insertOne([
                    'id' => $newId,
                    'first_name' => $customer->getFirstName(),
                    'second_name' => $customer->getSecondName(),
                    'address' => $customer->getAddress(),
                    'permit_number' => $customer->getPermitNumber()
                ]);

                // Set the integer ID for the new customer
                $customer->setId($newId);
            }
        } catch (\Exception $e) {
            throw new \Exception("Error saving customer: " . $e->getMessage());
        }
    }

    // Update customer details by ID
    public function update($id, $firstName, $secondName, $address, $permitNumber) {
        try {
            // Update the customer using the integer ID
            $result = $this->collection->updateOne(
                ['id' => (int)$id],  // Ensure ID is treated as an integer
                ['$set' => [
                    'first_name' => $firstName,
                    'second_name' => $secondName,
                    'address' => $address,
                    'permit_number' => $permitNumber
                ]]
            );

            return $result->getModifiedCount() > 0;  // True if any documents were modified
        } catch (\Exception $e) {
            throw new \Exception("Error updating customer: " . $e->getMessage());
        }
    }

    // Delete a customer by ID
    public function delete($id) {
        try {
            // Delete customer by ID, ensure ID is treated as integer
            $result = $this->collection->deleteOne(['id' => (int)$id]);

            return $result->getDeletedCount() > 0;  // Returns true if a customer was deleted
        } catch (\Exception $e) {
            throw new \Exception("Error deleting customer: " . $e->getMessage());
        }
    }
}
