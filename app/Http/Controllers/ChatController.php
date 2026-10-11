<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function show(User $user, Request $request)
    {
        abort_if(!auth()->check(), 403);
        abort_if($user->id === auth()->id(), 403, 'Tidak bisa chat dengan diri sendiri.');

        return view('chat.show', [
            'other' => $user,
        ]);
    }

    public function messages(User $user, Request $request)
    {
        abort_if(!auth()->check(), 403);
        abort_if($user->id === auth()->id(), 403);

        Message::where('sender_id', $user->id)
        ->where('receiver_id', auth()->id())
        ->whereNull('read_at')
        ->update(['read_at' => now()]);

        $authId = auth()->id();
        $q = Message::between($authId, $user->id);

        if ($after = (int) $request->query('after_id', 0)) {
            $q->where('id', '>', $after);
        }

        $items = $q->orderBy('id')->limit(100)->get()->map(function (Message $m) use ($authId) {
            return [
                'id'          => $m->id,
                'body'        => $m->body,
                'sender_id'   => $m->sender_id,
                'receiver_id' => $m->receiver_id,
                'created_at'  => $m->created_at?->toIso8601String(),
                'is_me'       => (int)$m->sender_id === (int)$authId,
            ];
        });

        return response()->json(['items' => $items]);
    }

    public function send(User $user, Request $request)
    {
        abort_if(!auth()->check(), 403);
        abort_if($user->id === auth()->id(), 403);

        $data = $request->validate([
            'body' => ['required','string','max:2000'],
        ]);

        $msg = Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $user->id,
            'body'        => $data['body'],
        ]);

        return response()->json([
            'id'          => $msg->id,
            'body'        => $msg->body,
            'sender_id'   => $msg->sender_id,
            'receiver_id' => $msg->receiver_id,
            'created_at'  => $msg->created_at?->toIso8601String(),
            'is_me'       => true,
        ]);
    }

    public function index(Request $request)
    {
        $auth = auth()->user();
        $initialUserId = $request->query('user');

        $prefillDummy = null;
        $initialName = null;
        $initialAvatar = null;

        if ($initialUserId && $initialUserId != $auth->id) {
            // Cek apakah sudah pernah chat
            $alreadyChatted = \App\Models\Message::between($auth->id, $initialUserId)->exists();

            if (! $alreadyChatted) {
                $prefillDummy = 'Hello, may I know more about you ?';      // ⬅️ kirim hanya jika belum ada chat
            }

            // (opsional) data untuk header fallback
            if ($target = \App\Models\User::find($initialUserId)) {
                $initialName   = $target->name ?? 'Conversation';
                $initialAvatar = $target->avatar_url ?? asset('assets/img/icons/user-chat-default.png');
            }
        }

        return view('chat.index', [
            'initialUserId' => $initialUserId,
            'prefillDummy'  => $prefillDummy,   // akan null kalau sudah pernah chat
            'initialName'   => $initialName,
            'initialAvatar' => $initialAvatar,
        ]);
    }

    public function conversations(Request $request)
    {
        $authId = auth()->id();

        // Ambil semua partner (lawan chat) dari semua pesan yg melibatkan kita
        $partners = Message::where(function($q) use ($authId) {
                $q->where('sender_id', $authId)->orWhere('receiver_id', $authId);
            })
            ->selectRaw("CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END AS partner_id", [$authId])
            ->groupBy('partner_id')
            ->pluck('partner_id')
            ->values();

        // Jika belum ada partner sama sekali, kembalikan array kosong
        if ($partners->isEmpty()) {
            return response()->json(['items' => []]);
        }

        // Deteksi kolom avatar yang tersedia di tabel users
        $avatarCol = collect(['avatar_url','avatar','photo','image','picture'])
            ->first(fn($c) => Schema::hasColumn('users', $c));

        $items = [];
        foreach ($partners as $pid) {
            // pesan terakhir antar kita dan partner
            $last = Message::where(function($q) use ($authId, $pid) {
                        $q->where('sender_id', $authId)->where('receiver_id', $pid);
                    })
                    ->orWhere(function($q) use ($authId, $pid) {
                        $q->where('sender_id', $pid)->where('receiver_id', $authId);
                    })
                    ->orderByDesc('id')
                    ->first();

            // data user partner
            $u = User::find($pid);
            $avatar = $avatarCol && $u ? $u->{$avatarCol} : null;

            // hitung pesan belum dibaca dari dia -> kita
            $unread = Message::where('sender_id', $pid)
                ->where('receiver_id', $authId)
                ->whereNull('read_at')
                ->count();

            $items[] = [
                'user_id'   => (int)$pid,
                'name'      => $u?->name ?? ('User #'.$pid),
                'avatar'    => $avatar,               // boleh null; JS aman
                'last_text' => $last?->body ?? '',
                'last_time' => optional($last?->created_at)->toIso8601String(),
                'unread'    => $unread,
                'url'       => route('chat.show', $pid),
            ];
        }

        // Urutkan by last_time desc
        usort($items, fn($a,$b) => strcmp($b['last_time'] ?? '', $a['last_time'] ?? ''));

        return response()->json(['items' => $items]);
    }

}
