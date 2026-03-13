@extends('layouts.mobile')

@section('content')
<div class="space-y-6 animate-slide-up">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-3">
            <a href="{{ route('stock.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-gray-100 text-gray-400 flex items-center justify-center active:scale-90 transition-all">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight uppercase">Edit Mutasi</h2>
        </div>
    </div>

    <!-- Form Section -->
    <div class="p-6 rounded-[2.5rem] bg-indigo-900 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
        
        <form action="{{ route('stock.update', $transaction->id) }}" method="POST" class="space-y-5 relative z-10" x-data="{ type: '{{ $transaction->type }}' }">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" :value="type">
            
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold uppercase opacity-60 ml-1 tracking-widest">Pilih Barang</label>
                <div class="relative group">
                    <select name="product_id" required class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/10 border-none text-sm font-bold text-white focus:ring-2 focus:ring-white/30 appearance-none transition-all">
                        <option value="" class="text-gray-900">Pilih Barang</option>
                        @foreach($products as $p)
                            <option value="{{ $p->id }}" {{ $transaction->product_id == $p->id ? 'selected' : '' }} class="text-gray-900">
                                {{ $p->name }} (Stok: {{ $p->calculated_stock }})
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">
                        <i class="fas fa-box text-sm"></i>
                    </div>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-white/40 pointer-events-none">
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <label class="text-[10px] font-bold uppercase opacity-60 ml-1 tracking-widest">Tipe Mutasi</label>
                <div class="grid grid-cols-2 p-1 bg-white/10 rounded-2xl border border-white/5 relative">
                    <!-- Sliding Background -->
                    <div class="absolute inset-y-1 transition-all duration-300 ease-out bg-white rounded-xl shadow-sm w-[calc(50%-4px)]"
                        :class="type === 'in' ? 'left-1' : 'left-[calc(50%+2px)]'"></div>
                    
                    <button type="button" @click="type = 'in'" 
                        class="relative z-10 py-3 text-[10px] font-black uppercase tracking-widest transition-colors duration-300 flex items-center justify-center gap-2"
                        :class="type === 'in' ? 'text-indigo-900' : 'text-white/60'">
                        <i class="fas fa-arrow-down-long text-[10px]" :class="type === 'in' ? 'text-green-500' : ''"></i>
                        Masuk
                    </button>
                    <button type="button" @click="type = 'out'" 
                        class="relative z-10 py-3 text-[10px] font-black uppercase tracking-widest transition-colors duration-300 flex items-center justify-center gap-2"
                        :class="type === 'out' ? 'text-indigo-900' : 'text-white/60'">
                        <i class="fas fa-arrow-up-long text-[10px]" :class="type === 'out' ? 'text-rose-500' : ''"></i>
                        Keluar
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold uppercase opacity-60 ml-1 tracking-widest">Jumlah Mutasi</label>
                    <div class="relative">
                        <input type="number" name="quantity" value="{{ $transaction->quantity }}" required min="1" placeholder="0"
                            class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/10 border-none text-sm font-bold text-white focus:ring-2 focus:ring-white/30 transition-all placeholder:text-white/20">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">
                            <i class="fas fa-hashtag text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-bold uppercase opacity-60 ml-1 tracking-widest">Tanggal</label>
                <div class="relative">
                    <input type="date" name="date" value="{{ $transaction->date->format('Y-m-d') }}" required
                        class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/10 border-none text-sm font-bold text-white focus:ring-2 focus:ring-white/30 transition-all">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">
                        <i class="fas fa-calendar-alt text-sm"></i>
                    </div>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-bold uppercase opacity-60 ml-1 tracking-widest">No. Surat (Opsional)</label>
                <div class="relative">
                    <input type="text" name="nosur" value="{{ $transaction->nosur }}" placeholder="Masukkan nomor surat..."
                        class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/10 border-none text-sm font-bold text-white focus:ring-2 focus:ring-white/30 transition-all placeholder:text-white/20 font-mono tracking-wider">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">
                        <i class="fas fa-file-invoice text-sm"></i>
                    </div>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-bold uppercase opacity-60 ml-1 tracking-widest">Keterangan (Opsional)</label>
                <div class="relative">
                    <textarea name="notes" rows="2" placeholder="Masukkan catatan jika ada..."
                        class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/10 border-none text-sm font-bold text-white focus:ring-2 focus:ring-white/30 transition-all placeholder:text-white/20">{{ $transaction->notes }}</textarea>
                    <div class="absolute left-4 top-4 text-white/40">
                        <i class="fas fa-sticky-note text-sm"></i>
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-5 bg-white text-indigo-900 rounded-3xl text-[11px] font-black uppercase tracking-[0.2em] shadow-xl active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i>
                    Perbarui Transaksi
                </button>
            </div>
        </form>
    </div>

    <!-- Delete Action -->
    <div class="p-5 rounded-[2rem] bg-rose-50 border border-rose-100 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center">
                <i class="fas fa-trash-alt text-sm"></i>
            </div>
            <div>
                <h4 class="text-[10px] font-black text-rose-900 uppercase tracking-widest">Zona Bahaya</h4>
                <p class="text-[9px] text-rose-400 font-medium leading-tight">Hapus transaksi ini secara permanen</p>
            </div>
        </div>
        <form action="{{ route('stock.destroy', $transaction->id) }}" method="POST" class="inline">
            @csrf @method('DELETE')
            <button type="submit" @click.prevent="if(confirm('Hapus transaksi ini? Stok akan dikembalikan otomatis.')) $el.form.submit()" 
                class="px-4 py-2 bg-rose-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg shadow-rose-200 active:scale-90 transition-all">
                Hapus
            </button>
        </form>
    </div>
</div>
@endsection
