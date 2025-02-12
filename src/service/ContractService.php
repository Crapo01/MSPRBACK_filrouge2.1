<?php
namespace Lucpa\Service;

use DateTime;
use Lucpa\Model\Contract;
use Lucpa\Repository\ContractRepository;

class ContractService {
    private $contractRepository;

    public function __construct(ContractRepository $contractRepository) {
        $this->contractRepository = $contractRepository;
    }

    public function saveContract($vehicleUid, $customerUid, $signDatetime, $locBeginDatetime, $locEndDatetime, $returningDatetime, $price, $id = null) {
        try {
            if ($price <= 0) {
                return new Response(400, "Le prix du contrat doit être supérieur à zéro.");
            }

            if (empty($vehicleUid) || !preg_match('/^[a-zA-Z0-9]{1,255}$/', $vehicleUid)) {
                return new Response(400, "L'UID du véhicule est invalide.");
            }

            if (empty($customerUid) || !preg_match('/^[a-zA-Z0-9]{1,255}$/', $customerUid)) {
                return new Response(400, "L'UID du client est invalide.");
            }

            if (!$this->isValidDateTime($signDatetime)) {
                return new Response(400, "La date et l'heure de la signature du contrat sont invalides.");
            }

            if (!$this->isValidDateTime($locBeginDatetime)) {
                return new Response(400, "La date et l'heure de début de la location sont invalides.");
            }

            if (!$this->isValidDateTime($locEndDatetime)) {
                return new Response(400, "La date et l'heure de fin de la location sont invalides.");
            }

            if (!$this->isValidDateTime($returningDatetime)) {
                return new Response(400, "La date et l'heure de rendu du véhicule sont invalides.");
            }

            if (strtotime($returningDatetime) < strtotime($locEndDatetime)) {
                return new Response(400, "La date de retour du véhicule ne peut pas être avant la fin de la location.");
            }

            if (strtotime($locEndDatetime) < strtotime($locBeginDatetime)) {
                return new Response(400, "La date de fin de location ne peut pas être avant la date de début.");
            }

            $contract = new Contract($id, $vehicleUid, $customerUid, $signDatetime, $locBeginDatetime, $locEndDatetime, $returningDatetime, $price);

            $this->contractRepository->save($contract);

            return new Response(201, "Contrat enregistré avec succès.", $contract);
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de l'enregistrement du contrat: " . $e->getMessage());
        }
    }

    private function isValidDateTime($dateTime) {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        return $date && $date->format('Y-m-d H:i:s') === $dateTime;
    }

    public function getContractById($id) {
        try {
            $contract = $this->contractRepository->getById($id);

            if ($contract) {
                return new Response(200, "Contrat trouvé.", $contract);
            } else {
                return new Response(404, "Contrat non trouvé.");
            }
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la récupération du contrat: " . $e->getMessage());
        }
    }

    public function deleteContract($id) {
        try {
            $this->contractRepository->delete($id);

            return new Response(200, "Contrat supprimé avec succès.");
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la suppression du contrat: " . $e->getMessage());
        }
    }

    public function createTable() {
        try {
            return $this->contractRepository->createTable();
        } catch (\Exception $e) {
            return new Response(500, "Erreur lors de la création de la table : " . $e->getMessage());
        }
    }
}
