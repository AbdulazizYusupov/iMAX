<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            // Qaysi telefon sotildi
            $table->foreignId('phone_id')->constrained()->onDelete('cascade');
            // Qaysi xodim sotdi (tizimga kirgan user)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('customer_name')->nullable(); // Xaridor ismi
            $table->string('customer_phone')->nullable(); // Xaridor tel raqami
            $table->decimal('sold_price', 15, 2); // Qanchaga sotildi (Skidka yoki ustama bilan)
            $table->string('payment_method')->default('naqd'); // naqd, karta, muddatli
            $table->text('notes')->nullable(); // Qo'shimcha izoh
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
