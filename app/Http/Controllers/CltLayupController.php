<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\CltLayup;
use App\Http\Requests\StoreLayupRequest;
use App\Repositories\Interfaces\CltLayupRepositoryInterface;

class CltLayupController extends Controller
{
    protected $layupRepo;

    public function __construct(CltLayupRepositoryInterface $layupRepo)
    {
        $this->layupRepo = $layupRepo;
    }

    public function store(StoreLayupRequest $request, Supplier $supplier)
    {
        $this->authorize('create', CltLayup::class);

        $data = array_merge($request->validated(), [
            'supplier_id' => $supplier->id
        ]);

        $layup = $this->layupRepo->create($data);

        return response()->json($layup, 201);
    }

    public function update(StoreLayupRequest $request, CltLayup $cltLayup)
    {
        $this->authorize('update', $cltLayup);
        
        $updated = $this->layupRepo->update($cltLayup->id, $request->validated());

        return response()->json($updated);
    }

    public function destroy(CltLayup $cltLayup)
    {
        $this->authorize('delete', $cltLayup);
        $this->layupRepo->delete($cltLayup->id);

        return response()->json(['message' => 'Layup deleted']);
    }
}
