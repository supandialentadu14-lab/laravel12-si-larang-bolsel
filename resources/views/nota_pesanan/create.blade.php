@extends('layouts.admin')

@section('header', 'Nota Pesanan')

@section('content')
    <script>
        window.notaForm = function () {
            return {
                tahun: '{{ $data['tahun'] ?? now()->year }}',
                tanggal: '{{ $data['tanggal'] ?? now()->toDateString() }}',
                belanja: '{{ $data['belanja'] ?? ($categories->first()->name ?? '') }}',
                items: {!! json_encode(($data['items'] ?? [])) !!},
                products: {!! json_encode($products->map(fn($p) => ['id'=>$p->id,'name'=>$p->name,'unit'=>$p->unit,'price'=>$p->price ?? 0,'category_id'=>$p->category_id,'category_name'=>optional($p->category)->name])) !!},
                init() {
                    this.items = (this.items || []).filter(it => (String(it.name || '').trim() !== '')).map(it => {
                        const p = Number(it.price ?? 0);
                        const q = Number(it.qty ?? 0);
                        const price = Number.isFinite(p) ? Math.round(p) : (parseInt(String(it.price).replace(/\D+/g,''),10) || '');
                        const qty = Number.isFinite(q) ? Math.round(q) : (parseInt(String(it.qty).replace(/\D+/g,''),10) || '');
                        const total = (Number.isFinite(qty) && Number.isFinite(price)) ? qty * price : '';
                        return { ...it, price, qty, total };
                    });
                },
                addItem() {
                    this.items.push({ name: '', qty: '', unit: '', price: '', total: '' });
                },
                removeItem(i) { this.items.splice(i, 1); },
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
                }
            }
        }
    </script>

    <div class="max-w-full mx-auto">
        <div class="bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-[#1e293b]">
                <h6 class="font-bold text-white flex items-center gap-2">
                    <i class="fas fa-file-invoice"></i> Form Dokumen Nota Pesanan
                </h6>
            </div>

            <form method="POST" action="{{ session('nota_current_id') ? route('reports.nota.update', session('nota_current_id')) : route('reports.nota.save') }}" x-data="notaForm()" x-init="init()" class="p-6 space-y-6">
                @csrf
                <input type="hidden" name="id" value="{{ session('nota_current_id') }}">

                <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 mb-6 transition-all duration-300">
                    <h4 class="text-slate-800 font-bold mb-5 flex items-center gap-2 border-b border-slate-200 pb-2">
                        <i class="fas fa-info-circle text-indigo-500"></i> Informasi Kegiatan & Dokumen
                    </h4>
                    
                    <div class="grid grid-cols-1 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Kegiatan <span class="text-rose-500">*</span></label>
                            <textarea name="kegiatan" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold shadow-sm" placeholder="Contoh: Penyediaan Jasa Penunjang..." rows="2" required>{{ old('kegiatan', $data['kegiatan'] ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sub Kegiatan <span class="text-rose-500">*</span></label>
                            <textarea name="sub_kegiatan" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold shadow-sm" placeholder="Contoh: Penyelenggaraan Rapat..." rows="2" required>{{ old('sub_kegiatan', $data['sub_kegiatan'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Kode Rekening</label>
                            <input type="text" name="rekening" list="opt-rekening" value="{{ old('rekening', $data['rekening'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-mono" placeholder="5.1.02.01.01.0024">
                            <datalist id="opt-rekening">
                                @foreach(($options['rekening'] ?? []) as $v)
                                    <option value="{{ $v }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nomor Nota Pesanan <span class="text-rose-500">*</span></label>
                            <input type="text" name="nomor" value="{{ old('nomor', $data['nomor'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold shadow-sm" placeholder="Contoh: 001/NOTA/2024" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Nota <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal" x-model="tanggal" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold" required>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-50/50 p-6 rounded-xl border border-indigo-100 mb-6 group">
                    <h4 class="text-indigo-900 font-bold mb-5 flex items-center gap-2 border-b border-indigo-100 pb-2">
                        <i class="fas fa-tags text-indigo-500"></i> Klasifikasi Belanja & Penyedia
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Kategori Jenis Belanja <span class="text-rose-500">*</span></label>
                            <select name="belanja" x-model="belanja" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold bg-white" required>
                                <option value="">-- Pilih Jenis Belanja --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-400 mt-1 italic italic">Memfilter daftar barang yang dapat dipilih.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Penyedia / Supplier <span class="text-rose-500">*</span></label>
                            <select name="supplier_id" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition text-sm font-bold bg-white" required>
                                <option value="">-- Pilih Penyedia --</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}" {{ (old('supplier_id', $data['supplier_id'] ?? '') == $s->id) ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-400 mt-1 italic">Detail penyedia akan tampil di footer laporan.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-50/30 p-4 rounded-xl border border-indigo-100 mb-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                        <h4 class="text-indigo-900 font-bold flex items-center gap-2">
                            <i class="fas fa-shopping-cart text-indigo-500"></i> Daftar Pesanan Barang
                        </h4>
                        <button type="button" @click="addItem()" class="w-full sm:w-auto px-4 py-2 rounded-lg bg-indigo-600 text-white text-xs font-bold shadow-md shadow-indigo-100 hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                            <i class="fas fa-plus"></i> Tambah Item Barang
                        </button>
                    </div>
                    <div class="overflow-x-auto border border-indigo-100 rounded-xl bg-white shadow-sm">
                        <table class="w-full text-left text-slate-700">
                            <thead class="bg-indigo-50 text-[10px] uppercase font-bold text-indigo-600 tracking-wider">
                                <tr>
                                    <th class="px-4 py-3 border-b border-indigo-100">Nama Barang / Spesifikasi</th>
                                    <th class="px-4 py-3 border-b border-indigo-100 w-24">Volume</th>
                                    <th class="px-4 py-3 border-b border-indigo-100 w-24">Satuan</th>
                                    <th class="px-4 py-3 border-b border-indigo-100 w-36">Harga Satuan (Rp)</th>
                                    <th class="px-4 py-3 border-b border-indigo-100 w-36 text-right">Total (Rp)</th>
                                    <th class="px-4 py-3 border-b border-indigo-100 w-12 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-indigo-50">
                                <template x-for="(item, i) in items" :key="i">
                                    <tr class="hover:bg-indigo-50/50 transition">
                                        <td class="p-3">
                                            <input type="text" :name="`items[${i}][name]`" x-model="item.name" list="opt-products" @change="onProductChange(i, $event.target.value)" class="w-full rounded-lg border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-1.5 px-3 transition" placeholder="Cari barang...">
                                            <datalist id="opt-products">
                                                <template x-for="p in productsByBelanja()" :key="p.id">
                                                    <option :value="p.name" x-text="p.name"></option>
                                                </template>
                                            </datalist>
                                        </td>
                                        <td class="p-3">
                                            <input type="number" :name="`items[${i}][qty]`" x-model="item.qty" @input="recalc(i)" class="w-full rounded-lg border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-1.5 px-3 transition text-center font-bold" min="0">
                                        </td>
                                        <td class="p-3">
                                            <input type="text" :name="`items[${i}][unit]`" x-model="item.unit" class="w-full rounded-lg border border-slate-300 bg-slate-50 text-sm py-1.5 px-3 transition text-center uppercase font-medium" placeholder="...">
                                        </td>
                                        <td class="p-3">
                                            <input type="number" :name="`items[${i}][price]`" x-model="item.price" @input="recalc(i)" class="w-full rounded-lg border border-indigo-200 bg-white text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-1.5 px-3 transition text-right font-mono" min="0">
                                        </td>
                                        <td class="p-3 text-right font-mono text-sm font-bold text-indigo-700" x-text="new Intl.NumberFormat('id-ID').format(item.total || 0)"></td>
                                        <td class="p-3 text-center">
                                            <button type="button" @click="removeItem(i)" class="text-rose-400 hover:text-rose-600 transition p-2"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                @include('partials.form-actions', [
                    'backRoute' => route('reports.nota.list'),
                ])
            </form>
        </div>
    </div>
@endsection
