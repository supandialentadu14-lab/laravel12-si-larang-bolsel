@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Edit Penyedia</h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Perbarui Vendor & Supplier</p>
        </div>
        <a href="{{ route('suppliers.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
            <i class="fas fa-arrow-left text-xs"></i>
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm">
        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="space-y-1.5">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Perusahaan</label>
                <input type="text" name="name" value="{{ old('name', $supplier->name) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                @error('name')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Direktur</label>
                <input type="text" name="dir" value="{{ old('dir', $supplier->dir) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                @error('dir')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Email (Opsional)</label>
                    <input type="email" name="email" value="{{ old('email', $supplier->email) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    @error('email')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Telepon (Opsional)</label>
                    <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    @error('phone')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">NPWP (Opsional)</label>
                <input type="text" name="npwp" value="{{ old('npwp', $supplier->npwp) }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none">
                @error('npwp')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Alamat (Opsional)</label>
                <textarea name="address" rows="3" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none">{{ old('address', $supplier->address) }}</textarea>
                @error('address')<p class="text-[10px] font-bold text-rose-600 mt-1 ml-4">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <a href="{{ route('suppliers.index') }}" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest text-center">Batal</a>
                <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-indigo-100">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

