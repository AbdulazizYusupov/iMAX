<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Xarajat nomi (kategoriya yoki sarlavha sifat)
            $table->text('description')->nullable(); // Nimaga sarflangani haqida batafsil izoh
            $table->decimal('amount', 15, 2); // Xarajat summasi
            $table->dateTime('expense_date'); // Xarajat qilingan sana va vaqt
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
