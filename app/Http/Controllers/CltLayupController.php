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

    public function create(Supplier $supplier)
    {
        // Sekarang $supplier otomatis terisi berkat Route Model Binding
        return view('layup.create', compact('supplier'));
    }

    public function store(StoreLayupRequest $request, Supplier $supplier)
    {
        $this->authorize('create', CltLayup::class);

        $data = array_merge($request->validated(), [
            'supplier_id' => $supplier->id
        ]);

        $layup = $this->layupRepo->create($data);

        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'Layup created successfully');
    }

    public function show(CltLayup $cltLayup)
    {
        // Karena menggunakan .shallow(), show layup tidak butuh ID supplier di URL
        // Cukup /layups/{layup}
        return view('layup.show', compact('cltLayup'));
    }

    public function edit(CltLayup $cltLayup)
    {
        $this->authorize('view', $cltLayup);

        return view('layup.edit', [
            'cltLayup' => $cltLayup
        ]);
    }

    public function update(StoreLayupRequest $request, CltLayup $cltLayup)
    {
        $this->authorize('update', $cltLayup);
        
        $updated = $this->layupRepo->update($cltLayup->id, $request->validated());

        return redirect()->route('suppliers.show', $cltLayup->supplier)->with("success", 'Layup updated');
    }

    public function destroy(CltLayup $cltLayup)
    {
        $this->authorize('delete', $cltLayup);
        $this->layupRepo->delete($cltLayup->id);

        return redirect()->back()->with("success", 'Layup deleted');
    }
}
