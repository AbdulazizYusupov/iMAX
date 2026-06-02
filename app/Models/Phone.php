<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'category_id',
    'name',
    'color',
    'imei',
    'cost_price',
    'selling_price',
    'margin_percent',
    'ram',
    'storage',
    'arrival_date',
    'status'
])]
class Phone extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'arrival_date' => 'datetime',
            'cost_price' => 'float',
            'selling_price' => 'float',
        ];
    }

    /**
     * Model yuklanayotganda foizni avtomatik hisoblash tizimi
     */
    protected static function booted()
    {
        static::saving(function ($phone) {
            if ($phone->cost_price > 0) {
                // Foizni hisoblash formulasi: ((Sotuv - Tan) / Tan) * 100
                $profit = $phone->selling_price - $phone->cost_price;
                $phone->margin_percent = round(($profit / $phone->cost_price) * 100);
            }
        });
    }

    /**
     * Telefon qaysi kategoriyaga tegishli ekanligi (Many-to-One)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
