<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->string('name');
            $table->string('color')->nullable();

            // IMEI endi ->unique() EMAS, chunki bitta partiyada kelgan telefonlar bitta qatorda turadi
            $table->string('imei')->nullable();

            $table->decimal('cost_price', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->integer('margin_percent')->nullable();

            $table->string('ram')->nullable();
            $table->string('storage')->nullable();

            // 🔥 SONINI HISOBLASH UCHUN YANGI USTUNLAR
            $table->integer('quantity')->default(1); // Olib kelingan jami soni
            $table->integer('sold_quantity')->default(0); // Shu paytgacha sotilgan soni

            $table->dateTime('arrival_date');
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('phones');
    }
};
