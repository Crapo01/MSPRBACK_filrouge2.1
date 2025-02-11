<?php
namespace Lucpa\Service;

use Lucpa\Model\Vehicle;
use Lucpa\Repository\VehicleRepository;

class VehicleService {
    private $vehicleRepository;

    public function __construct(VehicleRepository $vehicleRepository) {
        $this->vehicleRepository = $vehicleRepository;
    }

    public function saveVehicle($model, $licencePlate, $informations, $km) {
        try {
            if (empty($model) || empty($licencePlate)) {
                return new Response(400, "Le modèle ou l'immatriculation ne peut pas être vide.");
            }

            if (!is_string($model) || strlen($model) > 255) {
                return new Response(400, "Le modèle doit être une chaîne de caractères valide (maximum 255 caractères).");
            }

            if (!preg_match('/^[A-Z0-9\-]+$/i', $licencePlate)) {
                return new Response(400, "L'immatriculation doit être alphanumérique.");
            }

            if (!is_numeric($km) || $km < 0) {
                return new Response(400, "Le kilométrage doit être un nombre positif.");
            }

            $vehicle = new Vehicle(null, $model, $licencePlate, $informations, $km);

            $this->vehicleRepository->save($vehicle);

            return new Response(201, "Véhicule enregistré avec succès.");
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de l'enregistrement du véhicule: " . $e->getMessage());
        }
    }

    public function getVehicleByLicencePlate($licencePlate) {
        try {
            if (empty($licencePlate)) {
                return new Response(400, "L'immatriculation ne peut pas être vide.");
            }

            if (!preg_match('/^[A-Z0-9\-]+$/i', $licencePlate)) {
                return new Response(400, "L'immatriculation doit être alphanumérique.");
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

    public function updateVehicle($id, $model, $licencePlate, $informations, $km) {
        try {
            if (empty($model) || empty($licencePlate)) {
                return new Response(400, "Le modèle ou l'immatriculation ne peut pas être vide.");
            }

            if (!is_string($model) || strlen($model) > 255) {
                return new Response(400, "Le modèle doit être une chaîne de caractères valide (maximum 255 caractères).");
            }

            if (!preg_match('/^[A-Z0-9\-]+$/i', $licencePlate)) {
                return new Response(400, "L'immatriculation doit être alphanumérique.");
            }

            if (!is_numeric($km) || $km < 0) {
                return new Response(400, "Le kilométrage doit être un nombre positif.");
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

    public function countVehiclesWithMoreThanKm($km) {
        try {
            if (empty($km)) {
                return new Response(400, "Le kilométrage ne peut pas être vide.");
            }

            if (!is_numeric($km) || $km < 0) {
                return new Response(400, "Le kilométrage doit être un nombre positif.");
            }

            $count = $this->vehicleRepository->countVehiclesWithMoreThanKm($km);
            return new Response(200, "Nombre de véhicules avec plus de $km km: $count.");
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors du comptage des véhicules: " . $e->getMessage());
        }
    }

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


