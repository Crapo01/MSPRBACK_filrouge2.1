<?php
namespace Lucpa\Service;

use Lucpa\Model\Vehicle;
use Lucpa\Repository\VehicleRepository;

class VehicleService {
    private $vehicleRepository;

    // Constructor that receives the repository
    public function __construct(VehicleRepository $vehicleRepository) {
        $this->vehicleRepository = $vehicleRepository;
    }

    // Method to save a vehicle
    public function saveVehicle($model, $licencePlate, $informations, $km) {
        try {
            // Validation
            if (empty($model) || empty($licencePlate)) {
                return new Response(400, "Le modèle ou l'immatriculation ne peut pas être vide.");
            }

            $vehicle = new Vehicle(null, $model, $licencePlate, $informations, $km);  

            // Save in the database
            $this->vehicleRepository->save($vehicle);
            
            return new Response(201, "Véhicule enregistré avec succès.");
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de l'enregistrement du véhicule: " . $e->getMessage());
        }
    }

    // Method to retrieve a vehicle by its licence_plate
    public function getVehicleByLicencePlate($licencePlate) {
        try {
            if (empty($licencePlate)) {
                return new Response(400, "L'immatriculation ne peut pas être vide.");
            }

            $vehicle = $this->vehicleRepository->getByLicencePlate($licencePlate);

            if ($vehicle) {
                return new Response(200, "Véhicule trouvé.", $vehicle);
            } else {
                return new Response(404, "Véhicule non trouvé.");
            }
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la récupération du véhicule: " . $e->getMessage());
        }
    }

    // Method to update a vehicle's full details
    public function updateVehicle($id, $model, $licencePlate, $informations, $km) {
        try {
            if (empty($model) || empty($licencePlate)) {
                return new Response(400, "Le modèle ou l'immatriculation ne peut pas être vide.");
            }

            $result = $this->vehicleRepository->updateVehicle($id, $model, $licencePlate, $informations, $km);

            if ($result) {
                return new Response(200, "Véhicule mis à jour avec succès.");
            } else {
                return new Response(404, "Véhicule non trouvé pour mise à jour.");
            }
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la mise à jour du véhicule: " . $e->getMessage());
        }
    }

    // Method to count vehicles with more than a specified km
    public function countVehiclesWithMoreThanKm($km) {
        try {
            if (empty($km)) {
                return new Response(400, "Le kilométrage ne peut pas être vide.");
            }

            $count = $this->vehicleRepository->countVehiclesWithMoreThanKm($km);
            return new Response(200, "Nombre de véhicules avec plus de $km km: $count.");
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors du comptage des véhicules: " . $e->getMessage());
        }
    }

    // Method to delete a vehicle
    public function deleteVehicle($id) {
        try {
            if (empty($id)) {
                return new Response(400, "L'ID du véhicule ne peut pas être vide.");
            }

            $result = $this->vehicleRepository->delete($id);

            if ($result) {
                return new Response(200, "Véhicule supprimé avec succès.");
            } else {
                return new Response(404, "Véhicule non trouvé.");
            }
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la suppression du véhicule: " . $e->getMessage());
        }
    }
}

