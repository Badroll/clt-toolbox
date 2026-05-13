<?php

namespace App\Services;

use App\Models\Supplier;

use App\Repositories\Interfaces\SupplierRepositoryInterface;

class SupplierService
{
    protected $supplierRepo;

    public function __construct(SupplierRepositoryInterface $supplierRepo)
    {
        $this->supplierRepo = $supplierRepo;
    }

    public function getAllSuppliers() {
        return $this->supplierRepo->all();
    }
    
    public function exportSupplierData(Supplier $supplier) {
        return $supplier->load(['layups.layers'])->toArray();
    }

}