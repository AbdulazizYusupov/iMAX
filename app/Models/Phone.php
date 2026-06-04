<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;

    /**
     * Massivli to'ldirishga ruxsat berilgan ustunlar ro'yxati.
     * Yangi qo'shilgan 'quantity' va 'sold_quantity' shu yerga kiritildi.
     */
    protected $fillable = [
        'category_id',
        'name',
        'color',
        'imei',
        'cost_price',
        'selling_price',
        'margin_percent',
        'ram',
        'storage',
        'quantity',
        'sold_quantity',
        'arrival_date',
        'status',
    ];

    /**
     * Ma'lumotlar turini avtomat o'giring (Casting)
     */
    protected $casts = [
        'arrival_date' => 'datetime',
        'status' => 'boolean',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'quantity' => 'integer',
        'sold_quantity' => 'integer',
    ];

    /**
     * 🔥 VIRTUAL USTUN (Accessor)
     * Omborda ayni damda qolgan real tovar sonini qaytaradi.
     * Kod ichida xuddi ustundek $phone->current_stock ko'rinishida chaqiriladi.
     */
    public function getCurrentStockAttribute(): int
    {
        // Jami kelgan sonidan sotilgan sonini ayiramiz
        $stock = $this->quantity - $this->sold_quantity;

        // Qoldiq manfiy (minus) bo'lib ketmasligini ta'minlaymiz
        return $stock > 0 ? $stock : 0;
    }

    /**
     * Kategoriya bilan bog'liqlik (Har bitta telefon bitta kategoriyaga tegishli)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Sotuvlar tarixi bilan bog'liqlik (Bitta partiyadagi telefon modeli ko'p marta sotilishi mumkin)
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
