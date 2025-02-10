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
    public function saveBilling($amount, $billing_date, $id = null) {
        try {
            // Validate amount
            if ($amount <= 0) {
                return new Response(400, "Le montant de la facturation doit être supérieur à zéro.");
            }

            // Validate billing_date
            if (empty($billing_date)) {
                return new Response(400, "La date de facturation ne peut pas être vide.");
            }

            // Create a new Billing object
            $billing = new Billing($id, $amount, $billing_date);

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

    // Method to get all billing records
    public function getAllBillings() {
        try {
            $billings = $this->billingRepository->getAll();
            return new Response(200, "Liste des factures récupérée.", $billings);
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la récupération des factures: " . $e->getMessage());
        }
    }
}
