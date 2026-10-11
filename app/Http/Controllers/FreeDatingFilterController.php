<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class FreeDatingFilterController extends Controller
{
   public function filter(Request $request)
{
    // Ambil parameter (satu pilihan per accordion)
    $ageRange     = trim((string) $request->input('age_range', ''));
    $hobby        = trim((string) $request->input('hobby', ''));
    $lifestyle    = trim((string) $request->input('lifestyle', ''));
    $personality  = trim((string) $request->input('personality', ''));
    $loveLanguage = trim((string) $request->input('love_language', ''));

    // Selalu batasi 9 card (server-side cap)
    $limit = (int) $request->query('limit', 9);
    $limit = max(1, min($limit, 9));

    $authId = auth()->id();

    $q = \App\Models\User::query()
        ->when($authId, fn($qq) => $qq->where('id', '!=', $authId));

    // Age range
    if ($ageRange !== '') {
        // format "min-max" (boleh ada spasi)
        if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*$/', $ageRange, $m)) {
            $min = (int) $m[1];
            $max = (int) $m[2];
            if ($min > 0 && $max >= $min) {
                $q->whereBetween('age', [$min, $max]);
            }
        }
        // format "X+" (41+ / 45+)
        elseif (preg_match('/^\s*(\d+)\s*\+\s*$/', $ageRange, $m)) {
            $q->where('age', '>=', (int) $m[1]);
        }
    }

    // Satu pilihan per kategori
    if ($hobby !== '')        $q->where('hobby', $hobby);
    if ($lifestyle !== '')    $q->where('lifestyle', $lifestyle);
    if ($personality !== '')  $q->where('personality', $personality);
    if ($loveLanguage !== '') $q->where('love_language', $loveLanguage);

    // Urutan: likes terbanyak (sesuai kode kamu)
    $users = $q->orderByDesc('likes_count')
               ->limit($limit)     // <= batasi 9 card
               ->get();

    // Render partial cards (HTML)
    $html = '';
    foreach ($users as $u) {
        $html .= view('partials.user-card', ['u' => $u])->render();
    }

    if ($html === '') {
        $html = '<div class="col-12"><div class="alert alert-light border rounded-3">Tidak ada hasil sesuai filter.</div></div>';
    }

    return response($html, 200)->header('Content-Type', 'text/html; charset=UTF-8');
}

}
