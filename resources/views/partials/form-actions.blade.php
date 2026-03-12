<div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
    @if(isset($backRoute))
        <a href="{{ $backRoute }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 font-bold hover:bg-gray-100 transition">
            Batal
        </a>
    @endif

    @if(isset($previewRoute))
        <button type="submit" formaction="{{ $previewRoute }}" class="px-5 py-2.5 bg-orange-500 rounded-lg text-sm font-bold text-white shadow-lg shadow-orange-200 hover:bg-orange-600 transition flex items-center gap-2">
            <i class="fas fa-file-alt"></i> {{ $previewText ?? 'Preview Laporan' }}
        </button>
    @endif

    <button type="submit" 
        @if(isset($saveRoute)) formaction="{{ $saveRoute }}" @endif
        class="px-5 py-2.5 bg-emerald-600 rounded-lg text-sm font-bold text-white shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition flex items-center gap-2">
        <i class="fas fa-save"></i> {{ $saveText ?? 'Simpan' }}
    </button>
</div>
