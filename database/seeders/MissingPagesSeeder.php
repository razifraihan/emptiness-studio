<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use Illuminate\Database\Seeder;

class MissingPagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'kontak',
                'title' => 'Kontak',
                'intro' => 'Ada pertanyaan tentang pesanan atau produk? Kami senang membantu, tanpa terburu-buru.',
                'body' => <<<'TXT'
Email
[ISI: alamat email toko]

WhatsApp
[ISI: nomor WhatsApp]

Instagram
[ISI: akun Instagram]

Jam layanan
[ISI: hari dan jam layanan]

Sertakan nomor pesananmu (contoh: ES-20260930-0001) agar kami bisa membantu lebih cepat.
TXT,
            ],
            [
                'slug' => 'faq',
                'title' => 'FAQ',
                'intro' => 'Jawaban singkat untuk pertanyaan yang sering diajukan.',
                'body' => <<<'TXT'
Bagaimana cara memesan?
Pilih produk dan varian, tambahkan ke keranjang, lalu isi data pengiriman di halaman checkout. Konfirmasi pesanan kami kirim lewat email.

Kapan saya membayar?
Setelah pesanan dibuat, kami mengirim instruksi pembayaran lewat email dan WhatsApp. Pesanan yang belum dibayar dibatalkan otomatis setelah 24 jam, dan stoknya kembali tersedia.

Berapa ongkos kirim?
Ongkos kirim dikonfirmasi setelah pesanan dibuat, sesuai tujuan dan berat paket.

Apakah stok benar-benar terbatas?
Ya. Setiap project dibuat sesuai jumlah produksi. Kami hanya menampilkan status stok apa adanya.

Apa itu pre-order?
Produk pre-order dibuat setelah pesanan masuk. Estimasi produksi dan pengiriman tertulis di halaman produk.

Bagaimana memilih ukuran?
Lihat tabel ukuran di halaman setiap produk, karena potongan tiap produk bisa berbeda.
TXT,
            ],
            [
                'slug' => 'panduan-ukuran',
                'title' => 'Panduan Ukuran',
                'intro' => 'Cara memilih ukuran yang nyaman untukmu.',
                'body' => <<<'TXT'
Tabel ukuran tersedia di halaman setiap produk, karena potongan tiap produk bisa berbeda.

Cara membandingkan
Ambil satu pakaian yang nyaman kamu pakai. Ukur lebar dada (dari ketiak ke ketiak) dan panjangnya, lalu bandingkan dengan tabel ukuran produk yang kamu lihat.

Bila berada di antara dua ukuran
Pilih sesuai potongan yang kamu suka: pas di badan atau longgar.

Toleransi ukuran
[ISI: misalnya selisih 1 sampai 2 cm karena proses produksi]

Butuh bantuan memilih ukuran? Hubungi kami lewat halaman Kontak.
TXT,
            ],
        ];

        foreach ($pages as $data) {
            // Tidak menimpa halaman yang sudah ada. Status draft sampai teksnya kamu periksa.
            StaticPage::firstOrCreate(['slug' => $data['slug']], $data + ['status' => 'draft']);
        }
    }
}