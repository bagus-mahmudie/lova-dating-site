<?php

use Illuminate\Support\Str;

/**
 * Kembalikan URL gambar avatar yg aman:
 * - hanya menerima path lokal relatif ke /public (storage link juga boleh)
 * - TOLAK semua URL http/https -> fallback placeholder
 * - fallback jika file tidak ada / kosong
 */
function user_photo($user, string $placeholder = 'assets/img/icons/card-placeholder.png'): string
{
    $val = null;

    // Ambil nilai dari object User atau string langsung
    if (is_object($user) && isset($user->avatar)) {
        $val = $user->avatar;
    } elseif (is_string($user)) {
        $val = $user;
    }

    // Helper fallback cepat
    $fallback = function () use ($placeholder) {
        return asset($placeholder);
    };

    // Kosong / "null" / whitespace
    if ($val === null)   return $fallback();
    if (is_string($val)) $val = trim($val);
    if ($val === '' || strtolower($val) === 'null') return $fallback();

    // Jika URL absolut (http/https) → JANGAN dipakai, fallback
    if (Str::startsWith($val, ['http://', 'https://'])) {
        return $fallback();
    }

    // Path lokal: pastikan file ada.
    // Izinkan "storage/..." (hasil storage:link) atau "assets/..."
    $publicPath = public_path($val);
    if (! file_exists($publicPath)) {
        return $fallback();
    }

    return asset($val);
}
