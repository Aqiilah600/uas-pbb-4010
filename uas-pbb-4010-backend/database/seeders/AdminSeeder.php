<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Poliban',
            'email' => 'admin@poliban.ac.id',
            'password' => Hash::make('password123'),
        ]);
    }
}