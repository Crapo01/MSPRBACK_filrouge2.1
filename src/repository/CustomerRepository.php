<?php

namespace Lucpa\Repository;

use Lucpa\Model\Customer;
use MongoDB\BSON\ObjectId;

class CustomerRepository {
    private $mongoClient;
    private $collection;

    public function __construct($mongoClient) {
        $this->mongoClient = $mongoClient;
        $this->collection = $this->mongoClient->easyloc->customers;
    }

    public function getByFullName($firstName, $secondName) {
        try {
            $customer = $this->collection->findOne([
                'first_name' => $firstName,
                'second_name' => $secondName
            ]);

            if ($customer) {
                return new Customer(
                    (string)$customer['_id'],
                    $customer['first_name'], 
                    $customer['second_name'], 
                    $customer['address'], 
                    $customer['permit_number']
                );
            }

            return null;
        } catch (\Exception $e) {
            throw new \Exception("Error retrieving customer by name: " . $e->getMessage());
        }
    }

    public function save(Customer $customer) {
        try {
            if ($customer->getId()) {
                $this->collection->updateOne(
                    ['_id' => new ObjectId($customer->getId())],
                    ['$set' => [
                        'first_name' => $customer->getFirstName(),
                        'second_name' => $customer->getSecondName(),
                        'address' => $customer->getAddress(),
                        'permit_number' => $customer->getPermitNumber()
                    ]]
                );
            } else {
                $result = $this->collection->insertOne([
                    'first_name' => $customer->getFirstName(),
                    'second_name' => $customer->getSecondName(),
                    'address' => $customer->getAddress(),
                    'permit_number' => $customer->getPermitNumber()
                ]);

                $customer->setId((string)$result->getInsertedId());
            }
        } catch (\Exception $e) {
            throw new \Exception("Error saving customer: " . $e->getMessage());
        }
    }

    public function update($id, $firstName, $secondName, $address, $permitNumber) {
        try {
            $result = $this->collection->updateOne(
                ['_id' => new ObjectId($id)],
                ['$set' => [
                    'first_name' => $firstName,
                    'second_name' => $secondName,
                    'address' => $address,
                    'permit_number' => $permitNumber
                ]]
            );

            return $result->getModifiedCount() > 0;
        } catch (\Exception $e) {
            throw new \Exception("Error updating customer: " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $result = $this->collection->deleteOne(['_id' => new ObjectId($id)]);

            return $result->getDeletedCount() > 0;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting customer: " . $e->getMessage());
        }
    }
}