<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserLike;

class UserLikeController extends Controller
{
    public function index(Request $request)
    {
        $auth = $request->user();

        $items = UserLike::with(['user:id,name,avatar,cover_image']) // rel 'user' = liker
            ->where('liked_id', $auth->id)
            ->latest()
            ->limit(50)
            ->get()
            ->map(function ($like) {
                $liker = $like->user;
                return [
                    'id'          => $liker->id,
                    'name'        => $liker->name,
                    'avatar_url'  => $liker->avatar_url,     // accessor di model User
                    'date'        => $like->created_at?->format('M d'),
                    'profile_url' => route('users.show', $liker->id),
                ];
            })
            ->values();

        return response()->json(['items' => $items]);
    }
    /**
     * Toggle like / dislike terhadap user tertentu.
     * Dipanggil dari route POST /users/{user}/like
     */
    
    public function toggle(Request $request, User $user)
    {
        $auth = $request->user();

        // Cegah user like dirinya sendiri
        if ($auth->id === $user->id) {
            return response()->json([
                'ok'      => false,
                'message' => 'Tidak dapat menyukai diri sendiri.',
            ], 422);
        }

        // Cek apakah sudah ada like
        $existing = UserLike::where('liker_id', $auth->id)
                            ->where('liked_id', $user->id)
                            ->first();

        if ($existing) {
            // Jika sudah like → hapus (unlike)
            $existing->delete();
            $liked = false;
        } else {
            // Jika belum → buat baris baru (like)
            UserLike::create([
                'liker_id' => $auth->id,
                'liked_id' => $user->id,
            ]);
            $liked = true;
        }

        // Ambil ulang total like dari kolom users.likes_count
        $user->refresh();
        $count = (int) $user->likes_count;

        return response()->json([
            'ok'    => true,
            'liked' => $liked,
            'count' => $count,
        ]);
    }
}
