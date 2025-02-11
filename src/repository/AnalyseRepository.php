<?php
namespace Lucpa\Repository;

use PDO;

class AnalyseRepository {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getOngoingRentalsByCustomerUid($customerUid) {
        $stmt = $this->pdo->prepare("SELECT * FROM contracts WHERE customer_uid = :customerUid AND returning_datetime IS NULL");
        $stmt->bindParam(':customerUid', $customerUid);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLateRentals() {
        $stmt = $this->pdo->prepare("SELECT * FROM contracts WHERE TIMESTAMPDIFF(HOUR, loc_end_datetime, returning_datetime) > 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaymentsByContractId($contractId) {
        $stmt = $this->pdo->prepare("SELECT * FROM billings WHERE contract_id = :contractId");
        $stmt->bindParam(':contractId', $contractId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isRentalFullyPaid($contractId) {
        $stmt = $this->pdo->prepare("SELECT SUM(amount) AS total_paid FROM billings WHERE contract_id = :contractId");
        $stmt->bindParam(':contractId', $contractId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->pdo->prepare("SELECT price FROM contracts WHERE id = :contractId");
        $stmt->bindParam(':contractId', $contractId);
        $stmt->execute();
        $contract = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total_paid'] >= $contract['price'];
    }

    public function getUnpaidRentals() {    
        $stmt = $this->pdo->prepare("
            SELECT c.*
            FROM contracts c
            LEFT JOIN (
                SELECT contract_id, SUM(amount) AS total_billings
                FROM billings
                GROUP BY contract_id
            ) b ON c.id = b.contract_id
            WHERE b.total_billings IS NULL OR b.total_billings < c.price
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countLateRentalsBetweenDates($startDate, $endDate) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS late_count FROM contracts WHERE returning_datetime > loc_end_datetime AND returning_datetime BETWEEN :startDate AND :endDate");
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['late_count'];
    }

    public function countAverageLateRentalsPerCustomer() {
        $stmt = $this->pdo->prepare("SELECT customer_uid, AVG(TIMESTAMPDIFF(HOUR, loc_end_datetime, returning_datetime)) AS average_delay FROM contracts WHERE returning_datetime > loc_end_datetime GROUP BY customer_uid");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContractsByVehicleUid($vehicleUid) {
        $stmt = $this->pdo->prepare("SELECT * FROM contracts WHERE vehicle_uid = :vehicleUid");
        $stmt->bindParam(':vehicleUid', $vehicleUid);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAverageDelayByVehicle() {
        $stmt = $this->pdo->prepare("SELECT vehicle_uid, AVG(TIMESTAMPDIFF(HOUR, loc_end_datetime, returning_datetime)) AS avg_delay FROM contracts WHERE returning_datetime > loc_end_datetime GROUP BY vehicle_uid");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContractsGroupedByVehicle() {
        $stmt = $this->pdo->prepare("
            SELECT customer_uid, id, loc_begin_datetime, loc_end_datetime, price
            FROM contracts
            ORDER BY vehicle_uid ASC, loc_begin_datetime ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContractsGroupedByCustomer() {
        $stmt = $this->pdo->prepare("
            SELECT customer_uid, id, loc_begin_datetime, loc_end_datetime, price
            FROM contracts
            ORDER BY customer_uid ASC, loc_begin_datetime ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
