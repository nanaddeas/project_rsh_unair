<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('pet_owners')->onDelete('cascade');
            $table->string('name');
            $table->string('species', 100);
            $table->string('breed')->nullable();
            $table->enum('gender', ['Jantan', 'Betina']);
            $table->string('age')->nullable();
            $table->string('fur_color')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
