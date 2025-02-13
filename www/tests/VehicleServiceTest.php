<?php

namespace Tests\Service;

use Lucpa\Service\VehicleService;
use Lucpa\Repository\VehicleRepository;
use Lucpa\Model\Vehicle;
use Lucpa\Service\Response;
use PHPUnit\Framework\TestCase;

class VehicleServiceTest extends TestCase {
    private $vehicleRepository;
    private $vehicleService;

    protected function setUp(): void {
        $this->vehicleRepository = $this->createMock(VehicleRepository::class);
        $this->vehicleService = new VehicleService($this->vehicleRepository);
    }

    public function testSaveVehicleSuccess() {
        $vehicle = new Vehicle(null, 'Toyota', 'ABC-123', 'Car details', 10000);
        
        $this->vehicleRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($arg) use ($vehicle) {
                return $arg instanceof Vehicle && $arg->getModel() === 'Toyota' && $arg->getLicencePlate() === 'ABC-123';
            }));
        
        $response = $this->vehicleService->saveVehicle('Toyota', 'ABC-123', 'Car details', 10000);
        
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals("Véhicule enregistré avec succès.", $response->getMessage());
    }

    public function testSaveVehicleWithInvalidModel() {
        $response = $this->vehicleService->saveVehicle('', 'ABC-123', 'Car details', 10000);
        
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("Le modèle ou l'immatriculation ne peut pas être vide.", $response->getMessage());
    }

    public function testGetVehicleByLicencePlateSuccess() {
        $vehicle = new Vehicle(1, 'Toyota', 'ABC-123', 'Car details', 10000);
        
        $this->vehicleRepository
            ->method('getByLicencePlate')
            ->willReturn($vehicle);
        
        $response = $this->vehicleService->getVehicleByLicencePlate('ABC-123');
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Véhicule trouvé.", $response->getMessage());
        $this->assertEquals($vehicle, $response->getData());
    }

    public function testGetVehicleByLicencePlateNotFound() {
        $this->vehicleRepository
            ->method('getByLicencePlate')
            ->willReturn(null);
        
        $response = $this->vehicleService->getVehicleByLicencePlate('XYZ-999');
        
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Véhicule non trouvé.", $response->getMessage());
    }

    public function testUpdateVehicleSuccess() {
        $this->vehicleRepository
            ->method('updateVehicle')
            ->willReturn(true);
        
        $response = $this->vehicleService->updateVehicle(1, 'Toyota', 'ABC-123', 'Updated details', 12000);
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Véhicule mis à jour avec succès.", $response->getMessage());
    }

    public function testUpdateVehicleNotFound() {
        $this->vehicleRepository
            ->method('updateVehicle')
            ->willReturn(false);
        
        $response = $this->vehicleService->updateVehicle(1, 'Toyota', 'ABC-123', 'Updated details', 12000);
        
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Véhicule non trouvé pour mise à jour.", $response->getMessage());
    }

    public function testDeleteVehicleSuccess() {
        $this->vehicleRepository
            ->method('delete')
            ->willReturn(true);
        
        $response = $this->vehicleService->deleteVehicle(1);
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Véhicule supprimé avec succès.", $response->getMessage());
    }

    public function testDeleteVehicleNotFound() {
        $this->vehicleRepository
            ->method('delete')
            ->willReturn(false);
        
        $response = $this->vehicleService->deleteVehicle(999);
        
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Véhicule non trouvé.", $response->getMessage());
    }
}
