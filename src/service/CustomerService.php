<?php
namespace Lucpa\Service;

use Lucpa\Model\Customer;
use Lucpa\Repository\CustomerRepository;
use InvalidArgumentException;

class CustomerService {
    private $customerRepository;

    // Constructeur qui reçoit le repository
    public function __construct(CustomerRepository $customerRepository) {
        $this->customerRepository = $customerRepository;
    }

    // Méthode pour enregistrer un client
    public function saveCustomer($name) {
        try {
            // Validation (only in service layer, not in repository)
            if (empty($name)) {
                return new Response(400, "Le nom du client ne peut pas être vide.");
            }

            $customer = new Customer(null, $name);  // Créer un nouveau client

            // Sauvegarder dans la base de données
            $this->customerRepository->save($customer);
            
            // Return success response
            return new Response(201, "Client enregistré avec succès.");
        } catch (\Exception $e) {
            // Catch any exception generically
            return new Response(500, "Une erreur est survenue lors de l'enregistrement du client: " . $e->getMessage());
        }
    }

    // Méthode pour récupérer un client par son nom
    public function getCustomerByName($name) {
        try {
            // Validation (only in service layer, not in repository)
            if (empty($name)) {
                return new Response(400, "Le nom ne peut pas être vide.");
            }

            $customer = $this->customerRepository->getByName($name);

            if ($customer) {
                return new Response(200, "Client trouvé.", $customer);
            } else {
                return new Response(404, "Client non trouvé.");
            }
        } catch (\Exception $e) {
            // Catch any exception generically
            return new Response(500, "Une erreur est survenue lors de la récupération du client: " . $e->getMessage());
        }
    }

    public function updateCustomerName($id, $newName) {
        try {
            if (empty($newName)) {
                return new Response(400, "Le nom ne peut pas être vide.");
            }

            $result = $this->customerRepository->updateName($id, $newName);

            if ($result) {
                return new Response(200, "Nom du client mis à jour avec succès.");
            } else {
                return new Response(404, "Client non trouvé pour mise à jour.");
            }
        } catch (\Exception $e) {
            return new Response(500, "Une erreur est survenue lors de la mise à jour du nom du client: " . $e->getMessage());
        }
    }

    // Méthode pour supprimer un client
    public function deleteCustomer($id) {
        try {
            // Validation de l'ID du client
            if (empty($id)) {
                return new Response(400, "L'ID du client ne peut pas être vide.");
            }

            // Appel au repository pour supprimer le client
            $result = $this->customerRepository->delete($id);

            if ($result) {
                return new Response(200, "Client supprimé avec succès.");
            } else {
                return new Response(404, "Client non trouvé.");
            }
        } catch (\Exception $e) {
            // Catch any exception generically
            return new Response(500, "Une erreur est survenue lors de la suppression du client: " . $e->getMessage());
        }
    }
}


