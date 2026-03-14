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
        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Barang Baru</h3>
        <button @click="showCreateModal = false" class="w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Nama Barang</label>
            <input type="text" name="name" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="Masukkan nama barang..." required>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Jenis Belanja</label>
              <select name="category_id" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none" required>
                <option value="">Pilih Jenis</option>
                @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
              </select>
            </div>
            <div class="space-y-1.5">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Satuan</label>
              <select name="unit" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none" required>
                <option value="">Pilih Satuan</option>
                <option value="pcs">Pcs</option><option value="buah">Buah</option><option value="box">Box</option><option value="pak">Pak</option><option value="rim">Rim</option>
              </select>
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Harga Satuan</label>
            <div class="relative">
              <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">Rp</span>
              <input type="number" name="price" class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="0" required>
            </div>
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
        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Edit Barang</h3>
        <button @click="showEditModal = false" class="w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <form :action="editUrl" method="POST" class="space-y-6">
        @csrf @method('PUT')
        <div class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Nama Barang</label>
            <input type="text" name="name" x-model="editData.name" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Jenis Belanja</label>
              <select name="category_id" x-model="editData.category_id" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none" required>
                @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
              </select>
            </div>
            <div class="space-y-1.5">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Satuan</label>
              <select name="unit" x-model="editData.unit" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none appearance-none" required>
                <option value="pcs">Pcs</option><option value="buah">Buah</option><option value="box">Box</option><option value="pak">Pak</option><option value="rim">Rim</option>
              </select>
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Harga Satuan</label>
            <div class="relative">
              <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">Rp</span>
              <input type="number" name="price" x-model="editData.price" class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 outline-none" required>
            </div>
          </div>
        </div>

        <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-transform mt-4">
          Simpan Perubahan
        </button>
      </form>
    </div>
  </div>
</div>

{{-- Modal Import --}}
<div x-show="showImportModal" style="display: none;" class="fixed inset-0 z-[10000] overflow-y-auto" x-cloak>
  <div class="flex items-end sm:items-center justify-center min-h-screen text-center">
    <div x-show="showImportModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showImportModal = false"></div>
    
    <div x-show="showImportModal" 
      x-transition:enter="transition ease-out duration-300 transform"
      x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95"
      x-transition:enter-end="translate-y-0 sm:scale-100"
      class="relative w-full sm:max-w-xl bg-white rounded-t-[2.5rem] sm:rounded-[2.5rem] text-left overflow-hidden shadow-2xl p-8">
      
      <div class="flex items-center justify-between mb-8">
        <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Impor Barang</h3>
        <button @click="showImportModal = false" class="w-10 h-10 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <form action="{{ route('import.products') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="p-6 border-2 border-dashed border-slate-100 rounded-[2rem] text-center bg-slate-50/50">
          <i class="fas fa-file-excel text-4xl text-indigo-200 mb-4"></i>
          <p class="text-xs font-bold text-slate-500 uppercase tracking-widest leading-relaxed">Pilih file Excel atau CSV sesuai format</p>
          <input type="file" name="file" class="mt-4 text-xs font-medium text-slate-400" required>
        </div>

        <button type="submit" class="w-full py-5 bg-emerald-600 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-emerald-100 active:scale-95 transition-transform">
          Mulai Unggah
        </button>
      </form>
    </div>
  </div>
</div>
