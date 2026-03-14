<div class="flex justify-end items-center gap-2">
  @if(isset($show))
  <a href="{{ $show }}" class="w-8 h-8 rounded-lg bg-white text-slate-800 flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm border border-slate-800" title="Lihat">
    <i class="fas fa-eye text-xs"></i>
  </a>
  @endif
  
  <a href="{{ $edit ?? '#' }}" @if(isset($editClick)) x-on:click.prevent="{{ $editClick }}" @endif class="w-8 h-8 rounded-lg bg-white text-slate-800 flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm border border-slate-800" title="Edit">
    <i class="far fa-edit text-xs"></i>
  </a>
  
  @if(isset($delete))
  <form method="POST" action="{{ $delete }}" class="inline text-left">
    @csrf
    @method('DELETE')
    <button type="button" @click="if(confirm('Hapus item ini?')) $el.closest('form').submit()" class="w-8 h-8 rounded-lg bg-white text-slate-800 flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm border border-slate-800" title="Hapus">
      <i class="fas fa-trash text-xs"></i>
    </button>
  </form>
  @endif
</div>
