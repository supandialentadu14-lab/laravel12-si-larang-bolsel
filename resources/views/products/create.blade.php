@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Tambah Barang</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Input Data Barang Baru</p>
        </div>
        <a href="{{ route('products.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
            <i class="fas fa-arrow-left text-xs"></i>
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm">
        <form action="{{ route('products.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="space-y-1.5">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Barang</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                @error('name')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Kode (SKU)</label>
                    <input type="text" value="{{ old('sku', $newSku ?? '') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold text-slate-400 outline-none" readonly>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Stok Minimum</label>
                    <input type="number" name="min_stock" min="0" value="{{ old('min_stock') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    @error('min_stock')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Harga Satuan</label>
                    <input type="number" name="price" step="0.01" value="{{ old('price') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                    @error('price')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Satuan</label>
                    <select name="unit" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none" required>
                        <option value="">Pilih Satuan</option>
                        @foreach (['pcs','buah','box','pak','rim','kg','galon','paket','liter'] as $u)
                            <option value="{{ $u }}" {{ old('unit') === $u ? 'selected' : '' }}>{{ strtoupper($u) }}</option>
                        @endforeach
                    </select>
                    @error('unit')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Jenis Belanja</label>
                <select name="category_id" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none" required>
                    <option value="">Pilih Jenis Belanja</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string)old('category_id') === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Penyedia (Opsional)</label>
                <select name="supplier_id" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none">
                    <option value="">Pilih Penyedia</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ (string)old('supplier_id') === (string)$sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Keterangan (Opsional)</label>
                <textarea name="description" rows="4" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none">{{ old('description') }}</textarea>
                @error('description')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <a href="{{ route('products.index') }}" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">Batal</a>
                <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
