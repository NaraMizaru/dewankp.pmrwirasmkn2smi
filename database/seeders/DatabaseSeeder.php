<?php

namespace Database\Seeders;

use App\Models\Bidang;
use App\Models\Kelas;
use App\Models\Setting;
use App\Models\UangKas;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'fullname' => 'PMR Wira SMK Negeri 2 Sukabumi',
            'username' => 'pmrwirasmkn2smi',
            'password' => 'PMRWira.2SMI',
            'email' => 'pmrwirasmknegeri2sukabumi@gmail.com',
            'no_telp' => '08123456789',
            'role' => 'admin',
        ]);

        $kelas = [
            'name' => [
                '10 AKL 1',
                '10 AKL 2',
                '10 AKL 3',
                '10 MPLB 1',
                '10 MPLB 2',
                '10 PM 1',
                '10 PM 2',
                '10 PM 3',
                '10 PM 4',
                '10 PPLG 1',
                '10 PPLG 2',
                '10 TJKT 1',
                '10 TJKT 2',
            ],
            'slug' => [
                'x-akl-1',
                'x-akl-2',
                'x-akl-3',
                'x-mplb-1',
                'x-mplb-2',
                'x-pm-1',
                'x-pm-2',
                'x-pm-3',
                'x-pm-4',
                'x-pplg-1',
                'x-pplg-2',
                'x-tjkt-1',
                'x-tjkt-2',
            ],
        ];

        for ($i = 0; $i < count($kelas['name']); $i++) {
            Kelas::create([
                'name' => $kelas['name'][$i],
                'slug' => $kelas['slug'][$i],
            ]);
        }


        $bidang = [
            'name' => [
                'Pertolongan Pertama',
                'Tandu',
                'Karikatur',
                'Dapur Umum',
                'Pendidikan Remaja Sebaya',
            ],
            'slug' => [
                'pertolongan-pertama',
                'tandu',
                'karikatur',
                'dapur-umum',
                'pendidikan-remaja-sebaya',
            ]
        ];

        for ($i = 0; $i < count($bidang['name']); $i++) {
            Bidang::create([
                'name' => $bidang['name'][$i],
                'slug' => $bidang['slug'][$i],
            ]);
        }

        $unit = [
            'name' => [
                'Unit 1 | Kesehatan',
                'Unit 2 | Bakti Masyarakat',
                'Unit 3 | Persahabatan',
                'Unit 4 | Media Informasi dan Komunikasi',
            ],
            'slug' => [
                'unit-1',
                'unit-2',
                'unit-3',
                'unit-4',
            ]
        ];

        for ($i = 0; $i < count($unit['name']); $i++) {
            Unit::create([
                'name' => $unit['name'][$i],
                'slug' => $unit['slug'][$i],
            ]);
        }

        Setting::create([
            'name' => 'register',
            'type' => 'select',
            'value' => 'aktif',
        ]);

        Setting::create([
            'name' => 'background',
            'type' => 'image',
            // 'value' => 'img/unsplash/login-bg.jpg'
        ]);

        Setting::create([
            'name' => 'reset anggota',
            'type' => 'anggota',
            'value' => 'reset'
        ]);

        Setting::create([
            'name' => 'link grup whatsapp',
            'type' => 'text',
        ]);
    }
}
