<?php

namespace Tests\Repository;

use Lucpa\Repository\BillingRepository;
use Lucpa\Model\Billing;
use Lucpa\Service\Response;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

class BillingRepositoryTest extends TestCase {
    private $pdo;
    private $statement;
    private $repository;

    protected function setUp(): void {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
        $this->repository = new BillingRepository($this->pdo);
    }

    public function testSaveBillingInsertSuccess() {
        $billing = new Billing(null, 1, 200.50);
        
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');
        $this->pdo->method('lastInsertId')->willReturn("10");

        $this->repository->save($billing);
        
        $this->assertEquals(10, $billing->getId());
    }

    public function testSaveBillingUpdateSuccess() {
        $billing = new Billing(5, 1, 300.75);
        
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');

        $this->repository->save($billing);
        
        $this->assertEquals(5, $billing->getId());
    }

    public function testGetBillingByIdSuccess() {
        $expectedData = ['id' => 1, 'contract_id' => 1, 'amount' => 100.00];
        
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');
        $this->statement->method('fetch')->willReturn($expectedData);

        $result = $this->repository->getById(1);
        $this->assertInstanceOf(Billing::class, $result);
        $this->assertEquals(1, $result->getId());
    }

    public function testGetBillingByIdNotFound() {
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');
        $this->statement->method('fetch')->willReturn(false);

        $result = $this->repository->getById(999);
        $this->assertNull($result);
    }

    public function testDeleteBillingSuccess() {
        $this->pdo->method('prepare')->willReturn($this->statement);
        $this->statement->method('execute');

        $this->repository->delete(1);
        $this->assertTrue(true);
    }

    public function testCreateTableIfNotExistsSuccess() {
        $this->pdo->method('exec')->willReturn(1);
        
        $response = $this->repository->createTableIfNotExists();
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Table 'billings' vérifiée et créée si nécessaire.", $response->getMessage());
    }

    public function testCreateTableIfNotExistsFailure() {
        $this->pdo->method('exec')->willThrowException(new \PDOException("Database error"));
        
        $response = $this->repository->createTableIfNotExists();
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertStringContainsString("Erreur lors de la création de la table", $response->getMessage());
    }
}
