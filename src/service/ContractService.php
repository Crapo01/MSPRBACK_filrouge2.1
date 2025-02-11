<?php
namespace Lucpa\Service;

use DateTime;
use Lucpa\Model\Contract;
use Lucpa\Repository\ContractRepository;

class ContractService {
    private $contractRepository;

    // Constructor to initialize the repository
    public function __construct(ContractRepository $contractRepository) {
        $this->contractRepository = $contractRepository;
    }

    // Method to save a new contract or update an existing contract
    public function saveContract($vehicleUid, $customerUid, $signDatetime, $locBeginDatetime, $locEndDatetime, $returningDatetime, $price, $id = null) {
        try {
            // Validate price
            if ($price <= 0) {
                return new Response(400, "Le prix du contrat doit être supérieur à zéro.");
            }
    
            // Validate Vehicle UID (ensure it's a non-empty alphanumeric string of a specific length, e.g., 255 chars)
            if (empty($vehicleUid) || !preg_match('/^[a-zA-Z0-9]{1,255}$/', $vehicleUid)) {
                return new Response(400, "L'UID du véhicule est invalide.");
            }
    
            // Validate Customer UID (ensure it's a non-empty alphanumeric string of a specific length, e.g., 255 chars)
            if (empty($customerUid) || !preg_match('/^[a-zA-Z0-9]{1,255}$/', $customerUid)) {
                return new Response(400, "L'UID du client est invalide.");
            }
    
            // Validate Sign Datetime (must be a valid datetime format)
            if (!$this->isValidDateTime($signDatetime)) {
                return new Response(400, "La date et l'heure de la signature du contrat sont invalides.");
            }
    
            // Validate Loc Begin Datetime (must be a valid datetime format)
            if (!$this->isValidDateTime($locBeginDatetime)) {
                return new Response(400, "La date et l'heure de début de la location sont invalides.");
            }
    
            // Validate Loc End Datetime (must be a valid datetime format)
            if (!$this->isValidDateTime($locEndDatetime)) {
                return new Response(400, "La date et l'heure de fin de la location sont invalides.");
            }
    
            // Validate Returning Datetime (must be a valid datetime format)
            if (!$this->isValidDateTime($returningDatetime)) {
                return new Response(400, "La date et l'heure de rendu du véhicule sont invalides.");
            }
    
            // Validate that the returning datetime is after the loc end datetime (ensure the vehicle is returned after rental ends)
            if (strtotime($returningDatetime) < strtotime($locEndDatetime)) {
                return new Response(400, "La date de retour du véhicule ne peut pas être avant la fin de la location.");
            }
    
            // Validate that loc end datetime is after loc begin datetime (ensure rental doesn't end before it begins)
            if (strtotime($locEndDatetime) < strtotime($locBeginDatetime)) {
                return new Response(400, "La date de fin de location ne peut pas être avant la date de début.");
            }
    
            // Create a new contract
            $contract = new Contract($id, $vehicleUid, $customerUid, $signDatetime, $locBeginDatetime, $locEndDatetime, $returningDatetime, $price);
    
            // Save or update the contract in the repository
            $this->contractRepository->save($contract);
    
            // Return success response
            return new Response(201, "Contrat enregistré avec succès.", $contract);
        } catch (\Exception $e) {
            // Handle general errors
            return new Response(500, "Une erreur est survenue lors de l'enregistrement du contrat: " . $e->getMessage());
        }
    }
    
    // Helper function to validate datetime format
    private function isValidDateTime($dateTime) {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        return $date && $date->format('Y-m-d H:i:s') === $dateTime;
    }
    

    // Method to retrieve a contract by ID
    public function getContractById($id) {
        try {
            // Retrieve the contract from the repository
            $contract = $this->contractRepository->getById($id);

            if ($contract) {
                return new Response(200, "Contrat trouvé.", $contract);
            } else {
                return new Response(404, "Contrat non trouvé.");
            }
        } catch (\Exception $e) {
            // Handle errors during retrieval
            return new Response(500, "Une erreur est survenue lors de la récupération du contrat: " . $e->getMessage());
        }
    }

    // Method to delete a contract by ID
    public function deleteContract($id) {
        try {
            // Attempt to delete the contract
            $this->contractRepository->delete($id);

            return new Response(200, "Contrat supprimé avec succès.");
        } catch (\Exception $e) {
            // Handle errors during deletion
            return new Response(500, "Une erreur est survenue lors de la suppression du contrat: " . $e->getMessage());
        }
    }
}
