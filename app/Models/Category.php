<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'status'])]
class Category extends Model
{
    use HasFactory;

    /**
     * Atributlarni kasting (turini o'zgartirish) qilish.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'boolean', // Bazadagi 1 yoki 0 ni PHP da true/false ga o'giradi
        ];
    }

    /**
     * Kategoriya va Telefonlar o'rtasidagi bog'liqlik (One-to-Many)
     * Bitta kategoriyaga ko'plab telefonlar tegishli bo'lishi mumkin.
     */
    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class);
    }
}
