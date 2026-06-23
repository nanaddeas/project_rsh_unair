<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pet_owners', function (Blueprint $table) {
            $table->index('nik', 'pet_owners_nik_index');
        });
    }

    public function down(): void
    {
        Schema::table('pet_owners', function (Blueprint $table) {
            $table->dropIndex('pet_owners_nik_index');
        });
    }
};
