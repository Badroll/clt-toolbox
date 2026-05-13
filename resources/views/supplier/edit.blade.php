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

    <div class="max-w-2xl mx-auto px-6 py-8">

        {{-- Card --}}
        <div class="bg-white rounded-xl border border-stone-200 shadow-sm overflow-hidden">
            <div class="px-6 py-6 border-b border-stone-100">
                <h1 class="text-lg font-semibold text-stone-800">Edit Supplier</h1>
                <p class="text-sm text-stone-500 mt-0.5">Fill in the supplier details below.</p>
            </div>

            <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="px-6 py-6 space-y-5">
                
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-stone-700 mb-2">
                        Supplier Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ $supplier->name ?? old('name') }}"
                        placeholder="e.g. Nordic Timber Co."
                        class="w-full px-3.5 py-2.5 text-sm border rounded-lg bg-white transition
                            focus:outline-none focus:ring-2 focus:ring-[#3D6B4F]/30 focus:border-[#3D6B4F]
                            {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-stone-200' }}">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between pt-2 border-t border-stone-100">
                    <a href="{{ route('suppliers.index') }}"
                        class="text-sm text-stone-500 hover:text-stone-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-[#3D6B4F] hover:bg-[#2f5540] text-white text-sm font-medium px-6 py-3 rounded-lg transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        update Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection