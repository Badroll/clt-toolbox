<?php

namespace App\Repositories\Eloquent;

use App\Models\Supplier;
use App\Repositories\Interfaces\SupplierRepositoryInterface;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function all() { 
        return Supplier::all(); 
    }
    public function find($id) { 
        return Supplier::findOrFail($id); 
    }
    public function create(array $data) { 
        return Supplier::create($data); 
    }
    public function update($id, array $data) {
        $supplier = $this->find($id);
        $supplier->update($data);
        return $supplier;
    }
    public function delete($id) { 
        return Supplier::destroy($id); 
    }

    public function getAllWithCount()
    {
        return Supplier::withCount('layups')->latest()->paginate(10);
    }

    public function findWithLayups(int $id)
    {
        return Supplier::with(['layups' => function($query) {
            $query->latest();
        }])->findOrFail($id);
    }
    
}