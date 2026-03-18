@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<div class="space-y-6" x-data="{
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
      <h1 class="text-2xl font-black text-app-main tracking-tight uppercase">Edit Transaksi</h1>
      <p class="text-[10px] font-bold text-app-muted uppercase tracking-[0.2em] mt-1">Mutasi Masuk & Keluar</p>
    </div>
    <a href="{{ route('stock.index') }}" class="btn-icon-mini bg-app-surface border border-app-main shadow-sm flex items-center justify-center text-app-muted">
      <i class="fas fa-arrow-left text-xs"></i>
    </a>
  </div>

  <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm">
    <form action="{{ route('stock.update', $transaction->id) }}" method="POST" class="space-y-5" @submit="formatNosur($el.querySelector('[name=nosur]'), $el.querySelector('[name=date]').value)">
      @csrf
      @method('PUT')

      @php $isAutomatic = ($transaction->notes === 'Otomatis dari Kwitansi'); @endphp
      
      @if($isAutomatic)
        <div class="mb-4 p-4 bg-amber-50 border border-amber-100 rounded-2xl flex items-start gap-3">
          <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
          <p class="text-[10px] font-bold text-amber-700 leading-relaxed uppercase tracking-widest">
                Transaksi ini tidak dapat diubah karena dibuat otomatis dari Kwitansi. Silahkan ubah Nota Pesanan, Berita Acara Serah Terima Barang, atau Kwitansi untuk mengubah transaksi ini.
              </p>
            </div>
          @endif

          <div class="space-y-1.5">
            <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Barang</label>
            <div class="relative">
              <select name="product_id" class="mobile-input appearance-none bg-app-surface {{ $isAutomatic ? 'opacity-60 cursor-not-allowed' : '' }}" required @if($isAutomatic) readonly style="pointer-events: none;" @endif>
                <option value="">Pilih Barang</option>
                @foreach ($products as $product)
                  <option value="{{ $product->id }}" {{ (string)old('product_id', $transaction->product_id) === (string)$product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                @endforeach
              </select>
              <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-app-muted pointer-events-none text-[10px]"></i>
              @if($isAutomatic) <input type="hidden" name="product_id" value="{{ $transaction->product_id }}"> @endif
            </div>
            @error('product_id')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Jenis</label>
              <div class="relative">
                <select name="type" class="mobile-input appearance-none bg-app-surface {{ $isAutomatic ? 'opacity-60 cursor-not-allowed' : '' }}" required @if($isAutomatic) readonly style="pointer-events: none;" @endif>
                  <option value="in" {{ old('type', $transaction->type) === 'in' ? 'selected' : '' }}>Masuk</option>
                  <option value="out" {{ old('type', $transaction->type) === 'out' ? 'selected' : '' }}>Keluar</option>
                </select>
                <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-app-muted pointer-events-none text-[10px]"></i>
                @if($isAutomatic) <input type="hidden" name="type" value="{{ $transaction->type }}"> @endif
              </div>
              @error('type')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-1.5">
              <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Jumlah</label>
              <input type="number" name="quantity" inputmode="numeric" min="1" value="{{ old('quantity', $transaction->quantity) }}" class="mobile-input bg-app-surface {{ $isAutomatic ? 'opacity-60 cursor-not-allowed' : '' }}" required @if($isAutomatic) readonly @endif>
              @error('quantity')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Tanggal</label>
              <input type="date" name="date" value="{{ old('date', $transaction->date ? $transaction->date->format('Y-m-d') : now()->format('Y-m-d')) }}" class="mobile-input bg-app-surface {{ $isAutomatic ? 'opacity-60 cursor-not-allowed' : '' }}" required @if($isAutomatic) readonly @endif>
              @error('date')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-1.5">
              <div class="flex items-center justify-between ml-4">
                <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest">No. Surat {{ $isAutomatic ? '' : '(Opsional)' }}</label>
                @if($transaction->nosur)
                  <a href="{{ route('reports.penerimaan.list', ['search' => $transaction->nosur]) }}" class="text-[9px] font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-800 transition-colors flex items-center gap-1.5">
                    <i class="fas fa-external-link-alt text-[7px]"></i>
                    BASTB
                  </a>
                @endif
              </div>
              <input type="text" name="nosur" value="{{ old('nosur', $transaction->nosur) }}" class="mobile-input bg-app-surface font-mono tracking-tight {{ $isAutomatic ? 'opacity-60 cursor-not-allowed' : '' }}" @if($isAutomatic) readonly @endif>
              @error('nosur')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="block text-[10px] font-black text-app-muted uppercase tracking-widest ml-4">Keterangan (Opsional)</label>
            <textarea name="notes" rows="3" class="mobile-input bg-app-surface leading-relaxed {{ $isAutomatic ? 'opacity-60 cursor-not-allowed' : '' }}" @if($isAutomatic) readonly @endif>{{ old('notes', $transaction->notes) }}</textarea>
            @error('notes')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
          </div>

          <div class="grid grid-cols-2 gap-3 pt-2">
            <a href="{{ route('stock.index') }}" class="btn-ghost-mobile">Batal</a>
            <button type="submit" class="btn-primary-mobile">Simpan</button>
          </div>
    </form>
  </div>
</div>
@endsection

