<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pet_owners', function (Blueprint $table) {
            if (! Schema::hasColumn('pet_owners', 'nik')) {
                $table->string('nik', 20)->nullable()->after('id');
            }
        });

        Schema::table('pets', function (Blueprint $table) {
            if (! Schema::hasColumn('pets', 'breed')) {
                $table->string('breed')->nullable()->after('species');
            }

            if (! Schema::hasColumn('pets', 'gender')) {
                $table->enum('gender', ['Jantan', 'Betina'])->nullable()->after('breed');
            }

            if (! Schema::hasColumn('pets', 'fur_color')) {
                $table->string('fur_color')->nullable()->after('age');
            }
        });

        if (Schema::hasColumn('bookings', 'service_id')) {
            try {
                Schema::table('bookings', function (Blueprint $table) {
                    $table->dropForeign(['service_id']);
                });
            } catch (Throwable $e) {
                //
            }

            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn('service_id');
            });
        }

        Schema::table('bookings', function (Blueprint $table) {
            $table->unique(['booking_date', 'booking_time'], 'bookings_date_time_unique');
        });

        Schema::dropIfExists('services');
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique('bookings_date_time_unique');
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'service_id')) {
                $table->foreignId('service_id')->nullable()->after('pet_id');
            }
        });
    }
};
