@extends('layouts.mobile')

@section('content')
  <script>
    window.formData = function () {
      return {
        items: {!! json_encode(($data['items'] ?? [])) !!},
        nextKey: {{ count($data['items'] ?? []) + 2 }},
        ensureKeys() {
          this.items = (this.items || []).map(it => ({ ...it, _key: it._key || (this.nextKey++) }));
        },
        init() {
          const raw = (this.items || []).filter((row) => {
            const vals = Object.values(row || {});
            return vals.some(v => String(v ?? '').trim() !== '');
          });
          this.items = raw;
          this.dedupe();
          this.ensureKeys();
          if (this.items.length === 0) this.items = [{ '_key': this.nextKey++ }];
          this.updatePembuka();
        },
        prefill() { 
          this.items = {!! json_encode(($data['items'] ?? [])) !!}; 
          this.dedupe();
          this.ensureKeys(); 
          if (this.items.length === 0) this.items = [{ '_key': this.nextKey++ }];
        },
        addItem() { this.items.push({ _key: this.nextKey++ }); },
        removeItem(i) { if(this.items.length > 1) this.items.splice(i, 1); },
        dedupe() {
          const seen = new Set();
          this.items = this.items.filter((row) => {
            const key = `${row.nama || ''}|${row.merk || ''}|${row.tipe || ''}|${row.identitas || ''}|${row.tahun || ''}|${row.kondisi || ''}|${row.jumlah || ''}`;
            if (seen.has(key)) return false;
            seen.add(key);
            return true;
          });
        },
        updatePembuka() {
          try {
            const v = this.$refs.tanggal?.value;
            if (!v) return;
            const d = new Date(v);
            const hari = d.toLocaleDateString('id-ID', { weekday: 'long' });
            const bulan = d.toLocaleDateString('id-ID', { month: 'long' });
            const tanggal = d.getDate();
            const tahun = d.getFullYear();
            const toWords = (n) => {
              n = parseInt(n, 10);
              const h = ["","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan","sepuluh","sebelas"];
              const cap = s => s.replace(/\b\w/g, c => c.toUpperCase());
              const w = (v) => {
                if (v < 12) return h[v];
                if (v < 20) return w(v-10) + " belas";
                if (v < 100) return w(Math.floor(v/10)) + " puluh " + w(v%10);
                if (v < 200) return "seratus " + w(v-100);
                if (v < 1000) return w(Math.floor(v/100)) + " ratus " + w(v%100);
                if (v < 2000) return "seribu " + w(v-1000);
                if (v < 1000000) return w(Math.floor(v/1000)) + " ribu " + w(v%1000);
                return String(v);
              };
              return cap(w(n).trim());
            };
            const tanggalKata = toWords(tanggal);
            const tahunKata = toWords(tahun);
            this.$refs.pembuka.value = `Pada hari ini ${hari} Tanggal ${tanggalKata} Bulan ${bulan} Tahun ${tahunKata}, yang bertanda tangan di bawah ini:`;
          } catch (e) {}
        }
      }
    }
  </script>

  <div class="space-y-6 pb-24">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Edit Pinjam Pakai</h1>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Perbarui Berita Acara</p>
      </div>
      <a href="{{ route('reports.pinjam.list') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
        <i class="fas fa-times text-xs"></i>
      </a>
    </div>

    <form method="POST" action="{{ route('reports.pinjam.save') }}" x-data="formData()" x-init="init()" class="space-y-6">
      @csrf
      <input type="hidden" name="id" value="{{ $data['id'] ?? '' }}">

      {{-- Informasi Umum --}}
      <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-slate-50 pb-4">
          <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
            <i class="fas fa-edit text-xs"></i>
          </div>
          <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Informasi Dokumen</h3>
        </div>

        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Nomor Berita Acara</label>
            <input type="text" name="nomor" value="{{ $data['nomor'] ?? '' }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none font-mono" placeholder="001/BASTBI/..." required>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Tanggal</label>
              <input x-ref="tanggal" @change="updatePembuka()" type="date" name="tanggal" value="{{ $data['tanggal'] ?? now()->toDateString() }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
            </div>
            <div class="space-y-1.5">
              <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Tempat</label>
              <input x-ref="tempat" @input="updatePembuka()" type="text" name="tempat" value="{{ $data['tempat'] ?? ($opd->nama_opd ?? '') }}" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="Boroko" required>
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Narasi Pembuka</label>
            <textarea x-ref="pembuka" name="pembuka" rows="3" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none leading-relaxed">{{ $data['pembuka'] ?? '' }}</textarea>
          </div>
        </div>
      </div>

      {{-- Pihak Bersepakat --}}
      <div class="grid grid-cols-1 gap-6">
        {{-- Pihak Pertama (Pemberi) --}}
        <div class="bg-indigo-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-indigo-100 space-y-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                <i class="fas fa-user-tie text-[10px]"></i>
              </div>
              <h3 class="text-[11px] font-black uppercase tracking-widest opacity-80">Pihak Pertama (Pemberi)</h3>
            </div>
            <button type="button" @click="$refs.pp_nama.value='{{ $opd->kepala_nama }}'; $refs.pp_nip.value='{{ $opd->kepala_nip }}'; $refs.pp_jabatan.value='{{ $opd->kepala_jabatan }}';" class="text-[8px] font-black uppercase px-2 py-1 bg-white/20 rounded-lg">Auto</button>
          </div>
          <div class="space-y-4">
            <div class="space-y-1.5">
              <label class="text-[8px] font-black uppercase tracking-widest opacity-60 ml-2">Nama Lengkap</label>
              <input x-ref="pp_nama" type="text" name="pihak_pertama[nama]" value="{{ $data['pihak_pertama']['nama'] ?? '' }}" placeholder="Nama Atasan" class="w-full bg-white/10 border-none rounded-xl px-4 py-3 text-xs font-bold placeholder:text-white/40 outline-none" required>
            </div>
            <div class="space-y-1.5">
              <label class="text-[8px] font-black uppercase tracking-widest opacity-60 ml-2">NIP</label>
              <input x-ref="pp_nip" type="text" name="pihak_pertama[nip]" value="{{ $data['pihak_pertama']['nip'] ?? '' }}" placeholder="NIP" class="w-full bg-white/10 border-none rounded-xl px-4 py-3 text-xs font-mono placeholder:text-white/40 outline-none" required>
            </div>
            <div class="space-y-1.5">
              <label class="text-[8px] font-black uppercase tracking-widest opacity-60 ml-2">Jabatan</label>
              <textarea x-ref="pp_jabatan" name="pihak_pertama[jabatan]" rows="2" class="w-full bg-white/10 border-none rounded-xl px-4 py-3 text-xs font-bold placeholder:text-white/40 outline-none leading-snug" required>{{ $data['pihak_pertama']['jabatan'] ?? '' }}</textarea>
            </div>
          </div>
        </div>

        {{-- Pihak Kedua (Peminjam) --}}
        <div class="bg-rose-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-rose-100 space-y-6">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
              <i class="fas fa-user text-[10px]"></i>
            </div>
            <h3 class="text-[11px] font-black uppercase tracking-widest opacity-80">Pihak Kedua (Peminjam)</h3>
          </div>
          <div class="space-y-4">
            <div class="space-y-1.5">
              <label class="text-[8px] font-black uppercase tracking-widest opacity-60 ml-2">Nama Lengkap</label>
              <input type="text" name="pihak_kedua[nama]" value="{{ $data['pihak_kedua']['nama'] ?? '' }}" placeholder="Nama Peminjam" class="w-full bg-white/10 border-none rounded-xl px-4 py-3 text-xs font-bold placeholder:text-white/40 outline-none" required>
            </div>
            <div class="space-y-1.5">
              <label class="text-[8px] font-black uppercase tracking-widest opacity-60 ml-2">NIP</label>
              <input type="text" name="pihak_kedua[nip]" value="{{ $data['pihak_kedua']['nip'] ?? '' }}" placeholder="NIP / Identitas" class="w-full bg-white/10 border-none rounded-xl px-4 py-3 text-xs font-mono placeholder:text-white/40 outline-none" required>
            </div>
            <div class="space-y-1.5">
              <label class="text-[8px] font-black uppercase tracking-widest opacity-60 ml-2">Jabatan</label>
              <textarea name="pihak_kedua[jabatan]" rows="2" class="w-full bg-white/10 border-none rounded-xl px-4 py-3 text-xs font-bold placeholder:text-white/40 outline-none leading-snug" required>{{ $data['pihak_kedua']['jabatan'] ?? '' }}</textarea>
            </div>
          </div>
        </div>
      </div>

      {{-- Daftar Barang --}}
      <div class="bg-white rounded-[2.5rem] p-6 border border-slate-50 shadow-sm space-y-6 overflow-hidden">
        <div class="flex items-center justify-between border-b border-slate-50 pb-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
              <i class="fas fa-boxes text-xs"></i>
            </div>
            <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Daftar Barang</h3>
          </div>
          <div class="flex gap-2">
            <button type="button" @click="prefill()" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center transition active:scale-90">
              <i class="fas fa-sync text-[10px]"></i>
            </button>
            <button type="button" @click="addItem()" class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-100 transition active:scale-90">
              <i class="fas fa-plus text-[10px]"></i>
            </button>
          </div>
        </div>

        <div class="overflow-x-auto -mx-6 px-6">
          <table class="min-w-[800px] w-full text-left">
            <thead class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
              <tr>
                <th class="pb-4 px-3 w-[200px]">Nama Barang</th>
                <th class="pb-4 px-3 w-[150px]">Merk</th>
                <th class="pb-4 px-3 w-[150px]">Type</th>
                <th class="pb-4 px-3 w-[150px]">No. Kendaraan</th>
                <th class="pb-4 px-3 w-[80px]">Tahun</th>
                <th class="pb-4 px-3 w-[80px] text-center">Jumlah</th>
                <th class="pb-4 px-3 w-[50px]"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
              <template x-for="(item, i) in items" :key="item._key">
                <tr>
                  <td class="py-3 px-1">
                    <input type="text" :name="`items[${i}][nama]`" x-model="item.nama" class="w-full bg-slate-50 border-none rounded-xl px-3 py-2 text-[11px] font-bold outline-none focus:ring-1 focus:ring-indigo-500/20" placeholder="Nama">
                  </td>
                  <td class="py-3 px-1">
                    <input type="text" :name="`items[${i}][merk]`" x-model="item.merk" class="w-full bg-slate-50 border-none rounded-xl px-3 py-2 text-[11px] font-bold outline-none focus:ring-1 focus:ring-indigo-500/20" placeholder="Merk">
                  </td>
                  <td class="py-3 px-1">
                    <input type="text" :name="`items[${i}][tipe]`" x-model="item.tipe" class="w-full bg-slate-50 border-none rounded-xl px-3 py-2 text-[11px] font-bold outline-none focus:ring-1 focus:ring-indigo-500/20" placeholder="Type">
                  </td>
                  <td class="py-3 px-1">
                    <input type="text" :name="`items[${i}][identitas]`" x-model="item.identitas" class="w-full bg-slate-50 border-none rounded-xl px-3 py-2 text-[11px] font-bold outline-none focus:ring-1 focus:ring-indigo-500/20" placeholder="No. Kendaraan">
                  </td>
                  <td class="py-3 px-1">
                    <input type="text" :name="`items[${i}][tahun]`" x-model="item.tahun" class="w-full bg-slate-50 border-none rounded-xl px-3 py-2 text-[11px] font-bold text-center outline-none focus:ring-1 focus:ring-indigo-500/20" placeholder="2024">
                  </td>
                  <td class="py-3 px-1">
                    <input type="text" :name="`items[${i}][jumlah]`" x-model="item.jumlah" class="w-full bg-slate-50 border-none rounded-xl px-3 py-2 text-[11px] font-black text-center outline-none focus:ring-1 focus:ring-indigo-500/20" placeholder="1">
                  </td>
                  <td class="py-3 px-1 text-center">
                    <button type="button" @click="removeItem(i)" class="text-slate-300 hover:text-rose-500 transition">
                      <i class="fas fa-trash-alt text-[10px]"></i>
                    </button>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>

      {{-- Actions --}}
      <div class="flex gap-3 px-2">
        <a href="{{ route('reports.pinjam.list') }}" class="flex-1 py-5 bg-slate-100 text-slate-400 rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] text-center">Batal</a>
        <button type="submit" class="flex-[2] py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-all">Perbarui Laporan</button>
      </div>
    </form>
  </div>
@endsection
