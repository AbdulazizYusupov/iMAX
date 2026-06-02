<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Factory-ni chetlab o'tib, to'g'ridan-to'g'ri model orqali yaratamiz
        User::create([
            'name' => 'Muhammaddiyor',
            'username' => 'Iquva1',
            'password' => '123', // Modelda 'hashed' kastingi borligi uchun o'zi shifrlanadi
        ]);
    }
}
