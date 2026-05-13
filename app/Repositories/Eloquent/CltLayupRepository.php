<?php

namespace App\Repositories\Eloquent;

use App\Models\CltLayup;
use App\Repositories\Interfaces\CltLayupRepositoryInterface;

class CltLayupRepository implements CltLayupRepositoryInterface
{
    public function findBySupplier($supplierId) {
        return CltLayup::where('supplier_id', $supplierId)->get();
    }

    public function find($id) {
        return CltLayup::findOrFail($id);
    }

    public function create(array $data) { 
        return CltLayup::create($data); 
    }

    public function update($id, array $data) {
        $item = $this->find($id);
        $item->update($data);
        return $item;
    }

    public function delete($id) {
        return CltLayup::destroy($id); 
    }
}