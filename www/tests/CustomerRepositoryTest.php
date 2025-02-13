<?php

namespace Tests\Repository;

use Lucpa\Repository\CustomerRepository;
use Lucpa\Model\Customer;
use PHPUnit\Framework\TestCase;
use MongoDB\Collection;
use MongoDB\BSON\ObjectId;
use MongoDB\DeleteResult;
use MongoDB\InsertOneResult;
use MongoDB\UpdateResult;

class CustomerRepositoryTest extends TestCase {
    private $mongoClient;
    private $collection;
    private $repository;

    protected function setUp(): void {
        $this->mongoClient = $this->createMock(\MongoDB\Client::class);
        $this->collection = $this->createMock(\MongoDB\Collection::class);
    
        // Ensure the mock client returns the mock collection
        $this->mongoClient
            ->method('__get')
            ->with('easyloc')
            ->willReturn((object)['customers' => $this->collection]);
    
        $this->repository = new CustomerRepository($this->mongoClient);
    }
    

    public function testGetByFullNameSuccess() {
        $expectedData = [
            '_id' => new ObjectId("67aca05e988b1955d10cd272"),
            'first_name' => 'John',
            'second_name' => 'Doe',
            'address' => '123 Street',
            'permit_number' => 'P12345'
        ];
        
        $this->collection->method('findOne')->willReturn($expectedData);
        
        $result = $this->repository->getByFullName('John', 'Doe');
        $this->assertInstanceOf(Customer::class, $result);
        $this->assertEquals('John', $result->getFirstName());
    }

    public function testGetByFullNameNotFound() {
        $this->collection->method('findOne')->willReturn(null);
        
        $result = $this->repository->getByFullName('Jane', 'Doe');
        $this->assertNull($result);
    }

    public function testSaveNewCustomerSuccess() {
        $customer = new Customer(null, 'John', 'Doe', '123 Street', 'P12345');
        $insertResult = $this->createMock(InsertOneResult::class);
        
        $insertResult->method('getInsertedId')->willReturn(new ObjectId());
        $this->collection->method('insertOne')->willReturn($insertResult);
        
        $this->repository->save($customer);
        
        $this->assertNotNull($customer->getId());
    }

    public function testSaveExistingCustomerSuccess() {
        $customer = new Customer((string)new ObjectId(), 'John', 'Doe', '123 Street', 'P12345');
        
        $this->collection->method('updateOne')->willReturn($this->createMock(UpdateResult::class));
        
        $this->repository->save($customer);
        $this->assertNotNull($customer->getId());
    }

    public function testUpdateCustomerSuccess() {
        $updateResult = $this->createMock(UpdateResult::class);
        $updateResult->method('getModifiedCount')->willReturn(1);
        
        $this->collection->method('updateOne')->willReturn($updateResult);
        
        $result = $this->repository->update((string)new ObjectId(), 'John', 'Doe', '456 Avenue', 'P54321');
        $this->assertTrue($result);
    }

    public function testUpdateCustomerNotFound() {
        $updateResult = $this->createMock(UpdateResult::class);
        $updateResult->method('getModifiedCount')->willReturn(0);
        
        $this->collection->method('updateOne')->willReturn($updateResult);
        
        $result = $this->repository->update((string)new ObjectId(), 'Jane', 'Doe', '456 Avenue', 'P54321');
        $this->assertFalse($result);
    }

    public function testDeleteCustomerSuccess() {
        $deleteResult = $this->createMock(DeleteResult::class);
        $deleteResult->method('getDeletedCount')->willReturn(1);
        
        $this->collection->method('deleteOne')->willReturn($deleteResult);
        
        $result = $this->repository->delete((string)new ObjectId());
        $this->assertTrue($result);
    }

    public function testDeleteCustomerNotFound() {
        $deleteResult = $this->createMock(DeleteResult::class);
        $deleteResult->method('getDeletedCount')->willReturn(0);
        
        $this->collection->method('deleteOne')->willReturn($deleteResult);
        
        $result = $this->repository->delete((string)new ObjectId());
        $this->assertFalse($result);
    }
}
