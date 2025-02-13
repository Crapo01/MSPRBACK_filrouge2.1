<?php

namespace Lucpa\Service;

use Lucpa\Repository\AnalyseRepository;

class AnalyseService {
    private $analyseRepository;

    public function __construct(AnalyseRepository $analyseRepository) {
        $this->analyseRepository = $analyseRepository;
    }
  
    public function listOngoingRentalsByCustomerUid($customerUid) {
        try {
            if (empty($customerUid) || !is_string($customerUid) || strlen($customerUid) > 255) {
                return new Response(400, "L'UID du client est invalide.");
            }

            $rentals = $this->analyseRepository->getOngoingRentalsByCustomerUid($customerUid);
            return new Response(200, "Locations en cours récupérées avec succès.", $rentals);
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la récupération des locations en cours : " . $e->getMessage());
        }
    }

    public function listLateRentals() {
        try {
            $lateRentals = $this->analyseRepository->getLateRentals();
            return new Response(200, "Locations en retard récupérées avec succès.", $lateRentals);
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la récupération des locations en retard : " . $e->getMessage());
        }
    }

    public function listPaymentsByContractId($contractId) {
        try {
            if (empty($contractId) || !is_numeric($contractId)) {
                return new Response(400, "L'ID du contrat est invalide.");
            }

            $payments = $this->analyseRepository->getPaymentsByContractId($contractId);
            return new Response(200, "Paiements récupérés avec succès.", $payments);
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la récupération des paiements : " . $e->getMessage());
        }
    }

    public function isRentalFullyPaid($contractId) {
        try {
            if (empty($contractId) || !is_numeric($contractId)) {
                return new Response(400, "L'ID du contrat est invalide.");
            }

            $isPaid = $this->analyseRepository->isRentalFullyPaid($contractId);
            return new Response(200, $isPaid ? "La location est entièrement payée." : "La location n'est pas entièrement payée.");
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la vérification du statut de paiement : " . $e->getMessage());
        }
    }

    public function listUnpaidRentals() {
        try {
            $unpaidRentals = $this->analyseRepository->getUnpaidRentals();
            return new Response(200, "Locations impayées récupérées avec succès.", $unpaidRentals);
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la récupération des locations impayées : " . $e->getMessage());
        }
    }

    public function countLateRentalsBetweenDates($startDate, $endDate) {
        try {
            if (empty($startDate) || empty($endDate)) {
                return new Response(400, "Les dates de début et de fin ne peuvent pas être vides.");
            }

            if (!strtotime($startDate) || !strtotime($endDate)) {
                return new Response(400, "Les dates sont invalides.");
            }

            $count = $this->analyseRepository->countLateRentalsBetweenDates($startDate, $endDate);
            return new Response(200, "Comptage des locations en retard entre les dates.", ['count' => $count]);
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors du comptage des locations en retard : " . $e->getMessage());
        }
    }

    public function countAverageLateRentalsPerCustomer() {
        try {
            $averageLateRentals = $this->analyseRepository->countAverageLateRentalsPerCustomer();
            return new Response(200, "Moyenne des locations en retard par client.", $averageLateRentals);
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors du calcul de la moyenne des locations en retard par client : " . $e->getMessage());
        }
    }

    public function listContractsByVehicleUid($vehicleUid) {
        try {
            if (empty($vehicleUid) || !is_string($vehicleUid) || strlen($vehicleUid) > 255) {
                return new Response(400, "L'UID du véhicule est invalide.");
            }

            $contracts = $this->analyseRepository->getContractsByVehicleUid($vehicleUid);
            return new Response(200, "Contrats pour le véhicule récupérés avec succès.", $contracts);
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la récupération des contrats pour le véhicule : " . $e->getMessage());
        }
    }

    public function getAverageDelayByVehicle() {
        try {
            $averageDelays = $this->analyseRepository->getAverageDelayByVehicle();
            return new Response(200, "Retard moyen par véhicule récupéré avec succès.", $averageDelays);
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la récupération du retard moyen par véhicule : " . $e->getMessage());
        }
    }

    public function getContractsGroupedByVehicle() {
        try {
            $contractsGroupedByVehicle = $this->analyseRepository->getContractsGroupedByVehicle();
            return new Response(200, "Contrats regroupés par véhicule récupérés avec succès.", $contractsGroupedByVehicle);
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors du regroupement des contrats par véhicule : " . $e->getMessage());
        }
    }

    public function getContractsGroupedByCustomer() {
        try {
            $contractsGroupedByCustomer = $this->analyseRepository->getContractsGroupedByCustomer();
            return new Response(200, "Contrats regroupés par client récupérés avec succès.", $contractsGroupedByCustomer);
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors du regroupement des contrats par client : " . $e->getMessage());
        }
    }
}
