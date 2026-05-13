<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\ImportSupplierRequest;
use App\Repositories\Interfaces\SupplierRepositoryInterface;
use App\Services\CltService;
use App\Services\SupplierService;

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

        return view('supplier.index', [
            'suppliers' => $this->supplierRepo->getAllWithCount()
        ]);
    }

    public function create(Supplier $supplier)
    {
        $this->authorize('view', $supplier);

        return view('supplier.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        $this->authorize('create', Supplier::class);

        $data = array_merge($request->validated(), ['user_id' => auth()->id()]);
        
        $supplier = $this->supplierRepo->create($data);

        return redirect("suppliers")->with("success", 'Supplier created successfully');
    }

    public function edit(Supplier $supplier)
    {
        $this->authorize('view', $supplier);

        return view('supplier.edit', [
            'supplier' => $supplier
        ]);
    }

    public function show(Supplier $supplier)
    {
        $this->authorize('view', $supplier);
        
        return view('supplier.show', [
            'supplier' => $supplier
        ]);
    }

    public function update(StoreSupplierRequest $request, Supplier $supplier)
    {
        $this->authorize('update', $supplier);
        
        $updatedSupplier = $this->supplierRepo->update($supplier->id, $request->validated());

        return redirect("suppliers")->with("success", 'Supplier updated');
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorize('delete', $supplier);
        
        $this->supplierRepo->delete($supplier->id);

        return redirect()->back()->with("success", 'Supplier deleted');
    }


    public function export(Supplier $supplier) {
        $this->authorize('view', $supplier);
        
        $data = $this->supplierRepo->find($supplier->id)->load('layups.layers');
        
        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename="' . $supplier->name . '_export.json"',
        ]);
    }


    // harusnya di controller cltLayup
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

    
    public function resolveConflicts(Request $request, Supplier $supplier)
    {
        $this->authorize('update', $supplier);

        $resolutions = $request->input('resolutions', []);
        // Format: { "LayupName::2": "keep"|"accept", ... }

        if (empty($resolutions)) {
            return response()->json(['message' => 'No resolutions provided.'], 422);
        }

        $summary = $this->cltService->applyManualResolutions($supplier, $resolutions);

        return response()->json([
            'message' => 'Resolutions applied successfully.',
            'summary' => $summary,
        ]);
    }

}