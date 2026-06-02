<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'description', 'amount', 'expense_date'])]
class Expense extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'expense_date' => 'datetime',
            'amount' => 'float',
        ];
    }
}
