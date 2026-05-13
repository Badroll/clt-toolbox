<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\ImportSupplierRequest;
use App\Repositories\Interfaces\SupplierRepositoryInterface;
use App\Services\CltService;

use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $supplierRepo;
    protected $cltService;

    public function __construct(
        SupplierRepositoryInterface $supplierRepo,
        CltService $cltService
    ) {
        $this->supplierRepo = $supplierRepo;
        $this->cltService = $cltService;
    }

    public function index()
    {
        $this->authorize('viewAny', Supplier::class);
        $suppliers = $this->supplierRepo->all();
        
        return response()->json($suppliers);
    }

    public function store(StoreSupplierRequest $request)
    {
        $this->authorize('create', Supplier::class);

        $data = array_merge($request->validated(), ['user_id' => auth()->id()]);
        
        $supplier = $this->supplierRepo->create($data);

        return response()->json([
            'message' => 'Supplier created successfully',
            'data' => $supplier
        ], 201);
    }

    public function show(Supplier $supplier)
    {
        $this->authorize('view', $supplier);
        
        return response()->json($supplier->load('layups.layers'));
    }

    public function update(StoreSupplierRequest $request, Supplier $supplier)
    {
        $this->authorize('update', $supplier);
        
        $updatedSupplier = $this->supplierRepo->update($supplier->id, $request->validated());

        return response()->json($updatedSupplier);
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorize('delete', $supplier);
        
        $this->supplierRepo->delete($supplier->id);

        return response()->json(['message' => 'Supplier deleted']);
    }


    public function export(Supplier $supplier) {
        $this->authorize('view', $supplier);
        
        $data = $this->supplierRepo->find($supplier->id)->load('layups.layers');
        
        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename="' . $supplier->name . '_export.json"',
        ]);
    }


    public function import(ImportSupplierRequest $request, Supplier $supplier) {
        $this->authorize('update', $supplier);

        $fileContent = json_decode(file_get_contents($request->file('file')), true);
        
        if (!$fileContent) {
            return response()->json(['message' => 'Invalid JSON file'], 422);
        }

        $results = $this->cltService->processImport($supplier, $fileContent, [
            'strategy' => $request->strategy,
            'dry_run' => $request->boolean('dry_run')
        ]);

        return response()->json([
            'message' => $request->boolean('dry_run') ? 'Dry run completed' : 'Import processed',
            'report' => $results
        ]);
    }

}