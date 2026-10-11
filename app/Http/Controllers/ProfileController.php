<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\DeleteFeedback;

class ProfileController extends Controller
{
    
    // Menampilkan Data di Profile
    public function show()
    {
        $user = Auth::user();
        $age = $user->age ?: null;
        $social = is_array($user->social_links)
            ? $user->social_links
            : (json_decode($user->social_links ?? '[]', true) ?: []);
        $handle = function (?string $url) {
            if (!$url) return null;
            $path = trim(parse_url($url, PHP_URL_PATH) ?? '', '/');
            return $path ? '@'.Str::before($path, '/') : null;
        };
        $split = function (?string $v) {
            if (!$v) return [];
            $arr = preg_split('/[,;|]+/', $v);
            return array_values(array_filter(array_map('trim', $arr)));
        };
        $preferences = array_unique(array_merge(
            $split($user->hobby),
            $split($user->lifestyle),
            $split($user->personality),
            $split($user->love_language),
        ));
        return view('profile', compact('user', 'age', 'social', 'handle', 'preferences'));
    }

    // Mengedit Social Media di Profile
    public function updateSocial(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'instagram' => ['nullable','string','max:60'],
            'facebook'  => ['nullable','string','max:255'], 
            'tiktok'    => ['nullable','string','max:60'],
        ]);
        $links = [
            'instagram' => $data['instagram']
                ? 'https://instagram.com/' . ltrim($data['instagram'], '@/')
                : null,
            'facebook'  => $data['facebook'] 
                ? 'https://facebook.com/' . ltrim($data['facebook'], '@/')
                : null,
            'tiktok'    => $data['tiktok']
                ? 'https://www.tiktok.com/@' . ltrim($data['tiktok'], '@/')
                : null,
        ];
        $user->social_links = $links;
        $user->save();
        return back()->with('success', 'Social media updated.');
    }

    // Mengedit Preferences di Profile
     public function updatePreferences(Request $request)
    {
        $hobbies = [
            'Travel','Music','Reading','Sports','Gaming','Foodie',
            'Photography','Movies','Art & Design','Animals',
            'Hiking','Tech'
        ];
        $lifestyles = [
            'Non-smoker','Occasionally','Regular smoker',
            'Non-drinker','Social drinker','Regular drinker',
            'Omnivore','Vegetarian'
        ];
        $personalities = [
            'Introvert','Extrovert','Ambivert','Adventurous',
            'Easygoing','Ambitious','Creative','Funny'
        ];
        $loveLangs = [
            'Words of Affirmation','Quality Time',
            'Acts of Service','Receiving Gifts','Physical Touch'
        ];
        $data = $request->validate([
            'hobby'         => ['nullable', Rule::in($hobbies)],
            'lifestyle'     => ['nullable', Rule::in($lifestyles)],
            'personality'   => ['nullable', Rule::in($personalities)],
            'love_language' => ['nullable', Rule::in($loveLangs)],
        ]);

        $request->user()->update($data);
        return back()->with('success','Preferences updated.');
    }

    public function updateInformation(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name'        => ['required','string','max:16'],
            'age'         => ['nullable','integer','min:16','max:120'],
            'job'         => ['nullable','string','max:57'],
            'location'    => ['nullable','string','max:255'],
            'about'       => ['nullable','string','max:2000'],
            'avatar'      => ['nullable','image','max:2048'],
            'cover_image' => ['nullable','image','max:4096'],
        ]);

        // avatar
        if ($request->hasFile('avatar')) {
        // hapus file lama (bisa di storage public atau di assets)
        if (!empty($user->avatar)) {
            if (Str::startsWith($user->avatar, 'assets/')) {
                @unlink(public_path($user->avatar));
            } elseif (Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
        }

        $avatarDir = public_path('assets/img/avatars');
        if (!is_dir($avatarDir)) {
            mkdir($avatarDir, 0755, true);
        }

        $file = $request->file('avatar');
        $ext  = $file->getClientOriginalExtension();
        $name = 'ava-'.$user->id.'-'.time().'.'.$ext;

        $file->move($avatarDir, $name);
        // simpan path relatif dari public
        $data['avatar'] = 'assets/img/avatars/'.$name;
        }

        // cover
        if ($request->hasFile('cover_image')) {
        // hapus file lama (bisa di storage public atau di assets)
        if (!empty($user->cover_image)) {
            if (Str::startsWith($user->cover_image, 'assets/')) {
                @unlink(public_path($user->cover_image));
            } elseif (Storage::disk('public')->exists($user->cover_image)) {
                Storage::disk('public')->delete($user->cover_image);
            }
        }

        $coverDir = public_path('assets/img/covers');
        if (!is_dir($coverDir)) {
            mkdir($coverDir, 0755, true);
        }

        $file = $request->file('cover_image');
        $ext  = $file->getClientOriginalExtension();
        $name = 'cover-'.$user->id.'-'.time().'.'.$ext;

        $file->move($coverDir, $name);
        // simpan path relatif dari public
        $data['cover_image'] = 'assets/img/covers/'.$name;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function destroy(Request $request)
    {
        $user   = $request->user();
        $reason = $request->input('reason');

        // simpan feedback
        DeleteFeedback::create([
            'user_id'    => $user->id,
            'user_email' => $user->email,
            'reason'     => $reason,
            
        ]);

        // proses hapus akun
        auth()->logout();
        $user->delete();

        return redirect()
        ->route('landing')
        ->with('account_deleted', true)
        ->with('success', 'Your account has been deleted.');
    }

    // app/Http/Controllers/ProfileController.php
    public function updateFactfile(Request $request)
    {
        $data = $request->validate([
            'gender'    => ['nullable','string','max:50'],
            'religion'  => ['nullable','string','max:50'],
            'languages' => ['nullable','string','max:255'], 
        ]);

        $user = $request->user();
        $user->fill($data)->save();

        return back()->with('success', 'Factfile updated.');
    }

    public function albumUpdate(Request $request)
    {
        $request->validate([
            'slot'  => 'required|in:1,2,3',
            'photo' => 'required|image|max:4096',
            // opsional keamanan ekstra: tolak svg, dll
            // 'photo' => 'required|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        // hanya pemilik yang boleh update — kami gunakan user login
        $user = auth()->user(); 

        $dir = public_path('assets/img/albums/'.$user->id);
        if (!is_dir($dir)) { @mkdir($dir, 0755, true); }

        $ext  = $request->file('photo')->getClientOriginalExtension();
        $name = 'album_'.$request->slot.'_'.time().'.'.$ext;
        $request->file('photo')->move($dir, $name);
        $url  = '/assets/img/albums/'.$user->id.'/'.$name;

        $slot   = (string) $request->slot;
        $photos = $user->album_photos ?? [];

        // --- HAPUS FILE LAMA JIKA ADA ---
        if (!empty($photos[$slot])) {
            $oldPath = public_path(ltrim($photos[$slot], '/'));
            if (is_file($oldPath)) @unlink($oldPath);
        }

        // simpan url baru
        $photos[$slot] = $url;
        $user->album_photos = $photos;
        $user->save();

        return back()->with('success','Foto album diperbarui.');
    }

    public function albumDelete(Request $request)
    {
        $request->validate(['slot' => 'required|in:1,2,3']);

        // hanya pemilik yang boleh delete — pakai user login
        $user   = auth()->user();
        $slot   = (string) $request->slot;
        $photos = $user->album_photos ?? [];

        if (!empty($photos[$slot])) {
            $path = public_path(ltrim($photos[$slot], '/'));
            if (is_file($path)) @unlink($path); 
            unset($photos[$slot]);        
            $user->album_photos = $photos;
            $user->save();
        }

        return back()->with('success', 'Foto album dihapus.');
    }

    public function view(User $user)
    {
        // sesuaikan normalisasi data dengan field kamu
        $age = $user->age ?? null;

        $social = is_array($user->social_links)
            ? $user->social_links
            : (json_decode($user->social_links ?? '[]', true) ?: []);

        $split = function (?string $v) {
            if (!$v) return [];
            $arr = preg_split('/[,;|]+/', $v);
            return array_values(array_filter(array_map('trim', $arr)));
        };

        $preferences = array_unique(array_merge(
            $split($user->hobby ?? ''),
            $split($user->lifestyle ?? ''),
            $split($user->personality ?? ''),
            $split($user->love_language ?? ''),
        ));

        return view('profile', [
            'user'        => $user,
            'age'         => $age,
            'social'      => $social,
            'preferences' => $preferences,
        ]);
    }

}
