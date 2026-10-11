<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLike;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
{
    $authId = auth()->id();

    // Pool semua kandidat id (kecuali diri sendiri) → disimpan agar konsisten selama sesi
    $pool = User::query()
        ->when($authId, fn ($q) => $q->where('users.id', '!=', $authId))
        ->pluck('id')
        ->map(fn ($i) => (int) $i)
        ->values()
        ->all();

    // Ambil 9 user acak untuk grid awal
    $users = User::query()
        ->select([
            'users.id','users.name','users.age','users.location','users.about',
            'users.hobby','users.lifestyle','users.personality','users.avatar'
        ])
        ->when($authId, fn ($q) => $q->where('users.id', '!=', $authId))
        ->inRandomOrder()
        ->limit(9)
        ->get();

    $visible = $users->pluck('id')->map(fn($i)=>(int)$i)->values()->all();

    session([
        'pool_all_ids'     => $pool,     // ← total 20 id (atau berapa pun di DB saat ini)
        'visible_user_ids' => $visible,  // ← 9 id yang sedang kelihatan
        'seen_user_ids'    => $visible,  // ← siklus “sudah pernah tampil”
    ]);

    return view('free-online-dating', ['users' => $users]);
}

    // Dipanggil tombol flip/refresh di dalam kartu (AJAX)
    public function nextCard(Request $request)
{
    abort_unless($request->ajax(), 400);

    $authId    = auth()->id();
    $currentId = (int) $request->input('current_id');

    // --- state dari sesi (bisa saja kosong karena session regenerate)
    $pool    = collect(session('pool_all_ids', []))->map(fn($i)=>(int)$i)->values();
    $visible = collect(session('visible_user_ids', []))->map(fn($i)=>(int)$i)->values();
    $seen    = collect(session('seen_user_ids', []))->map(fn($i)=>(int)$i)->values();

    // --- kalau pool kosong (mis. sesi baru), rebuild dari DB
    if ($pool->isEmpty()) {
        $pool = User::query()
            ->when($authId, fn ($q) => $q->where('users.id','!=',$authId))
            ->pluck('id')->map(fn($i)=>(int)$i)->values();
        session(['pool_all_ids' => $pool->all()]);
    }

    // --- ambil daftar visible dari klien (DOM sekarang)
    $clientVisible = collect($request->input('visible_ids', []))
        ->map(fn($i)=>(int)$i)->filter()->values();

    // ==== MODE DETECTION: mobile vs desktop ====
    $mode     = strtolower((string) $request->input('mode', ''));
    $isMobile = ($mode === 'mobile')
        || ($clientVisible->count() === 1 && $clientVisible->contains($currentId));

    if ($isMobile) {
        // MOBILE → anggap hanya 1 kartu yg sedang tampil (current), dan JANGAN kurangi pool dg 8 lainnya
        $visible = collect([$currentId]);
        // reset "seen" agar semua selain current bisa dipakai lagi
        $seen    = collect([$currentId]);
        session([
            'visible_user_ids' => $visible->all(),
            'seen_user_ids'    => $seen->all(),
        ]);
    } else {
        // DESKTOP → hormati daftar dari klien kalau ada; batasi 9 & pastikan current masih valid
        if ($clientVisible->isNotEmpty()) {
            $visible = $clientVisible->take(9)->values();
            $seen    = $seen->merge($visible)->unique()->values();
            session([
                'visible_user_ids' => $visible->all(),
                'seen_user_ids'    => $seen->all(),
            ]);
        }
    }

    // validasi lagi setelah resync
    if (!$visible->contains($currentId)) {
        return response('Invalid card', 422);
    }

    // ---- builder data user (samakan select dengan index)
    $base = fn() => User::query()
        ->select([
            'users.id','users.name','users.age','users.location','users.about',
            'users.hobby','users.lifestyle','users.personality','users.avatar'
        ])
        ->when($authId, fn ($q) => $q->where('users.id', '!=', $authId));

    // === rotasi: pool - seen - visible → kandidat
    $remaining  = $pool->diff($seen);
    $candidates = $remaining->diff($visible);

    if ($candidates->isEmpty()) {
        // reset siklus: yang terlihat sekarang dianggap sudah terlihat
        $seen = $visible->values();
        $remaining  = $pool->diff($seen);
        $candidates = $remaining->diff($visible);
    }

    if ($candidates->isEmpty()) {
        // fallback: minimal beda dari current & tidak menabrak visible
        $candidates = $pool->diff($visible->push($currentId));
    }

    if ($candidates->isEmpty()) {
        return response('', 204);
    }

    $nextId = $candidates->shuffle()->first();
    $nextUser = $base()->where('users.id', $nextId)->firstOrFail();

    // update sesi
    $newVisible = $isMobile
        ? collect([(int) $nextUser->id])->values()
        : $visible->map(fn($id) => $id === $currentId ? (int)$nextUser->id : $id)->values();
    $newSeen    = $seen->push((int)$nextUser->id)->unique()->values();

    session([
        'visible_user_ids' => $newVisible->all(),
        'seen_user_ids'    => $newSeen->all(),
        // pool_all_ids tetap sama
    ]);

    $html = view('partials.user-card', ['u' => $nextUser])->render();
    return response($html, 200)->header('Content-Type', 'text/html; charset=UTF-8');
}





}
