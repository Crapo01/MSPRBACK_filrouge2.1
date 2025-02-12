<?php

namespace Tests\Service;

use Lucpa\Service\BillingService;
use Lucpa\Repository\BillingRepository;
use Lucpa\Model\Billing;
use Lucpa\Service\Response;
use PHPUnit\Framework\TestCase;

class BillingServiceTest extends TestCase {
    private $billingRepository;
    private $billingService;

    protected function setUp(): void {
        $this->billingRepository = $this->createMock(BillingRepository::class);
        $this->billingService = new BillingService($this->billingRepository);
    }

    public function testSaveBillingSuccess() {
        $contractId = 1;
        $amount = 100;
        $billing = new Billing(null, $contractId, $amount);

        $this->billingRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($arg) use ($contractId, $amount) {
                return $arg instanceof Billing && $arg->getContractId() === $contractId && $arg->getAmount() === $amount;
            }));

        $response = $this->billingService->saveBilling($contractId, $amount);
        
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals("Facture enregistrée avec succès.", $response->getMessage());
    }

    public function testSaveBillingWithInvalidAmount() {
        $response = $this->billingService->saveBilling(1, 0);
        
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("Le montant de la facturation doit être supérieur à zéro.", $response->getMessage());
    }

    public function testSaveBillingServerError() {
        $this->billingRepository
            ->method('save')
            ->willThrowException(new \Exception("Database error"));

        $response = $this->billingService->saveBilling(1, 100);
        
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertStringContainsString("Une erreur est survenue lors de l'enregistrement de la facture", $response->getMessage());
    }

    public function testGetBillingByIdSuccess() {
        $billing = new Billing(1, 1, 100);
        
        $this->billingRepository
            ->method('getById')
            ->willReturn($billing);
        
        $response = $this->billingService->getBillingById(1);
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Facture trouvée.", $response->getMessage());
        $this->assertEquals($billing, $response->getData());
    }

    public function testGetBillingByIdNotFound() {
        $this->billingRepository
            ->method('getById')
            ->willReturn(null);
        
        $response = $this->billingService->getBillingById(999);
        
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Facture non trouvée.", $response->getMessage());
    }

    public function testGetBillingByIdServerError() {
        $this->billingRepository
            ->method('getById')
            ->willThrowException(new \Exception("Database error"));
        
        $response = $this->billingService->getBillingById(1);
        
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertStringContainsString("Une erreur est survenue lors de la récupération de la facture", $response->getMessage());
    }

    public function testDeleteBillingSuccess() {
        $this->billingRepository
            ->expects($this->once())
            ->method('delete')
            ->with(1);
        
        $response = $this->billingService->deleteBilling(1);
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Facture supprimée avec succès.", $response->getMessage());
    }

    public function testDeleteBillingServerError() {
        $this->billingRepository
            ->method('delete')
            ->willThrowException(new \Exception("Database error"));
        
        $response = $this->billingService->deleteBilling(1);
        
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertStringContainsString("Une erreur est survenue lors de la suppression de la facture", $response->getMessage());
    }

    public function testCreateTableSuccess() {
        $this->billingRepository
            ->method('createTableIfNotExists')
            ->willReturn(true);
        
        $response = $this->billingService->createTable();
        
        $this->assertTrue($response);
    }

    public function testCreateTableServerError() {
        $this->billingRepository
            ->method('createTableIfNotExists')
            ->willThrowException(new \Exception("Database error"));
        
        $response = $this->billingService->createTable();
        
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertStringContainsString("Erreur lors de la création de la table", $response->getMessage());
    }
}
