<?php

namespace Tests\Service;

use Lucpa\Service\AnalyseService;
use Lucpa\Repository\AnalyseRepository;
use Lucpa\Service\Response;
use PHPUnit\Framework\TestCase;

class AnalyseServiceTest extends TestCase {
    private $analyseRepository;
    private $analyseService;

    protected function setUp(): void {
        $this->analyseRepository = $this->createMock(AnalyseRepository::class);
        $this->analyseService = new AnalyseService($this->analyseRepository);
    }

    public function testListOngoingRentalsByCustomerUidWithValidUid() {
        $customerUid = 'valid-uid';
        $expectedData = [['id' => 1, 'customer_uid' => $customerUid, 'returning_datetime' => null]];
        
        $this->analyseRepository
            ->method('getOngoingRentalsByCustomerUid')
            ->with($customerUid)
            ->willReturn($expectedData);
        
        $response = $this->analyseService->listOngoingRentalsByCustomerUid($customerUid);
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Locations en cours récupérées avec succès.", $response->getMessage());
        $this->assertEquals($expectedData, $response->getData());
    }

    public function testListOngoingRentalsByCustomerUidWithEmptyUid() {
        $response = $this->analyseService->listOngoingRentalsByCustomerUid('');
        
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("L'UID du client est invalide.", $response->getMessage());
    }
    
    public function testListOngoingRentalsByCustomerUidWithNonStringUid() {
        $response = $this->analyseService->listOngoingRentalsByCustomerUid(12345);
        
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("L'UID du client est invalide.", $response->getMessage());
    }
    
    public function testListOngoingRentalsByCustomerUidWithTooLongUid() {
        $longUid = str_repeat('a', 256);
        $response = $this->analyseService->listOngoingRentalsByCustomerUid($longUid);
        
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("L'UID du client est invalide.", $response->getMessage());
    }

    public function testListOngoingRentalsByCustomerUidWithServerError() {
        $customerUid = 'valid-uid';
        
        $this->analyseRepository
            ->method('getOngoingRentalsByCustomerUid')
            ->with($customerUid)
            ->willThrowException(new \Exception("Database error"));
        
        $response = $this->analyseService->listOngoingRentalsByCustomerUid($customerUid);
        
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertStringContainsString("Une erreur est survenue lors de la récupération des locations en cours", $response->getMessage());
    }

    public function testListLateRentalsSuccess() {
        $expectedData = [
            ['id' => 1, 'customer_uid' => 'cust123', 'loc_end_datetime' => '2024-02-01 12:00:00', 'returning_datetime' => '2024-02-02 14:00:00'],
            ['id' => 2, 'customer_uid' => 'cust456', 'loc_end_datetime' => '2024-02-03 09:00:00', 'returning_datetime' => '2024-02-04 11:00:00']
        ];
        
        $this->analyseRepository
            ->method('getLateRentals')
            ->willReturn($expectedData);
        
        $response = $this->analyseService->listLateRentals();
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Locations en retard récupérées avec succès.", $response->getMessage());
        $this->assertEquals($expectedData, $response->getData());
    }

    public function testListLateRentalsServerError() {
        $this->analyseRepository
            ->method('getLateRentals')
            ->willThrowException(new \Exception("Database error"));
        
        $response = $this->analyseService->listLateRentals();
        
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertStringContainsString("Une erreur est survenue lors de la récupération des locations en retard", $response->getMessage());
    }
}
