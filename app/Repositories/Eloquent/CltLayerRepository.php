<?php

namespace App\Repositories\Eloquent;

use App\Models\CltLayer;
use App\Repositories\Interfaces\CltLayerRepositoryInterface;

class CltLayerRepository implements CltLayerRepositoryInterface
{
    public function findByLayup($layupId) {
        return CltLayer::where('layup_id', $layupId)->get();
    }

    public function find($id) { 
        return CltLayer::findOrFail($id); 
    }

    public function create(array $data) { 
        return CltLayer::create($data); 
    }

    public function update($id, array $data) {
        $item = $this->find($id);
        $item->update($data);
        return $item;
    }

    public function delete($id) { 
        return CltLayer::destroy($id); 
    }

}