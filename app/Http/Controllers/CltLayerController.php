<?php

namespace App\Http\Controllers;

use App\Models\CltLayup;
use App\Models\CltLayer;
use App\Http\Requests\StoreLayerRequest;
use App\Repositories\Interfaces\CltLayerRepositoryInterface;

class CltLayerController extends Controller
{
    protected $layerRepo;

    public function __construct(CltLayerRepositoryInterface $layerRepo)
    {
        $this->layerRepo = $layerRepo;
    }

    public function store(StoreLayerRequest $request, CltLayup $cltLayup)
    {
        $this->authorize('create', CltLayer::class);

        $data = array_merge($request->validated(), [
            'layup_id' => $cltLayup->id
        ]);

        $layer = $this->layerRepo->create($data);

        return response()->json($layer, 201);
    }

    public function destroy(CltLayer $cltLayer)
    {
        $this->authorize('delete', $cltLayer);
        $this->layerRepo->delete($cltLayer->id);

        return response()->json(['message' => 'Layer deleted']);
    }
}
