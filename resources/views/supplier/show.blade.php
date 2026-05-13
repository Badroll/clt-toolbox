@extends('layouts.app2')

@section('content')
<div class="min-h-screen bg-[#F5F4F0]">

    <nav class="bg-white border-b border-stone-200 px-6 py-0 flex items-center justify-between h-14">
        <div class="flex items-center gap-8">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-[#3D6B4F] rounded-md flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h8M4 18h8"/>
                    </svg>
                </div>
                <div class="text-xs font-semibold text-stone-800 leading-none">CLT Layup</div>
            </div>
            <div class="flex items-center gap-1 text-sm">
                <a href="{{ route('suppliers.index') }}" 
                   class="px-3 py-4 text-[#3D6B4F] font-semibold border-b-2 border-[#3D6B4F]">Suppliers</a>
                <a href="#" class="px-3 py-4 text-stone-500 hover:text-stone-800 transition-colors">Layups</a>
                <a href="#" class="px-3 py-4 text-stone-500 hover:text-stone-800 transition-colors">Layers</a>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button class="relative p-2 text-stone-400 hover:text-stone-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </button>
            <div class="flex items-center gap-2 pl-3 border-l border-stone-200">
                <div class="w-8 h-8 rounded-full bg-[#3D6B4F] flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="text-right hidden sm:block">
                    <div class="text-xs font-semibold text-stone-800">{{ auth()->user()->name ?? 'User' }}</div>
                    <div class="text-[10px] text-stone-400">{{ auth()->user()->role ?? 'User' }}</div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-6 py-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-[11px] uppercase tracking-wider font-semibold text-stone-400 mb-6">
            <a href="{{ route('suppliers.index') }}" class="hover:text-[#3D6B4F] transition-colors">Suppliers</a>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-stone-600">{{ $supplier->name }}</span>
        </nav>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="mb-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg">
            <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Supplier Header Card --}}
        <div class="bg-white rounded-xl border border-stone-200 shadow-sm mb-6 overflow-hidden">
            <div class="px-6 py-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-stone-800">{{ $supplier->name }}</h1>
                        <p class="text-xs text-stone-400 font-mono mt-0.5 uppercase tracking-tight">ID: SUP-{{ str_pad($supplier->id, 7, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
                <a href="{{ route('suppliers.edit', $supplier) }}"
                    class="inline-flex items-center gap-2 text-sm text-stone-600 border border-stone-200 bg-white hover:bg-stone-50 px-4 py-2 rounded-lg transition shadow-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Supplier
                </a>
            </div>
        </div>

        {{-- Associated Layups Table --}}
        <div class="bg-white rounded-xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-stone-100 flex items-center justify-between bg-stone-50/30">
                <div>
                    <h2 class="text-sm font-bold text-stone-800">Associated Layups</h2>
                    <p class="text-[11px] text-stone-400">List of all layups provided by this supplier.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="document.getElementById('import-modal').classList.remove('hidden')"
                        class="inline-flex items-center gap-1.5 text-xs font-medium text-stone-600 border border-stone-200 bg-white hover:bg-stone-50 px-3 py-2 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Import
                    </button>
                    <a href="{{ route('suppliers.export', $supplier) ?? '#' }}"
                        class="inline-flex items-center gap-1.5 text-xs font-medium text-stone-600 border border-stone-200 bg-white hover:bg-stone-50 px-3 py-2 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                    </a>
                    <a href="{{ route('suppliers.layups.create', $supplier) }}" 
                        class="inline-flex items-center gap-2 bg-[#3D6B4F] hover:bg-[#2f5540] text-white text-xs font-medium px-4 py-2 rounded-lg transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Layup
                    </a>
                </div>
            </div>

            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-stone-100 bg-stone-50/60">
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-stone-400 uppercase tracking-wider">Layup ID</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-stone-400 uppercase tracking-wider">Name</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-stone-400 uppercase tracking-wider">Layers</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-stone-400 uppercase tracking-wider">Created At</th>
                        <th class="text-right px-5 py-3 text-[11px] font-semibold text-stone-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($supplier->layups as $layup)
                    <tr class="hover:bg-stone-50/50 transition-colors group">
                        <td class="px-5 py-3.5 font-mono text-[10px] text-stone-400">
                            L-{{ str_pad($layup->id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-5 py-3.5">
                            <a href="{{ route('layups.show', $layup) ?? '#' }}"
                                class="font-medium text-stone-800 hover:text-[#3D6B4F] transition-colors">
                                {{ $layup->name }}
                            </a>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-stone-100 text-stone-600">
                                {{ $layup->layers->count() }} Layers
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-stone-500">
                            {{ $layup->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                x-data="{ confirmDelete: false }">
                                <a href="{{ route('layups.show', $layup) ?? '#' }}"
                                    class="p-1.5 text-stone-400 hover:text-[#3D6B4F] hover:bg-green-50 rounded-md transition-colors" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('layups.edit', $layup) ?? '#' }}"
                                    class="p-1.5 text-stone-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button @click="confirmDelete = true"
                                    class="p-1.5 text-stone-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>

                                {{-- Delete Modal (Styled Like Index) --}}
                                <div x-show="confirmDelete" x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
                                    <div @click.outside="confirmDelete = false"
                                        class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm mx-4 text-left">
                                        <h3 class="text-sm font-bold text-stone-800 mb-2">Delete Layup</h3>
                                        <p class="text-xs text-stone-500 mb-5 leading-relaxed">
                                            Are you sure you want to delete <strong>{{ $layup->name }}</strong>? This will also remove all its associated layers. This action cannot be undone.
                                        </p>
                                        <div class="flex gap-2 justify-end">
                                            <button @click="confirmDelete = false"
                                                class="px-4 py-2 text-xs font-semibold text-stone-600 border border-stone-200 rounded-lg hover:bg-stone-50 transition">
                                                Cancel
                                            </button>
                                            <form method="POST" action="{{ route('layups.destroy', $layup) ?? '#' }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
                                                    Delete Layup
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-stone-400">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-stone-500">No layups found</p>
                                    <p class="text-xs text-stone-400 mt-1">Start by adding or importing layups for this supplier.</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($supplier->layups->isNotEmpty())
            <div class="px-5 py-3 border-t border-stone-100 text-[11px] text-stone-400 bg-stone-50/30">
                Showing total of {{ $supplier->layups->count() }} registered layup(s)
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Import Modal --}}
<div id="import-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-stone-100 bg-stone-50/50">
            <h3 class="text-sm font-bold text-stone-800">Import Layup Data</h3>
            <button onclick="document.getElementById('import-modal').classList.add('hidden')" class="text-stone-400 hover:text-stone-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="import-form" method="POST" action="{{ route('suppliers.import', $supplier) ?? '#' }}" enctype="multipart/form-data" class="px-6 py-5 space-y-5">
            @csrf
            <div class="border-2 border-dashed border-stone-200 rounded-xl p-8 text-center cursor-pointer hover:border-[#3D6B4F]/40 hover:bg-stone-50/50 transition-all group"
                onclick="document.getElementById('import-file').click()">
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-[#3D6B4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <p class="text-sm text-stone-600">
                    <span class="text-[#3D6B4F] font-bold">Upload file</span> or drag and drop
                </p>
                <p class="text-[10px] text-stone-400 mt-1 uppercase tracking-wider font-semibold">CSV or JSON (Max 10MB)</p>
                <input type="file" id="import-file" name="file" accept=".json,.csv" class="hidden">
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-[11px] font-bold text-stone-400 uppercase tracking-widest mb-1.5">Conflict Strategy</label>
                    <select name="strategy" class="w-full px-3 py-2 text-sm border border-stone-200 rounded-lg focus:ring-2 focus:ring-[#3D6B4F]/30 focus:border-[#3D6B4F] outline-none transition">
                        <option value="skip">Skip conflicts</option>
                        <option value="overwrite">Overwrite existing</option>
                        <option value="duplicate">Keep both (Duplicate)</option>
                    </select>
                </div>

                <label class="flex items-start gap-3 p-3 bg-stone-50 rounded-lg cursor-pointer hover:bg-stone-100 transition">
                    <input type="checkbox" name="dry_run" value="1" class="mt-0.5 w-4 h-4 rounded border-stone-300 text-[#3D6B4F] focus:ring-[#3D6B4F]">
                    <div>
                        <span class="text-xs font-bold text-stone-700">Dry Run Simulation</span>
                        <p class="text-[10px] text-stone-500 mt-0.5 leading-relaxed">Validate data and see results without modifying the database.</p>
                    </div>
                </label>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-stone-100">
                <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')"
                    class="px-4 py-2 text-xs font-bold text-stone-500 hover:text-stone-700 transition">
                    Cancel
                </button>
                <button type="submit" class="bg-[#3D6B4F] hover:bg-[#2f5540] text-white text-xs font-bold px-6 py-2 rounded-lg transition shadow-sm">
                    Start Import
                </button>
            </div>
        </form>
    </div>
</div>
{{-- Tambahkan Wadah Modal Resolusi Konflik (Sesuai Halaman 3 PDF) --}}
<div id="conflict-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-md">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl mx-4 max-h-[90vh] flex flex-col overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-stone-100 flex justify-between items-center bg-stone-50">
            <h3 class="font-bold text-stone-800">Conflict Resolution: <span id="conflict-filename"></span></h3>
            <button onclick="closeConflictModal()" class="text-stone-400 hover:text-stone-600">✕</button>
        </div>
        
        <div class="flex flex-1 overflow-hidden">
            {{-- Sidebar Daftar Konflik (Kiri) --}}
            <div class="w-64 border-r border-stone-100 overflow-y-auto bg-stone-50/50 p-4 space-y-2" id="conflict-list">
                </div>

            {{-- Detail Perbandingan (Kanan) --}}
            <div class="flex-1 overflow-y-auto p-6" id="conflict-detail">
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-xs font-bold text-stone-400 uppercase mb-4">Existing Version</h4>
                        <div id="existing-container" class="space-y-4"></div>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-[#3D6B4F] uppercase mb-4">Importing Version</h4>
                        <div id="importing-container" class="space-y-4"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-stone-100 flex justify-between items-center">
            <span id="conflict-pagination" class="text-xs text-stone-400 font-medium"></span>
            <div class="flex gap-2">
                <button onclick="closeConflictModal()" class="px-4 py-2 text-xs font-bold text-stone-500">Cancel Import</button>
                <button id="btn-keep-existing" class="px-4 py-2 text-xs font-bold border border-stone-200 rounded-lg">Keep Existing</button>
                <button id="btn-accept-new" class="px-4 py-2 text-xs font-bold bg-[#3D6B4F] text-white rounded-lg">Accept New</button>
            </div>
        </div>
    </div>
</div>

<script>

    document.getElementById('import-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const btn = e.target.querySelector('button[type="submit"]');
    
    btn.disabled = true;
    btn.innerText = 'Processing...';

    try {
        const response = await fetch(e.target.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const data = await response.json();

        if (data.report.summary.conflicts > 0) {
            // Jika ada konflik, buka modal resolusi (Halaman 3 PDF)
            showConflictInterface(data.report.conflicts_detail);
        } else {
            alert('Import Successful!');
            window.location.reload();
        }
    } catch (error) {
        alert('Something went wrong');
    } finally {
        btn.disabled = false;
        btn.innerText = 'Start Import';
    }
});

function showConflictInterface(conflicts) {
    document.getElementById('import-modal').classList.add('hidden');
    const modal = document.getElementById('conflict-modal');
    modal.classList.remove('hidden');

    const listContainer = document.getElementById('conflict-list');
    listContainer.innerHTML = '';

    conflicts.forEach((item, index) => {
        const btn = document.createElement('button');
        btn.className = `w-full text-left p-3 rounded-lg text-xs font-medium transition ${index === 0 ? 'bg-white shadow-sm border border-stone-200 text-[#3D6B4F]' : 'text-stone-500 hover:bg-stone-100'}`;
        btn.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${item.layup_name}</span>
                <span class="w-2 h-2 bg-red-400 rounded-full"></span>
            </div>
            <div class="text-[10px] text-stone-400 mt-1">${item.layers.length} conflicts</div>
        `;
        btn.onclick = () => renderConflictDetail(item, conflicts.length, index + 1);
        listContainer.appendChild(btn);
    });

    // Render item pertama secara default
    renderConflictDetail(conflicts[0], conflicts.length, 1);
}

function renderConflictDetail(data, total, current) {
    document.getElementById('conflict-pagination').innerText = `${current} of ${total} DISCREPANCIES`;
    const existingContainer = document.getElementById('existing-container');
    const importingContainer = document.getElementById('importing-container');
    
    existingContainer.innerHTML = '';
    importingContainer.innerHTML = '';

    data.layers.forEach(layer => {
        // Buat tabel perbandingan kecil untuk setiap layer yang konflik
        const htmlExisting = createLayerCard(layer.existing, layer.diff_fields, false);
        const htmlImporting = createLayerCard(layer.importing, layer.diff_fields, true);
        
        existingContainer.insertAdjacentHTML('beforeend', htmlExisting);
        importingContainer.insertAdjacentHTML('beforeend', htmlImporting);
    });
}

function createLayerCard(layer, diffs, isImporting) {
    const highlight = (field) => diffs.includes(field) ? 'bg-red-50 text-red-600 font-bold' : '';
    return `
        <div class="p-4 border border-stone-100 rounded-lg bg-white text-xs space-y-2">
            <div class="flex justify-between ${highlight('thickness')} p-1 rounded">
                <span>Thickness:</span> <span>${layer.thickness}mm</span>
            </div>
            <div class="flex justify-between ${highlight('width')} p-1 rounded">
                <span>Width:</span> <span>${layer.width}mm</span>
            </div>
            <div class="flex justify-between ${highlight('angle')} p-1 rounded">
                <span>Angle:</span> <span>${layer.angle}°</span>
            </div>
        </div>
    `;
}

function closeConflictModal() {
    document.getElementById('conflict-modal').classList.add('hidden');
}


</script>
@endsection