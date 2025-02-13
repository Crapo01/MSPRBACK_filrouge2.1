<?php
namespace Lucpa\Service;

use Lucpa\Model\Billing;
use Lucpa\Repository\BillingRepository;

class BillingService {
    private $billingRepository;

    public function __construct(BillingRepository $billingRepository) {
        $this->billingRepository = $billingRepository;
    }

    public function saveBilling($contract_id, $amount, $id = null) {
        try {
            if ($amount <= 0) {
                return new Response(400, "Le montant de la facturation doit être supérieur à zéro.");
            }

            $billing = new Billing($id, $contract_id, $amount);
            $this->billingRepository->save($billing);

            return new Response(201, "Facture enregistrée avec succès.", $billing);
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de l'enregistrement de la facture: " . $e->getMessage());
        }
    }

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

    public function deleteBilling($id) {
        try {
            $this->billingRepository->delete($id);
            return new Response(200, "Facture supprimée avec succès.");
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la suppression de la facture: " . $e->getMessage());
        }
    }

    public function createTable() {
        try {
            return $this->billingRepository->createTableIfNotExists();
        } catch (\Exception $e) {
            return new Response(500, "Erreur lors de la création de la table : " . $e->getMessage());
        }
    }
}
