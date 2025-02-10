<?php
namespace Lucpa\Service;

use Lucpa\Model\Contract;
use Lucpa\Repository\ContractRepository;

class ContractService {
    private $contractRepository;

    // Constructor to initialize the repository
    public function __construct(ContractRepository $contractRepository) {
        $this->contractRepository = $contractRepository;
    }

    // Method to save a new contract or update an existing contract
    public function saveContract($amount, $id = null) {
        try {
            // Validate amount
            if ($amount <= 0) {
                return new Response(400, "Le montant du contrat doit être supérieur à zéro.");
            }

            // Create a new contract
            $contract = new Contract($id, $amount);

            // Save or update the contract in the repository
            $this->contractRepository->save($contract);

            // Return success response
            return new Response(201, "Contrat enregistré avec succès.", $contract);
        } catch (\Exception $e) {
            // Handle general errors
            return new Response(500, "Une erreur est survenue lors de l'enregistrement du contrat: " . $e->getMessage());
        }
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
