@extends('layouts.admin')

@section('header', 'Daftar Barang')
@section('content')

<div x-data="{
    selected: [],
    allSelected: false,
    toggleAll() {
        this.allSelected = !this.allSelected;
        if (this.allSelected) {
            this.selected = [
                @foreach ($products as $product)
                    '{{ $product->id }}',
                @endforeach
            ];
        } else {
            this.selected = [];
        }
    },
    updateSelectAll() {
        this.allSelected = this.selected.length === {{ count($products) }};
    }
}" class="bg-white rounded-lg shadow p-6 mb-6">

    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('products.create') }}" class=" btn btn-primary h-10 px-4">
                <i class="fas fa-plus"></i> Tambah Barang
            </a>
            <a href="{{ route('import.index') }}" class="btn h-10 px-4">
                <i class="fas fa-file-import text-indigo-500"></i> Impor
            </a>
            <a href="{{ route('products.export') }}" class="btn h-10 px-4">
                <i class="fas fa-file-excel text-emerald-600"></i> Ekspor Excel
            </a>
        </div>

        <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 w-full max-w-2xl">
            <form action="{{ route('products.index') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center gap-2 w-full">
                <select name="category_id" onchange="this.form.requestSubmit()" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 outline-none transition w-full md:w-auto">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <div x-data="{ query: '{{ request('search') }}' }" class="relative flex-1">

                    </span>
                </div>

                @if(request('category_id') || request('search') || request('low_stock'))
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-3 py-2 text-sm text-gray-500 hover:text-red-600 transition" title="Reset">
                        <i class="fas fa-times-circle"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="overflow-x-auto border rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-100 text-xs uppercase font-bold">
                <tr>
                    <th class="px-3 py-2 w-10">
                        <input type="checkbox" @click="toggleAll()" x-model="allSelected" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </th>
                    <th class="px-3 py-2">Nama Barang</th>
                    <th class="px-3 py-2">Kategori</th>
                    <th class="px-3 py-2 text-right">Harga</th>
                    <th class="px-3 py-2 text-center">Stok Akhir</th>
                    <th class="px-3 py-2 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-t hover:bg-gray-50 transition" :class="{ 'bg-indigo-50': selected.includes('{{ $product->id }}') }">
                        <td class="px-3 py-2">
                            <input type="checkbox" value="{{ $product->id }}" x-model="selected" @click="updateSelectAll()" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </td>
                        <td class="px-3 py-2 font-bold text-gray-800">{{ $product->name }}</td>
                        <td class="px-3 py-2">
                            @if($product->category)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                                    {{ $product->category->name }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-right">{{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 text-center">
                            @if($product->stock <= $product->min_stock)
                                <span class="text-red-600 font-bold">{{ $product->stock }}</span>
                            @else
                                <span class="text-green-600 font-bold">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-right flex justify-end items-center gap-2">
                            <a href="{{ route('products.barcode', $product->id) }}" target="_blank" class="text-gray-500 hover:text-indigo-600 transition" title="Cetak Barcode">
                                <i class="fas fa-barcode"></i>
                            </a>
                            @include('partials.action_buttons', [
                                'edit' => route('products.edit', $product->id),
                                'delete' => route('products.destroy', $product->id),
                            ])
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-3 py-6 text-center text-gray-500">Belum ada data barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 flex justify-between items-center">
        <form x-show="selected.length > 0" method="POST" action="{{ route('products.bulk_delete') }}" class="inline-block">
            @csrf
            <template x-for="id in selected" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            <button type="button" @click="if(confirm('Hapus ' + selected.length + ' item terpilih?')) $el.closest('form').submit()" class="text-red-600 italic hover:underline text-xs">
                Hapus data yang terpilih (<span x-text="selected.length"></span>)
            </button>
        </form>
        <div class="flex-1">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
