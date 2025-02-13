<?php
namespace Lucpa\Service;

use Lucpa\Model\Customer;
use Lucpa\Repository\CustomerRepository;

class CustomerService {
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository) {
        $this->customerRepository = $customerRepository;
    }

    public function saveCustomer($firstName, $secondName, $address, $permitNumber) {
        try {
            if (empty($firstName) || empty($secondName) || empty($address) || empty($permitNumber)) {
                return new Response(400, "Tous les champs sont requis.");
            }

            $firstName = strtolower($firstName);
            $secondName = strtolower($secondName);
            $address = strtolower($address);
            $permitNumber = strtolower($permitNumber);

            $customer = new Customer(null, $firstName, $secondName, $address, $permitNumber);
            $this->customerRepository->save($customer);

            return new Response(201, "Client enregistré avec succès.");
        } catch (\Exception $e) {
            return new Response(500, "Erreur lors de l'enregistrement: " . $e->getMessage());
        }
    }

    public function getCustomerByFullName($firstName, $secondName) {
        try {
            if (empty($firstName) || empty($secondName)) {
                return new Response(400, "Les noms ne peuvent pas être vides.");
            }

            $firstName = strtolower($firstName);
            $secondName = strtolower($secondName);

            $customer = $this->customerRepository->getByFullName($firstName, $secondName);

            if ($customer) {
                return new Response(200, "Client trouvé.", $customer);
            } else {
                return new Response(404, "Client non trouvé.");
            }
        } catch (\Exception $e) {
            return new Response(500, "Erreur lors de la récupération: " . $e->getMessage());
        }
    }

    public function updateCustomer($id, $firstName, $secondName, $address, $permitNumber) {
        try {
            if (empty($firstName) || empty($secondName) || empty($address) || empty($permitNumber)) {
                return new Response(400, "Tous les champs sont requis.");
            }

            $firstName = strtolower($firstName);
            $secondName = strtolower($secondName);
            $address = strtolower($address);
            $permitNumber = strtolower($permitNumber);

            $result = $this->customerRepository->update($id, $firstName, $secondName, $address, $permitNumber);

            if ($result) {
                return new Response(200, "Client mis à jour avec succès.");
            } else {
                return new Response(404, "Client non trouvé.");
            }
        } catch (\Exception $e) {
            return new Response(500, "Erreur lors de la mise à jour: " . $e->getMessage());
        }
    }

    public function deleteCustomer($id) {
        try {
            if (empty($id)) {
                return new Response(400, "L'ID du client ne peut pas être vide.");
            }

            $result = $this->customerRepository->delete($id);

            if ($result) {
                return new Response(200, "Client supprimé avec succès.");
            } else {
                return new Response(404, "Client non trouvé.");
            }
        } catch (\Exception $e) {
            return new Response(500, "Erreur lors de la suppression: " . $e->getMessage());
        }
    }
}


