<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\UserLike;
use Illuminate\Support\Facades\DB;
use App\Models\Message;

class LightNotificationController extends Controller
{
    public function index(Request $request)
    {
        $u = $request->user();
        $userId = $u->id;

        // Ambil timestamp terakhir panel notif dibuka (persist di server)
        $seenKey = "notif:seen:$userId";
        $seenAt  = Cache::get($seenKey);

        $items = [];

        // ✅ Welcome jika: belum pernah buka panel + profil belum lengkap
        $seenKey = "notif:seen:$userId";
        $seenAt  = Cache::get($seenKey);

        $items[] = [
            'kind'       => 'welcome',
            'title'      => 'Welcome to LoveConnect',
            'body'       => 'Complete your profile today and boost your chances of matching 3x faster!',
            'created_at' => optional($u->created_at)->toIso8601String(),
            'link'       => url('/profile'),
            'is_unread'  => !$seenAt,
        ];

        // 2) It’s a Match (mutual like)
        // Sesuaikan nama tabel/kolom jika berbeda
       $mutual = DB::table('user_likes as a')
        ->join('user_likes as b', function ($j) use ($userId) {
            $j->on('a.liked_id', '=', 'b.liker_id')
            ->where('a.liker_id', '=', $userId)
            ->where('b.liked_id', '=', $userId);
        })
        // ambil 1 baris per pasangan (other_id) dengan matched_at = yang terbaru
        ->selectRaw('a.liked_id as other_id, GREATEST(a.created_at, b.created_at) as matched_at')
        ->orderByDesc('matched_at')
        ->get()
        ->unique('other_id') 
        ->values();

        // tampilkan semuanya (mis. 20 terakhir), unread ditentukan oleh seenAt
        $mutual = $mutual->take(20);

        foreach ($mutual as $m) {
            $isUnread = !$seenAt || strtotime($m->matched_at ?? '') > strtotime($seenAt);
            $other    = DB::table('users')->where('id', $m->other_id)->first();

            $items[] = [
                'kind'       => 'match',
                'title'      => "It's a Match!",
                'body'       => 'You and '.(($other->name ?? 'someone')).' liked each other. Start chatting now and see where it goes.',
                'created_at' => $m->matched_at ? date('c', strtotime($m->matched_at)) : now()->toIso8601String(),
                'link'       => url('/chat?user='.$m->other_id),
                'is_unread'  => $isUnread,
                // opsional: kirim icon name bila dipakai di JS
                'icon'       => 'hand', 
            ];
        }

        // 3) New Message (contoh sederhana)
        $latestInbound = Message::where('receiver_id', $userId)->latest('id')->first();
        if ($latestInbound) {
            $items[] = [
                'kind'       => 'new_message',
                'title'      => 'You Have a New Message',
                'body'       => 'Someone is waiting to hear back from you. Open and reply instantly!',
                'created_at' => $latestInbound->created_at?->toIso8601String(),
                'link'       => url('/chat?user='.$latestInbound->sender_id),
                'is_unread'  => $this->isNewer($seenAt, $latestInbound->created_at),
            ];
        }

        // Badge = jumlah item unread
        $unread = collect($items)->where('is_unread', true)->count();
        $today = now()->startOfDay()->timestamp;
        $yesterday = now()->subDay()->startOfDay()->timestamp;
        $groups = [
            'today'     => collect($items)->filter(fn($n)=> $this->ts($n['created_at']) >= $today)->values(),
            'yesterday' => collect($items)->filter(fn($n)=> $this->ts($n['created_at']) >= $yesterday && $this->ts($n['created_at']) < $today)->values(),
        ];

        return response()->json([
            'unread' => $unread,
            'groups' => $groups,
        ]);
    }

    public function markSeen(Request $request)
    {
        $userId = $request->user()->id;
        Cache::put("notif:seen:$userId", now()->toIso8601String(), now()->addDays(90));
        return response()->json(['ok' => true]);
    }

    private function isNewer($seenAt, $createdAt): bool
    {
        if (!$createdAt) return false;
        if (!$seenAt)    return true;
        return strtotime((string)$createdAt) > strtotime((string)$seenAt);
    }
    private function ts($iso) { return $iso ? strtotime($iso) : 0; }
}
