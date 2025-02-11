<?php
namespace Lucpa\Repository;

use Lucpa\Model\Contract;
use Lucpa\Service\Response;
use PDO;

class ContractRepository {
    private $pdo;

    // Constructor to initialize PDO connection
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Method to save the contract (insert or update)
    public function save(Contract $contract) {
        $id = $contract->getId();
        $vehicleUid = $contract->getVehicleUid();
        $customerUid = $contract->getCustomerUid();
        $signDatetime = $contract->getSignDatetime();
        $locBeginDatetime = $contract->getLocBeginDatetime();
        $locEndDatetime = $contract->getLocEndDatetime();
        $returningDatetime = $contract->getReturningDatetime();
        $price = $contract->getPrice();

        if ($id) {
            // Update contract
            $stmt = $this->pdo->prepare("UPDATE contracts 
                                         SET vehicle_uid = :vehicle_uid, customer_uid = :customer_uid, 
                                             sign_datetime = :sign_datetime, loc_begin_datetime = :loc_begin_datetime, 
                                             loc_end_datetime = :loc_end_datetime, returning_datetime = :returning_datetime, 
                                             price = :price
                                         WHERE id = :id");
            $stmt->bindParam(':vehicle_uid', $vehicleUid);
            $stmt->bindParam(':customer_uid', $customerUid);
            $stmt->bindParam(':sign_datetime', $signDatetime);
            $stmt->bindParam(':loc_begin_datetime', $locBeginDatetime);
            $stmt->bindParam(':loc_end_datetime', $locEndDatetime);
            $stmt->bindParam(':returning_datetime', $returningDatetime);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } else {
            // Insert new contract
            $stmt = $this->pdo->prepare("INSERT INTO contracts (vehicle_uid, customer_uid, sign_datetime, loc_begin_datetime, loc_end_datetime, returning_datetime, price)
                                         VALUES (:vehicle_uid, :customer_uid, :sign_datetime, :loc_begin_datetime, :loc_end_datetime, :returning_datetime, :price)");
            $stmt->bindParam(':vehicle_uid', $vehicleUid);
            $stmt->bindParam(':customer_uid', $customerUid);
            $stmt->bindParam(':sign_datetime', $signDatetime);
            $stmt->bindParam(':loc_begin_datetime', $locBeginDatetime);
            $stmt->bindParam(':loc_end_datetime', $locEndDatetime);
            $stmt->bindParam(':returning_datetime', $returningDatetime);
            $stmt->bindParam(':price', $price);
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
            return new Contract(
                $contract['id'], 
                $contract['vehicle_uid'], 
                $contract['customer_uid'], 
                $contract['sign_datetime'], 
                $contract['loc_begin_datetime'], 
                $contract['loc_end_datetime'], 
                $contract['returning_datetime'], 
                $contract['price']
            );
        }
        
        return null;  // Return null if not found
    }

    // Method to delete a contract by ID
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM contracts WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Method to create the contracts table if it doesn't exist
    public function createTable() {
        try {
            // SQL to create the contracts table
            $sql = "
                CREATE TABLE IF NOT EXISTS contracts (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    vehicle_uid CHAR(255) NOT NULL,
                    customer_uid CHAR(255) NOT NULL,
                    sign_datetime DATETIME NOT NULL,
                    loc_begin_datetime DATETIME NOT NULL,
                    loc_end_datetime DATETIME NOT NULL,
                    returning_datetime DATETIME ,
                    price DECIMAL(10, 2) NOT NULL
                ) ENGINE=InnoDB;
            ";

            // Execute the query to create the table
            $this->pdo->exec($sql);

            return new Response(200, "Table 'contracts' vérifiée et créée si nécessaire.");
        } catch (\Exception $e) {
            return new Response(500, "Erreur lors de la création de la table des contrats: " . $e->getMessage());
        }
    }

}
