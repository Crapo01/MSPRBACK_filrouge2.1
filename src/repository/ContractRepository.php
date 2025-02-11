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
}
