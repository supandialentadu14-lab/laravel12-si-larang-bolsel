@forelse($messages as $msg)
    @php
        $isOwn = $msg->sender_id === auth()->id();
    @endphp
    <div class="flex items-center w-full gap-3 {{ $isOwn ? 'justify-end' : 'justify-start' }} group/msg" id="message-{{ $msg->id }}">
        {{-- Selection Checkbox --}}
        <div x-show="isSelectionMode" x-transition x-cloak class="flex-none {{ $isOwn ? 'order-2' : 'order-1' }}">
            <button @click="toggleMessageSelection({{ $msg->id }})" 
                    class="w-6 h-6 rounded-lg flex items-center justify-center transition-all border-2"
                    :class="selectedMessages.includes({{ $msg->id }}) ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-white border-slate-200 text-transparent'">
                <i class="fas fa-check text-[10px]"></i>
            </button>
        </div>

        <div class="max-w-[80%] group relative {{ $isOwn ? 'order-1' : 'order-2' }}">
            <div class="relative flex items-end gap-2 {{ $isOwn ? 'flex-row-reverse' : 'flex-row' }}">
                <div class="relative {{ $isOwn ? 'bg-white text-slate-800 rounded-[1.5rem] rounded-tr-none border border-slate-50 shadow-sm' : 'bg-indigo-600 text-white rounded-[1.5rem] rounded-tl-none' }} px-4 py-2.5 shadow-sm transition-all cursor-pointer"
                     :class="selectedMessages.includes({{ $msg->id }}) || activeActionId === {{ $msg->id }} ? 'ring-2 ring-indigo-500/50 scale-[0.98]' : ''"
                     @click="isSelectionMode ? toggleMessageSelection({{ $msg->id }}) : toggleMessageActions({{ $msg->id }})">
                    <p class="text-[11px] font-bold leading-relaxed">{{ $msg->message }}</p>
                    @if($msg->is_edited)
                        <span class="text-[7px] opacity-50 block mt-0.5" :class="activeActionId === {{ $msg->id }} ? 'text-slate-400' : '{{ $isOwn ? 'text-slate-400' : 'text-indigo-200' }}'">(diedit)</span>
                    @endif
                </div>

                {{-- Action Buttons (Visible only on Click) - Now BELOW message --}}
                <div class="mt-2 flex items-center gap-2 transition-all no-print {{ $isOwn ? 'justify-end' : 'justify-start' }}" 
                     x-show="activeActionId === {{ $msg->id }} && !isSelectionMode" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-[-5px]"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak>
                    @if($isOwn)
                        <button @click.stop="startEdit({{ $msg->id }}, '{{ addslashes($msg->message) }}')" class="px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-600 text-[8px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                            Edit
                        </button>
                    @endif
                    <button @click.stop="deleteMessage({{ $msg->id }})" class="px-3 py-1.5 rounded-xl bg-rose-50 text-rose-600 text-[8px] font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                        Hapus
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
