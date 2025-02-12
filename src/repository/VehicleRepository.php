<?php

namespace Lucpa\Repository;

use Lucpa\Model\Vehicle;
use MongoDB\BSON\ObjectId;

class VehicleRepository {
    private $mongoClient;
    private $collection;

    public function __construct($mongoClient) {
        $this->mongoClient = $mongoClient;
        $this->collection = $this->mongoClient->easyloc->vehicles;
    }

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
            
            return null;
        } catch (\Exception $e) {
            throw new \Exception("Une erreur s'est produite lors de la récupération du véhicule : " . $e->getMessage());
        }
    }

    public function countVehiclesWithMoreThanKm($km) {
        try {
            $count = $this->collection->countDocuments(['km' => ['$gt' => $km]]);
            return $count;
        } catch (\Exception $e) {
            throw new \Exception("Une erreur s'est produite lors du comptage des véhicules : " . $e->getMessage());
        }
    }

    public function save(Vehicle $vehicle) {
        try {
            if ($vehicle->getId()) {
                $this->collection->updateOne(
                    ['_id' => new ObjectId($vehicle->getId())],
                    ['$set' => [
                        'model' => $vehicle->getModel(),
                        'licence_plate' => $vehicle->getLicencePlate(),
                        'informations' => $vehicle->getInformations(),
                        'km' => $vehicle->getKm()
                    ]]
                );
            } else {
                $result = $this->collection->insertOne([
                    'model' => $vehicle->getModel(),
                    'licence_plate' => $vehicle->getLicencePlate(),
                    'informations' => $vehicle->getInformations(),
                    'km' => $vehicle->getKm()
                ]);
                
                $vehicle->setId((string)$result->getInsertedId());
            }
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de l'enregistrement du véhicule : " . $e->getMessage());
        }
    }

    public function updateVehicle($id, $model, $licencePlate, $informations, $km) {
        try {
            $result = $this->collection->updateOne(
                ['_id' => new ObjectId($id)],
                ['$set' => [
                    'model' => $model,
                    'licence_plate' => $licencePlate,
                    'informations' => $informations,
                    'km' => $km
                ]]
            );

            return $result->getModifiedCount() > 0;
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la mise à jour du véhicule : " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $result = $this->collection->deleteOne(['_id' => new ObjectId($id)]);
            return $result->getDeletedCount() > 0;
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la suppression du véhicule : " . $e->getMessage());
        }
    }
}
