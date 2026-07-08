<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hobi;

class HobiSeeder extends Seeder
{
    public function run(): void
    {
        $hobis = ['Bola', 'Baca', 'Menyanyi'];

        foreach ($hobis as $nama) {
            Hobi::create(['nama_hobi' => $nama]);
        }
    }
}