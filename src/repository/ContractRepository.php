<?php
namespace Lucpa\Repository;

use Lucpa\Model\Contract;
use PDO;

class ContractRepository {
    private $pdo;

    // Constructor to initialize PDO connection
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Method to save the contract (insert or update)
    public function save(Contract $contract) {
        $amount = $contract->getAmount();
        $id = $contract->getId();

        if ($id) {
            // Update contract
            $stmt = $this->pdo->prepare("UPDATE contracts SET amount = :amount WHERE id = :id");
            $stmt->bindParam(':amount', $amount);  // Pass variables here
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } else {
            // Insert new contract
            $stmt = $this->pdo->prepare("INSERT INTO contracts (amount) VALUES (:amount)");
            $stmt->bindParam(':amount', $amount);  // Pass variable here
            $stmt->execute();

            // Set the ID after insert (auto-increment)
            $contract->setId($this->pdo->lastInsertId());
        }
    }

    // Method to get a contract by ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM contracts WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $contract = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($contract) {
            return new Contract($contract['id'], $contract['amount']);
        }
        
        return null;  // Return null if not found
    }

    // Method to delete a contract by ID
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM contracts WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
