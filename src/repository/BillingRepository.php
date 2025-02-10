<?php
namespace Lucpa\Repository;

use Lucpa\Model\Billing;
use PDO;

class BillingRepository {
    private $pdo;

    // Constructor to initialize PDO connection
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Method to save or update the billing record
    public function save(Billing $billing) {
        $amount = $billing->getAmount();
        $billing_date = $billing->getBillingDate();
        $id= $billing->getId();
        
        if ($billing->getId()) {
            // Update existing billing record
            $stmt = $this->pdo->prepare("UPDATE billings SET amount = :amount, billing_date = :billing_date WHERE id = :id");
            $stmt->bindParam(':amount', $amount);  // pass variables here
            $stmt->bindParam(':billing_date', $billing_date);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } else {
            // Insert new billing record
            $stmt = $this->pdo->prepare("INSERT INTO billings (amount, billing_date) VALUES (:amount, :billing_date)");
            $stmt->bindParam(':amount', $amount);  // pass variables here
            $stmt->bindParam(':billing_date', $billing_date);
            $stmt->execute();

            // Set the ID of the newly inserted billing record
            $billing->setId($this->pdo->lastInsertId());
        }
    }

    // Method to get a billing record by ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM billings WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $billing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($billing) {
            return new Billing($billing['id'], $billing['amount'], $billing['billing_date']);
        }

        return null;  // Return null if not found
    }

    // Method to delete a billing record by ID
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM billings WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Method to get all billing records
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM billings");
        $billings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $result = [];
        foreach ($billings as $billing) {
            $result[] = new Billing($billing['id'], $billing['amount'], $billing['billing_date']);
        }

        return $result;
    }
}
