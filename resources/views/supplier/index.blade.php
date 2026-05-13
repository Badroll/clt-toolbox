@extends('layouts.app2')

@section('content')
<div class="min-h-screen bg-[#F5F4F0]">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-stone-200 px-6 py-0 flex items-center justify-between h-14">
        <div class="flex items-center gap-8">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-[#3D6B4F] rounded-md flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h8M4 18h8"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold text-stone-800 leading-none">CLT Layup</div>
                </div>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </button>
            <div class="flex items-center gap-2 pl-3 border-l border-stone-200">
                <div class="w-8 h-8 rounded-full bg-[#3D6B4F] flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="text-right hidden sm:block">
                    <div class="text-xs font-semibold text-stone-800">{{ auth()->user()->name ?? 'User' }}</div>
                    <div class="text-[10px] text-stone-400">{{ auth()->user()->role ?? 'User'  }}</div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <div class="max-w-6xl mx-auto px-6 py-8">

        {{-- Header --}}
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-stone-800">Suppliers</h1>
                <p class="text-sm text-stone-500 mt-1">Manage timber suppliers and material sourcing.</p>
            </div>
            <a href="{{ route('suppliers.create') }}"
                class="inline-flex items-center gap-2 bg-[#3D6B4F] hover:bg-[#2f5540] text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Supplier
            </a>
        </div>

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

        {{-- Toolbar --}}
        <div class="flex items-center justify-between mb-4">
            <div class="relative w-72">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-stone-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input type="text" id="search-input" placeholder="Search suppliers by name..."
                    class="w-full pl-9 pr-4 py-2 text-sm border border-stone-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#3D6B4F]/30 focus:border-[#3D6B4F] transition"/>
            </div>
            <div class="flex items-center gap-2">
                <button class="inline-flex items-center gap-2 text-sm text-stone-600 border border-stone-200 bg-white hover:bg-stone-50 px-3 py-2 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl border border-stone-200 shadow-sm overflow-hidden">
            <table class="w-full text-sm" id="suppliers-table">
                <thead>
                    <tr class="border-b border-stone-100 bg-stone-50/60">
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-stone-400 uppercase tracking-wider">Name</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-stone-400 uppercase tracking-wider">Total Layups</th>
                        <th class="text-left px-5 py-3 text-[11px] font-semibold text-stone-400 uppercase tracking-wider">Created At</th>
                        <th class="text-right px-5 py-3 text-[11px] font-semibold text-stone-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($suppliers as $supplier)
                    <tr class="hover:bg-stone-50/50 transition-colors group supplier-row"
                        data-name="{{ strtolower($supplier->name) }}">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0"
                                    style="background-color: {{ ['#3D6B4F','#6B7C3D','#4F5E6B','#7C3D6B','#6B4F3D'][crc32($supplier->name) % 5] }}">
                                    {{ strtoupper(substr($supplier->name, 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('suppliers.show', $supplier) }}"
                                        class="font-medium text-stone-800 hover:text-[#3D6B4F] transition-colors">
                                        {{ $supplier->name }}
                                    </a>
                                    <div class="text-[11px] text-stone-400">ID: SUP-{{ str_pad($supplier->id, 7, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-stone-600">
                            {{ $supplier->layups_count ?? $supplier->layups->count() }}
                        </td>
                        <td class="px-5 py-3.5 text-stone-500">
                            {{ $supplier->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                x-data="{ confirmDelete: false }">
                                <a href="{{ route('suppliers.show', $supplier) }}"
                                    class="p-1.5 text-stone-400 hover:text-[#3D6B4F] hover:bg-green-50 rounded-md transition-colors"
                                    title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('suppliers.edit', $supplier) }}"
                                    class="p-1.5 text-stone-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button @click="confirmDelete = true"
                                    class="p-1.5 text-stone-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors"
                                    title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>

                                {{-- Delete Confirm Modal --}}
                                <div x-show="confirmDelete" x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
                                    @keydown.escape.window="confirmDelete = false">
                                    <div @click.outside="confirmDelete = false"
                                        class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm mx-4">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center shrink-0">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-semibold text-stone-800">Delete Supplier</h3>
                                                <p class="text-xs text-stone-500 mt-0.5">This will also delete all associated layups and layers.</p>
                                            </div>
                                        </div>
                                        <p class="text-sm text-stone-600 mb-5">
                                            Are you sure you want to delete <strong>{{ $supplier->name }}</strong>? This action cannot be undone.
                                        </p>
                                        <div class="flex gap-2 justify-end">
                                            <button @click="confirmDelete = false"
                                                class="px-4 py-2 text-sm text-stone-600 border border-stone-200 rounded-lg hover:bg-stone-50 transition">
                                                Cancel
                                            </button>
                                            <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-4 py-2 text-sm text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
                                                    Delete
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
                        <td colspan="4" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-stone-400">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-stone-500">No suppliers yet</p>
                                    <p class="text-xs text-stone-400 mt-1">Get started by adding your first supplier.</p>
                                </div>
                                <a href="{{ route('suppliers.create') }}"
                                    class="mt-1 inline-flex items-center gap-1.5 text-sm text-[#3D6B4F] font-medium hover:underline">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add Supplier
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($suppliers->hasPages())
            <div class="px-5 py-3 border-t border-stone-100 flex items-center justify-between text-sm text-stone-500">
                <span>Showing {{ $suppliers->firstItem() }} to {{ $suppliers->lastItem() }} of {{ $suppliers->total() }} results</span>
                <div class="flex items-center gap-1">
                    @if($suppliers->onFirstPage())
                        <span class="px-2 py-1 rounded border border-stone-200 text-stone-300 cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </span>
                    @else
                        <a href="{{ $suppliers->previousPageUrl() }}"
                            class="px-2 py-1 rounded border border-stone-200 hover:bg-stone-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                    @endif
                    @if($suppliers->hasMorePages())
                        <a href="{{ $suppliers->nextPageUrl() }}"
                            class="px-2 py-1 rounded border border-stone-200 hover:bg-stone-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <span class="px-2 py-1 rounded border border-stone-200 text-stone-300 cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    @endif
                </div>
            </div>
            @else
            <div class="px-5 py-3 border-t border-stone-100 text-xs text-stone-400">
                Showing {{ $suppliers->count() }} result{{ $suppliers->count() !== 1 ? 's' : '' }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.getElementById('search-input').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.supplier-row').forEach(row => {
            row.style.display = row.dataset.name.includes(q) ? '' : 'none';
        });
    });
</script>
@endsection