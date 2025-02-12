<?php

namespace Tests\Repository;

use Lucpa\Repository\AnalyseRepository;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

class AnalyseRepositoryTest extends TestCase {
    private $pdo;
    private $statement;
    private $repository;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
        $this->repository = new AnalyseRepository($this->pdo);
    }

    public function testGetOngoingRentalsByCustomerUidSuccess() {
        $customerUid = 'cust123';
        $expectedData = [['id' => 1, 'customer_uid' => $customerUid]];
        
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');
        $this->statement->method('fetchAll')->willReturn($expectedData);
        
        $result = $this->repository->getOngoingRentalsByCustomerUid($customerUid);
        $this->assertEquals($expectedData, $result);
    }

    public function testGetLateRentalsSuccess() {
        $expectedData = [['id' => 2, 'late' => true]];
        
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');
        $this->statement->method('fetchAll')->willReturn($expectedData);
        
        $result = $this->repository->getLateRentals();
        $this->assertEquals($expectedData, $result);
    }

    public function testGetPaymentsByContractIdSuccess() {
        $contractId = 123;
        $expectedData = [['id' => 1, 'contract_id' => $contractId, 'amount' => 100]];
        
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');
        $this->statement->method('fetchAll')->willReturn($expectedData);
        
        $result = $this->repository->getPaymentsByContractId($contractId);
        $this->assertEquals($expectedData, $result);
    }

    public function testIsRentalFullyPaidSuccess() {
        $contractId = 456;
        
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');
        $this->statement->method('fetch')->willReturnOnConsecutiveCalls(['total_paid' => 500], ['price' => 500]);
        
        $result = $this->repository->isRentalFullyPaid($contractId);
        $this->assertTrue($result);
    }

    public function testGetUnpaidRentalsSuccess() {
        $expectedData = [['id' => 3, 'unpaid' => true]];
        
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');
        $this->statement->method('fetchAll')->willReturn($expectedData);
        
        $result = $this->repository->getUnpaidRentals();
        $this->assertEquals($expectedData, $result);
    }

    public function testGetContractsByVehicleUidSuccess() {
        $vehicleUid = 'V123';
        $expectedData = [['id' => 1, 'vehicle_uid' => $vehicleUid]];
        
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');
        $this->statement->method('fetchAll')->willReturn($expectedData);
        
        $result = $this->repository->getContractsByVehicleUid($vehicleUid);
        $this->assertEquals($expectedData, $result);
    }

    public function testGetAverageDelayByVehicleSuccess() {
        $expectedData = [['vehicle_uid' => 'V123', 'avg_delay' => 5]];
        
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');
        $this->statement->method('fetchAll')->willReturn($expectedData);
        
        $result = $this->repository->getAverageDelayByVehicle();
        $this->assertEquals($expectedData, $result);
    }

    public function testGetContractsGroupedByVehicleSuccess() {
        $expectedData = [['customer_uid' => 'C123', 'id' => 1, 'price' => 500]];
        
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');
        $this->statement->method('fetchAll')->willReturn($expectedData);
        
        $result = $this->repository->getContractsGroupedByVehicle();
        $this->assertEquals($expectedData, $result);
    }

    public function testGetContractsGroupedByCustomerSuccess() {
        $expectedData = [['customer_uid' => 'C123', 'id' => 1, 'price' => 500]];
        
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');
        $this->statement->method('fetchAll')->willReturn($expectedData);
        
        $result = $this->repository->getContractsGroupedByCustomer();
        $this->assertEquals($expectedData, $result);
    }
}
