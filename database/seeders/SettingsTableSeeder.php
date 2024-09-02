<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            ['key' => 'template_pengingat_tagihan', 'value' => 'Assalamualaikum Bapak Ibu, Semoga dalam keadaan sehat selalu. Berikut kami informasikan Tagihan {nama_biaya} Bulan {bulan} {tahun} atas nama {nama} dengan jumlah tagihan {jumlah_biaya}. Terima kasih.'],
            ['key' => 'template_pengingat_tagihan_angsur', 'value' => 'Assalamualaikum Bapak Ibu, Semoga dalam keadaan sehat selalu. Berikut kami informasikan Tagihan {nama_biaya} Bulan {bulan} {tahun} atas nama {nama} dengan jumlah tagihan {jumlah_biaya}. Total yang sudah dibayar: {total_dibayar}. Sisa pembayaran: {sisa_pembayaran}. Terima kasih.'],
        ]);
    }
}
