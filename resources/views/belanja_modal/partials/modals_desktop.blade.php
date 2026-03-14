<div x-show="showModal" style="display: none;" class="fixed inset-0 z-[10000] overflow-y-auto" x-cloak>
  <div class="flex items-center justify-center min-h-screen text-center p-6">
    <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-2xl" @click="showModal = false"></div>
    
    <div x-show="showModal" 
      x-transition:enter="transition ease-out duration-300 transform"
      x-transition:enter-start="opacity-0 translate-y-4 scale-95"
      x-transition:enter-end="opacity-100 translate-y-0 scale-100"
      x-transition:leave="transition ease-in duration-200 transform"
      x-transition:leave-start="opacity-100 translate-y-0 scale-100"
      x-transition:leave-end="opacity-0 translate-y-4 scale-95"
      class="relative w-full max-w-6xl bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl p-8">
      
      <div class="flex items-center justify-between mb-8">
        <div>
          <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight" x-text="isEdit ? 'Edit Kontrak Modal' : 'Kontrak Modal Baru'"></h3>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Input Data Kontrak Belanja Modal</p>
        </div>
        <button type="button" @click="showModal = false" class="w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <form method="POST" action="{{ route('reports.belanja.modal.save') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="id" x-model="editId">
        
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-1.5">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Tahun Anggaran</label>
            <input type="number" name="tahun" x-model="tahun" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
          </div>
          <div class="space-y-1.5">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Instansi / OPD</label>
            <input type="text" :value="opd" class="w-full px-6 py-4 bg-slate-100 border-none rounded-2xl text-sm font-bold text-slate-400 outline-none" disabled>
          </div>
        </div>

        <div class="flex items-center justify-between px-2 pt-4">
          <h4 class="text-[10px] font-black text-slate-800 uppercase tracking-[0.2em]">Daftar Item Pekerjaan</h4>
          <button type="button" @click="addItem()" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Baris
          </button>
        </div>

        <div class="overflow-x-auto -mx-8 px-8 pb-4">
          <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden border border-slate-100 rounded-3xl">
              <table class="min-w-full divide-y divide-slate-100 text-[11px] font-bold">
                <thead class="bg-slate-50 text-slate-400 uppercase tracking-widest">
                  <tr>
                    <th class="px-4 py-3 text-left">Kegiatan & Pekerjaan</th>
                    <th class="px-4 py-3 text-right">Nilai Kontrak</th>
                    <th class="px-4 py-3 text-center">Periode</th>
                    <th class="px-4 py-3 text-center">SP2D / Termin</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 w-10"></th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-50">
                  <template x-for="(item, i) in items" :key="i">
                    <tr class="hover:bg-slate-50/50 transition-colors">
                      <td class="px-2 py-2 min-w-[260px] space-y-1">
                        <input type="text" :name="`items[${i}][nama_kegiatan]`" x-model="item.nama_kegiatan" placeholder="Nama Kegiatan" class="w-full px-3 py-2 bg-slate-50 border-none rounded-xl text-[11px] focus:ring-2 focus:ring-indigo-500/10 outline-none">
                        <input type="text" :name="`items[${i}][pekerjaan]`" x-model="item.pekerjaan" placeholder="Pekerjaan" class="w-full px-3 py-2 bg-white border border-slate-100 rounded-xl text-[10px] focus:ring-2 focus:ring-indigo-500/10 outline-none font-medium">
                      </td>
                      <td class="px-2 py-2">
                        <div class="relative min-w-[140px]">
                          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-[10px]">Rp</span>
                          <input type="number" :name="`items[${i}][nilai_kontrak]`" x-model="item.nilai_kontrak" class="w-full pl-8 pr-3 py-2 bg-slate-50 border-none rounded-xl text-right text-[11px] focus:ring-2 focus:ring-indigo-500/10 outline-none">
                        </div>
                      </td>
                      <td class="px-2 py-2 space-y-1 min-w-[160px]">
                        <input type="date" :name="`items[${i}][tanggal_mulai]`" x-model="item.tanggal_mulai" class="w-full px-2 py-1.5 bg-slate-50 border-none rounded-lg text-[10px] focus:ring-2 focus:ring-indigo-500/10 outline-none">
                        <input type="date" :name="`items[${i}][tanggal_akhir]`" x-model="item.tanggal_akhir" class="w-full px-2 py-1.5 bg-slate-50 border-none rounded-lg text-[10px] focus:ring-2 focus:ring-indigo-500/10 outline-none">
                      </td>
                      <td class="px-2 py-2">
                        <div class="grid grid-cols-2 gap-1 min-w-[220px]">
                          <input type="number" :name="`items[${i}][uang_muka]`" x-model="item.uang_muka" @input="recalc(i)" placeholder="UM" class="px-2 py-1.5 bg-slate-50 border-none rounded-lg text-[10px] text-right focus:ring-2 focus:ring-indigo-500/10 outline-none">
                          <input type="number" :name="`items[${i}][termin1]`" x-model="item.termin1" @input="recalc(i)" placeholder="T1" class="px-2 py-1.5 bg-slate-50 border-none rounded-lg text-[10px] text-right focus:ring-2 focus:ring-indigo-500/10 outline-none">
                          <input type="number" :name="`items[${i}][termin2]`" x-model="item.termin2" @input="recalc(i)" placeholder="T2" class="px-2 py-1.5 bg-slate-50 border-none rounded-lg text-[10px] text-right focus:ring-2 focus:ring-indigo-500/10 outline-none">
                          <input type="number" :name="`items[${i}][termin3]`" x-model="item.termin3" @input="recalc(i)" placeholder="T3" class="px-2 py-1.5 bg-slate-50 border-none rounded-lg text-[10px] text-right focus:ring-2 focus:ring-indigo-500/10 outline-none">
                          <input type="number" :name="`items[${i}][termin4]`" x-model="item.termin4" @input="recalc(i)" placeholder="T4" class="px-2 py-1.5 bg-slate-50 border-none rounded-lg text-[10px] text-right focus:ring-2 focus:ring-indigo-500/10 outline-none">
                        </div>
                      </td>
                      <td class="px-2 py-2 text-right">
                        <span class="text-[11px] font-black text-indigo-600" x-text="'Rp' + new Intl.NumberFormat('id-ID').format(item.total)"></span>
                      </td>
                      <td class="px-2 py-2 min-w-[140px]">
                        <input type="text" :name="`items[${i}][status]`" x-model="item.status" placeholder="Status..." class="w-full px-3 py-2 bg-slate-50 border-none rounded-xl text-[10px] focus:ring-2 focus:ring-indigo-500/10 outline-none">
                      </td>
                      <td class="px-2 py-2">
                        <button type="button" @click="removeItem(i)" class="w-8 h-8 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all">
                          <i class="fas fa-trash-alt text-[10px]"></i>
                        </button>
                      </td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between gap-4 pt-4">
          <button type="button" @click="showModal = false" class="flex-1 py-4 bg-slate-50 text-slate-400 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] transition-all">
            Batal
          </button>
          <button type="submit" class="flex-[2] py-4 bg-indigo-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-all">
            Simpan Data Kontrak
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

