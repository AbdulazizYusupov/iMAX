<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // updateOrCreate — agar ulamoqchi bo'lgan username bazada bo'lsa yangilaydi, yo'q bo'lsa yaratadi.
        User::updateOrCreate(
            ['username' => 'admin'], // Tekshirish sharti
            [
                'name' => 'Muhammaddiyor',
                'password' => Hash::make('admin123'), // Majburiy xavfsiz shifrlash
            ]
        );
    }
}