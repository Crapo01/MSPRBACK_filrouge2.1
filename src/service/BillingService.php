<?php
namespace Lucpa\Service;

use Lucpa\Model\Billing;
use Lucpa\Repository\BillingRepository;

class BillingService {
    private $billingRepository;

    // Constructor to initialize the repository
    public function __construct(BillingRepository $billingRepository) {
        $this->billingRepository = $billingRepository;
    }

    // Method to save a new billing or update an existing one
    public function saveBilling($contract_id, $amount, $id = null) {
        try {
            // Validate amount
            if ($amount <= 0) {
                return new Response(400, "Le montant de la facturation doit être supérieur à zéro.");
            }

            // Create a new Billing object with contract_id
            $billing = new Billing($id, $contract_id, $amount);

            // Save or update the billing record in the repository
            $this->billingRepository->save($billing);

            return new Response(201, "Facture enregistrée avec succès.", $billing);
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de l'enregistrement de la facture: " . $e->getMessage());
        }
    }

    // Method to retrieve a billing record by ID
    public function getBillingById($id) {
        try {
            $billing = $this->billingRepository->getById($id);

            if ($billing) {
                return new Response(200, "Facture trouvée.", $billing);
            } else {
                return new Response(404, "Facture non trouvée.");
            }
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la récupération de la facture: " . $e->getMessage());
        }
    }

    // Method to delete a billing record by ID
    public function deleteBilling($id) {
        try {
            $this->billingRepository->delete($id);
            return new Response(200, "Facture supprimée avec succès.");
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la suppression de la facture: " . $e->getMessage());
        }
    }

    // Method to create the table if it doesn't exist
    public function createTable() {
        try {
            // Call the method from the repository to create the table
            return $this->billingRepository->createTableIfNotExists();
        } catch (\Exception $e) {
            // If there's an error, return a response with status 500
            return new Response(500, "Erreur lors de la création de la table : " . $e->getMessage());
        }
    }

}

