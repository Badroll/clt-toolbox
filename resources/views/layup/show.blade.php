@extends('layouts.app2')

@section('content')
<div class="min-h-screen bg-[#F5F4F0]">

    <nav class="bg-white border-b border-stone-200 px-6 flex items-center justify-between h-16 sticky top-0 z-50">
        <div class="flex items-center gap-10">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-[#3D6B4F] rounded-md flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h8M4 18h8"/>
                    </svg>
                </div>
                <div class="text-xs font-semibold text-stone-800 leading-none">CLT Layup</div>
            </div>
            
            <div class="flex items-center h-16">
                <a href="{{ route('suppliers.index') }}" class="px-4 flex items-center h-full text-sm font-medium text-stone-500 hover:text-stone-800 transition-colors">Suppliers</a>
                <a href="#" class="px-4 flex items-center h-full text-sm font-bold text-[#3D6B4F] border-b-2 border-[#3D6B4F] bg-green-50/30">Layups</a>
                <a href="#" class="px-4 flex items-center h-full text-sm font-medium text-stone-500 hover:text-stone-800 transition-colors">Layers</a>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="flex flex-col items-end hidden sm:flex">
                <span class="text-xs font-bold text-stone-800">{{ auth()->user()->name ?? 'User' }}</span>
                <span class="text-[10px] text-stone-400 uppercase tracking-tighter">Administrator</span>
            </div>
            <div class="w-9 h-9 rounded-full bg-stone-100 border border-stone-200 flex items-center justify-center text-stone-600 text-xs font-bold">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-6 py-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-[11px] uppercase tracking-wider font-bold text-stone-400 mb-6">
            <a href="{{ route('suppliers.index') }}" class="hover:text-[#3D6B4F] transition-colors">Suppliers</a>
            <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('suppliers.show', $cltLayup->supplier) }}" class="hover:text-[#3D6B4F] transition-colors">{{ $cltLayup->supplier->name }}</a>
            <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-stone-600 italic">Layup: {{ $cltLayup->name }}</span>
        </nav>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="mb-6 flex items-center justify-between bg-green-50 border border-green-100 text-green-800 text-sm px-4 py-3 rounded-xl shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
            <button @click="show = false" class="text-green-400 hover:text-green-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
        </div>
        @endif

        <div class="bg-white rounded-xl border border-stone-200 shadow-sm mb-6 overflow-hidden">
            <div class="px-6 py-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-stone-800">{{ $cltLayup->name }}</h1>
                        <p class="text-xs text-stone-400 font-mono mt-0.5 uppercase tracking-tight">ID: SUP-{{ str_pad($cltLayup->supplier->id, 7, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
                <a href="{{ route('layups.edit', $cltLayup) }}"
                    class="inline-flex items-center gap-2 text-sm text-stone-600 border border-stone-200 bg-white hover:bg-stone-50 px-4 py-2 rounded-lg transition shadow-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Layup
                </a>
            </div>
        </div>

        {{-- Layers Table Section --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="px-6 py-6 border-b border-stone-100 flex items-center justify-between bg-stone-50/50">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-white rounded-lg border border-stone-200">
                        <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-stone-800">Layer Specification</h2>
                        <p class="text-[11px] text-stone-400">Structural arrangement of timber layers.</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-bold text-stone-400 uppercase block">Total Depth</span>
                    <span class="text-sm font-mono font-bold text-[#3D6B4F]">{{ $cltLayup->layers->sum('thickness') }} mm</span>
                </div>
                <a href="{{ route('layups.layers.create', $cltLayup) }}" 
                    class="inline-flex items-center gap-2 bg-[#3D6B4F] hover:bg-[#2f5540] text-white text-xs font-medium px-4 py-2 rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Layer
                    </a>
            </div>

            {{-- Table content remains similar but with cleaner spacing --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    {{-- ... (sama dengan kode tabel Anda sebelumnya, namun tambahkan class "align-middle" pada <td>) ... --}}
                    <thead>
                        <tr class="border-b border-stone-100 bg-stone-50/30">
                            <th class="text-left px-6 py-4 text-[11px] font-bold text-stone-400 uppercase tracking-widest">Order</th>
                            <th class="text-left px-6 py-4 text-[11px] font-bold text-stone-400 uppercase tracking-widest">Thickness (mm)</th>
                            <th class="text-left px-6 py-4 text-[11px] font-bold text-stone-400 uppercase tracking-widest">Width (mm)</th>
                            <th class="text-left px-6 py-4 text-[11px] font-bold text-stone-400 uppercase tracking-widest">Grain Angle</th>
                            <th class="text-right px-6 py-4 text-[11px] font-bold text-stone-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($cltLayup->layers as $layer)
                        <tr class="hover:bg-stone-50/80 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="w-8 h-8 rounded-lg bg-stone-100 border border-stone-200 flex items-center justify-center text-xs font-bold text-stone-600 group-hover:bg-white group-hover:shadow-sm transition-all">
                                    {{ $layer->layer_order }}
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono font-semibold text-stone-700">
                                {{ $layer->thickness }}
                            </td>
                            <td class="px-6 py-4 font-mono text-stone-500">
                                {{ $layer->width }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $layer->angle == 0 ? 'bg-blue-50 text-blue-700' : 'bg-orange-50 text-orange-700' }}">
                                    <svg class="w-3 h-3 {{ $layer->angle == 90 ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                    </svg>
                                    {{ $layer->angle }}°
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-all">
                                    <a href="{{ route('layers.edit', $layer) }}" class="p-2 text-stone-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('layers.destroy', $layer) }}" onsubmit="return confirm('Delete this layer?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-stone-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        {{-- ... (tetap sama dengan empty state sebelumnya) ... --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection