@extends('layouts.app2')

@section('content')
<div class="min-h-screen bg-[#F5F4F0]">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-stone-200 px-6 py-0 flex items-center justify-between h-14">
        <div class="flex items-center gap-8">

            {{-- Brand --}}
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-[#3D6B4F] rounded-md flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h8M4 18h8"/>
                    </svg>
                </div>

                <div>
                    <div class="text-xs font-semibold text-stone-800 leading-none">
                        CLT Layup
                    </div>
                </div>
            </div>

            {{-- Menu --}}
            <div class="flex items-center gap-1 text-sm">
                <a href="{{ route('suppliers.index') }}"
                    class="px-3 py-4 text-stone-500 hover:text-stone-800 transition-colors">
                    Suppliers
                </a>

                <a href="{{ route('layups.show', $cltLayer->layup) }}"
                    class="px-3 py-4 text-stone-500 hover:text-stone-800 transition-colors">
                    Layups
                </a>

                <a href="#"
                    class="px-3 py-4 text-[#3D6B4F] font-semibold border-b-2 border-[#3D6B4F]">
                    Layers
                </a>
            </div>
        </div>

        {{-- User --}}
        <div class="flex items-center gap-3">

            <button class="relative p-2 text-stone-400 hover:text-stone-600 transition-colors">
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
                    <div class="text-xs font-semibold text-stone-800">
                        {{ auth()->user()->name ?? 'User' }}
                    </div>

                    <div class="text-[10px] text-stone-400">
                        {{ auth()->user()->role ?? 'User' }}
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <div class="max-w-2xl mx-auto px-6 py-8">

        {{-- Card --}}
        <div class="bg-white rounded-xl border border-stone-200 shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="px-6 py-6 border-b border-stone-100">
                <h1 class="text-lg font-semibold text-stone-800">
                    Edit Layer
                </h1>

                <p class="text-sm text-stone-500 mt-0.5">
                    Update layer configuration for layup {{ $cltLayer->layup->name }}.
                </p>
            </div>

            {{-- Form --}}
            <form method="POST"
                action="{{ route('layers.update', $cltLayer) }}"
                class="px-6 py-6 space-y-5">

                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Layer Order --}}
                    <div>
                        <label for="layer_order"
                            class="block text-sm font-medium text-stone-700 mb-2">
                            Layer Order <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="number"
                            id="layer_order"
                            name="layer_order"
                            value="{{ old('layer_order', $cltLayer->layer_order) }}"
                            placeholder="e.g. 1"
                            class="w-full px-3.5 py-2.5 text-sm border rounded-lg bg-white transition
                                focus:outline-none focus:ring-2 focus:ring-[#3D6B4F]/30 focus:border-[#3D6B4F]
                                {{ $errors->has('layer_order') ? 'border-red-400 bg-red-50' : 'border-stone-200' }}"
                        >

                        @error('layer_order')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"/>
                                </svg>

                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Thickness --}}
                    <div>
                        <label for="thickness"
                            class="block text-sm font-medium text-stone-700 mb-2">
                            Thickness <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            id="thickness"
                            name="thickness"
                            value="{{ old('thickness', $cltLayer->thickness) }}"
                            placeholder="e.g. 25.50"
                            class="w-full px-3.5 py-2.5 text-sm border rounded-lg bg-white transition
                                focus:outline-none focus:ring-2 focus:ring-[#3D6B4F]/30 focus:border-[#3D6B4F]
                                {{ $errors->has('thickness') ? 'border-red-400 bg-red-50' : 'border-stone-200' }}"
                        >

                        @error('thickness')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"/>
                                </svg>

                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Width --}}
                    <div>
                        <label for="width"
                            class="block text-sm font-medium text-stone-700 mb-2">
                            Width <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            id="width"
                            name="width"
                            value="{{ old('width', $cltLayer->width) }}"
                            placeholder="e.g. 1200"
                            class="w-full px-3.5 py-2.5 text-sm border rounded-lg bg-white transition
                                focus:outline-none focus:ring-2 focus:ring-[#3D6B4F]/30 focus:border-[#3D6B4F]
                                {{ $errors->has('width') ? 'border-red-400 bg-red-50' : 'border-stone-200' }}"
                        >

                        @error('width')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"/>
                                </svg>

                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Angle --}}
                    <div>
                        <label for="angle"
                            class="block text-sm font-medium text-stone-700 mb-2">
                            Angle <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            id="angle"
                            name="angle"
                            value="{{ old('angle', $cltLayer->angle) }}"
                            placeholder="e.g. 90"
                            class="w-full px-3.5 py-2.5 text-sm border rounded-lg bg-white transition
                                focus:outline-none focus:ring-2 focus:ring-[#3D6B4F]/30 focus:border-[#3D6B4F]
                                {{ $errors->has('angle') ? 'border-red-400 bg-red-50' : 'border-stone-200' }}"
                        >

                        @error('angle')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"/>
                                </svg>

                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between pt-2 border-t border-stone-100">

                    {{-- Delete --}}
                    <button
                        type="submit"
                        form="delete-layer-form"
                        onclick="return confirm('Delete this layer?')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition-colors">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8"/>
                        </svg>

                        Delete
                    </button>

                    {{-- Right Actions --}}
                    <div class="flex items-center gap-3">

                        <a href="{{ route('layups.show', $cltLayer->layup) }}"
                            class="text-sm text-stone-500 hover:text-stone-700 transition-colors">
                            Back
                        </a>

                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-[#3D6B4F] hover:bg-[#2f5540] text-white text-sm font-medium px-6 py-3 rounded-lg transition-colors shadow-sm">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"/>
                            </svg>

                            Save Changes
                        </button>

                    </div>
                </div>

            </form>
        </div>

        {{-- Delete Form --}}
        <form id="delete-layer-form"
            method="POST"
            action="{{ route('layers.destroy', $cltLayer) }}"
            class="hidden">

            @csrf
            @method('DELETE')
        </form>

    </div>
</div>
@endsection