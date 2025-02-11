<?php

namespace Lucpa\Repository;

use Lucpa\Model\Vehicle;

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
                    ['id' => $vehicle->getId()],
                    ['$set' => [
                        'model' => $vehicle->getModel(),
                        'licence_plate' => $vehicle->getLicencePlate(),
                        'informations' => $vehicle->getInformations(),
                        'km' => $vehicle->getKm()
                    ]]
                );
            } else {
                $lastVehicle = $this->collection->find([], ['sort' => ['id' => -1], 'limit' => 1])->toArray();
                $newId = (count($lastVehicle) > 0) ? $lastVehicle[0]['id'] + 1 : 1;

                $result = $this->collection->insertOne([
                    'id' => $newId,
                    'model' => $vehicle->getModel(),
                    'licence_plate' => $vehicle->getLicencePlate(),
                    'informations' => $vehicle->getInformations(),
                    'km' => $vehicle->getKm()
                ]);

                $vehicle->setId($newId);
            }
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de l'enregistrement du véhicule : " . $e->getMessage());
        }
    }

    public function updateVehicle($id, $model, $licencePlate, $informations, $km) {
        try {
            $result = $this->collection->updateOne(
                ['id' => (int)$id],
                ['$set' => [
                    'model' => $model,
                    'licence_plate' => $licencePlate,
                    'informations' => $informations,
                    'km' => $km
                ]]
            );

            if ($result->getModifiedCount() > 0) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la mise à jour du véhicule : " . $e->getMessage());
        }
    }

    public function delete($id) {
        try {
            $result = $this->collection->deleteOne(['id' => (int)$id]);
            return $result->getDeletedCount() > 0;
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la suppression du véhicule : " . $e->getMessage());
        }
    }
}
