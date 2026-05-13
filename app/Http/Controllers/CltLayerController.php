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

    public function create(CltLayup $layup)
    {
        $this->authorize('view', $layup);

        return view('layer.create', compact('layup'));
    }

    public function store(StoreLayerRequest $request, CltLayup $layup)
    {
        $this->authorize('create', CltLayer::class);

        $data = array_merge($request->validated(), [
            'layup_id' => $layup->id
        ]);

        $layer = $this->layerRepo->create($data);

        return redirect()->route('layups.show', $layup)
            ->with('success', 'Layer added successfully');
    }

    public function edit(CltLayer $cltLayer)
    {
        $this->authorize('view', $cltLayer);

        return view('layer.edit', [
            'cltLayer' => $cltLayer
        ]);
    }

    public function update(StoreLayerRequest $request, CltLayer $cltLayer)
    {
        $this->authorize('update', $cltLayer);
        
        $updated = $this->layerRepo->update($cltLayer->id, $request->validated());

        return response()->json($updated);
    }


    public function destroy(CltLayer $cltLayer)
    {
        $this->authorize('delete', $cltLayer);
        $this->layerRepo->delete($cltLayer->id);

        return response()->json(['message' => 'Layer deleted']);
    }
}
