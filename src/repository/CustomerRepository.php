<?php
namespace Lucpa\Repository;

use Lucpa\Model\Customer;

class CustomerRepository {
    private $mongoClient;
    private $collection;

    // Constructeur qui initialise la connexion MongoDB et sélectionne la collection
    public function __construct($mongoClient) {
        $this->mongoClient = $mongoClient;
        $this->collection = $this->mongoClient->easyloc->customers;  // Nom de la collection
    }

    // Méthode pour obtenir un client par nom
    public function getByName($name) {
        try {
            // Recherche dans la collection
            $customer = $this->collection->findOne(['name' => $name]);
            
            if ($customer) {
                return new Customer((string)$customer['_id'], $customer['name']);
            }
            
            return null;  // Si pas trouvé
        }catch (\Exception $e) {
            // Gestion des autres erreurs
            throw new \Exception("Une erreur est survenue lors de la récupération du client: " . $e->getMessage());
        }
    }

    public function save(Customer $customer) {
        try {
            if ($customer->getId()) {
                // Update the existing customer
                $this->collection->updateOne(
                    ['id' => $customer->getId()],
                    ['$set' => ['name' => $customer->getName()]]
                );
            } else {
                // Insert new customer with an auto-increment ID
                // You would likely use some form of auto-increment logic (not shown here) or a database feature to generate unique IDs
                $lastCustomer = $this->collection->find([], ['sort' => ['id' => -1], 'limit' => 1])->toArray();
                $newId = (count($lastCustomer) > 0) ? $lastCustomer[0]['id'] + 1 : 1; // Get next available ID

                $result = $this->collection->insertOne([
                    'id' => $newId,
                    'name' => $customer->getName()
                ]);

                $customer->setId($newId);  // Set the ID for the new customer
            }
        } catch (\Exception $e) {
            throw new \Exception("Error saving customer: " . $e->getMessage());
        }
    }

    // Method to update the customer name
    public function updateName($id, $newName) {
        try {
            // Update the customer's name by their ID
            $result = $this->collection->updateOne(
                ['id' => (int)$id], // Search by customer ID
                ['$set' => ['name' => $newName]] // Set the new name
            );

            // Check if a customer was updated
            if ($result->getModifiedCount() > 0) {
                return true; // Successfully updated
            }

            return false; // No customer updated (could be because the ID doesn't exist)
        } catch (\Exception $e) {
            throw new \Exception("Error updating customer name: " . $e->getMessage());
        }
    }

    // Method to delete a customer by ID
    public function delete($id) {
        try {
            // Delete a customer by ID
            $result = $this->collection->deleteOne(['id' => (int)$id]); // Ensure ID is cast to integer

            return $result->getDeletedCount() > 0; // Returns true if a customer was deleted
        } catch (\Exception $e) {
            throw new \Exception("Error deleting customer: " . $e->getMessage());
        }
    }
}


