<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installment_payments', function (Blueprint $table) {
            $table->id();
            // Qaysi sotuvga tegishli
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->integer('month_number'); // Nechanchi oy (1-oy, 2-oy...)
            $table->decimal('amount', 15, 2); // Shu oy to'lanishi kerak bo'lgan summa
            $table->boolean('is_paid')->default(false); // To'landimi?
            $table->timestamp('paid_at')->nullable(); // Qachon to'ladi?
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installment_payments');
    }
};
