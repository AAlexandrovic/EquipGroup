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
        Schema::create('groups', function (Blueprint $table) {
            $table->id(); // создаёт поле id (bigint auto-increment)
            $table->tinyInteger('id_parent'); // создаёт поле id_parent
            $table->string('name', 100); // создаёт поле name с ограничением на 100 символов
            $table->timestamps(); // создаёт поля created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
