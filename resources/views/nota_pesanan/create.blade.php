@extends('layouts.mobile')

@section('content')
    <script>
        window.notaForm = function () {
            return {
                tahun: '{{ $data['tahun'] ?? now()->year }}',
                tanggal: '{{ $data['tanggal'] ?? now()->toDateString() }}',
                belanja: '{{ $data['belanja'] ?? ($categories->first()->name ?? '') }}',
                items: {!! json_encode(($data['items'] ?? [])) !!},
                nextKey: 1,
                products: {!! json_encode($products->map(fn($p) => ['id'=>$p->id,'name'=>$p->name,'unit'=>$p->unit,'price'=>$p->price ?? 0,'category_id'=>$p->category_id,'category_name'=>optional($p->category)->name])) !!},
                init() {
                    if (this.items.length === 0) this.addItem();
                    this.ensureKeys();
                },
                ensureKeys() {
                    this.items = (this.items || []).map(it => ({ ...it, _key: it._key || (this.nextKey++) }));
                },
                addItem() {
                    this.items.push({ _key: this.nextKey++, name: '', qty: '', unit: '', price: '', total: '' });
                },
                removeItem(i) { 
                    if (this.items.length > 1) {
                        this.items.splice(i, 1); 
                    }
                },
                onProductChange(i, name) {
                    const p = this.products.find(x => x.name === name);
                    if (p) {
                        const raw = Number(p.price ?? 0);
                        const price = Number.isFinite(raw) ? Math.round(raw) : (parseInt(String(p.price).replace(/\D+/g,''),10) || '');
                        this.items[i].unit = p.unit || '';
                        this.items[i].price = price;
                    } else {
                        this.items[i].unit = '';
                        this.items[i].price = '';
                    }
                    this.recalc(i);
                },
                productsByBelanja() {
                    const b = (this.belanja || '').trim();
                    if (!b) return this.products;
                    return this.products.filter(p => (p.category_name || '') === b);
                },
                recalc(i) {
                    const it = this.items[i] || {};
                    const qty = parseInt(it.qty, 10);
                    const price = parseInt(it.price, 10);
                    if (Number.isFinite(qty) && Number.isFinite(price)) {
                        this.items[i].total = qty * price;
                    } else {
                        this.items[i].total = '';
                    }
                },
                getTotal() {
                    return this.items.reduce((sum, item) => sum + (Number(item.total) || 0), 0);
                }
            }
        }
    </script>

    <div class="space-y-6 pb-24">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Nota Pesanan</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Buat Surat Pesanan Baru</p>
            </div>
            <a href="{{ route('reports.nota.list') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
                <i class="fas fa-times text-xs"></i>
            </a>
        </div>

        <form method="POST" action="{{ route('reports.nota.save') }}" x-data="notaForm()" x-init="init()" class="space-y-6">
            @csrf

            {{-- Informasi Kegiatan --}}
            <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-6">
                <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="fas fa-project-diagram text-xs"></i>
                    </div>
                    <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Informasi Kegiatan</h3>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nama Kegiatan</label>
                        <textarea name="kegiatan" rows="2" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none leading-relaxed" placeholder="Contoh: Penyediaan Jasa Penunjang..." required>{{ old('kegiatan', $data['kegiatan'] ?? '') }}</textarea>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Sub Kegiatan</label>
                        <textarea name="sub_kegiatan" rows="2" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none leading-relaxed" placeholder="Contoh: Penyelenggaraan Rapat..." required>{{ old('sub_kegiatan', $data['sub_kegiatan'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Detail Dokumen --}}
            <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-6">
                <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fas fa-file-invoice text-xs"></i>
                    </div>
                    <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Detail Dokumen</h3>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Kode Rekening</label>
                            <input type="text" name="rekening" value="{{ old('rekening', $data['rekening'] ?? '') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-mono font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="5.1.02.01...">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Tanggal</label>
                            <input type="date" name="tanggal" x-model="tanggal" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nomor Nota Pesanan</label>
                        <input type="text" name="nomor" value="{{ old('nomor', $data['nomor'] ?? '') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="001/NPB/..." required>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Kategori Belanja</label>
                        <select name="belanja" x-model="belanja" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Penyedia / Supplier</label>
                        <select name="supplier_id" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none" required>
                            <option value="">-- Pilih Penyedia --</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" {{ (old('supplier_id', $data['supplier_id'] ?? '') == $s->id) ? 'selected' : '' }}>
                                    {{ $s->name }} ({{ $s->toko }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Daftar Barang --}}
            <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-6 overflow-hidden">
                <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fas fa-boxes text-xs"></i>
                        </div>
                        <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Rincian Barang</h3>
                    </div>
                    <button type="button" @click="addItem()" class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-100 transition active:scale-90">
                        <i class="fas fa-plus text-[10px]"></i>
                    </button>
                </div>

                <div class="overflow-x-auto -mx-6 px-6">
                    <table class="min-w-[800px] w-full text-left">
                        <thead class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                            <tr>
                                <th class="pb-4 px-3 w-[250px]">Nama Barang</th>
                                <th class="pb-4 px-3 w-[80px] text-center">Qty</th>
                                <th class="pb-4 px-3 w-[100px] text-center">Satuan</th>
                                <th class="pb-4 px-3 w-[150px] text-right">Harga (Rp)</th>
                                <th class="pb-4 px-3 w-[150px] text-right">Total (Rp)</th>
                                <th class="pb-4 px-3 w-[50px]"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <template x-for="(item, i) in items" :key="item._key">
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-3 px-1">
                                        <input type="text" :name="`items[${i}][name]`" x-model="item.name" list="opt-products" @change="onProductChange(i, $event.target.value)" class="w-full bg-slate-50 border-none rounded-xl px-3 py-2 text-[11px] font-bold outline-none focus:ring-1 focus:ring-indigo-500/20" placeholder="Cari barang...">
                                        <datalist id="opt-products">
                                            <template x-for="p in productsByBelanja()" :key="p.id">
                                                <option :value="p.name" x-text="p.name"></option>
                                            </template>
                                        </datalist>
                                    </td>
                                    <td class="py-3 px-1">
                                        <input type="number" :name="`items[${i}][qty]`" x-model="item.qty" @input="recalc(i)" class="w-full bg-slate-50 border-none rounded-xl px-3 py-2 text-[11px] font-bold text-center outline-none focus:ring-1 focus:ring-indigo-500/20" placeholder="0">
                                    </td>
                                    <td class="py-3 px-1 text-center uppercase font-bold text-[10px] text-slate-400">
                                        <input type="text" :name="`items[${i}][unit]`" x-model="item.unit" class="w-full bg-slate-50 border-none rounded-xl px-3 py-2 text-[11px] font-bold text-center outline-none focus:ring-1 focus:ring-indigo-500/20 uppercase" placeholder="...">
                                    </td>
                                    <td class="py-3 px-1">
                                        <input type="number" :name="`items[${i}][price]`" x-model="item.price" @input="recalc(i)" class="w-full bg-slate-50 border-none rounded-xl px-3 py-2 text-[11px] font-mono font-bold text-right outline-none focus:ring-1 focus:ring-indigo-500/20" placeholder="0">
                                    </td>
                                    <td class="py-3 px-3 text-right font-mono text-[11px] font-black text-indigo-600" x-text="new Intl.NumberFormat('id-ID').format(item.total || 0)"></td>
                                    <td class="py-3 px-1 text-center">
                                        <button type="button" @click="removeItem(i)" class="text-slate-300 hover:text-rose-500 transition">
                                            <i class="fas fa-trash-alt text-[10px]"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot>
                            <tr class="bg-indigo-600 text-white">
                                <td colspan="4" class="px-6 py-4 text-[10px] font-black uppercase tracking-widest">Total Keseluruhan</td>
                                <td class="px-3 py-4 text-right font-mono text-sm font-black" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(getTotal())"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 px-2">
                <a href="{{ route('reports.nota.list') }}" class="flex-1 py-5 bg-slate-100 text-slate-400 rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] text-center">Batal</a>
                <button type="submit" class="flex-[2] py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-all">Simpan Nota</button>
            </div>
        </form>
    </div>
@endsection
