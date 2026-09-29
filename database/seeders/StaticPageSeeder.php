<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use Illuminate\Database\Seeder;

class StaticPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            'about' => 'About',
            'pengiriman' => 'Pengiriman',
            'pengembalian' => 'Pengembalian',
            'syarat-ketentuan' => 'Syarat & Ketentuan',
            'kebijakan-privasi' => 'Kebijakan Privasi',
        ];

        foreach ($pages as $slug => $title) {
            StaticPage::firstOrCreate(
                ['slug' => $slug],
                ['title' => $title, 'status' => 'draft'],
            );
        }
    }
}