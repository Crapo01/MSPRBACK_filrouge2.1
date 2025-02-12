<?php

namespace Tests\Repository;

use Lucpa\Repository\ContractRepository;
use Lucpa\Model\Contract;
use Lucpa\Service\Response;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

class ContractRepositoryTest extends TestCase {
    private $pdo;
    private $statement;
    private $repository;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
        $this->repository = new ContractRepository($this->pdo);
    }

    public function testSaveContractInsertSuccess() {
        $contract = new Contract(null, 'V123', 'C123', '2024-02-01 10:00:00', '2024-02-02 10:00:00', '2024-02-05 10:00:00', '2024-02-06 10:00:00', 500);
        
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');
        $this->pdo->method('lastInsertId')->willReturn("10");

        $this->repository->save($contract);
        
        $this->assertEquals(10, $contract->getId());
    }

    public function testSaveContractUpdateSuccess() {
        $contract = new Contract(5, 'V123', 'C123', '2024-02-01 10:00:00', '2024-02-02 10:00:00', '2024-02-05 10:00:00', '2024-02-06 10:00:00', 500);
        
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');

        $this->repository->save($contract);
        
        $this->assertEquals(5, $contract->getId());
    }

    public function testGetContractByIdSuccess() {
        $expectedData = ['id' => 1, 'vehicle_uid' => 'V123', 'customer_uid' => 'C123', 'sign_datetime' => '2024-02-01 10:00:00', 'loc_begin_datetime' => '2024-02-02 10:00:00', 'loc_end_datetime' => '2024-02-05 10:00:00', 'returning_datetime' => '2024-02-06 10:00:00', 'price' => 500];
        
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');
        $this->statement->method('fetch')->willReturn($expectedData);

        $result = $this->repository->getById(1);
        $this->assertInstanceOf(Contract::class, $result);
        $this->assertEquals(1, $result->getId());
    }

    public function testGetContractByIdNotFound() {
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');
        $this->statement->method('fetch')->willReturn(false);

        $result = $this->repository->getById(999);
        $this->assertNull($result);
    }

    public function testDeleteContractSuccess() {
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');

        $this->repository->delete(1);
        $this->assertTrue(true);
    }

    public function testCreateTableSuccess() {
        $this->pdo->method('exec')->willReturn(1);
        
        $response = $this->repository->createTable();
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Table 'contracts' vérifiée et créée si nécessaire.", $response->getMessage());
    }

    public function testCreateTableFailure() {
        $this->pdo->method('exec')->willThrowException(new \PDOException("Database error"));
        
        $response = $this->repository->createTable();
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertStringContainsString("Erreur lors de la création de la table des contrats", $response->getMessage());
    }
}
