<?php
namespace Lucpa\Service;

use Lucpa\Repository\AnalyseRepository;


class AnalyseService {
    private $analyseRepository;

    // Constructor to initialize the repository
    public function __construct(AnalyseRepository $analyseRepository) {
        $this->analyseRepository = $analyseRepository;
    }

    // List all ongoing rentals associated with a customer UID
    public function listOngoingRentalsByCustomerUid($customerUid) {
        try {
            $rentals = $this->analyseRepository->getOngoingRentalsByCustomerUid($customerUid);
            return new Response(200, "Ongoing rentals fetched successfully.", $rentals);
        } catch (\Exception $e) {
            return new Response(500, "An error occurred while fetching ongoing rentals: " . $e->getMessage());
        }
    }

    // List all late rentals (returning time is more than 1 hour after end time)
    public function listLateRentals() {
        try {
            $lateRentals = $this->analyseRepository->getLateRentals();
            return new Response(200, "Late rentals fetched successfully.", $lateRentals);
        } catch (\Exception $e) {
            return new Response(500, "An error occurred while fetching late rentals: " . $e->getMessage());
        }
    }

    // List all payments associated with a given rental (contract ID)
    public function listPaymentsByContractId($contractId) {
        try {
            $payments = $this->analyseRepository->getPaymentsByContractId($contractId);
            return new Response(200, "Payments fetched successfully.", $payments);
        } catch (\Exception $e) {
            return new Response(500, "An error occurred while fetching payments: " . $e->getMessage());
        }
    }

    // Check if a rental has been fully paid
    public function isRentalFullyPaid($contractId) {
        try {
            $isPaid = $this->analyseRepository->isRentalFullyPaid($contractId);
            return new Response(200, $isPaid ? "Rental is fully paid." : "Rental is not fully paid.");
        } catch (\Exception $e) {
            return new Response(500, "An error occurred while checking payment status: " . $e->getMessage());
        }
    }

    // List all unpaid rentals
    public function listUnpaidRentals() {
        try {
            $unpaidRentals = $this->analyseRepository->getUnpaidRentals();
            return new Response(200, "Unpaid rentals fetched successfully.", $unpaidRentals);
        } catch (\Exception $e) {
            return new Response(500, "An error occurred while fetching unpaid rentals: " . $e->getMessage());
        }
    }

    // Count the number of late rentals between two given dates
    public function countLateRentalsBetweenDates($startDate, $endDate) {
        try {
            $count = $this->analyseRepository->countLateRentalsBetweenDates($startDate, $endDate);
            return new Response(200, "Late rentals count between dates.", ['count' => $count]);
        } catch (\Exception $e) {
            return new Response(500, "An error occurred while counting late rentals: " . $e->getMessage());
        }
    }

    // Count the average number of late rentals per customer
    public function countAverageLateRentalsPerCustomer() {
        try {
            $averageLateRentals = $this->analyseRepository->countAverageLateRentalsPerCustomer();
            return new Response(200, "Average late rentals per customer.", $averageLateRentals);
        } catch (\Exception $e) {
            return new Response(500, "An error occurred while calculating average late rentals per customer: " . $e->getMessage());
        }
    }

    // List all contracts where a certain vehicle was used
    public function listContractsByVehicleUid($vehicleUid) {
        try {
            $contracts = $this->analyseRepository->getContractsByVehicleUid($vehicleUid);
            return new Response(200, "Contracts for vehicle fetched successfully.", $contracts);
        } catch (\Exception $e) {
            return new Response(500, "An error occurred while fetching contracts for vehicle: " . $e->getMessage());
        }
    }

    // Get the average delay by vehicle (in hours)
    public function getAverageDelayByVehicle() {
        try {
            $averageDelays = $this->analyseRepository->getAverageDelayByVehicle();
            return new Response(200, "Average delay by vehicle fetched successfully.", $averageDelays);
        } catch (\Exception $e) {
            return new Response(500, "An error occurred while fetching average delay by vehicle: " . $e->getMessage());
        }
    }

    // Retrieve all contracts grouped by vehicle
    public function getContractsGroupedByVehicle() {
        try {
            $contractsGroupedByVehicle = $this->analyseRepository->getContractsGroupedByVehicle();
            return new Response(200, "Contracts grouped by vehicle fetched successfully.", $contractsGroupedByVehicle);
        } catch (\Exception $e) {
            return new Response(500, "An error occurred while grouping contracts by vehicle: " . $e->getMessage());
        }
    }

    // Retrieve all contracts grouped by customer
    public function getContractsGroupedByCustomer() {
        try {
            $contractsGroupedByCustomer = $this->analyseRepository->getContractsGroupedByCustomer();
            return new Response(200, "Contracts grouped by customer fetched successfully.", $contractsGroupedByCustomer);
        } catch (\Exception $e) {
            return new Response(500, "An error occurred while grouping contracts by customer: " . $e->getMessage());
        }
    }
}
