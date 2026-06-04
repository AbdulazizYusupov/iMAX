<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentPayment extends Model
{
    protected $fillable = ['sale_id', 'month_number', 'amount', 'is_paid', 'paid_at'];

    protected $casts = [
        'paid_at' => 'datetime',
        'is_paid' => 'boolean'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
