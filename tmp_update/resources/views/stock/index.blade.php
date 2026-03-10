@extends('layouts.admin')

@section('header', 'Transaksi Masuk/Keluar')
@section('content')

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
        <a href="{{ route('stock.create') }}" class="inline-flex justify-center w-full sm:w-auto items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-bold shadow whitespace-nowrap">
            <i class="fas fa-exchange-alt"></i> Tambah Transaksi
        </a>

        <div class="relative w-full sm:w-64" x-data="{ search: '{{ request('search') }}' }">
            <form method="GET" action="{{ route('stock.index') }}" x-ref="searchForm">
                <input 
                    type="text" 
                    name="search" 
                    x-model="search"
                    x-init="$el.focus(); $el.setSelectionRange($el.value.length, $el.value.length)"
                    @input.debounce.750ms="$refs.searchForm.requestSubmit()"
                    placeholder="Cari transaksi..." 
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-300 focus:outline-none transition-all"
                >
                <div class="absolute left-3 top-2.5 text-gray-400" x-show="!search">
                    <i class="fas fa-search"></i>
                </div>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto border rounded-lg">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-100 text-xs uppercase font-bold">
                <tr>
                    <th class="px-3 py-2">Tanggal</th>
                    <th class="px-3 py-2">Barang</th>
                    <th class="px-3 py-2">Nomor Surat</th>
                    <th class="px-3 py-2 text-center">Masuk/Keluar</th>
                    <th class="px-3 py-2">Jumlah</th>
                    <th class="px-3 py-2">Saldo Akhir</th>
                    <th class="px-3 py-2">Nilai Saldo</th>
                    <th class="px-3 py-2">Diinput oleh</th>
                    <th class="px-3 py-2 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $runningStock = []; @endphp
                @forelse($transactions->sortBy('date') as $transaction)
                    @php
                        $productId = $transaction->product->id;
                        if (!isset($runningStock[$productId])) { $runningStock[$productId] = 0; }
                        if ($transaction->type === 'in') {
                            $runningStock[$productId] += $transaction->quantity;
                        } else {
                            $runningStock[$productId] -= $transaction->quantity;
                        }
                        $saldoAkhir = $runningStock[$productId];
                        $nilaiSaldo = $saldoAkhir * $transaction->product->price;
                    @endphp

                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="px-3 py-2 font-bold text-gray-800">
                            {{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}
                        </td>
                        <td class="px-3 py-2">
                            <span class="font-semibold text-gray-700">{{ $transaction->product->name }}</span>
                            <div class="text-xs text-gray-400 font-mono">{{ $transaction->product->sku }}</div>
                        </td>
                        <td class="px-3 py-2 text-gray-500 text-xs italic">
                            {{ $transaction->nosur ?: '-' }}
                        </td>
                        <td class="px-3 py-2 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $transaction->type === 'in' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ strtoupper($transaction->type) }}
                            </span>
                        </td>
                        <td class="px-3 py-2 font-bold {{ $transaction->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transaction->type === 'in' ? '+' : '-' }} {{ $transaction->quantity }}
                        </td>
                        <td class="px-3 py-2 font-bold text-blue-600">
                            {{ $saldoAkhir }}
                        </td>
                        <td class="px-3 py-2 font-bold text-orange-600">
                            Rp {{ number_format($nilaiSaldo, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-2 text-xs font-bold text-gray-600">
                            {{ $transaction->user->name ?? 'System' }}
                        </td>
                        <td class="px-3 py-2 text-right">
                            @include('partials.action_buttons', [
                                'edit' => route('stock.edit', $transaction->id),
                                'delete' => route('stock.destroy', $transaction->id),
                            ])
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-3 py-6 text-center text-gray-500">Tidak Ada Transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
