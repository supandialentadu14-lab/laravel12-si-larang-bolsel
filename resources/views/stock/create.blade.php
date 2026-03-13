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
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Transaksi Baru</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Mutasi Masuk & Keluar</p>
        </div>
        <a href="{{ route('stock.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
            <i class="fas fa-times text-xs"></i>
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm">
        <form action="{{ route('stock.store') }}" method="POST" class="space-y-5" @submit="formatNosur($el.querySelector('[name=nosur]'), $el.querySelector('[name=date]').value)">
            @csrf

            <div class="space-y-1.5">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Barang</label>
                <select name="product_id" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none" required>
                    <option value="">Pilih Barang</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ (string)old('product_id') === (string)$product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
                @error('product_id')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Jenis</label>
                    <select name="type" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none" required>
                        <option value="in" {{ old('type', 'in') === 'in' ? 'selected' : '' }}>Masuk</option>
                        <option value="out" {{ old('type') === 'out' ? 'selected' : '' }}>Keluar</option>
                    </select>
                    @error('type')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Jumlah</label>
                    <input type="number" name="quantity" min="1" value="{{ old('quantity') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                    @error('quantity')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Tanggal</label>
                    <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                    @error('date')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">No. Surat (Opsional)</label>
                    <input type="text" name="nosur" value="{{ old('nosur') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none font-mono">
                    @error('nosur')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Keterangan (Opsional)</label>
                <textarea name="notes" rows="3" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none">{{ old('notes') }}</textarea>
                @error('notes')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <a href="{{ route('stock.index') }}" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">Batal</a>
                <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
