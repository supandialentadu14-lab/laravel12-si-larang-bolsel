@extends(($isMobile ?? false) ? 'layouts.mobile' : 'layouts.admin')

@section('content')
<div class="flex flex-col h-[calc(100vh-220px)] {{ !($isMobile ?? false) ? 'pt-10' : '' }}" x-data="{
    messages: [],
    newMessage: '',
    loading: false,
    isEditing: false,
    editId: null,
    selectedMessages: [],
    isSelectionMode: false,
    
    toggleSelectionMode() {
        this.isSelectionMode = !this.isSelectionMode;
        if (!this.isSelectionMode) this.selectedMessages = [];
    },

    activeActionId: null,

    toggleMessageActions(id) {
        if (this.isSelectionMode) return;
        this.activeActionId = (this.activeActionId === id) ? null : id;
    },

    toggleMessageSelection(id) {
        if (this.selectedMessages.includes(id)) {
            this.selectedMessages = this.selectedMessages.filter(i => i !== id);
        } else {
            this.selectedMessages.push(id);
        }
    },
    
    scrollToBottom() {
        this.$nextTick(() => {
            const container = this.$refs.messageContainer;
            if (container) container.scrollTop = container.scrollHeight;
        });
    },
    
    async sendMessage() {
        if (!this.newMessage.trim() || this.loading) return;
        this.loading = true;
        
        try {
            const url = this.isEditing ? `/chat/message/${this.editId}` : '{{ route('chat.store') }}';
            const method = this.isEditing ? 'PUT' : 'POST';
            
            const res = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    receiver_id: {{ $user->id }},
                    message: this.newMessage
                })
            });
            
            if (res.ok) {
                this.newMessage = '';
                this.cancelEdit();
                await this.refreshMessages();
            }
        } finally {
            this.loading = false;
        }
    },

    startEdit(id, text) {
        this.isEditing = true;
        this.editId = id;
        this.newMessage = text;
        this.$nextTick(() => this.$refs.input.focus());
    },

    cancelEdit() {
        this.isEditing = false;
        this.editId = null;
        this.newMessage = '';
    },
    
    async deleteMessage(id) {
        if (!confirm('Hapus pesan ini?')) return;
        try {
            const res = await fetch(`/chat/message/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            if (res.ok) {
                await this.refreshMessages();
            }
        } catch (e) {}
    },

    async bulkDelete() {
        if (this.selectedMessages.length === 0) return;
        if (!confirm(`Hapus ${this.selectedMessages.length} pesan terpilih?`)) return;
        
        try {
            const res = await fetch('{{ route('chat.bulk_delete') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: this.selectedMessages })
            });
            
            if (res.ok) {
                this.selectedMessages = [];
                this.isSelectionMode = false;
                await this.refreshMessages();
            }
        } catch (e) {}
    },
    
    async refreshMessages() {
        const res = await fetch(window.location.href, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const html = await res.text();
        this.$refs.messageContainer.innerHTML = html;
        this.scrollToBottom();
    }
}" x-init="
    scrollToBottom();
    setInterval(() => refreshMessages(), 5000);
">
    <div class="flex items-center gap-4 px-2 mb-6">
        <a href="{{ route('chat.index') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400">
            <i class="fas fa-chevron-left text-xs"></i>
        </a>
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-100 overflow-hidden shadow-sm">
                <img src="{{ $user->avatar ? asset('media/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4F46E5&color=ffffff' }}" class="w-full h-full object-cover">
            </div>
            <div class="min-w-0 flex-1">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight truncate">{{ $user->name }}</h3>
                <p class="text-[9px] font-black {{ $user->isOnline() ? 'text-emerald-500' : 'text-slate-400' }} uppercase tracking-widest mt-0.5">
                    @if($user->isOnline())
                        Online
                    @else
                        {{ $user->last_seen_at ? 'Aktif ' . $user->last_seen_at->diffForHumans() : 'Offline' }}
                    @endif
                </p>
            </div>
        </div>
        <div class="flex items-center gap-1.5" x-show="!isSelectionMode">
            <button @click="toggleSelectionMode()" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 hover:text-indigo-500 transition-colors">
                <i class="fas fa-tasks text-xs"></i>
            </button>
            <form action="{{ route('chat.clear', $user) }}" method="POST" onsubmit="return confirm('Hapus semua obrolan dengan {{ $user->name }}?')">
                @csrf
                <button type="submit" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-rose-500 transition-colors">
                    <i class="fas fa-trash-alt text-xs"></i>
                </button>
            </form>
            <a href="{{ route('dashboard') }}" class="w-10 h-10 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-400 transition-colors">
                <i class="fas fa-times text-xs"></i>
            </a>
        </div>

        <div class="flex items-center gap-1.5" x-show="isSelectionMode" x-cloak>
            <button @click="bulkDelete()" :disabled="selectedMessages.length === 0" class="px-4 h-10 rounded-2xl bg-rose-500 text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-100 flex items-center gap-2 disabled:opacity-50 disabled:shadow-none transition-all">
                <i class="fas fa-trash-alt"></i>
                Hapus (<span x-text="selectedMessages.length"></span>)
            </button>
            <button @click="toggleSelectionMode()" class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto px-2 space-y-4 pb-4" x-ref="messageContainer">
        @include('chat.messages')
    </div>

    <div class="px-2 pt-4 border-t border-slate-100 relative">
        {{-- Edit Bar --}}
        <div x-show="isEditing" x-transition class="absolute -top-12 left-0 right-0 bg-indigo-50 px-6 py-2 flex items-center justify-between border-t border-indigo-100">
            <div class="flex items-center gap-2">
                <i class="fas fa-edit text-indigo-400 text-[10px]"></i>
                <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Edit Pesan</p>
            </div>
            <button @click="cancelEdit()" class="text-indigo-400 hover:text-indigo-600">
                <i class="fas fa-times text-[10px]"></i>
            </button>
        </div>

        <div class="relative flex items-center gap-2">
            <textarea x-model="newMessage" x-ref="input" @keydown.enter.prevent="sendMessage()" placeholder="Tulis pesan..." rows="1" class="w-full px-6 py-4 bg-white border border-slate-100 rounded-[2rem] text-xs font-bold text-slate-800 shadow-sm focus:ring-2 focus:ring-indigo-500/20 outline-none resize-none transition-all" :class="loading ? 'opacity-50' : ''"></textarea>
            <button @click="sendMessage()" class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center text-white shadow-lg shadow-indigo-100 active:scale-95 transition-all" :disabled="loading">
                <i class="fas fa-paper-plane text-xs" :class="loading ? 'fa-spinner fa-spin' : ''; !isEditing ? 'fa-paper-plane' : 'fa-check'"></i>
            </button>
        </div>
    </div>
</div>
@endsection
