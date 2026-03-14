{{-- Modal Tambah --}}
<div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-[10000] overflow-y-auto" x-cloak>
  <div class="flex items-end sm:items-center justify-center min-h-screen text-center">
    <div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCreateModal = false"></div>
    
    <div x-show="showCreateModal" 
      x-transition:enter="transition ease-out duration-300 transform"
      x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95"
      x-transition:enter-end="translate-y-0 sm:scale-100"
      class="relative w-full sm:max-w-xl bg-white rounded-t-[2.5rem] sm:rounded-[2.5rem] text-left overflow-hidden shadow-2xl p-8">
      
      <div class="flex items-center justify-between mb-8">
        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Jenis Belanja Baru</h3>
        <button @click="showCreateModal = false" class="w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Nama Jenis Belanja</label>
            <input type="text" name="name" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="Masukkan nama kategori..." required>
          </div>

          <div class="space-y-1.5">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Keterangan (Opsional)</label>
            <textarea name="description" rows="3" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="Penjelasan singkat..."></textarea>
          </div>
        </div>

        <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-transform mt-4">
          Simpan Data
        </button>
      </form>
    </div>
  </div>
</div>

{{-- Modal Edit --}}
<div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-[10000] overflow-y-auto" x-cloak>
  <div class="flex items-end sm:items-center justify-center min-h-screen text-center">
    <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showEditModal = false"></div>
    
    <div x-show="showEditModal" 
      x-transition:enter="transition ease-out duration-300 transform"
      x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95"
      x-transition:enter-end="translate-y-0 sm:scale-100"
      class="relative w-full sm:max-w-xl bg-white rounded-t-[2.5rem] sm:rounded-[2.5rem] text-left overflow-hidden shadow-2xl p-8">
      
      <div class="flex items-center justify-between mb-8">
        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Edit Jenis Belanja</h3>
        <button @click="showEditModal = false" class="w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <form :action="editUrl" method="POST" class="space-y-6">
        @csrf @method('PUT')
        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Nama Jenis Belanja</label>
            <input type="text" name="name" x-model="editData.name" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
          </div>

          <div class="space-y-1.5">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Keterangan (Opsional)</label>
            <textarea name="description" x-model="editData.description" rows="3" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none"></textarea>
          </div>
        </div>

        <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-transform mt-4">
          Simpan Perubahan
        </button>
      </form>
    </div>
  </div>
</div>
