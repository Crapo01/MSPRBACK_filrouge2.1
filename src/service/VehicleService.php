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
    public function saveVehicle($model) {
        try {
            // Validation (only in service layer, not in repository)
            if (empty($model)) {
                return new Response(400, "Le modèle du véhicule ne peut pas être vide.");
            }

            $vehicle = new Vehicle(null, $model);  // Create a new vehicle

            // Save in the database
            $this->vehicleRepository->save($vehicle);
            
            // Return success response
            return new Response(201, "Véhicule enregistré avec succès.");
        } catch (\Exception $e) {
            // Catch any exception generically
            return new Response(500, "Une erreur est survenue lors de l'enregistrement du véhicule: " . $e->getMessage());
        }
    }

    // Method to retrieve a vehicle by its model
    public function getVehicleByModel($model) {
        try {
            // Validation (only in service layer, not in repository)
            if (empty($model)) {
                return new Response(400, "Le modèle ne peut pas être vide.");
            }

            $vehicle = $this->vehicleRepository->getByModel($model);

            if ($vehicle) {
                return new Response(200, "Véhicule trouvé.", $vehicle);
            } else {
                return new Response(404, "Véhicule non trouvé.");
            }
        } catch (\Exception $e) {
            // Catch any exception generically
            return new Response(500, "Une erreur est survenue lors de la récupération du véhicule: " . $e->getMessage());
        }
    }

    // Method to update a vehicle's model
    public function updateVehicleModel($id, $newModel) {
        try {
            if (empty($newModel)) {
                return new Response(400, "Le modèle ne peut pas être vide.");
            }

            $result = $this->vehicleRepository->updateModel($id, $newModel);

            if ($result) {
                return new Response(200, "Modèle du véhicule mis à jour avec succès.");
            } else {
                return new Response(404, "Véhicule non trouvé pour mise à jour.");
            }
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la mise à jour du modèle du véhicule: " . $e->getMessage());
        }
    }

    // Method to delete a vehicle
    public function deleteVehicle($id) {
        try {
            // Validate the vehicle ID
            if (empty($id)) {
                return new Response(400, "L'ID du véhicule ne peut pas être vide.");
            }

            // Call the repository to delete the vehicle
            $result = $this->vehicleRepository->delete($id);

            if ($result) {
                return new Response(200, "Véhicule supprimé avec succès.");
            } else {
                return new Response(404, "Véhicule non trouvé.");
            }
        } catch (\Exception $e) {
            // Catch any exception generically
            return new Response(500, "Une erreur est survenue lors de la suppression du véhicule: " . $e->getMessage());
        }
    }
}

