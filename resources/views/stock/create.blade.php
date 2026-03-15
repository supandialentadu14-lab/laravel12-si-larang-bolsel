@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<div class="space-y-6" x-data="{
  selectedProductId: '{{ old('product_id', '') }}',
  type: '{{ old('type', 'in') }}',
  quantity: {{ old('quantity', 0) }},
  products: @js($products),
  
  get currentProduct() {
    return this.products.find(p => p.id == this.selectedProductId);
  },
  
  get availableStock() {
    return this.currentProduct ? this.currentProduct.stock : 0;
  },
  
  get isInvalid() {
    if (this.type === 'out' && this.selectedProductId) {
      return this.quantity > this.availableStock;
    }
    return false;
  },

  formatNosur(el, dateStr) {
    if (!el || !dateStr) return;
    let val = (el.value || '').trim();
    if (/^\\d+$/.test(val) && !val.includes('/')) {
      const romans = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
      const dateVal = new Date(dateStr);
      if (!isNaN(dateVal.getTime())) {
        const month = dateVal.getMonth() + 1;
        const year = dateVal.getFullYear();
        el.value = `${val}/BAPB/{{ $singkatanOpd ?? 'DISKOMINFO' }}/${romans[month]}/${year}`;
        el.dispatchEvent(new Event('input'));
      }
    }
  }
}">
  <div class="flex items-center justify-between px-2">
    <div>
      <h1 class="text-2xl font-black text-slate-800 transition-colors uppercase tracking-tight">Transaksi Baru</h1>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Mutasi Masuk & Keluar</p>
    </div>
    <a href="{{ route('stock.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 transition-colors">
      <i class="fas fa-times text-xs"></i>
    </a>
  </div>

  <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm transition-colors">
    <form action="{{ route('stock.store') }}" method="POST" class="space-y-5" @submit="formatNosur($el.querySelector('[name=nosur]'), $el.querySelector('[name=date]').value)">
      @csrf

      <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 transition-colors">Barang</label>
        <select name="product_id" x-model="selectedProductId" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none transition-colors" required>
          <option value="">Pilih Barang</option>
          @foreach ($products as $product)
            <option value="{{ $product->id }}">{{ $product->name }} (Stok: {{ $product->stock }} {{ $product->unit }})</option>
          @endforeach
        </select>
        @error('product_id')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div class="space-y-1.5">
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 transition-colors">Jenis</label>
          <select name="type" x-model="type" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none transition-colors" required>
            <option value="in">Masuk</option>
            <option value="out">Keluar</option>
          </select>
          @error('type')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
        </div>
        <div class="space-y-1.5">
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 transition-colors">Jumlah</label>
          <input type="number" name="quantity" x-model.number="quantity" min="1" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 outline-none transition-colors" :class="isInvalid ? 'ring-2 ring-rose-500 bg-rose-50 text-rose-900' : 'text-slate-800 focus:ring-indigo-500/20'" required>
          <template x-if="isInvalid">
            <p class="text-[9px] font-black text-rose-600 mt-1 ml-4 uppercase tracking-tighter">Stok tidak mencukupi (Tersedia: <span x-text="availableStock"></span>)</p>
          </template>
          @error('quantity')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
        </div>
      </div>

      <div class="space-y-4">
        <div class="space-y-1.5">
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 transition-colors">Tanggal Transaksi</label>
          <div class="relative">
            <i class="fas fa-calendar-alt absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 text-xs pointer-events-none"></i>
            <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" class="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors" required>
          </div>
          @error('date')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-1.5">
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 transition-colors">Nomor Surat / Referensi (Opsional)</label>
          <div class="relative">
            <i class="fas fa-file-invoice absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 text-xs pointer-events-none"></i>
            <input type="text" name="nosur" value="{{ old('nosur') }}" placeholder="Contoh: 001/BAPB/..." class="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none font-mono transition-colors">
          </div>
          @error('nosur')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
        </div>
      </div>

      <div class="space-y-1.5">
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 transition-colors">Keterangan (Opsional)</label>
        <textarea name="notes" rows="3" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-colors">{{ old('notes') }}</textarea>
        @error('notes')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
      </div>

      <div class="grid grid-cols-2 gap-3 pt-2">
        <a href="{{ route('stock.index') }}" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center transition-colors">Batal</a>
        <button type="submit" :disabled="isInvalid" :class="isInvalid ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-indigo-600 bg-indigo-600 shadow-indigo-100 active:scale-[0.98]'" class="w-full py-4 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md transition-all">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection
