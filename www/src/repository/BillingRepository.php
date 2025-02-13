<?php

namespace Lucpa\Repository;

use Lucpa\Model\Billing;
use Lucpa\Service\Response;
use PDO;

class BillingRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Billing $billing)
    {
        $contract_id = $billing->getContractId();
        $amount = $billing->getAmount();
        $id = $billing->getId();

        if ($id) {
            $stmt = $this->pdo->prepare("UPDATE billings SET contract_id = :contract_id, amount = :amount WHERE id = :id");
            $stmt->bindParam(':contract_id', $contract_id);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO billings (contract_id, amount) VALUES (:contract_id, :amount)");
            $stmt->bindParam(':contract_id', $contract_id);
            $stmt->bindParam(':amount', $amount);
            $stmt->execute();

            $billing->setId($this->pdo->lastInsertId());
        }
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM billings WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $billing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($billing) {
            return new Billing($billing['id'], $billing['contract_id'], $billing['amount']);
        }

        return null;
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM billings WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function createTableIfNotExists()
    {
        $query = "
        CREATE TABLE IF NOT EXISTS billings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        contract_id INT NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (contract_id) REFERENCES contracts(id) ON DELETE CASCADE
        )        
        ";

        try {
            $this->pdo->exec($query);
            return new Response(200, "Table 'billings' vérifiée et créée si nécessaire.");
        } catch (\PDOException $e) {
            return new Response(500, "Erreur lors de la création de la table : " . $e->getMessage());
        }
    }
}

