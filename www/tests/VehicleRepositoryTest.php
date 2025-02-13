<?php

namespace Tests\Repository;

use Lucpa\Repository\VehicleRepository;
use Lucpa\Model\Vehicle;
use PHPUnit\Framework\TestCase;
use MongoDB\Collection;
use MongoDB\BSON\ObjectId;
use MongoDB\DeleteResult;
use MongoDB\InsertOneResult;
use MongoDB\UpdateResult;

class VehicleRepositoryTest extends TestCase {
    private $mongoClient;
    private $collection;
    private $repository;

    protected function setUp(): void {
        $this->mongoClient = $this->createMock(\MongoDB\Client::class);
        $this->collection = $this->createMock(Collection::class);
        
        $this->mongoClient
            ->method('__get')
            ->with('easyloc')
            ->willReturn((object)['vehicles' => $this->collection]);
        
        $this->repository = new VehicleRepository($this->mongoClient);
    }

    public function testGetByLicencePlateSuccess() {
        $expectedData = [
            '_id' => new ObjectId(),
            'model' => 'Toyota',
            'licence_plate' => 'ABC-123',
            'informations' => 'Red sedan',
            'km' => 50000
        ];
        
        $this->collection->method('findOne')->willReturn($expectedData);
        
        $result = $this->repository->getByLicencePlate('ABC-123');
        $this->assertInstanceOf(Vehicle::class, $result);
        $this->assertEquals('Toyota', $result->getModel());
    }

    public function testGetByLicencePlateNotFound() {
        $this->collection->method('findOne')->willReturn(null);
        
        $result = $this->repository->getByLicencePlate('XYZ-999');
        $this->assertNull($result);
    }

    public function testSaveNewVehicleSuccess() {
        $vehicle = new Vehicle(null, 'Toyota', 'ABC-123', 'Red sedan', 50000);
        $insertResult = $this->createMock(InsertOneResult::class);
        
        $insertResult->method('getInsertedId')->willReturn(new ObjectId());
        $this->collection->method('insertOne')->willReturn($insertResult);
        
        $this->repository->save($vehicle);
        
        $this->assertNotNull($vehicle->getId());
    }

    public function testSaveExistingVehicleSuccess() {
        $vehicle = new Vehicle((string)new ObjectId(), 'Toyota', 'ABC-123', 'Red sedan', 50000);
        
        $this->collection->method('updateOne')->willReturn($this->createMock(UpdateResult::class));
        
        $this->repository->save($vehicle);
        $this->assertNotNull($vehicle->getId());
    }

    public function testUpdateVehicleSuccess() {
        $updateResult = $this->createMock(UpdateResult::class);
        $updateResult->method('getModifiedCount')->willReturn(1);
        
        $this->collection->method('updateOne')->willReturn($updateResult);
        
        $result = $this->repository->updateVehicle((string)new ObjectId(), 'Toyota', 'ABC-123', 'Updated info', 60000);
        $this->assertTrue($result);
    }

    public function testUpdateVehicleNotFound() {
        $updateResult = $this->createMock(UpdateResult::class);
        $updateResult->method('getModifiedCount')->willReturn(0);
        
        $this->collection->method('updateOne')->willReturn($updateResult);
        
        $result = $this->repository->updateVehicle((string)new ObjectId(), 'Toyota', 'XYZ-999', 'Updated info', 60000);
        $this->assertFalse($result);
    }

    public function testDeleteVehicleSuccess() {
        $deleteResult = $this->createMock(DeleteResult::class);
        $deleteResult->method('getDeletedCount')->willReturn(1);
        
        $this->collection->method('deleteOne')->willReturn($deleteResult);
        
        $result = $this->repository->delete((string)new ObjectId());
        $this->assertTrue($result);
    }

    public function testDeleteVehicleNotFound() {
        $deleteResult = $this->createMock(DeleteResult::class);
        $deleteResult->method('getDeletedCount')->willReturn(0);
        
        $this->collection->method('deleteOne')->willReturn($deleteResult);
        
        $result = $this->repository->delete((string)new ObjectId());
        $this->assertFalse($result);
    }
}
