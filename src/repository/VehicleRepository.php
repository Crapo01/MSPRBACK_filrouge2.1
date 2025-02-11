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

    // Method to get a vehicle by its licence_plate
    public function getByLicencePlate($licencePlate) {
        try {
            $vehicle = $this->collection->findOne(['licence_plate' => $licencePlate]);
            
            if ($vehicle) {
                return new Vehicle(
                    (string)$vehicle['_id'], 
                    $vehicle['model'],
                    $vehicle['licence_plate'],
                    $vehicle['informations'],
                    $vehicle['km']
                );
            }
            
            return null;  // If not found
        } catch (\Exception $e) {
            throw new \Exception("An error occurred while retrieving the vehicle: " . $e->getMessage());
        }
    }

    // Method to count vehicles with more than a specified km
    public function countVehiclesWithMoreThanKm($km) {
        try {
            $count = $this->collection->countDocuments(['km' => ['$gt' => $km]]);
            return $count;
        } catch (\Exception $e) {
            throw new \Exception("An error occurred while counting vehicles: " . $e->getMessage());
        }
    }

    // Method to save a vehicle
    public function save(Vehicle $vehicle) {
        try {
            if ($vehicle->getId()) {
                // Update existing vehicle
                $this->collection->updateOne(
                    ['id' => $vehicle->getId()],
                    ['$set' => [
                        'model' => $vehicle->getModel(),
                        'licence_plate' => $vehicle->getLicencePlate(),
                        'informations' => $vehicle->getInformations(),
                        'km' => $vehicle->getKm()
                    ]]
                );
            } else {
                // Insert new vehicle with an auto-increment ID
                $lastVehicle = $this->collection->find([], ['sort' => ['id' => -1], 'limit' => 1])->toArray();
                $newId = (count($lastVehicle) > 0) ? $lastVehicle[0]['id'] + 1 : 1; // Get next available ID

                $result = $this->collection->insertOne([
                    'id' => $newId,
                    'model' => $vehicle->getModel(),
                    'licence_plate' => $vehicle->getLicencePlate(),
                    'informations' => $vehicle->getInformations(),
                    'km' => $vehicle->getKm()
                ]);

                $vehicle->setId($newId);  // Set the ID for the new vehicle
            }
        } catch (\Exception $e) {
            throw new \Exception("Error saving vehicle: " . $e->getMessage());
        }
    }

    // Method to update a vehicle's details
    public function updateVehicle($id, $model, $licencePlate, $informations, $km) {
        try {
            // Update the vehicle's details by its ID
            $result = $this->collection->updateOne(
                ['id' => (int)$id],
                ['$set' => [
                    'model' => $model,
                    'licence_plate' => $licencePlate,
                    'informations' => $informations,
                    'km' => $km
                ]]
            );

            // Check if a vehicle was updated
            if ($result->getModifiedCount() > 0) {
                return true; // Successfully updated
            }

            return false; // No vehicle updated (could be because the ID doesn't exist)
        } catch (\Exception $e) {
            throw new \Exception("Error updating vehicle: " . $e->getMessage());
        }
    }

    // Method to delete a vehicle by ID
    public function delete($id) {
        try {
            // Delete a vehicle by ID
            $result = $this->collection->deleteOne(['id' => (int)$id]);
            return $result->getDeletedCount() > 0; 
        } catch (\Exception $e) {
            throw new \Exception("Error deleting vehicle: " . $e->getMessage());
        }
    }
}
