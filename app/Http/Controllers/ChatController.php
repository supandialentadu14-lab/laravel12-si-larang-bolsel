<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        // Admin selalu punya akses chat, user lain harus diaktifkan dulu
        if (!auth()->user()->chat_enabled && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Fitur chat belum diaktifkan oleh admin untuk akun Anda.');
        }

        $users = \App\Models\User::where('id', '<>', auth()->id(), 'and')
            ->where('is_active', true)
            ->where('chat_enabled', true)
            ->get()
            ->map(function($user) {
                $user->unread_count = \App\Models\ChatMessage::where('sender_id', $user->id)
                    ->where('receiver_id', auth()->id())
                    ->where('is_read', false)
                    ->count();
                return $user;
            });

        return view('chat.index', compact('users'));
    }

    public function show(\App\Models\User $user)
    {
        if (!auth()->user()->chat_enabled && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Fitur chat belum diaktifkan oleh admin untuk akun Anda.');
        }

        if (!$user->chat_enabled && !auth()->user()->isAdmin()) {
            return redirect()->route('chat.index')->with('error', 'User ini belum mengaktifkan fitur chat.');
        }
        $messages = \App\Models\ChatMessage::where(function($q) use ($user) {
                $q->where('sender_id', auth()->id())->where('receiver_id', $user->id);
            })->orWhere(function($q) use ($user) {
                $q->where('sender_id', $user->id)->where('receiver_id', auth()->id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read
        \App\Models\ChatMessage::where('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        if (request()->ajax()) {
            return view('chat.messages', compact('messages', 'user'));
        }

        return view('chat.show', compact('messages', 'user'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->chat_enabled && !auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Chat disabled for your account'], 403);
        }
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $message = \App\Models\ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back();
    }

    public function update(Request $request, \App\Models\ChatMessage $message)
    {
        // Only sender can edit their own message
        if ($message->sender_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $message->update([
            'message' => $request->message,
            'is_edited' => true,
        ]);

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function destroy(\App\Models\ChatMessage $message)
    {
        // As requested: user can delete other people's messages too
        // In a real app, we might check if they are part of the conversation
        if ($message->sender_id !== auth()->id() && $message->receiver_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $message->delete();

        return response()->json(['success' => true]);
    }

    public function clearConversation(\App\Models\User $user)
    {
        \App\Models\ChatMessage::where(function($q) use ($user) {
            $q->where('sender_id', auth()->id())->where('receiver_id', $user->id);
        })->orWhere(function($q) use ($user) {
            $q->where('sender_id', $user->id)->where('receiver_id', auth()->id());
        })->delete();

        return redirect()->route('chat.index')->with('success', 'Obrolan telah dihapus.');
    }

    public function getUnreadMessages()
    {
        $messages = \App\Models\ChatMessage::with('sender')
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->latest()
            ->get();

        return response()->json([
            'count' => $messages->count(),
            'messages' => $messages->map(function($m) {
                return [
                    'id' => $m->id,
                    'sender_name' => $m->sender->name,
                    'message' => \Illuminate\Support\Str::limit($m->message, 50),
                    'time' => $m->created_at->diffForHumans(),
                ];
            })
        ]);
    }
}
