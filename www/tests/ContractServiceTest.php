<?php

namespace Tests\Service;

use Lucpa\Service\ContractService;
use Lucpa\Repository\ContractRepository;
use Lucpa\Model\Contract;
use Lucpa\Service\Response;
use PHPUnit\Framework\TestCase;

class ContractServiceTest extends TestCase {
    private $contractRepository;
    private $contractService;

    protected function setUp(): void {
        $this->contractRepository = $this->createMock(ContractRepository::class);
        $this->contractService = new ContractService($this->contractRepository);
    }

    public function testSaveContractSuccess() {
        $contract = new Contract(null, 'V123', 'C123', '2024-02-01 10:00:00', '2024-02-02 10:00:00', '2024-02-05 10:00:00', '2024-02-06 10:00:00', 500);
        
        $this->contractRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($arg) use ($contract) {
                return $arg instanceof Contract && $arg->getVehicleUid() === 'V123' && $arg->getCustomerUid() === 'C123';
            }));
        
        $response = $this->contractService->saveContract('V123', 'C123', '2024-02-01 10:00:00', '2024-02-02 10:00:00', '2024-02-05 10:00:00', '2024-02-06 10:00:00', 500);
        
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals("Contrat enregistré avec succès.", $response->getMessage());
    }

    public function testSaveContractWithInvalidPrice() {
        $response = $this->contractService->saveContract('V123', 'C123', '2024-02-01 10:00:00', '2024-02-02 10:00:00', '2024-02-05 10:00:00', '2024-02-06 10:00:00', 0);
        
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("Le prix du contrat doit être supérieur à zéro.", $response->getMessage());
    }

    public function testSaveContractWithInvalidDates() {
        $response = $this->contractService->saveContract('V123', 'C123', 'invalid-date', '2024-02-02 10:00:00', '2024-02-05 10:00:00', '2024-02-06 10:00:00', 500);
        
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("La date et l'heure de la signature du contrat sont invalides.", $response->getMessage());
    }

    public function testGetContractByIdSuccess() {
        $contract = new Contract(1, 'V123', 'C123', '2024-02-01 10:00:00', '2024-02-02 10:00:00', '2024-02-05 10:00:00', '2024-02-06 10:00:00', 500);
        
        $this->contractRepository
            ->method('getById')
            ->willReturn($contract);
        
        $response = $this->contractService->getContractById(1);
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Contrat trouvé.", $response->getMessage());
        $this->assertEquals($contract, $response->getData());
    }

    public function testGetContractByIdNotFound() {
        $this->contractRepository
            ->method('getById')
            ->willReturn(null);
        
        $response = $this->contractService->getContractById(999);
        
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Contrat non trouvé.", $response->getMessage());
    }

    public function testDeleteContractSuccess() {
        $this->contractRepository
            ->expects($this->once())
            ->method('delete')
            ->with(1);
        
        $response = $this->contractService->deleteContract(1);
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Contrat supprimé avec succès.", $response->getMessage());
    }

    public function testCreateTableSuccess() {
        $this->contractRepository
            ->method('createTable')
            ->willReturn(true);
        
        $response = $this->contractService->createTable();
        
        $this->assertTrue($response);
    }

    public function testCreateTableServerError() {
        $this->contractRepository
            ->method('createTable')
            ->willThrowException(new \Exception("Database error"));
        
        $response = $this->contractService->createTable();
        
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertStringContainsString("Erreur lors de la création de la table", $response->getMessage());
    }
}
