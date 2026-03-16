@extends('layouts.mobile')

@section('content')
<div class="space-y-6 animate-slide-up">
  <!-- Header Section -->
  <div class="flex items-center justify-between mb-2">
    <div class="flex items-center gap-3">
      <a href="{{ route('stock.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-gray-100 text-gray-400 flex items-center justify-center active:scale-90 transition-all">
        <i class="fas fa-arrow-left text-xs"></i>
      </a>
      <h2 class="text-xl font-extrabold text-gray-900 tracking-tight uppercase">Tambah Mutasi</h2>
    </div>
  </div>

  <!-- Form Section -->
  <div class="p-6 rounded-[2.5rem] bg-indigo-900 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
    <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
    
    <form action="{{ route('stock.store') }}" method="POST" class="space-y-5 relative z-10" x-data="{ type: '{{ request('type', 'in') }}' }">
      @csrf
      <input type="hidden" name="type" :value="type">
      
      <div class="space-y-1.5">
        <label class="text-[10px] font-bold uppercase opacity-60 ml-1 tracking-widest">Pilih Barang</label>
        <div class="relative group">
          <select name="product_id" required 
            oninvalid="this.setCustomValidity('Barang harus dipilih')" 
            oninput="this.setCustomValidity('')"
            class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/10 border-none text-sm font-bold text-white focus:ring-2 focus:ring-white/30 appearance-none transition-all">
            <option value="" class="text-gray-900">Pilih Barang</option>
            @foreach($products as $p)
              <option value="{{ $p->id }}" class="text-gray-900">{{ $p->name }} (Stok: {{ $p->calculated_stock }})</option>
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
            <input type="number" name="quantity" required min="1" placeholder="0"
              oninvalid="this.setCustomValidity('Jumlah harus diisi')" 
              oninput="this.setCustomValidity('')"
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
          <input type="date" name="date" value="{{ date('Y-m-d') }}" required
            oninvalid="this.setCustomValidity('Tanggal harus diisi')" 
            oninput="this.setCustomValidity('')"
            class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/10 border-none text-sm font-bold text-white focus:ring-2 focus:ring-white/30 transition-all">
          <div class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40">
            <i class="fas fa-calendar-alt text-sm"></i>
          </div>
        </div>
      </div>

      <div class="space-y-1.5">
        <label class="text-[10px] font-bold uppercase opacity-60 ml-1 tracking-widest">Keterangan (Opsional)</label>
        <div class="relative">
          <textarea name="notes" rows="2" placeholder="Masukkan catatan jika ada..."
            class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white/10 border-none text-sm font-bold text-white focus:ring-2 focus:ring-white/30 transition-all placeholder:text-white/20"></textarea>
          <div class="absolute left-4 top-4 text-white/40">
            <i class="fas fa-sticky-note text-sm"></i>
          </div>
        </div>
      </div>

      <div class="pt-2">
        <button type="submit" class="w-full py-5 bg-white text-indigo-900 rounded-3xl text-[11px] font-black uppercase tracking-[0.2em] shadow-xl active:scale-[0.98] transition-all flex items-center justify-center gap-2">
          <i class="fas fa-save"></i>
          Simpan Transaksi
        </button>
      </div>
    </form>
  </div>

  <!-- Info Section -->
  <div class="p-5 rounded-[2rem] bg-white border border-gray-50 shadow-sm">
    <div class="flex items-start gap-4">
      <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center flex-shrink-0">
        <i class="fas fa-info-circle text-sm"></i>
      </div>
      <div class="space-y-1">
        <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest">Petunjuk Pengisian</h4>
        <p class="text-[10px] text-gray-400 font-medium leading-relaxed">Pilih barang yang akan dimutasi, tentukan tipe mutasi (masuk untuk menambah stok, keluar untuk mengurangi), masukkan jumlah barang, dan simpan.</p>
      </div>
    </div>
  </div>
</div>
@endsection
