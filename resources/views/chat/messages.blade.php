@forelse($messages as $msg)
    @php
        $isOwn = $msg->sender_id === auth()->id();
    @endphp
    <div class="flex {{ $isOwn ? 'justify-end' : 'justify-start' }}" id="message-{{ $msg->id }}">
        <div class="max-w-[80%] group">
            <div class="relative flex items-end gap-2">
                @if($isOwn)
                    <div class="flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button @click="startEdit({{ $msg->id }}, '{{ addslashes($msg->message) }}')" class="w-6 h-6 rounded-lg bg-slate-50 text-indigo-500 flex items-center justify-center active:scale-90 transition-all">
                            <i class="fas fa-edit text-[8px]"></i>
                        </button>
                    </div>
                @endif
                
                <div class="relative {{ $isOwn ? 'bg-indigo-600 text-white rounded-[1.5rem] rounded-tr-none' : 'bg-white text-slate-800 rounded-[1.5rem] rounded-tl-none border border-slate-50 shadow-sm' }} px-4 py-2.5 shadow-sm">
                    <p class="text-[11px] font-bold leading-relaxed">{{ $msg->message }}</p>
                    @if($msg->is_edited)
                        <span class="text-[7px] opacity-50 block mt-0.5">(diedit)</span>
                    @endif

                    {{-- Delete Button for both --}}
                    <button @click="deleteMessage({{ $msg->id }})" class="absolute {{ $isOwn ? '-left-8' : '-right-8' }} top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center opacity-0 group-hover:opacity-100 active:scale-90 transition-all shadow-sm">
                        <i class="fas fa-trash-alt text-[9px]"></i>
                    </button>
                </div>
            </div>
            <div class="flex items-center gap-1.5 mt-1 px-2 {{ $isOwn ? 'justify-end' : 'justify-start' }}">
                <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest">
                    {{ $msg->created_at->format('H:i') }}
                </p>
                @if($isOwn)
                    @if($msg->is_read)
                        <div class="flex items-center -space-x-1">
                            <i class="fas fa-check text-[7px] text-indigo-400"></i>
                            <i class="fas fa-check text-[7px] text-indigo-400"></i>
                        </div>
                    @else
                        <i class="fas fa-check text-[7px] text-slate-300"></i>
                    @endif
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="h-full flex items-center justify-center py-20">
        <div class="text-center">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-comments text-slate-200 text-xl"></i>
            </div>
            <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">Belum ada pesan</p>
        </div>
    </div>
@endforelse
