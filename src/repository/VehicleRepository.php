<?php
namespace Lucpa\Repository;

use Lucpa\Model\Vehicle;

class VehicleRepository {
    private $mongoClient;
    private $collection;

    // Constructor that initializes the MongoDB connection and selects the collection
    public function __construct($mongoClient) {
        $this->mongoClient = $mongoClient;
        $this->collection = $this->mongoClient->easyloc->vehicles;  // Name of the collection
    }

    // Method to get a vehicle by its model
    public function getByModel($model) {
        try {
            // Search in the collection
            $vehicle = $this->collection->findOne(['model' => $model]);
            
            if ($vehicle) {
                return new Vehicle((string)$vehicle['_id'], $vehicle['model']);
            }
            
            return null;  // If not found
        } catch (\Exception $e) {
            // Handle other errors
            throw new \Exception("An error occurred while retrieving the vehicle: " . $e->getMessage());
        }
    }

    // Method to save a vehicle
    public function save(Vehicle $vehicle) {
        try {
            if ($vehicle->getId()) {
                // Update existing vehicle
                $this->collection->updateOne(
                    ['id' => $vehicle->getId()],
                    ['$set' => ['model' => $vehicle->getModel()]]
                );
            } else {
                // Insert new vehicle with an auto-increment ID
                // You would likely use some form of auto-increment logic (not shown here) or a database feature to generate unique IDs
                $lastVehicle = $this->collection->find([], ['sort' => ['id' => -1], 'limit' => 1])->toArray();
                $newId = (count($lastVehicle) > 0) ? $lastVehicle[0]['id'] + 1 : 1; // Get next available ID

                $result = $this->collection->insertOne([
                    'id' => $newId,
                    'model' => $vehicle->getModel()
                ]);

                $vehicle->setId($newId);  // Set the ID for the new vehicle
            }
        } catch (\Exception $e) {
            throw new \Exception("Error saving vehicle: " . $e->getMessage());
        }
    }

    // Method to update the vehicle's model
    public function updateModel($id, $newModel) {
        try {
            // Update the vehicle's model by its ID
            $result = $this->collection->updateOne(
                ['id' => (int)$id], // Search by vehicle ID
                ['$set' => ['model' => $newModel]] // Set the new model
            );

            // Check if a vehicle was updated
            if ($result->getModifiedCount() > 0) {
                return true; // Successfully updated
            }

            return false; // No vehicle updated (could be because the ID doesn't exist)
        } catch (\Exception $e) {
            throw new \Exception("Error updating vehicle model: " . $e->getMessage());
        }
    }

    // Method to delete a vehicle by ID
    public function delete($id) {
        try {
            // Delete a vehicle by ID
            $result = $this->collection->deleteOne(['id' => (int)$id]); // Ensure ID is cast to integer

            return $result->getDeletedCount() > 0; // Returns true if a vehicle was deleted
        } catch (\Exception $e) {
            throw new \Exception("Error deleting vehicle: " . $e->getMessage());
        }
    }
}
