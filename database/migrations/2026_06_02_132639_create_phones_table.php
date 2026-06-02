<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->id();

            // Kategoriya bilan bog'lash (Agar kategoriya o'chsa, unga tegishli telefonlar o'chib ketmaydi, taqiqlanadi)
            $table->foreignId('category_id')->constrained()->onDelete('restrict');

            $table->string('name'); // Telefon modeli nomi (Masalan: iPhone 15 Pro Max)
            $table->string('color')->nullable(); // Rangi (Masalan: Natural Titanium, Black)
            $table->string('imei')->unique()->nullable(); // IMEI kodi (unikal bo'lishi shart)

            // Narxlar (Katta sonlar va tiyinlar uchun decimal ma'qul)
            $table->decimal('cost_price', 15, 2); // Kelgan narxi (Tan narxi)
            $table->decimal('selling_price', 15, 2); // Sotuv narxi
            $table->integer('margin_percent')->nullable(); // Ustiga qo'yilgan foiz (%)

            // Qshimcha xarakteristikalar (RAM, Xotira kabilar uchun text yoki sodda qilib alohida ustunlar)
            $table->string('ram')->nullable(); // Tezkor xotira (Masalan: 8 GB, 12 GB)
            $table->string('storage')->nullable(); // Doimiy xotira (Masalan: 128 GB, 256 GB)

            $table->dateTime('arrival_date'); // Kelgan sana va vaqti
            $table->boolean('status')->default(true); // Sotuvda bormi (true) yoki sotilgan/muzlatilgan (false)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phones');
    }
};
