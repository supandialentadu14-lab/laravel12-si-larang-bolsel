@extends($isMobile ? 'layouts.mobile' : 'layouts.admin')

@section('content')
  <script>
    window.opnameForm = function () {
      return {
        items: {!! json_encode(($data['items'] ?? [])) !!},
        onDateChange() { this.updatePembuka(); },
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
            const tglKata = toWords(tanggal);
            const thnKata = toWords(tahun);
            this.$refs.pembuka.value = `Pada hari ini ${hari} Tanggal ${tglKata} Bulan ${bulan} Tahun ${thnKata}, yang bertanda tangan di bawah ini:`;
          } catch (e) {}
        }
      }
    }
  </script>

  <div class="space-y-6 pb-24">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Edit Opname</h1>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Perbarui Pemeriksaan Stok</p>
      </div>
      <a href="{{ route('reports.opname.list') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
        <i class="fas fa-times text-xs"></i>
      </a>
    </div>

    <form method="POST" action="{{ route('reports.opname.save', $id) }}" x-data="opnameForm()" x-init="$nextTick(() => updatePembuka())" class="space-y-6">
      @csrf
      <input type="hidden" name="id" value="{{ $id }}">

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
            <input type="text" name="nomor" value="{{ $data['nomor'] ?? '' }}" 
              oninvalid="this.setCustomValidity('Nomor Berita Acara harus diisi')" 
              oninput="this.setCustomValidity('')"
              class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none font-mono" placeholder="001/BA-SO/..." required>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Tanggal</label>
              <input x-ref="tanggal" @change="updatePembuka()" type="date" name="tanggal" value="{{ $data['tanggal'] ?? now()->toDateString() }}" 
                oninvalid="this.setCustomValidity('Tanggal harus diisi')" 
                oninput="this.setCustomValidity('')"
                class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
            </div>
            <div class="space-y-1.5">
              <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Tempat</label>
              <input x-ref="tempat" @input="updatePembuka()" type="text" name="tempat" value="{{ $data['tempat'] ?? ($opd->nama_opd ?? '') }}" 
                oninvalid="this.setCustomValidity('Tempat harus diisi')" 
                oninput="updatePembuka(); this.setCustomValidity('');"
                class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="Boroko" required>
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-4">Narasi Pembuka</label>
            <textarea x-ref="pembuka" name="pembuka" rows="3" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none leading-relaxed">{{ $data['pembuka'] ?? '' }}</textarea>
          </div>
        </div>
      </div>

      {{-- Pihak Berwenang --}}
      <div class="grid grid-cols-1 gap-6">
        {{-- Pihak Pertama (Atasan) --}}
        <div class="bg-indigo-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-indigo-100 space-y-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                <i class="fas fa-user-tie text-[10px]"></i>
              </div>
              <h3 class="text-[11px] font-black uppercase tracking-widest opacity-80">Pihak Pertama (Atasan)</h3>
            </div>
            <button type="button" @click="$refs.pp_nama.value='{{ $opd->kepala_nama }}'; $refs.pp_nip.value='{{ $opd->kepala_nip }}'; $refs.pp_jabatan.value='{{ $opd->kepala_jabatan }}';" class="text-[8px] font-black uppercase px-2 py-1 bg-white/20 rounded-lg">Auto</button>
          </div>
          <div class="space-y-4">
            <div class="space-y-1.5">
              <label class="text-[8px] font-black uppercase tracking-widest opacity-60 ml-2">Nama Lengkap</label>
              <input x-ref="pp_nama" type="text" name="pihak_pertama[nama]" value="{{ $data['pihak_pertama']['nama'] ?? '' }}" placeholder="Nama Atasan" 
                oninvalid="this.setCustomValidity('Nama Pihak Pertama harus diisi')" 
                oninput="this.setCustomValidity('')"
                class="w-full bg-white/10 border-none rounded-xl px-4 py-3 text-xs font-bold placeholder:text-white/40 outline-none" required>
            </div>
            <div class="space-y-1.5">
              <label class="text-[8px] font-black uppercase tracking-widest opacity-60 ml-2">NIP</label>
              <input x-ref="pp_nip" type="text" name="pihak_pertama[nip]" value="{{ $data['pihak_pertama']['nip'] ?? '' }}" placeholder="NIP" class="w-full bg-white/10 border-none rounded-xl px-4 py-3 text-xs font-mono placeholder:text-white/40 outline-none">
            </div>
            <div class="space-y-1.5">
              <label class="text-[8px] font-black uppercase tracking-widest opacity-60 ml-2">Jabatan</label>
              <input x-ref="pp_jabatan" type="text" name="pihak_pertama[jabatan]" value="{{ $data['pihak_pertama']['jabatan'] ?? '' }}" placeholder="Jabatan" class="w-full bg-white/10 border-none rounded-xl px-4 py-3 text-xs font-bold placeholder:text-white/40 outline-none">
            </div>
          </div>
        </div>

        {{-- Pihak Kedua (Pengurus) --}}
        <div class="bg-rose-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-rose-100 space-y-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                <i class="fas fa-user text-[10px]"></i>
              </div>
              <h3 class="text-[11px] font-black uppercase tracking-widest opacity-80">Pihak Kedua (Pengurus)</h3>
            </div>
            <button type="button" @click="$refs.pk_nama.value='{{ $opd->pengurus_nama }}'; $refs.pk_nip.value='{{ $opd->pengurus_nip }}'; $refs.pk_jabatan.value='{{ $opd->pengurus_jabatan }}';" class="text-[8px] font-black uppercase px-2 py-1 bg-white/20 rounded-lg">Auto</button>
          </div>
          <div class="space-y-4">
            <div class="space-y-1.5">
              <label class="text-[8px] font-black uppercase tracking-widest opacity-60 ml-2">Nama Lengkap</label>
              <input x-ref="pk_nama" type="text" name="pihak_kedua[nama]" value="{{ $data['pihak_kedua']['nama'] ?? '' }}" placeholder="Nama Pengurus" 
                oninvalid="this.setCustomValidity('Nama Pihak Kedua harus diisi')" 
                oninput="this.setCustomValidity('')"
                class="w-full bg-white/10 border-none rounded-xl px-4 py-3 text-xs font-bold placeholder:text-white/40 outline-none" required>
            </div>
            <div class="space-y-1.5">
              <label class="text-[8px] font-black uppercase tracking-widest opacity-60 ml-2">NIP</label>
              <input x-ref="pk_nip" type="text" name="pihak_kedua[nip]" value="{{ $data['pihak_kedua']['nip'] ?? '' }}" placeholder="NIP" class="w-full bg-white/10 border-none rounded-xl px-4 py-3 text-xs font-mono placeholder:text-white/40 outline-none">
            </div>
            <div class="space-y-1.5">
              <label class="text-[8px] font-black uppercase tracking-widest opacity-60 ml-2">Jabatan</label>
              <input x-ref="pk_jabatan" type="text" name="pihak_kedua[jabatan]" value="{{ $data['pihak_kedua']['jabatan'] ?? '' }}" placeholder="Jabatan" class="w-full bg-white/10 border-none rounded-xl px-4 py-3 text-xs font-bold placeholder:text-white/40 outline-none">
            </div>
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="flex gap-3 px-2">
        <a href="{{ route('reports.opname.list') }}" class="flex-1 py-5 bg-slate-100 text-slate-400 rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] text-center">Batal</a>
        <button type="submit" class="flex-[2] py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-all">Perbarui Laporan</button>
      </div>
    </form>
  </div>
@endsection
