@extends('layouts.mobile')

@section('content')
<div class="space-y-6 animate-slide-up">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight uppercase">Laporan</h2>
        <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
            <i class="fas fa-file-invoice"></i>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="p-5 rounded-3xl bg-white border border-gray-100 shadow-sm space-y-4">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Filter Laporan</h3>
        <form method="GET" action="{{ route('reports.index') }}" class="space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase ml-1">Dari</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                        class="w-full px-4 py-3 rounded-2xl bg-gray-50 border-none text-xs font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-500 uppercase ml-1">Sampai</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" 
                        class="w-full px-4 py-3 rounded-2xl bg-gray-50 border-none text-xs font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-gray-500 uppercase ml-1">Kategori</label>
                <select name="category_id" class="w-full px-4 py-3 rounded-2xl bg-gray-50 border-none text-xs font-bold text-gray-700 focus:ring-2 focus:ring-indigo-500 appearance-none">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-mini btn-mini-primary w-full !py-4 !text-[10px] !tracking-widest shadow-indigo-100">
                Tampilkan Laporan
            </button>
        </form>
    </div>

    <!-- Summary of Report -->
    <div class="p-5 rounded-3xl bg-indigo-900 text-white shadow-xl shadow-indigo-100 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
        <div class="relative z-10 space-y-4">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Total Nilai Persediaan</p>
                @php
                    $grandTotal = 0;
                    foreach ($reportData as $item) {
                        // This logic is simplified for mobile summary
                    }
                    // Using total from controller if available or calculating
                @endphp
                <p class="text-2xl font-black">Rp {{ number_format($totalInventoryValue ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="flex gap-2 pt-2 border-t border-white/10">
                <a href="{{ route('reports.export_persediaan', ['start_date' => $startDate, 'end_date' => $endDate, 'category_id' => $categoryId]) }}" 
                    class="btn-mini !flex-1 bg-white/10 hover:bg-white/20 !text-[8px] !tracking-widest !rounded-xl !px-2">
                    <i class="fas fa-file-excel"></i> EXCEL
                </a>
                <a href="{{ route('reports.index', ['start_date' => $startDate, 'end_date' => $endDate, 'category_id' => $categoryId, 'layout' => 'desktop']) }}" 
                    target="_blank"
                    class="btn-mini !flex-1 bg-white/10 hover:bg-white/20 !text-[8px] !tracking-widest !rounded-xl !px-2 text-white">
                    <i class="fas fa-eye"></i> PREVIEW
                </a>
                <a href="{{ route('reports.index', ['start_date' => $startDate, 'end_date' => $endDate, 'category_id' => $categoryId, 'layout' => 'desktop', 'print' => 'true']) }}" 
                    target="_blank"
                    class="btn-mini !flex-1 bg-white text-indigo-900 !text-[8px] !tracking-widest !rounded-xl !px-2">
                    <i class="fas fa-print"></i> CETAK
                </a>
            </div>
        </div>
    </div>

    <!-- List of Items in Report (Minimalist) -->
    <div class="space-y-3">
        <h3 class="text-sm font-extrabold text-gray-900 uppercase tracking-widest px-1">Ringkasan Barang</h3>
        <div class="space-y-3">
            @php $shownProducts = []; @endphp
            @forelse($reportData as $item)
                @if(!in_array($item['product_id'], $shownProducts))
                    @php $shownProducts[] = $item['product_id']; @endphp
                    <div class="p-4 rounded-3xl bg-white border border-gray-50 shadow-sm flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ $item['name'] }}</p>
                            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Mutasi: +{{ $item['masuk'] }} / -{{ $item['keluar'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-gray-900">{{ number_format($item['masuk'] - $item['keluar']) }}</p>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">{{ $item['satuan'] }}</p>
                        </div>
                    </div>
                @endif
            @empty
                <div class="p-10 text-center text-gray-300">
                    <i class="fas fa-folder-open text-4xl mb-3"></i>
                    <p class="text-xs font-bold uppercase tracking-widest">Tidak ada data laporan</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
