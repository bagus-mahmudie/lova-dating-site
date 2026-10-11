<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $cities        = ['Jakarta','Bandung','Surabaya','Yogyakarta','Semarang','Medan','Makassar','Denpasar','Malang','Bogor'];
        $jobs          = ['Software Engineer','Graphic Designer','Teacher','Barista','Content Creator','Marketing Specialist','Student','Product Manager','UI/UX Designer','Data Analyst'];
        $religions     = ['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya'];
        $loveLangs     = ['Words of Affirmation','Quality Time','Receiving Gifts','Acts of Service','Physical Touch'];
        $lifestyles    = ['Active','Chill','Night Owl','Early Bird','Minimalist','Adventurous','Eco-friendly','Foodie'];
        $personalities = ['Introvert','Extrovert','Ambivert','Optimistic','Calm','Go-getter','Empathetic','Witty'];
        $hobbies       = ['Music','Gaming','Traveling','Reading','Cooking','Photography','Hiking','Movies','Pets / Animals','Art & Craft'];
        $langsPool     = ['Indonesian','English','Japanese','Korean','Arabic','French','German'];

        // Tambahkan 10 user dummy
        for ($i = 0; $i < 10; $i++) {
            $name  = $faker->unique()->name();
            $email = Str::slug($name, '.') . $faker->numberBetween(10, 9999) . '@example.com';

            if (User::where('email', $email)->exists()) {
                $i--;
                continue;
            }

            $gender = $faker->randomElement(['male','female']);
            $age    = $faker->numberBetween(18, 45);

            // Avatar & album dummy → kosong/null
            $avatar       = null;
            $cover        = null;
            $album        = null; // atau bisa [] kalau kamu ingin tetap array kosong

            $languages = collect($faker->randomElements($langsPool, $faker->numberBetween(1, 3)))->implode(', ');
            $social    = [
                'facebook'  => 'https://facebook.com/'.Str::slug($name),
                'instagram' => 'https://instagram.com/'.Str::slug($name),
                'twitter'   => 'https://twitter.com/'.Str::slug($name),
                'tiktok'    => 'https://tiktok.com/@'.Str::slug($name),
            ];

            User::create([
                'name'               => $name,
                'email'              => $email,
                'avatar'             => $avatar,      // kosong/null
                'cover_image'        => $cover,       // kosong/null
                'album_photos'       => $album,       // kosong/null
                'email_verified_at'  => now(),
                'password'           => \Illuminate\Support\Facades\Hash::make(bin2hex(random_bytes(24))),
                'age'                => $age,
                'gender'             => $gender,
                'job'                => $faker->randomElement($jobs),
                'religion'           => $faker->randomElement($religions),
                'languages'          => $languages,
                'social_links'       => json_encode($social),
                'location'           => $faker->randomElement($cities),
                'about'              => $faker->realTextBetween(120, 220),
                'hobby'              => $faker->randomElement($hobbies),
                'lifestyle'          => $faker->randomElement($lifestyles),
                'personality'        => $faker->randomElement($personalities),
                'love_language'      => $faker->randomElement($loveLangs),
                'remember_token'     => Str::random(10),
                'likes_count'        => 0,
                'provider'           => null,
                'provider_id'        => null,
            ]);
        }

        // Lengkapi user lama (opsional, sama seperti sebelumnya)
        User::query()
            ->whereNull('likes_count')
            ->orWhereNull('gender')
            ->orWhereNull('location')
            ->chunkById(100, function ($users) use ($faker, $cities, $jobs, $religions, $langsPool, $lifestyles, $personalities, $hobbies, $loveLangs) {
                foreach ($users as $u) {
                    $changed = false;

                    if (is_null($u->likes_count)) { $u->likes_count = 0; $changed = true; }
                    if (is_null($u->gender))       { $u->gender = $faker->randomElement(['male','female']); $changed = true; }
                    if (is_null($u->age))          { $u->age = $faker->numberBetween(18, 45); $changed = true; }
                    if (empty($u->job))            { $u->job = $faker->randomElement($jobs); $changed = true; }
                    if (empty($u->religion))       { $u->religion = $faker->randomElement($religions); $changed = true; }
                    if (empty($u->languages))      { $u->languages = collect($faker->randomElements($langsPool, $faker->numberBetween(1, 3)))->implode(', '); $changed = true; }
                    if (empty($u->social_links))   { $u->social_links = json_encode([]); $changed = true; }
                    if (empty($u->location))       { $u->location = $faker->randomElement($cities); $changed = true; }
                    if (empty($u->about))          { $u->about = $faker->realTextBetween(100, 180); $changed = true; }
                    if (empty($u->hobby))          { $u->hobby = $faker->randomElement($hobbies); $changed = true; }
                    if (empty($u->lifestyle))      { $u->lifestyle = $faker->randomElement($lifestyles); $changed = true; }
                    if (empty($u->personality))    { $u->personality = $faker->randomElement($personalities); $changed = true; }
                    if (empty($u->love_language))  { $u->love_language = $faker->randomElement($loveLangs); $changed = true; }
                    if ($changed) { $u->save(); }
                }
            });
    }
}
