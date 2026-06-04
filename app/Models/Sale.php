<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['phone_id', 'user_id', 'customer_name', 'customer_phone', 'sold_price', 'payment_method', 'notes'])]
class Sale extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'sold_price' => 'float',
        ];
    }

    // Har bir sotuv bitta telefonga tegishli
    public function phone(): BelongsTo
    {
        return $this->belongsTo(Phone::class);
    }

    // Har bir sotuvni bitta xodim amalga oshirgan
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function installmentPayments()
    {
        return $this->hasMany(InstallmentPayment::class);
    }
}
